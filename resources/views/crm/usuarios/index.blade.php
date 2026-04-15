<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; color: #0f172a; display: flex; min-height: 100vh; }
        .main { margin-left: 280px; width: calc(100% - 280px); padding: 20px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 8px 24px rgba(15,23,42,.04); }
        .header { padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid #e2e8f0; }
        .title { font-size: 22px; font-weight: 800; }
        .subtitle { color: #64748b; font-size: 13px; margin-top: 4px; }
        .btn { border: none; border-radius: 10px; padding: 9px 14px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary { background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; }
        .btn-ghost { background: #f8fafc; border: 1px solid #cbd5e1; color: #334155; }
        .filters { padding: 14px 20px; display: flex; gap: 8px; border-bottom: 1px solid #e2e8f0; }
        .filters input { flex: 1; border: 1px solid #cbd5e1; border-radius: 999px; padding: 9px 14px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 14px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 13px; }
        th { font-size: 11px; text-transform: uppercase; color: #64748b; background: #f8fafc; }
        tbody tr:hover { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; }
        .badge-admin { background: #fef3c7; color: #92400e; }
        .badge-tecnico { background: #dbeafe; color: #1e40af; }
        .badge-comercial { background: #e0e7ff; color: #3730a3; }
        .success { background: #dcfce7; color: #166534; padding: 12px 16px; margin: 16px 20px 0; border-radius: 10px; font-size: 14px; font-weight: 600; }
        .err { background: #fee2e2; color: #991b1b; padding: 12px 16px; margin: 16px 20px 0; border-radius: 10px; font-size: 14px; }
        .pagination { padding: 16px 20px; }
        @media (max-width: 1100px) { .main { margin-left: 0; width: 100%; } }
    </style>
</head>
<body>
    @include('components.sidebar')
    <div class="main">
        <div class="card">
            <div class="header">
                <div>
                    <div class="title">Usuários do sistema</div>
                    <div class="subtitle">Crie logins, defina o perfil e vincule ao cadastro do colaborador (portal do técnico).</div>
                </div>
                <a href="{{ route('crm.usuarios.create') }}" class="btn btn-primary">+ Novo usuário</a>
            </div>

            @if (session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif
            @if ($errors->has('delete'))
                <div class="err">{{ $errors->first('delete') }}</div>
            @endif

            <form class="filters" method="GET" action="{{ route('crm.usuarios.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome ou e-mail…">
                <button type="submit" class="btn btn-ghost">Buscar</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Colaborador vinculado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
                            <td><strong>{{ $u->name }}</strong></td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if($u->role === 'admin')
                                    <span class="badge badge-admin">Admin</span>
                                @elseif($u->role === 'tecnico')
                                    <span class="badge badge-tecnico">Técnico</span>
                                @else
                                    <span class="badge badge-comercial">Comercial</span>
                                @endif
                            </td>
                            <td>
                                @if($u->colaboradorConta)
                                    {{ $u->colaboradorConta->nome_profissional }}
                                @else
                                    <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">
                                <a href="{{ route('crm.usuarios.edit', $u) }}" class="btn btn-ghost" style="padding:6px 10px;">Editar</a>
                                <form action="{{ route('crm.usuarios.destroy', $u) }}" method="POST" style="display:inline;" onsubmit="return confirm('Excluir este usuário?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost" style="padding:6px 10px;color:#b91c1c;border-color:#fecaca;">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;color:#64748b;padding:24px;">Nenhum usuário encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">{{ $usuarios->links() }}</div>
        </div>
    </div>
</body>
</html>
