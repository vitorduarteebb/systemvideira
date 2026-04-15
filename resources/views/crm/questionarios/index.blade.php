<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Questionários - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            color: #0f172a;
            display: flex;
            min-height: 100vh;
        }
        .main {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 20px;
        }
        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(15,23,42,.04);
        }
        .header {
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            gap: 10px;
        }
        .title {
            font-size: 22px;
            font-weight: 800;
        }
        .subtitle {
            color: #64748b;
            margin-top: 4px;
            font-size: 13px;
        }
        .header-right {
            display: flex;
            gap: 8px;
        }
        .btn {
            border: none;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary {
            background: linear-gradient(135deg,#2563eb,#1d4ed8);
            color: #fff;
        }
        .btn-primary:hover {
            box-shadow: 0 8px 18px rgba(37,99,235,.35);
            transform: translateY(-1px);
        }
        .btn-ghost {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            color: #334155;
        }
        .btn-ghost:hover {
            background: #e2e8f0;
        }
        .filters {
            padding: 14px 20px 12px;
            display: flex;
            gap: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .filters input {
            flex: 1;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 13px;
        }
        .filters input::placeholder {
            color: #94a3b8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px 14px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            font-size: 13px;
        }
        th {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            background: #f8fafc;
            letter-spacing: .04em;
        }
        tbody tr:hover {
            background: #f8fafc;
        }
        .q-title {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .q-meta {
            font-size: 11px;
            color: #64748b;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
        }
        .badge-green {
            background: #dcfce7;
            color: #15803d;
        }
        .badge-gray {
            background: #e2e8f0;
            color: #475569;
        }
        .badge-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .badge-yellow {
            background: #fef9c3;
            color: #854d0e;
        }
        .actions {
            display: flex;
            gap: 6px;
        }
        .actions .btn {
            padding: 6px 10px;
            font-size: 11px;
            border-radius: 8px;
        }
        .btn-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        .btn-danger:hover {
            background: #fecaca;
        }
        .pagination {
            padding: 10px 20px 14px;
        }
        @media (max-width: 1200px) {
            .main { margin-left: 0; width: 100%; }
            .header { flex-direction: column; align-items: flex-start; }
            .header-right { width: 100%; justify-content: flex-start; flex-wrap: wrap; }
        }
    </style>
</head>
<body>
    @include('components.sidebar')
    <div class="main">
        <div class="card header">
            <div>
                <div class="title">Gerenciar questionários</div>
                <div class="subtitle">Biblioteca de modelos para OS digital e relatórios</div>
            </div>
            <div class="header-right">
                <button class="btn btn-ghost" type="button" disabled title="Em breve">⤓ Importar planilha</button>
                <a href="{{ route('crm.questionarios.create') }}" class="btn btn-primary">+ Novo questionário</a>
            </div>
        </div>

        <div class="card">
            <form class="filters" method="GET" action="{{ route('crm.questionarios.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar por título...">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Perguntas</th>
                        <th>OS Digital</th>
                        <th>Atualizado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questionarios as $q)
                        <tr>
                            <td>
                                <div class="q-title">{{ $q->titulo }}</div>
                                <div class="q-meta">
                                    @if($q->questionario_pmoc)
                                        <span class="badge badge-yellow">PMOC</span>
                                    @endif
                                    @if($q->habilitar_resposta_equipamento)
                                        <span class="badge badge-blue">Por equipamento</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-gray">{{ $q->perguntas_count }} pergunta(s)</span>
                            </td>
                            <td>
                                @if($q->exibir_na_os_digital)
                                    <span class="badge badge-green">Sim</span>
                                @else
                                    <span class="badge badge-gray">Não</span>
                                @endif
                            </td>
                            <td>{{ $q->updated_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-ghost" href="{{ route('crm.questionarios.edit', $q->id) }}">Editar</a>
                                    <form method="POST" action="{{ route('crm.questionarios.duplicate', $q->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost">Duplicar</button>
                                    </form>
                                    <form method="POST" action="{{ route('crm.questionarios.destroy', $q->id) }}" onsubmit="return confirm('Remover questionário?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Remover</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Nenhum questionário encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $questionarios->links() }}</div>
        </div>
    </div>
</body>
</html>
