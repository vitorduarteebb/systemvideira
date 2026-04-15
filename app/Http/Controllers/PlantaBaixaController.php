<?php

namespace App\Http\Controllers;

use App\Models\PlantaBaixa;
use App\Models\PlantaEquipamentoMarcador;
use App\Models\Servico;
use App\Models\ServicoHoraRegistro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlantaBaixaController extends Controller
{
    public function serveImagem(PlantaBaixa $planta)
    {
        if (! $planta->imagem_path) {
            abort(404);
        }
        $path = str_replace('\\', '/', $planta->imagem_path);
        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path);
    }

    public function show(PlantaBaixa $planta)
    {
        PlantaEquipamentoMarcador::aplicarVencimentoManutencaoPorPlanta($planta->id);
        $planta->load(['cliente', 'marcadores.equipamento']);
        $planta->cliente->load(['equipamentos' => fn ($q) => $q->where('ativo', true)->orderBy('nome')]);

        $cid = (int) $planta->cliente_id;
        $plantaResumo = [
            'servicos_em_aberto' => Servico::query()
                ->where('cliente_id', $cid)
                ->whereIn('status_operacional', ['pendente', 'em_andamento', 'pausado'])
                ->count(),
            'minutos_mes' => (int) ServicoHoraRegistro::query()
                ->whereHas('servico', static fn ($q) => $q->where('cliente_id', $cid))
                ->whereYear('horario', now()->year)
                ->whereMonth('horario', now()->month)
                ->sum('tempo_corrido_minutos'),
        ];

        return view('crm.planta-marcadores', [
            'planta' => $planta,
            'cliente' => $planta->cliente,
            'equipamentos' => $planta->cliente->equipamentos,
            'plantaResumo' => $plantaResumo,
        ]);
    }

    public function destroy(PlantaBaixa $planta)
    {
        $cliente = $planta->cliente;
        if ($planta->imagem_path) {
            $path = str_replace('\\', '/', $planta->imagem_path);
            Storage::disk('public')->delete($path);
        }
        $planta->delete();

        return redirect()
            ->route('crm.clientes.show', $cliente)
            ->with('success', 'Planta excluída com sucesso.');
    }

    public function marcadores(Request $request, PlantaBaixa $planta)
    {
        try {
            PlantaEquipamentoMarcador::aplicarVencimentoManutencaoPorPlanta($planta->id);

            $query = $planta->marcadores()->with([
                'equipamento' => static function ($q) {
                    $q->select('id', 'cliente_id', 'nome', 'tag', 'tipo_unidade', 'ativo');
                },
            ]);
            if ($request->filled('mes_ref')) {
                $query->where('mes_ref', $request->mes_ref);
            } else {
                $query->whereNull('mes_ref');
            }

            $planta->loadMissing('cliente');
            $marcadores = $this->enriquecerMarcadoresComServico($planta, $query->get());

            return response()->json([
                'marcadores' => $marcadores->map(fn (PlantaEquipamentoMarcador $m) => $this->marcadorParaApiPlanta($m))->values(),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'marcadores' => [],
                'success' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Não foi possível carregar os marcadores (verifique se a migração da tabela planta_equipamento_marcadores foi executada).',
            ], config('app.debug') ? 500 : 200);
        }
    }

    public function storeMarcador(Request $request, PlantaBaixa $planta)
    {
        $validator = Validator::make($request->all(), [
            'equipamento_id' => 'required|exists:equipamentos,id',
            'pos_x' => 'required|numeric|min:0|max:100',
            'pos_y' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:realizado,pendente,duplicado',
            'mes_ref' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $equipamento = $planta->cliente->equipamentos()->find($data['equipamento_id']);
        if (! $equipamento) {
            return response()->json(['success' => false, 'message' => 'Equipamento não pertence ao cliente desta planta.'], 422);
        }

        $mesRef = ! empty($data['mes_ref'] ?? null)
            ? Carbon::parse($data['mes_ref'])->startOfMonth()->format('Y-m-d')
            : null;

        // Uma posição por equipamento (por planta e mês de referência): reposicionar ao clicar de novo
        $marcador = $planta->marcadores()->updateOrCreate(
            [
                'equipamento_id' => (int) $data['equipamento_id'],
                'mes_ref' => $mesRef,
            ],
            [
                'pos_x' => round((float) $data['pos_x'], 2),
                'pos_y' => round((float) $data['pos_y'], 2),
                'status' => $data['status'],
                'realizado_em' => $data['status'] === 'realizado' ? now() : null,
            ]
        );
        $marcador->load('equipamento');
        $this->enriquecerMarcadoresComServico($planta, collect([$marcador]));

        return response()->json(['success' => true, 'marcador' => $this->marcadorParaApiPlanta($marcador)]);
    }

    public function updateMarcador(Request $request, PlantaEquipamentoMarcador $marcador)
    {
        $validator = Validator::make($request->all(), [
            'pos_x' => 'nullable|numeric|min:0|max:100',
            'pos_y' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|in:realizado,pendente,duplicado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = array_filter($validator->validated(), fn ($v) => $v !== null && $v !== '');
        if (isset($data['pos_x'])) {
            $data['pos_x'] = round($data['pos_x'], 2);
        }
        if (isset($data['pos_y'])) {
            $data['pos_y'] = round($data['pos_y'], 2);
        }
        if (array_key_exists('status', $data)) {
            if ($data['status'] === 'realizado') {
                $data['realizado_em'] = now();
            } else {
                $data['realizado_em'] = null;
            }
        }
        $marcador->update($data);
        $marcador->load('equipamento');
        $marcador->loadMissing('plantaBaixa');
        if ($marcador->plantaBaixa) {
            $this->enriquecerMarcadoresComServico($marcador->plantaBaixa, collect([$marcador]));
        }

        return response()->json(['success' => true, 'marcador' => $this->marcadorParaApiPlanta($marcador)]);
    }

    /** @return array<string, mixed> */
    private function marcadorParaApiPlanta(PlantaEquipamentoMarcador $m): array
    {
        $a = $m->toArray();
        $a['planta_cor_servico'] = $m->getAttribute('planta_cor_servico');
        $a['servico_id'] = $m->getAttribute('servico_id');
        $a['servico_relatorio_url'] = $m->getAttribute('servico_relatorio_url');
        $a['servico_atendimento_url'] = $m->getAttribute('servico_atendimento_url');

        return $a;
    }

    /**
     * Cor na planta conforme o serviço CRM mais recente (updated_at) do mesmo cliente + equipamento.
     * Cancelados são ignorados na busca. Assim o verde/laranja reflete o estado atual, não OS antigas.
     *
     * @param  Collection<int, PlantaEquipamentoMarcador>  $marcadores
     * @return Collection<int, PlantaEquipamentoMarcador>
     */
    private function enriquecerMarcadoresComServico(PlantaBaixa $planta, Collection $marcadores): Collection
    {
        if ($marcadores->isEmpty()) {
            return $marcadores;
        }

        $clienteId = (int) $planta->cliente_id;
        $equipIds = $marcadores->pluck('equipamento_id')->unique()->filter()->map(fn ($id) => (int) $id)->values();
        if ($equipIds->isEmpty()) {
            foreach ($marcadores as $m) {
                $m->setAttribute('planta_cor_servico', null);
                $m->setAttribute('servico_id', null);
                $m->setAttribute('servico_relatorio_url', null);
                $m->setAttribute('servico_atendimento_url', null);
            }

            return $marcadores;
        }

        $servicos = Servico::query()
            ->where('cliente_id', $clienteId)
            ->whereIn('equipamento_id', $equipIds)
            ->whereNotIn('status_operacional', ['cancelado'])
            ->orderByDesc('updated_at')
            ->get(['id', 'equipamento_id', 'status_operacional', 'updated_at']);

        $byEquip = [];
        foreach ($servicos as $s) {
            $eid = (int) $s->equipamento_id;
            if (! isset($byEquip[$eid])) {
                $byEquip[$eid] = [];
            }
            $byEquip[$eid][] = $s;
        }

        $map = [];
        $servicoLink = [];
        foreach ($equipIds as $eid) {
            $rows = $byEquip[$eid] ?? [];
            $map[$eid] = null;
            $servicoLink[$eid] = null;

            if ($rows === []) {
                continue;
            }

            // $rows já vem ordenado por updated_at desc — só o serviço mais recente define a cor
            $latest = $rows[0];
            $st = $latest->status_operacional;
            if (in_array($st, ['em_andamento', 'pausado'], true)) {
                $map[$eid] = 'servico_execucao';
            } elseif ($st === 'concluido') {
                $map[$eid] = 'servico_concluido';
            }

            $coll = collect($rows);
            $paraRelatorio = $coll->firstWhere('status_operacional', 'concluido') ?? $coll->first();
            if ($paraRelatorio) {
                $sid = (int) $paraRelatorio->id;
                $servicoLink[$eid] = [
                    'id' => $sid,
                    'relatorio' => route('crm.servicos.relatorio', ['servico' => $sid], true),
                    'atendimento' => route('crm.relatorios.show', ['servico' => $sid], true),
                ];
            }
        }

        foreach ($marcadores as $m) {
            $eid = (int) $m->equipamento_id;
            $m->setAttribute('planta_cor_servico', $map[$eid] ?? null);
            $link = $servicoLink[$eid] ?? null;
            $m->setAttribute('servico_id', $link['id'] ?? null);
            $m->setAttribute('servico_relatorio_url', $link['relatorio'] ?? null);
            $m->setAttribute('servico_atendimento_url', $link['atendimento'] ?? null);
        }

        return $marcadores;
    }

    public function destroyMarcador(PlantaEquipamentoMarcador $marcador)
    {
        $marcador->delete();
        return response()->json(['success' => true]);
    }
}
