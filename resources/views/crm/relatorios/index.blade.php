<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; color: #1a202c; display: flex; min-height: 100vh; }
        .main-wrapper { margin-left: 280px; width: calc(100% - 280px); padding: 20px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 2px 10px rgba(15, 23, 42, .04); }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 20px; margin-bottom: 16px; }
        .title { font-size: 24px; font-weight: 800; }
        .subtitle { color: #64748b; font-size: 13px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; background: #f8fafc; }
        tr:hover { background: #f8fafc; }
        .badge { font-size: 10px; border-radius: 999px; padding: 4px 8px; font-weight: 600; }
        .badge-pendente { background: #fef3c7; color: #92400e; }
        .badge-andamento { background: #dbeafe; color: #1d4ed8; }
        .badge-concluido { background: #d1fae5; color: #065f46; }
        .badge-cancelado { background: #f1f5f9; color: #475569; }
        .btn-link { display: inline-block; padding: 6px 12px; background: #10b981; border: 1px solid #059669; border-radius: 6px; font-size: 12px; color: white; font-weight: 600; text-decoration: none; }
        .btn-link:hover { background: #059669; }
        .btn-ghost { display: inline-block; padding: 6px 12px; background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 6px; font-size: 12px; color: #0369a1; font-weight: 600; text-decoration: none; margin-left: 4px; }
        .btn-ghost:hover { background: #e0f2fe; }
        .pagination { display: flex; gap: 8px; justify-content: center; padding: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 13px; }
        .pagination a { background: #fff; border: 1px solid #e2e8f0; color: #334155; }
        .pagination a:hover { background: #f1f5f9; }
        .pagination .active span { background: #2563eb; color: white; border: none; }
        .filtros-relatorio { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; }
        .filtros-relatorio .campo { display: flex; flex-direction: column; gap: 4px; min-width: 160px; }
        .filtros-relatorio label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .filtros-relatorio input, .filtros-relatorio select { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
        .filtros-relatorio .btn-filtrar { padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .filtros-relatorio .btn-filtrar:hover { background: #1d4ed8; }
        .filtros-relatorio .btn-limpar { padding: 8px 14px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 13px; }
        @media (max-width: 1200px) { .main-wrapper { margin-left: 0; width: 100%; } }
    </style>
</head>
<body>
    @include('components.sidebar')

    @php
        $mapStatus = [
            'pendente' => ['badge-pendente', 'Pendente'],
            'pendencia' => ['badge-pendente', 'Pendência'],
            'em_andamento' => ['badge-andamento', 'Em Andamento'],
            'pausado' => ['badge-pendente', 'Pausado'],
            'concluido' => ['badge-concluido', 'Concluído'],
            'cancelado' => ['badge-cancelado', 'Cancelado'],
        ];
    @endphp

    <div class="main-wrapper">
        <div class="card header">
            <div>
                <div class="title">Relatórios</div>
                <div class="subtitle">Lista de serviços e relatórios de atendimento</div>
            </div>
        </div>

        <div class="card" style="overflow-x: auto;">
            <form method="get" action="{{ route('crm.relatorios.index') }}" class="filtros-relatorio">
                <div class="campo" style="flex: 1; min-width: 200px; max-width: 320px;">
                    <label for="busca_cliente">Cliente (digite para filtrar)</label>
                    <input type="text" id="busca_cliente" name="busca_cliente" value="{{ request('busca_cliente') }}" placeholder="Nome do cliente..." autocomplete="off" list="lista_clientes_rel">
                    <datalist id="lista_clientes_rel">
                        @foreach($clientes as $c)
                            <option value="{{ $c->nome }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="campo" style="min-width: 200px;">
                    <label for="cliente_id">Ou selecione</label>
                    <select id="cliente_id" name="cliente_id">
                        <option value="">Todos</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->id }}" {{ (string) request('cliente_id') === (string) $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="campo">
                    <label for="data_de">Data início (de)</label>
                    <input type="date" id="data_de" name="data_de" value="{{ request('data_de') }}">
                </div>
                <div class="campo">
                    <label for="data_ate">Data início (até)</label>
                    <input type="date" id="data_ate" name="data_ate" value="{{ request('data_ate') }}">
                </div>
                <button type="submit" class="btn-filtrar">Filtrar</button>
                <a href="{{ route('crm.relatorios.index') }}" class="btn-limpar">Limpar</a>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>O.S / VE</th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Data / Horário</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicos as $s)
                        @php
                            $statusCfg = $mapStatus[$s->status_efetivo] ?? ['badge-cancelado', ucfirst($s->status_efetivo ?? '-')];
                            $dataBase = $s->horario_agendamento ?: $s->data_inicio;
                            $ultimoHora = $s->horas->sortByDesc('horario')->first();
                            $statusHoras = $ultimoHora ? match($ultimoHora->monitoramento) { 'check_in' => 'Check-In', 'check_out' => 'Check-Out', 'pausa' => 'Pausa', 'retorno' => 'Retorno', default => 'Ajuste' } : null;
                        @endphp
                        <tr>
                            <td><strong>{{ $s->numero_os ?? '#' . $s->id }}</strong><br><span style="font-size: 11px; color: #64748b;">VE: {{ $s->codigo_ve ?? '-' }}</span></td>
                            <td>{{ $s->cliente->nome ?? '-' }}</td>
                            <td>{{ $s->tipo_tarefa ?: '-' }}</td>
                            <td>{{ $dataBase ? $dataBase->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <span class="badge {{ $statusCfg[0] }}">{{ $statusCfg[1] }}</span>
                                @if($statusHoras)
                                    <br><span style="font-size: 11px; color: #64748b;">{{ $statusHoras }}{{ $ultimoHora->monitoramento === 'pausa' && $ultimoHora->motivo ? ': ' . $ultimoHora->motivo : '' }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('crm.relatorios.show', $s->id) }}" class="btn-link">Atendimento</a>
                                <a href="{{ route('crm.servicos.relatorio', $s->id) }}" class="btn-ghost" target="_blank">Imprimir</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">Nenhum serviço encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($servicos->hasPages())
                <div class="pagination">
                    @if ($servicos->onFirstPage())
                        <span class="disabled">◀</span>
                    @else
                        <a href="{{ $servicos->previousPageUrl() }}">◀</a>
                    @endif
                    @foreach ($servicos->getUrlRange(1, $servicos->lastPage()) as $page => $url)
                        @if ($page == $servicos->currentPage())
                            <span class="active"><span>{{ $page }}</span></span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if ($servicos->hasMorePages())
                        <a href="{{ $servicos->nextPageUrl() }}">▶</a>
                    @else
                        <span class="disabled">▶</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>
