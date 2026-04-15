<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\FinancialTransaction;
use App\Models\Servico;
use App\Models\Tecnico;
use App\Services\DashboardOperacaoService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->isTecnico()) {
            return $this->dashboardColaborador($user);
        }

        $year = (int) $request->input('ano', now()->year);
        $month = (int) $request->input('mes', now()->month);
        $year = max(2000, min(2100, $year));
        $month = max(1, min(12, $month));

        $operacao = app(DashboardOperacaoService::class)->build($year, $month);

        // Cálculos financeiros (mesmo mês/ano da visão operacional)
        $entradas = FinancialTransaction::where('type', 'entrada')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        $saidas = FinancialTransaction::where('type', 'saida')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        $saldoOperacional = $entradas - $saidas;
        $resultadoLiquido = $saldoOperacional;

        // Dados para gráfico mensal (últimos 4 meses)
        $meses = [];
        $receitas = [];
        $despesas = [];
        
        for ($i = 3; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $meses[] = $date->translatedFormat('M');

            $receitas[] = FinancialTransaction::where('type', 'entrada')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            $despesas[] = FinancialTransaction::where('type', 'saida')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');
        }

        // Equipe técnica
        $tecnicosAtivos = Tecnico::where('status', 'ativo')->count();
        $totalTecnicos = Tecnico::count();

        return view('dashboard', compact(
            'user',
            'year',
            'month',
            'operacao',
            'saldoOperacional',
            'resultadoLiquido',
            'entradas',
            'saidas',
            'meses',
            'receitas',
            'despesas',
            'tecnicosAtivos',
            'totalTecnicos'
        ));
    }

    private function dashboardColaborador($user)
    {
        $colab = Colaborador::resolveFromUser($user);
        $emExecucao = collect();
        $proximos = collect();

        if ($colab) {
            $emExecucao = Servico::with('cliente')
                ->whereHas('tecnicos', fn ($q) => $q->where('colaboradores.id', $colab->id))
                ->whereIn('status_operacional', ['em_andamento', 'pausado'])
                ->orderByDesc('updated_at')
                ->limit(12)
                ->get();

            $hoje = now()->toDateString();
            $proximos = Servico::with('cliente')
                ->whereHas('tecnicos', fn ($q) => $q->where('colaboradores.id', $colab->id))
                ->whereNotIn('status_operacional', ['concluido', 'cancelado'])
                ->where(function ($q) use ($hoje) {
                    $q->whereDate('horario_agendamento', $hoje)
                        ->orWhereDate('data_inicio', $hoje)
                        ->orWhereHas('diasTrabalho', fn ($d) => $d->whereDate('data', $hoje));
                })
                ->orderBy('horario_agendamento')
                ->limit(15)
                ->get();
        }

        return view('dashboard-colaborador', compact('user', 'colab', 'emExecucao', 'proximos'));
    }
}
