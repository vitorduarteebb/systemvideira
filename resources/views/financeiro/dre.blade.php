<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Financeiro - DRE</title>
    @include('financeiro.partials.styles')
</head>
<body>
    @include('components.sidebar')

    <div class="main-content">
        <div class="top-header">
            <h1>DRE - Demonstrativo de Resultado</h1>
            <div class="header-actions">
                <a class="btn" href="{{ route('crm.financeiro.dashboard') }}">Dashboard</a>
                <a class="btn" href="{{ route('crm.financeiro.contas-receber.index') }}">Contas a Receber</a>
                <a class="btn" href="{{ route('crm.financeiro.contas-pagar.index') }}">Contas a Pagar</a>
            </div>
        </div>

        <div class="content-area">
            <form method="GET" class="card filter-grid">
                <div>
                    <label>Data início</label>
                    <input type="date" name="inicio" value="{{ optional($inicio)->format('Y-m-d') }}">
                </div>
                <div>
                    <label>Data fim</label>
                    <input type="date" name="fim" value="{{ optional($fim)->format('Y-m-d') }}">
                </div>
                <div>
                    <label>Regime</label>
                    <select name="regime">
                        <option value="competencia" {{ $regime === 'competencia' ? 'selected' : '' }}>Competência</option>
                        <option value="caixa" {{ $regime === 'caixa' ? 'selected' : '' }}>Caixa</option>
                    </select>
                </div>
                <div class="filter-action">
                    <button class="btn btn-primary" type="submit">Calcular DRE</button>
                    <a class="btn" href="{{ route('crm.financeiro.dre') }}">Limpar</a>
                </div>
            </form>

            <div class="card">
                <div class="table-wrap">
                <table class="table compact">
                    <tbody>
                        <tr>
                            <th>Receita Bruta</th>
                            <td>R$ {{ number_format($dre['receita_bruta'], 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>(-) Deduções</th>
                            <td>R$ {{ number_format($dre['deducoes'], 2, ',', '.') }}</td>
                        </tr>
                        <tr class="result-row">
                            <th>= Receita Líquida</th>
                            <td>R$ {{ number_format($dre['receita_liquida'], 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>(-) Despesas Operacionais</th>
                            <td>R$ {{ number_format($dre['despesas_operacionais'], 2, ',', '.') }}</td>
                        </tr>
                        <tr class="result-row">
                            <th>= Resultado Operacional</th>
                            <td class="{{ $dre['resultado_operacional'] >= 0 ? 'text-positive' : 'text-negative' }}">
                                R$ {{ number_format($dre['resultado_operacional'], 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Margem Operacional</th>
                            <td>{{ number_format($dre['margem_percentual'], 2, ',', '.') }}%</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
