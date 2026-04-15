<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Financeiro - Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('financeiro.partials.styles')
</head>
<body>
    @include('components.sidebar')

    <div class="main-content">
        <div class="top-header">
            <h1>Resumo Financeiro</h1>
            <div class="header-actions">
                <a class="btn btn-primary" href="{{ route('crm.financeiro.contas-receber.index') }}">Contas a Receber</a>
                <a class="btn" href="{{ route('crm.financeiro.contas-pagar.index') }}">Contas a Pagar</a>
                <a class="btn" href="{{ route('crm.financeiro.dre') }}">DRE</a>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <form method="GET" class="card filter-grid">
                <div>
                    <label>Início</label>
                    <input type="date" name="inicio" value="{{ optional($inicio)->format('Y-m-d') }}">
                </div>
                <div>
                    <label>Fim</label>
                    <input type="date" name="fim" value="{{ optional($fim)->format('Y-m-d') }}">
                </div>
                <div>
                    <label>Regime DRE</label>
                    <select name="regime">
                        <option value="competencia" {{ request('regime', 'competencia') === 'competencia' ? 'selected' : '' }}>Competência</option>
                        <option value="caixa" {{ request('regime') === 'caixa' ? 'selected' : '' }}>Caixa</option>
                    </select>
                </div>
                <div class="filter-action">
                    <button class="btn btn-primary" type="submit">Atualizar</button>
                    <a class="btn" href="{{ route('crm.financeiro.dashboard') }}">Limpar</a>
                </div>
            </form>

            <div class="stats-grid">
                <div class="stat-card">
                    <span class="kpi-title">A Receber em Aberto</span>
                    <strong>R$ {{ number_format($totalReceberAberto, 2, ',', '.') }}</strong>
                </div>
                <div class="stat-card">
                    <span class="kpi-title">A Pagar em Aberto</span>
                    <strong>R$ {{ number_format($totalPagarAberto, 2, ',', '.') }}</strong>
                </div>
                <div class="stat-card">
                    <span class="kpi-title">Fluxo Previsto</span>
                    <strong class="{{ $fluxoPrevisto >= 0 ? 'text-positive' : 'text-negative' }}">
                        R$ {{ number_format($fluxoPrevisto, 2, ',', '.') }}
                    </strong>
                </div>
                <div class="stat-card">
                    <span class="kpi-title">Inadimplência</span>
                    <strong class="text-negative">{{ $quantidadeInadimplencia }} títulos | R$ {{ number_format($valorInadimplencia, 2, ',', '.') }}</strong>
                </div>
                <div class="stat-card">
                    <span class="kpi-title">Duplicatas Vencidas</span>
                    <strong class="text-warning">{{ $duplicatasVencidas }} parcelas vencidas</strong>
                </div>
            </div>

            <div class="chart-grid">
                <div class="card">
                    <h3>Projeção de Recebíveis x A Pagar (6 meses)</h3>
                    <canvas id="projecaoChart"></canvas>
                </div>
                <div class="card">
                    <h3>DRE ({{ request('regime', 'competencia') === 'caixa' ? 'Caixa' : 'Competência' }})</h3>
                    <div class="table-wrap">
                    <table class="table compact">
                        <tr><td>Receita Bruta</td><td>R$ {{ number_format($dre['receita_bruta'], 2, ',', '.') }}</td></tr>
                        <tr><td>Deduções</td><td>R$ {{ number_format($dre['deducoes'], 2, ',', '.') }}</td></tr>
                        <tr><td>Receita Líquida</td><td>R$ {{ number_format($dre['receita_liquida'], 2, ',', '.') }}</td></tr>
                        <tr><td>Despesas Operacionais</td><td>R$ {{ number_format($dre['despesas_operacionais'], 2, ',', '.') }}</td></tr>
                        <tr class="result-row">
                            <td>Resultado Operacional</td>
                            <td class="{{ $dre['resultado_operacional'] >= 0 ? 'text-positive' : 'text-negative' }}">
                                R$ {{ number_format($dre['resultado_operacional'], 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr><td>Margem</td><td>{{ number_format($dre['margem_percentual'], 2, ',', '.') }}%</td></tr>
                    </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Títulos críticos (vencidos ou vencendo em 7 dias)</h3>
                <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Descrição</th>
                            <th>Vencimento</th>
                            <th>Saldo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($titulosCriticos as $titulo)
                            <tr>
                                <td>{{ $titulo->cliente_nome ?: optional($titulo->cliente)->razao_social ?: '-' }}</td>
                                <td>{{ $titulo->descricao }}</td>
                                <td>{{ optional($titulo->data_vencimento)->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($titulo->saldo, 2, ',', '.') }}</td>
                                <td>
                                    @if($titulo->inadimplente)
                                        <span class="badge danger">Inadimplente ({{ $titulo->dias_atraso }}d)</span>
                                    @else
                                        <span class="badge warning">A vencer</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5">Nenhum título crítico no período.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const labels = @json($projecoes['labels']);
        const receber = @json($projecoes['receber']);
        const pagar = @json($projecoes['pagar']);

        const ctx = document.getElementById('projecaoChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Recebíveis',
                        data: receber,
                        backgroundColor: 'rgba(34, 197, 94, 0.6)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'A pagar',
                        data: pagar,
                        backgroundColor: 'rgba(239, 68, 68, 0.6)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: R$ ${Number(context.parsed.y).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
