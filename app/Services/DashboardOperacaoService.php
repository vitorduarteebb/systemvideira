<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Colaborador;
use App\Models\Equipamento;
use App\Models\Servico;
use App\Models\ServicoHoraRegistro;
use App\Support\EnderecoBrasil;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardOperacaoService
{
    public function build(int $year, int $month): array
    {
        $inicio = Carbon::create($year, $month, 1)->startOfDay();
        $fim = $inicio->copy()->endOfMonth()->endOfDay();

        $servicosMes = $this->servicosReferentesAoMes($inicio, $fim)->get();
        $idsMes = $servicosMes->pluck('id')->all();

        $totalClientes = Cliente::count();
        $totalEquipamentos = Equipamento::where('ativo', true)->count();
        $equipInativos = Equipamento::where('ativo', false)->count();

        $falhasMes = $servicosMes->filter(fn (Servico $s) => $this->ehCorretivaOuFalha($s))->count();

        [$minCorretivas, $minPreventivas, $minOutras] = $this->minutosPorNatureza($idsMes, $inicio, $fim);

        $alarmesOperacionais = Servico::query()
            ->whereIn('status_operacional', ['pendente', 'em_andamento', 'pausado'])
            ->count();

        $alarmesManutencao = Equipamento::query()
            ->where('ativo', true)
            ->whereNotNull('ultima_manutencao')
            ->whereDate('ultima_manutencao', '<', now()->subDays(35)->toDateString())
            ->count();

        $totalNaoCancelados = $servicosMes->where('status_operacional', '!=', 'cancelado')->count();
        $concluidos = $servicosMes->where('status_operacional', 'concluido')->count();
        $pctConclusao = $totalNaoCancelados > 0
            ? round(100 * $concluidos / $totalNaoCancelados, 1)
            : 0.0;

        $planejadoOs = $totalNaoCancelados;
        $executadoOs = $concluidos;

        $geoClientes = $this->clientesPorUf();
        $geoServicosAbertos = $this->servicosAbertosPorUf();

        $topFalhas = $this->topFalhasPorTipo($servicosMes);

        $horasManutencaoSerie = $this->serieHorasMeses(6);

        $rankingTecnicos = $this->rankingTecnicosMes($inicio, $fim);

        $agendaResumo = $this->agendaProximosDias(7);

        [$mtbfHoras, $mttrHoras, $dispPct] = $this->mtbfMttrDisponibilidade($inicio, $fim);

        return [
            'periodo_label' => $inicio->copy()->locale('pt_BR')->translatedFormat('F \d\e Y'),
            'total_clientes' => $totalClientes,
            'total_equipamentos' => $totalEquipamentos,
            'equipamentos_inativos' => $equipInativos,
            'falhas_mes' => $falhasMes,
            'horas_corretivas_h' => round($minCorretivas / 60, 1),
            'horas_preventivas_h' => round($minPreventivas / 60, 1),
            'horas_outras_h' => round($minOutras / 60, 1),
            'alarmes_ativos' => $alarmesOperacionais + $alarmesManutencao,
            'alarmes_detalhe' => [
                'operacionais' => $alarmesOperacionais,
                'manutencao_vencida' => $alarmesManutencao,
            ],
            'pct_conclusao' => $pctConclusao,
            'planejado_os' => $planejadoOs,
            'executado_os' => $executadoOs,
            'geo_clientes_uf' => $geoClientes,
            'geo_servicos_abertos_uf' => $geoServicosAbertos,
            'top_falhas' => $topFalhas,
            'horas_manutencao_serie' => $horasManutencaoSerie,
            'ranking_tecnicos' => $rankingTecnicos,
            'agenda_resumo' => $agendaResumo,
            'mtbf_horas' => $mtbfHoras,
            'mttr_horas' => $mttrHoras,
            'disponibilidade_pct' => $dispPct,
        ];
    }

    private function servicosReferentesAoMes(Carbon $inicio, Carbon $fim)
    {
        $di = $inicio->toDateString();
        $df = $fim->toDateString();

        return Servico::query()->where(function ($q) use ($inicio, $fim, $di, $df) {
            $q->whereBetween('data_inicio', [$di, $df])
                ->orWhereBetween('horario_agendamento', [$inicio, $fim]);
        });
    }

    private function ehCorretivaOuFalha(Servico $s): bool
    {
        $t = mb_strtolower((string) $s->tipo_tarefa.' '.(string) $s->descricao, 'UTF-8');

        return (bool) preg_match('/corretiv|emerg|falha|defeito|avaria|urg[eê]ncia|chamado|parada\b/i', $t);
    }

    private function ehPreventiva(Servico $s): bool
    {
        $t = mb_strtolower((string) $s->tipo_tarefa.' '.(string) $s->descricao, 'UTF-8');

        return (bool) preg_match('/prevent|pmoc|rotina|inspe[cç][aã]o|calibr/i', $t);
    }

    /**
     * @param  array<int, int>  $servicoIds
     * @return array{0: float, 1: float, 2: float} minutos
     */
    private function minutosPorNatureza(array $servicoIds, Carbon $inicio, Carbon $fim): array
    {
        if ($servicoIds === []) {
            return [0.0, 0.0, 0.0];
        }

        $porServico = Servico::query()->whereIn('id', $servicoIds)->get()->keyBy('id');

        $rows = ServicoHoraRegistro::query()
            ->whereIn('servico_id', $servicoIds)
            ->whereBetween('horario', [$inicio, $fim])
            ->get(['servico_id', 'tempo_corrido_minutos']);

        $c = 0.0;
        $p = 0.0;
        $o = 0.0;

        foreach ($rows as $row) {
            $m = (float) ($row->tempo_corrido_minutos ?? 0);
            if ($m <= 0) {
                continue;
            }
            $serv = $porServico->get($row->servico_id);
            if (! $serv) {
                $o += $m;

                continue;
            }
            if ($this->ehPreventiva($serv) && ! $this->ehCorretivaOuFalha($serv)) {
                $p += $m;
            } elseif ($this->ehCorretivaOuFalha($serv)) {
                $c += $m;
            } else {
                $o += $m;
            }
        }

        return [$c, $p, $o];
    }

    /** @return array<string, int> */
    private function clientesPorUf(): array
    {
        $map = [];
        foreach (Cliente::query()->get(['id', 'endereco_completo']) as $c) {
            $uf = EnderecoBrasil::ufDeTexto($c->endereco_completo) ?? 'Não informado';
            $map[$uf] = ($map[$uf] ?? 0) + 1;
        }
        arsort($map);

        return $map;
    }

    /** @return array<string, int> */
    private function servicosAbertosPorUf(): array
    {
        $map = [];
        $q = Servico::query()
            ->with('cliente:id,endereco_completo')
            ->whereIn('status_operacional', ['pendente', 'em_andamento', 'pausado']);

        foreach ($q->get() as $s) {
            $end = $s->cliente?->endereco_completo ?? '';
            $uf = EnderecoBrasil::ufDeTexto($end) ?? 'Não informado';
            $map[$uf] = ($map[$uf] ?? 0) + 1;
        }
        arsort($map);

        return $map;
    }

    /** @return array<int, array{tipo: string, total: int}> */
    private function topFalhasPorTipo(Collection $servicosMes): array
    {
        $filt = $servicosMes->filter(fn (Servico $s) => $this->ehCorretivaOuFalha($s));
        $grupos = $filt->groupBy(function (Servico $s) {
            $t = trim((string) $s->tipo_tarefa);

            return $t !== '' ? mb_strimwidth($t, 0, 48, '…') : 'Sem tipo informado';
        });

        $out = [];
        foreach ($grupos as $label => $col) {
            $out[] = ['tipo' => $label, 'total' => $col->count()];
        }
        usort($out, fn ($a, $b) => $b['total'] <=> $a['total']);

        return array_slice($out, 0, 5);
    }

    /** @return array{labels: array<int, string>, valores: array<int, float>} */
    private function serieHorasMeses(int $meses): array
    {
        $labels = [];
        $valores = [];
        $cursor = now()->startOfMonth()->subMonths($meses - 1);
        for ($i = 0; $i < $meses; $i++) {
            $inicio = $cursor->copy()->startOfMonth();
            $fim = $cursor->copy()->endOfMonth()->endOfDay();
            $ids = $this->servicosReferentesAoMes($inicio, $fim)->pluck('id')->all();
            $min = $ids === [] ? 0.0 : (float) ServicoHoraRegistro::query()
                ->whereIn('servico_id', $ids)
                ->whereBetween('horario', [$inicio, $fim])
                ->sum('tempo_corrido_minutos');
            $labels[] = $cursor->copy()->locale('pt_BR')->translatedFormat('M/y');
            $valores[] = round($min / 60, 1);
            $cursor->addMonth();
        }

        return ['labels' => $labels, 'valores' => $valores];
    }

    /** @return array<int, array{nome: string, minutos: int, horas: float}> */
    private function rankingTecnicosMes(Carbon $inicio, Carbon $fim): array
    {
        $rows = ServicoHoraRegistro::query()
            ->selectRaw('colaborador_id, SUM(COALESCE(tempo_corrido_minutos,0)) as t')
            ->whereNotNull('colaborador_id')
            ->whereBetween('horario', [$inicio, $fim])
            ->groupBy('colaborador_id')
            ->orderByDesc('t')
            ->limit(8)
            ->get();

        $colabIds = $rows->pluck('colaborador_id')->filter()->all();
        $nomes = Colaborador::query()->whereIn('id', $colabIds)->pluck('nome_profissional', 'id');

        $out = [];
        foreach ($rows as $r) {
            $id = (int) $r->colaborador_id;
            $min = (int) $r->t;
            $out[] = [
                'nome' => $nomes[$id] ?? ('#'.$id),
                'minutos' => $min,
                'horas' => round($min / 60, 1),
            ];
        }

        return $out;
    }

    /** @return \Illuminate\Support\Collection<int, \App\Models\Servico> */
    private function agendaProximosDias(int $dias): Collection
    {
        $ini = now()->startOfDay();
        $f = now()->addDays($dias)->endOfDay();

        return Servico::query()
            ->with(['cliente', 'tecnicos'])
            ->whereNotIn('status_operacional', ['concluido', 'cancelado'])
            ->where(function ($q) use ($ini, $f) {
                $q->whereBetween('horario_agendamento', [$ini, $f])
                    ->orWhereBetween('data_inicio', [$ini->toDateString(), $f->toDateString()]);
            })
            ->orderBy('horario_agendamento')
            ->orderBy('data_inicio')
            ->limit(12)
            ->get();
    }

    /**
     * @return array{0: ?float, 1: ?float, 2: ?float} horas
     */
    private function mtbfMttrDisponibilidade(Carbon $inicio, Carbon $fim): array
    {
        $concluidos = Servico::query()
            ->where('status_operacional', 'concluido')
            ->whereNotNull('horario_inicio_execucao')
            ->whereNotNull('horario_fim_execucao')
            ->whereBetween('horario_fim_execucao', [$inicio, $fim])
            ->get(['id', 'equipamento_id', 'horario_inicio_execucao', 'horario_fim_execucao']);

        $duracoesMin = [];
        foreach ($concluidos as $s) {
            $a = $s->horario_inicio_execucao;
            $b = $s->horario_fim_execucao;
            if (! $a || ! $b) {
                continue;
            }
            $duracoesMin[] = max(0, Carbon::parse($a)->diffInMinutes(Carbon::parse($b)));
        }

        $mttr = count($duracoesMin) > 0 ? array_sum($duracoesMin) / count($duracoesMin) / 60 : null;

        // MTBF: média de dias entre serviços corretivos no mesmo equipamento (equipamentos com 2+ eventos)
        $corretivas = Servico::query()
            ->whereNotNull('equipamento_id')
            ->whereBetween('data_inicio', [$inicio->toDateString(), $fim->toDateString()])
            ->get();

        $porEquip = $corretivas->filter(fn (Servico $s) => $this->ehCorretivaOuFalha($s))
            ->groupBy('equipamento_id');

        $intervalosHoras = [];
        foreach ($porEquip as $eid => $col) {
            if ($col->count() < 2) {
                continue;
            }
            $datas = $col->sortBy('data_inicio')->values();
            for ($i = 1; $i < $datas->count(); $i++) {
                $d0 = Carbon::parse($datas[$i - 1]->data_inicio)->startOfDay();
                $d1 = Carbon::parse($datas[$i]->data_inicio)->startOfDay();
                $intervalosHoras[] = max(1, $d0->diffInHours($d1));
            }
        }

        $mtbf = count($intervalosHoras) > 0 ? array_sum($intervalosHoras) / count($intervalosHoras) : null;

        $disp = null;
        if ($mtbf !== null && $mttr !== null && ($mtbf + $mttr) > 0) {
            $disp = round(100 * $mtbf / ($mtbf + $mttr), 1);
        }

        return [$mtbf, $mttr, $disp];
    }
}
