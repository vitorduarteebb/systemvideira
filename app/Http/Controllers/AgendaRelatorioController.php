<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaRelatorioController extends Controller
{
    public function index(Request $request)
    {
        $dataParam = $request->get('data', now()->format('Y-m-d'));
        $dataBase = Carbon::parse($dataParam);

        // Semana: segunda a domingo
        $inicioSemana = $dataBase->copy()->startOfWeek(Carbon::MONDAY);
        $fimSemana = $inicioSemana->copy()->endOfWeek(Carbon::SUNDAY);

        $portalColaborador = null;
        if ($request->user()?->isTecnico()) {
            $portalColaborador = Colaborador::resolveFromUser($request->user());
        }

        $q = Servico::with([
                'cliente',
                'tecnicos',
                'horas',
                'questionariosVinculados.questionario.perguntas',
                'diasTrabalho' => function ($q) use ($inicioSemana, $fimSemana) {
                    $q->whereBetween('data', [$inicioSemana->toDateString(), $fimSemana->toDateString()]);
                },
            ])
            ->where(function ($query) use ($inicioSemana, $fimSemana) {
                $query->whereBetween('horario_agendamento', [$inicioSemana, $fimSemana])
                    ->orWhereBetween('data_inicio', [$inicioSemana->toDateString(), $fimSemana->toDateString()])
                    ->orWhereHas('diasTrabalho', function ($q) use ($inicioSemana, $fimSemana) {
                        $q->whereBetween('data', [$inicioSemana->toDateString(), $fimSemana->toDateString()]);
                    });
            });

        if ($request->user()?->isTecnico()) {
            if ($portalColaborador) {
                $q->whereHas('tecnicos', fn ($qq) => $qq->where('colaboradores.id', $portalColaborador->id));
            } else {
                $q->whereRaw('1 = 0');
            }
        }

        $servicos = $q->orderBy('horario_agendamento')
            ->orderBy('data_inicio')
            ->get();

        $itensPorDia = [];
        foreach ($servicos as $servico) {
            $responsaveis = $servico->tecnicos->pluck('nome_profissional')->filter()->values();
            $responsavelResumo = $this->formatarResponsaveis($responsaveis->all());

            // Se o serviço tiver dias de trabalho cadastrados, cada dia deve aparecer na agenda
            if ($servico->diasTrabalho && $servico->diasTrabalho->count() > 0) {
                foreach ($servico->diasTrabalho as $diaTrabalho) {
                    if (!$diaTrabalho->data) {
                        continue;
                    }

                    $dia = Carbon::parse($diaTrabalho->data)->format('Y-m-d');
                    if ($dia < $inicioSemana->toDateString() || $dia > $fimSemana->toDateString()) {
                        continue;
                    }

                    $itensPorDia[$dia][] = $this->montarItemAgenda($servico, $diaTrabalho->hora_inicio ? substr($diaTrabalho->hora_inicio, 0, 5) : '--:--', $responsaveis, $responsavelResumo, $portalColaborador?->id);
                }
                continue;
            }

            // Fallback: serviços sem dias de trabalho usam horário_agendamento / data_inicio
            $dataBaseItem = $servico->horario_agendamento ?: $servico->data_inicio;
            if (!$dataBaseItem) {
                continue;
            }

            $dia = Carbon::parse($dataBaseItem)->format('Y-m-d');
            $horario = $servico->horario_agendamento ? Carbon::parse($servico->horario_agendamento)->format('H:i') : '--:--';
            $itensPorDia[$dia][] = $this->montarItemAgenda($servico, $horario, $responsaveis, $responsavelResumo, $portalColaborador?->id);
        }

        return view('crm.agenda', [
            'inicioSemana' => $inicioSemana,
            'fimSemana' => $fimSemana,
            'itensPorDia' => $itensPorDia,
            'portalColaborador' => $portalColaborador,
        ]);
    }

    private function montarItemAgenda($servico, string $horario, $responsaveis, string $responsavelResumo, ?int $portalColaboradorId = null): array
    {
        $ultimoHora = $servico->horas->sortByDesc('horario')->first();
        if ($portalColaboradorId) {
            $meu = $servico->horas->where('colaborador_id', $portalColaboradorId)->sortByDesc('horario')->first();
            if ($meu) {
                $ultimoHora = $meu;
            }
        }
        $statusHoras = null;
        $motivoPausa = null;
        if ($ultimoHora) {
            $statusHoras = match ($ultimoHora->monitoramento) {
                'check_in' => 'Check-In',
                'check_out' => 'Check-Out',
                'pausa' => 'Pausa',
                'retorno' => 'Retorno',
                default => 'Ajuste',
            };
            if ($ultimoHora->monitoramento === 'pausa' && $ultimoHora->motivo) {
                $motivoPausa = $ultimoHora->motivo;
            }
        }

        $url = route('crm.relatorios.show', $servico->id);
        $confirmarInicio = false;
        if ($portalColaboradorId && $servico->tecnicos->contains('id', $portalColaboradorId)) {
            $url = route('crm.colaborador.execucao', $servico);
            if (! in_array($servico->status_operacional, ['concluido', 'cancelado'], true)) {
                $ultimoMeu = $servico->horas->where('colaborador_id', $portalColaboradorId)->sortByDesc('horario')->first();
                $emAberto = $ultimoMeu && $ultimoMeu->monitoramento !== 'check_out';
                $confirmarInicio = ! $emAberto;
            }
        }

        return [
            'id' => $servico->id,
            'horario' => $horario,
            'cliente' => $servico->cliente->nome ?? '-',
            'codigo_ve' => $servico->codigo_ve ?? '-',
            'colaboradores' => $responsaveis->all(),
            'tipo_tarefa' => $servico->tipo_tarefa ?: ($servico->descricao ? mb_strimwidth($servico->descricao, 0, 45, '...') : 'Atividade'),
            'status' => $servico->status_efetivo,
            'status_horas' => $statusHoras,
            'motivo_pausa' => $motivoPausa,
            'responsavel_resumo' => $responsavelResumo,
            'url' => $url,
            'confirmar_inicio' => $confirmarInicio,
        ];
    }

    private function formatarResponsaveis(array $nomes): string
    {
        $total = count($nomes);
        if ($total === 0) {
            return '-';
        }
        if ($total <= 2) {
            return implode(', ', $nomes);
        }

        return $nomes[0] . ', ' . $nomes[1] . ' (+' . ($total - 2) . ')';
    }
}
