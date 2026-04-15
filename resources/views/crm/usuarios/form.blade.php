<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $usuario->exists ? 'Editar usuário' : 'Novo usuário' }} - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; color: #0f172a; display: flex; min-height: 100vh; }
        .main { margin-left: 280px; width: calc(100% - 280px); padding: 20px; max-width: 640px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 22px 24px 26px; box-shadow: 0 8px 24px rgba(15,23,42,.04); }
        h1 { font-size: 22px; font-weight: 800; margin-bottom: 6px; }
        .lead { color: #64748b; font-size: 14px; margin-bottom: 20px; line-height: 1.45; }
        .group { margin-bottom: 16px; }
        label { display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 5px; }
        input, select { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 14px; font-family: inherit; }
        .hint { font-size: 12px; color: #94a3b8; margin-top: 5px; line-height: 1.35; }
        .err { color: #b91c1c; font-size: 12px; margin-top: 4px; }
        .actions { display: flex; gap: 10px; margin-top: 22px; flex-wrap: wrap; }
        .btn { border: none; border-radius: 10px; padding: 10px 16px; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-primary { background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; }
        .btn-ghost { background: #f8fafc; border: 1px solid #cbd5e1; color: #334155; }
        @media (max-width: 1100px) { .main { margin-left: 0; width: 100%; } }
    </style>
</head>
<body>
    @include('components.sidebar')
    <div class="main">
        <div class="card">
            <h1>{{ $usuario->exists ? 'Editar usuário' : 'Novo usuário' }}</h1>
            <p class="lead">Perfil <strong>técnico</strong> usa o portal simplificado (agenda + execução de serviço). Vincule ao colaborador para reconhecer o cadastro na agenda.</p>

            @php
                $usuario->loadMissing('colaboradorConta');
                $colSel = old('colaborador_id', $usuario->colaboradorConta?->id);
            @endphp

            <form method="POST" action="{{ $usuario->exists ? route('crm.usuarios.update', $usuario) : route('crm.usuarios.store') }}">
                @csrf
                @if($usuario->exists)
                    @method('PUT')
                @endif

                <div class="group">
                    <label for="name">Nome completo</label>
                    <input id="name" name="name" value="{{ old('name', $usuario->name) }}" required autocomplete="name">
                    @error('name')<div class="err">{{ $message }}</div>@enderror
                </div>

                <div class="group">
                    <label for="email">E-mail (login)</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $usuario->email) }}" required autocomplete="email">
                    @error('email')<div class="err">{{ $message }}</div>@enderror
                </div>

                <div class="group">
                    <label for="password">{{ $usuario->exists ? 'Nova senha (deixe em branco para manter)' : 'Senha' }}</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" {{ $usuario->exists ? '' : 'required' }}>
                    @error('password')<div class="err">{{ $message }}</div>@enderror
                </div>

                <div class="group">
                    <label for="password_confirmation">Confirmar senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" {{ $usuario->exists ? '' : 'required' }}>
                </div>

                <div class="group">
                    <label for="role">Perfil de acesso</label>
                    <select id="role" name="role" required>
                        @foreach(['admin' => 'Administrador', 'comercial' => 'Comercial', 'tecnico' => 'Técnico (colaborador em campo)'] as $val => $lab)
                            <option value="{{ $val }}" {{ old('role', $usuario->role) === $val ? 'selected' : '' }}>{{ $lab }}</option>
                        @endforeach
                    </select>
                    @error('role')<div class="err">{{ $message }}</div>@enderror
                </div>

                <div class="group">
                    <label for="colaborador_id">Vincular a colaborador (opcional)</label>
                    <select id="colaborador_id" name="colaborador_id">
                        <option value="">— Nenhum —</option>
                        @foreach($colaboradores as $c)
                            <option value="{{ $c->id }}" {{ (string) $colSel === (string) $c->id ? 'selected' : '' }}>
                                {{ $c->nome_profissional }}
                                @if($c->user_id && (!$usuario->exists || (int) $c->user_id !== (int) $usuario->id))
                                    (vínculo atual com outro usuário — será transferido para este)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="hint">O colaborador precisa estar ativo. Um colaborador só pode estar vinculado a um usuário por vez. O e-mail do login e do colaborador podem ser iguais para facilitar o reconhecimento automático.</p>
                    @error('colaborador_id')<div class="err">{{ $message }}</div>@enderror
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">{{ $usuario->exists ? 'Salvar alterações' : 'Criar usuário' }}</button>
                    <a href="{{ route('crm.usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
