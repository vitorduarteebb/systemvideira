<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Servico;
use App\Models\ServicoHoraRegistro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColaboradorPortalController extends Controller
{
    public function execucao(Request $request, Servico $servico)
    {
        $colab = Colaborador::resolveFromUser($request->user());
        abort_if(! $colab, 403, 'Seu usuário precisa estar vinculado a um colaborador (cadastro ou mesmo e-mail).');
        abort_if(! $servico->tecnicos->contains('id', $colab->id), 403, 'Você não está designado neste serviço.');

        $servico->load([
            'cliente',
            'equipamento',
            'tecnicos',
            'anexos.usuario',
            'horas.colaborador',
            'horas.usuario',
            'questionariosVinculados.questionario.perguntas',
            'questionariosVinculados.usuario',
        ]);

        $questionariosDisponiveis = \App\Models\Questionario::withCount('perguntas')->orderBy('titulo')->get();
        $equipamentosCliente = \App\Models\Equipamento::query()
            ->when($servico->cliente_id, fn ($q) => $q->where('cliente_id', $servico->cliente_id))
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        $inicioCronometro = $this->resolverInicioCronometro($servico, $colab->id);

        return view('crm.colaborador.execucao', compact(
            'servico',
            'colab',
            'questionariosDisponiveis',
            'equipamentosCliente',
            'inicioCronometro'
        ));
    }

    public function iniciarServico(Request $request, Servico $servico)
    {
        $colab = Colaborador::resolveFromUser($request->user());
        if (! $colab) {
            return response()->json(['success' => false, 'message' => 'Usuário sem colaborador vinculado.'], 403);
        }
        if (! $servico->tecnicos->contains('id', $colab->id)) {
            return response()->json(['success' => false, 'message' => 'Serviço não designado para você.'], 403);
        }

        if (in_array($servico->status_operacional, ['concluido', 'cancelado'], true)) {
            return response()->json(['success' => false, 'message' => 'Este serviço já foi encerrado.'], 422);
        }

        $ultimo = ServicoHoraRegistro::query()
            ->where('servico_id', $servico->id)
            ->where('colaborador_id', $colab->id)
            ->latest('horario')
            ->first();

        $emAberto = $ultimo && $ultimo->monitoramento !== 'check_out';
        if ($emAberto) {
            return response()->json([
                'success' => true,
                'message' => 'Continuando serviço em andamento.',
                'redirect' => route('crm.colaborador.execucao', $servico),
            ]);
        }

        DB::transaction(function () use ($servico, $colab, $request) {
            $servico->status_operacional = 'em_andamento';
            if (! $servico->horario_inicio_execucao) {
                $servico->horario_inicio_execucao = now();
            }
            if (! $servico->horario_chegada) {
                $servico->horario_chegada = $servico->horario_inicio_execucao;
            }
            $servico->save();

            ServicoHoraRegistro::create([
                'servico_id' => $servico->id,
                'colaborador_id' => $colab->id,
                'usuario_id' => $request->user()->id,
                'monitoramento' => 'check_in',
                'horario' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Serviço iniciado. Cronômetro ativo.',
            'redirect' => route('crm.colaborador.execucao', $servico),
        ]);
    }

    private function resolverInicioCronometro(Servico $servico, int $colaboradorId): ?Carbon
    {
        $checkIn = ServicoHoraRegistro::query()
            ->where('servico_id', $servico->id)
            ->where('colaborador_id', $colaboradorId)
            ->whereIn('monitoramento', ['check_in', 'retorno'])
            ->latest('horario')
            ->first();

        $candidatos = collect([
            $checkIn?->horario,
            $servico->horario_inicio_execucao,
        ])->filter();

        if ($candidatos->isEmpty()) {
            return null;
        }

        return $candidatos->max();
    }
}
