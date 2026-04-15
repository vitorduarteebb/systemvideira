<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agenda - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; color: #1a202c; display: flex; min-height: 100vh; }
        .main-wrapper { margin-left: 280px; width: calc(100% - 280px); padding: 20px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 2px 10px rgba(15, 23, 42, .04); }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 20px; margin-bottom: 16px; }
        .title { font-size: 24px; font-weight: 800; }
        .subtitle { color: #64748b; font-size: 13px; margin-top: 4px; }
        .week-nav { display: flex; gap: 8px; align-items: center; }
        .week-nav a { text-decoration: none; border: 1px solid #cbd5e1; background: #fff; color: #1e293b; padding: 8px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; }
        .week-nav a:hover { background: #f1f5f9; }
        .calendar { padding: 16px; }
        .week-header, .week-row { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 10px; }
        .week-header { margin-bottom: 10px; }
        .week-header div { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: center; padding: 6px; }
        .day-cell { border: 1px solid #e2e8f0; border-radius: 10px; min-height: 380px; padding: 8px; background: #fff; display: flex; flex-direction: column; gap: 6px; }
        .day-number { font-size: 12px; font-weight: 800; color: #1e293b; }
        .day-label { font-size: 10px; color: #64748b; margin-bottom: 4px; }
        .evento-item { position: relative; display: block; text-decoration: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px; font-size: 11px; color: #0f172a; }
        .evento-head { display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .badge { font-size: 10px; border-radius: 999px; padding: 2px 6px; background: #e2e8f0; color: #334155; }
        .evento-line { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .evento-time { font-weight: 700; }
        .more-btn { border: none; background: transparent; color: #2563eb; font-weight: 700; font-size: 11px; text-align: left; cursor: pointer; }
        .hidden-item { display: none; }
        .evento-item:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 8px;
            top: calc(100% + 6px);
            z-index: 10;
            min-width: 220px;
            max-width: 260px;
            white-space: pre-line;
            background: #0f172a;
            color: #fff;
            border-radius: 10px;
            padding: 10px;
            font-size: 11px;
            line-height: 1.35;
            box-shadow: 0 10px 25px rgba(2, 6, 23, .35);
        }
        .agenda-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.6); z-index: 3000; align-items: center; justify-content: center; padding: 20px; }
        .agenda-modal-overlay.open { display: flex; }
        .agenda-modal { background: #fff; border-radius: 18px; max-width: 420px; width: 100%; padding: 28px 24px; text-align: center; box-shadow: 0 25px 60px rgba(0,0,0,.25); }
        .agenda-modal h2 { font-size: 20px; font-weight: 800; margin-bottom: 12px; color: #0f172a; line-height: 1.3; }
        .agenda-modal p { color: #64748b; font-size: 15px; line-height: 1.45; margin-bottom: 22px; }
        .agenda-modal-actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        .agenda-modal .btn { border: none; border-radius: 12px; padding: 14px 22px; font-size: 15px; font-weight: 700; cursor: pointer; }
        .agenda-modal .btn-no { background: #f1f5f9; color: #334155; }
        .agenda-modal .btn-yes { background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; }
        @media (max-width: 1200px) {
            .main-wrapper { margin-left: 0; width: 100%; }
            .day-cell { min-height: 280px; }
        }
    </style>
</head>
<body>
    @include('components.sidebar')

    @php
        $diasSemana = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'];
        $mapStatus = [
            'pendente' => ['#eab308', 'Pendente'],
            'pendencia' => ['#dc2626', 'Pendência'],
            'em_andamento' => ['#3b82f6', 'Em Andamento'],
            'pausado' => ['#f97316', 'Pausado'],
            'concluido' => ['#16a34a', 'Concluído'],
            'cancelado' => ['#64748b', 'Cancelado'],
        ];
        $semanaAnterior = $inicioSemana->copy()->subWeek();
        $semanaProxima = $inicioSemana->copy()->addWeek();
    @endphp

    <div class="main-wrapper">
        <div class="card header">
            <div>
                <div class="title">Agenda</div>
                <div class="subtitle">Visão semanal - Segunda a Domingo @if(!empty($portalColaborador))<strong style="color:#2563eb">· Seus serviços designados</strong>@endif</div>
            </div>
            <div class="week-nav">
                <a href="{{ route('crm.agenda', ['data' => $semanaAnterior->format('Y-m-d')]) }}">◀ Semana anterior</a>
                <strong>{{ $inicioSemana->translatedFormat('d/m') }} – {{ $fimSemana->translatedFormat('d/m/Y') }}</strong>
                <a href="{{ route('crm.agenda', ['data' => $semanaProxima->format('Y-m-d')]) }}">Próxima semana ▶</a>
            </div>
        </div>

        <div class="card calendar">
            <div class="week-header">
                @foreach($diasSemana as $dia)
                    <div>{{ $dia }}</div>
                @endforeach
            </div>

            <div class="week-row">
                @for($i = 0; $i < 7; $i++)
                    @php
                        $cursor = $inicioSemana->copy()->addDays($i);
                        $dayKey = $cursor->format('Y-m-d');
                        $itens = $itensPorDia[$dayKey] ?? [];
                    @endphp
                    <div class="day-cell">
                        <div class="day-label">{{ $cursor->translatedFormat('D') }}</div>
                        <div class="day-number">{{ $cursor->day }}</div>

                        @foreach($itens as $idx => $item)
                            @php
                                $statusCfg = $mapStatus[$item['status']] ?? ['#64748b', ucfirst($item['status'])];
                                $tooltip = "Tipo: {$item['tipo_tarefa']}\nCliente: {$item['cliente']}\nResponsável(is): {$item['responsavel_resumo']}";
                                if (!empty($item['status_horas'])) {
                                    $tooltip .= "\nÚltimo: {$item['status_horas']}";
                                    if (!empty($item['motivo_pausa'])) {
                                        $tooltip .= " - Motivo: {$item['motivo_pausa']}";
                                    }
                                }
                            @endphp
                            @if(!empty($portalColaborador) && !empty($item['confirmar_inicio']))
                            <a href="#" class="evento-item js-agenda-iniciar {{ $idx > 2 ? 'hidden-item' : '' }}" data-day="{{ $dayKey }}" data-tooltip="{{ $tooltip }}"
                               data-iniciar-url="{{ route('crm.colaborador.servicos.iniciar', $item['id']) }}">
                            @else
                            <a href="{{ $item['url'] }}" class="evento-item {{ $idx > 2 ? 'hidden-item' : '' }}" data-day="{{ $dayKey }}" data-tooltip="{{ $tooltip }}">
                            @endif
                                <div class="evento-head">
                                    <span class="dot" style="background: {{ $statusCfg[0] }}"></span>
                                    <span class="badge">{{ $statusCfg[1] }}</span>
                                </div>
                                <div class="evento-line"><strong>{{ $item['cliente'] }}</strong></div>
                                @if(!empty($item['colaboradores'][0] ?? null))
                                    <div class="evento-line">{{ $item['colaboradores'][0] }}</div>
                                @endif
                                @if(!empty($item['colaboradores'][1] ?? null))
                                    <div class="evento-line">{{ $item['colaboradores'][1] }}</div>
                                @endif
                                <div class="evento-line">VE: {{ $item['codigo_ve'] ?: '-' }}</div>
                                <div class="evento-line evento-time">{{ $item['horario'] }}</div>
                                @if(!empty($item['status_horas']))
                                    <div class="evento-line" style="font-size: 10px; color: #64748b;">{{ $item['status_horas'] }}{{ !empty($item['motivo_pausa']) ? ': ' . $item['motivo_pausa'] : '' }}</div>
                                @endif
                            </a>
                        @endforeach

                        @if(count($itens) > 3)
                            <button class="more-btn" onclick="toggleDay('{{ $dayKey }}', this)">mais +{{ count($itens) - 3 }}</button>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    </div>

    @if(!empty($portalColaborador))
    <div class="agenda-modal-overlay" id="agendaModalIniciar">
        <div class="agenda-modal">
            <h2>Deseja iniciar este serviço?</h2>
            <p>Ao confirmar, o sistema registra o início do trabalho e <strong>liga o cronômetro</strong> para contagem do tempo em campo.</p>
            <div class="agenda-modal-actions">
                <button type="button" class="btn btn-no" id="agendaModalNao">Não</button>
                <button type="button" class="btn btn-yes" id="agendaModalSim">Sim, iniciar</button>
            </div>
        </div>
    </div>
    @endif

    <script>
        function toggleDay(dayKey, btn) {
            const itens = document.querySelectorAll(`.evento-item[data-day="${dayKey}"]`);
            let expanded = btn.dataset.expanded === '1';
            expanded = !expanded;

            itens.forEach((el, idx) => {
                if (idx > 2) {
                    el.style.display = expanded ? 'block' : 'none';
                }
            });

            btn.dataset.expanded = expanded ? '1' : '0';
            const total = itens.length;
            btn.textContent = expanded ? 'recolher' : `mais +${Math.max(0, total - 3)}`;
        }

        @if(!empty($portalColaborador))
        (function () {
            const overlay = document.getElementById('agendaModalIniciar');
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            let urlIniciar = null;
            document.querySelectorAll('.js-agenda-iniciar').forEach(el => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    urlIniciar = el.getAttribute('data-iniciar-url');
                    overlay.classList.add('open');
                });
            });
            document.getElementById('agendaModalNao')?.addEventListener('click', () => {
                overlay.classList.remove('open');
                urlIniciar = null;
            });
            overlay?.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    overlay.classList.remove('open');
                    urlIniciar = null;
                }
            });
            document.getElementById('agendaModalSim')?.addEventListener('click', async () => {
                if (!urlIniciar || !token) return;
                const btn = document.getElementById('agendaModalSim');
                btn.disabled = true;
                try {
                    const fd = new FormData();
                    const r = await fetch(urlIniciar, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: fd
                    });
                    const data = await r.json();
                    if (!r.ok || !data.success) throw new Error(data.message || 'Não foi possível iniciar.');
                    window.location.href = data.redirect || '{{ route('crm.agenda') }}';
                } catch (err) {
                    alert(err.message);
                    btn.disabled = false;
                }
            });
        })();
        @endif
    </script>
</body>
</html>
