<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Minha área - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; color: #0f172a; display: flex; min-height: 100vh; }
        .main { margin-left: 280px; width: calc(100% - 280px); padding: 28px; max-width: 960px; }
        h1 { font-size: 26px; font-weight: 800; margin-bottom: 8px; }
        .lead { color: #64748b; font-size: 15px; margin-bottom: 24px; line-height: 1.5; }
        .flash { background: #fff7ed; border: 1px solid #fdba74; color: #9a3412; padding: 12px 14px; border-radius: 12px; margin-bottom: 18px; font-size: 14px; }
        .grid { display: grid; gap: 16px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(15,23,42,.05); }
        .card h2 { font-size: 16px; font-weight: 700; margin-bottom: 14px; color: #1e293b; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 12px 18px; border-radius: 12px; font-weight: 700; font-size: 14px; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; }
        .btn-ghost { background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; }
        .list { list-style: none; }
        .list li { border-bottom: 1px solid #f1f5f9; padding: 12px 0; }
        .list li:last-child { border-bottom: none; }
        .list a { color: #2563eb; font-weight: 600; text-decoration: none; }
        .list a:hover { text-decoration: underline; }
        .muted { color: #64748b; font-size: 13px; margin-top: 4px; }
        .empty { color: #94a3b8; font-size: 14px; padding: 8px 0; }
        @media (max-width: 1100px) { .main { margin-left: 0; width: 100%; } }
    </style>
</head>
<body>
    @include('components.sidebar')
    <div class="main">
        <h1>Olá, {{ $user->name }}</h1>
        <p class="lead">Aqui você vê seus serviços do dia e o que está em execução. Use a <strong>Agenda</strong> para a semana inteira. Ao abrir um serviço, você pode iniciar o cronômetro e preencher o relatório.</p>

        @if (session('warning'))
            <div class="flash">{{ session('warning') }}</div>
        @endif

        @if (!$colab)
            <div class="card">
                <h2>Vincule seu usuário ao cadastro</h2>
                <p class="muted">Peça ao administrador para informar o seu e-mail no cadastro do colaborador <strong>ou</strong> vincular seu usuário ao registro. Enquanto isso, a agenda ficará vazia.</p>
            </div>
        @else
            <div class="grid" style="grid-template-columns: 1fr 1fr;">
                <div class="card">
                    <h2>Em execução agora</h2>
                    <ul class="list">
                        @forelse($emExecucao as $s)
                            <li>
                                <a href="{{ route('crm.colaborador.execucao', $s) }}">O.S {{ $s->numero_os ?? $s->id }} — {{ $s->cliente->nome ?? 'Cliente' }}</a>
                                <div class="muted">{{ $s->tipo_tarefa ?: 'Serviço' }} · {{ $s->status_operacional_label }}</div>
                            </li>
                        @empty
                            <li class="empty" style="border:none;">Nenhum serviço em andamento. Inicie um pela agenda.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card">
                    <h2>Hoje na sua agenda</h2>
                    <ul class="list">
                        @forelse($proximos as $s)
                            <li>
                                <a href="{{ route('crm.colaborador.execucao', $s) }}">O.S {{ $s->numero_os ?? $s->id }} — {{ $s->cliente->nome ?? 'Cliente' }}</a>
                                <div class="muted">
                                    @if($s->horario_agendamento)
                                        {{ $s->horario_agendamento->format('d/m H:i') }}
                                    @else
                                        {{ $s->data_inicio?->format('d/m/Y') ?? '—' }}
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="empty" style="border:none;">Nada agendado para hoje.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <a class="btn btn-primary" href="{{ route('crm.agenda') }}">Abrir agenda da semana</a>
            </div>
        @endif
    </div>
</body>
</html>
