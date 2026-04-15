<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            color: #1a202c;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styles - Completo */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.3);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(180deg, rgba(74, 144, 226, 0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .sidebar-header {
            padding: 28px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 1;
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.05) 0%, transparent 100%);
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 50%, #5ba3f5 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 800;
            color: white;
            box-shadow: 0 8px 24px rgba(74, 144, 226, 0.4),
                        0 0 0 1px rgba(255, 255, 255, 0.1) inset,
                        0 2px 8px rgba(0, 0, 0, 0.2) inset;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .logo-icon:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 12px 32px rgba(74, 144, 226, 0.5),
                        0 0 0 1px rgba(255, 255, 255, 0.2) inset;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 8px rgba(74, 144, 226, 0.3);
        }

        .logo-subtitle {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 2px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            flex: 1;
            padding: 24px 0;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            z-index: 1;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .menu-section {
            margin-bottom: 32px;
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .menu-section:nth-child(1) { animation-delay: 0.1s; }
        .menu-section:nth-child(2) { animation-delay: 0.2s; }
        .menu-section:nth-child(3) { animation-delay: 0.3s; }
        .menu-section:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu-section-title {
            font-size: 10px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.35);
            padding: 0 24px;
            margin-bottom: 16px;
            font-weight: 700;
            letter-spacing: 1.5px;
            position: relative;
        }

        .menu-section-title::after {
            content: '';
            position: absolute;
            left: 24px;
            bottom: -8px;
            width: 30px;
            height: 2px;
            background: linear-gradient(90deg, rgba(74, 144, 226, 0.5), transparent);
            border-radius: 2px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 24px;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            font-weight: 500;
            position: relative;
            margin: 0 12px;
            border-radius: 12px;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #4a90e2, #357abd);
            transform: scaleY(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0 4px 4px 0;
        }

        .menu-item::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.1), rgba(53, 122, 189, 0.05));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .menu-item:hover {
            color: white;
            transform: translateX(4px);
            background: rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .menu-item:hover::before {
            transform: scaleY(1);
        }

        .menu-item:hover::after {
            opacity: 1;
        }

        .menu-item.active {
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.15), rgba(53, 122, 189, 0.1));
            color: #87ceeb;
            box-shadow: 0 4px 16px rgba(74, 144, 226, 0.2),
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        .menu-item.active::before {
            transform: scaleY(1);
        }

        .menu-item.active::after {
            opacity: 1;
        }

        .menu-item.active .menu-icon {
            transform: scale(1.1);
            filter: drop-shadow(0 0 8px rgba(135, 206, 235, 0.5));
        }

        .menu-icon {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        .menu-item:hover .menu-icon {
            transform: scale(1.15) rotate(5deg);
        }

        #particlesCanvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.3;
            pointer-events: none;
            z-index: 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .top-header {
            background: white;
            padding: 16px 32px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .top-header-left {
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
        }

        .top-header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: #f7fafc;
            border-radius: 12px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #1a202c;
        }

        .user-role {
            font-size: 11px;
            color: #718096;
            text-transform: uppercase;
        }

        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 32px;
            background: #f5f7fa;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 15px;
            color: #718096;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-title {
            font-size: 14px;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
            color: white;
        }

        .stat-icon.green {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
            color: white;
        }

        .stat-icon.red {
            background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
            color: white;
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%);
            color: white;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .stat-change.positive {
            color: #4caf50;
        }

        .stat-change.negative {
            color: #e53935;
        }

        .stat-link {
            margin-top: 12px;
            font-size: 13px;
            color: #4fc3f7;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .stat-link:hover {
            text-decoration: underline;
        }

        .alert-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .chart-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .chart-header {
            margin-bottom: 24px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .chart-subtitle {
            font-size: 13px;
            color: #718096;
        }

        .team-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .team-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .team-stat {
            text-align: center;
            padding: 20px;
            background: #f7fafc;
            border-radius: 12px;
        }

        .team-stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #4fc3f7;
            margin-bottom: 8px;
        }

        .team-stat-label {
            font-size: 14px;
            color: #718096;
            font-weight: 500;
        }

        .date-selector {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .date-selector select {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            background: white;
            color: #1a202c;
        }

        .dash-kpi-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(132px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        }

        .kpi-card .kpi-val {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.15;
        }

        .kpi-card .kpi-lbl {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-top: 8px;
        }

        .kpi-tiny {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 6px;
        }

        .dash-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .dash-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .geo-panel {
            font-size: 13px;
            color: #0c4a6e;
            background: #e0f2fe;
            border: 1px solid #7dd3fc;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            line-height: 1.55;
        }

        .section-title {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin: 28px 0 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-title:first-of-type {
            margin-top: 0;
        }

        .muted {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 14px;
        }

        .agenda-list {
            list-style: none;
            font-size: 13px;
        }

        .agenda-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .agenda-list a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }

        .agenda-list a:hover {
            text-decoration: underline;
        }

        .btn-refiltrar {
            padding: 8px 16px;
            background: #0f172a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }

        .gauge-wrap {
            position: relative;
            max-height: 220px;
        }

        .gauge-center {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
        }

        .gauge-center strong {
            font-size: 28px;
            color: #0f172a;
        }

        @media (max-width: 1024px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }

            .dash-grid-3 {
                grid-template-columns: 1fr;
            }

            .dash-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    @include('components.sidebar')

    <div class="main-content">
        <div class="top-header">
            <div class="top-header-left">PORTAL ADMINISTRATIVO</div>
            <div class="top-header-right">
                <div class="user-profile">
                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-role">ACESSO TOTAL</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="padding: 8px 16px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500;">Sair</button>
                </form>
            </div>
        </div>


        <div class="content-area">
            <div class="container">
                <div class="page-header">
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">Visão geral operacional · logística · financeiro</p>
                </div>

                <form method="get" action="{{ route('dashboard') }}" class="date-selector">
                    <label for="mesDash" class="muted" style="margin:0;">Mês</label>
                    <select id="mesDash" name="mes" onchange="this.form.submit()">
                        @php
                            $nomesMes = [1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'];
                        @endphp
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" @selected((int) $month === $m)>{{ $nomesMes[$m] }}</option>
                        @endforeach
                    </select>
                    <label for="anoDash" class="muted" style="margin:0;">Ano</label>
                    <select id="anoDash" name="ano" onchange="this.form.submit()">
                        @for($a = now()->year - 3; $a <= now()->year + 2; $a++)
                            <option value="{{ $a }}" @selected((int) $year === $a)>{{ $a }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn-refiltrar">Atualizar</button>
                </form>
                <p class="muted">Período selecionado: <strong>{{ $operacao['periodo_label'] }}</strong></p>

                <div class="dash-kpi-row">
                    <div class="kpi-card">
                        <div class="kpi-val">{{ number_format($operacao['total_clientes']) }}</div>
                        <div class="kpi-lbl">Total de clientes</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-val">{{ number_format($operacao['total_equipamentos']) }}</div>
                        <div class="kpi-lbl">Equipamentos ativos</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-val">{{ number_format($operacao['falhas_mes']) }}</div>
                        <div class="kpi-lbl">Falhas / corretivas (mês)</div>
                        <div class="kpi-tiny">Por tipo de tarefa e descrição</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-val">{{ number_format($operacao['horas_corretivas_h'], 1, ',', '.') }} h</div>
                        <div class="kpi-lbl">Horas corretivas</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-val">{{ number_format($operacao['horas_preventivas_h'], 1, ',', '.') }} h</div>
                        <div class="kpi-lbl">Horas preventivas</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-val">{{ number_format($operacao['alarmes_ativos']) }}</div>
                        <div class="kpi-lbl">Alarmes ativos</div>
                        <div class="kpi-tiny">OS abertas + manutenção &gt;35d</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-val">{{ number_format($operacao['pct_conclusao'], 1, ',', '.') }}%</div>
                        <div class="kpi-lbl">Conclusão (mês)</div>
                    </div>
                </div>

                <div class="section-title">Estatísticas geográficas e logística</div>
                <div class="dash-grid-2">
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-title">Clientes por UF</div>
                            <div class="chart-subtitle">Endereço (heurística)</div>
                        </div>
                        <canvas id="chartGeoClientes" height="220"></canvas>
                    </div>
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-title">OS abertas por UF</div>
                            <div class="chart-subtitle">Priorize rotas nas UFs com mais demanda</div>
                        </div>
                        <canvas id="chartGeoAbertos" height="220"></canvas>
                    </div>
                </div>
                @php
                    $topUfLog = collect($operacao['geo_servicos_abertos_uf'])->take(5);
                @endphp
                <div class="geo-panel">
                    <strong>Otimização de logística:</strong>
                    @if($topUfLog->isEmpty())
                        Não há OS abertas no momento.
                    @else
                        UFs com maior carga de serviços em aberto:
                        @foreach($topUfLog as $uf => $n)
                            <strong>{{ $uf }}</strong> ({{ $n }})@if(!$loop->last), @endif
                        @endforeach
                        — agrupe visitas na mesma região e revise deslocamento.
                    @endif
                    <span style="display:block;margin-top:8px;font-size:12px;opacity:.9;">Complete o endereço com UF nos cadastros de clientes para melhorar o mapa.</span>
                </div>

                <div class="section-title">Gráficos operacionais</div>
                <div class="dash-grid-3">
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-title">Conclusão (velocímetro)</div>
                            <div class="chart-subtitle">OS concluídas ÷ total no mês</div>
                        </div>
                        <div class="gauge-wrap">
                            <canvas id="gaugeConclusao" height="200"></canvas>
                            <div class="gauge-center"><strong id="gaugePctLabel">{{ number_format($operacao['pct_conclusao'], 1) }}%</strong></div>
                        </div>
                    </div>
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-title">Planejado vs executado</div>
                            <div class="chart-subtitle">Volume de OS no período</div>
                        </div>
                        <canvas id="chartPlanejado" height="220"></canvas>
                    </div>
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-title">Status dos equipamentos</div>
                            <div class="chart-subtitle">Ativos vs inativos</div>
                        </div>
                        <canvas id="chartEquipStatus" height="220"></canvas>
                    </div>
                </div>

                <div class="chart-card" style="margin-bottom:24px;">
                    <div class="chart-header">
                        <div class="chart-title">Horas de manutenção (linha temporal)</div>
                        <div class="chart-subtitle">Últimos 6 meses · registros de horas</div>
                    </div>
                    <canvas id="chartHorasLinha" height="100"></canvas>
                </div>

                <div class="chart-card" style="margin-bottom:24px;">
                    <div class="chart-header">
                        <div class="chart-title">Top 5 falhas (por tipo de tarefa)</div>
                        <div class="chart-subtitle">Serviços classificados como corretiva/falha no mês</div>
                    </div>
                    <canvas id="chartTopFalhas" height="120"></canvas>
                </div>

                <div class="section-title">Técnicos · agenda · KPIs técnicos</div>
                <div class="dash-grid-3">
                    <div class="team-card">
                        <div class="chart-header">
                            <div class="chart-title">Ranking de carga (horas)</div>
                            <div class="chart-subtitle">Registros de horas no período</div>
                        </div>
                        @if(count($operacao['ranking_tecnicos']) === 0)
                            <p class="muted">Sem horas registradas neste período.</p>
                        @else
                            <ol style="margin:0;padding-left:18px;font-size:14px;line-height:1.7;">
                                @foreach($operacao['ranking_tecnicos'] as $r)
                                    <li><strong>{{ $r['nome'] }}</strong> — {{ $r['horas'] }} h</li>
                                @endforeach
                            </ol>
                        @endif
                        <div class="team-stats" style="margin-top:16px;">
                            <div class="team-stat">
                                <div class="team-stat-value">{{ $tecnicosAtivos }}</div>
                                <div class="team-stat-label">Técnicos ativos</div>
                            </div>
                            <div class="team-stat">
                                <div class="team-stat-value">{{ $totalTecnicos }}</div>
                                <div class="team-stat-label">Total cadastro</div>
                            </div>
                        </div>
                    </div>
                    <div class="team-card">
                        <div class="chart-header">
                            <div class="chart-title">Programação (próximos dias)</div>
                            <div class="chart-subtitle">Resumo da agenda</div>
                        </div>
                        @if($operacao['agenda_resumo']->isEmpty())
                            <p class="muted">Nenhuma OS aberta com data nos próximos dias.</p>
                        @else
                            <ul class="agenda-list">
                                @foreach($operacao['agenda_resumo'] as $s)
                                    <li>
                                        <a href="{{ route('crm.relatorios.show', $s) }}">O.S {{ $s->numero_os ?? $s->id }}</a>
                                        · {{ $s->cliente?->nome ?? '—' }}
                                        <span style="color:#64748b;display:block;font-size:12px;">
                                            {{ $s->tipo_tarefa ?: 'Serviço' }} · {{ $s->status_operacional_label }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <p style="margin-top:12px;"><a href="{{ route('crm.agenda') }}" style="color:#2563eb;font-weight:600;font-size:13px;">Abrir agenda completa →</a></p>
                    </div>
                    <div class="team-card">
                        <div class="chart-header">
                            <div class="chart-title">KPIs técnicos</div>
                            <div class="chart-subtitle">Estimativas no período</div>
                        </div>
                        <div style="font-size:14px;line-height:1.85;color:#334155;">
                            <div><strong>MTBF</strong> (entre falhas corretivas, mesmo equip.): 
                                @if($operacao['mtbf_horas'] !== null)
                                    {{ number_format($operacao['mtbf_horas'], 1, ',', '.') }} h
                                @else
                                    —
                                @endif
                            </div>
                            <div><strong>MTTR</strong> (duração média execução concluída): 
                                @if($operacao['mttr_horas'] !== null)
                                    {{ number_format($operacao['mttr_horas'], 2, ',', '.') }} h
                                @else
                                    —
                                @endif
                            </div>
                            <div><strong>Disponibilidade</strong> (aprox.): 
                                @if($operacao['disponibilidade_pct'] !== null)
                                    {{ number_format($operacao['disponibilidade_pct'], 1, ',', '.') }}%
                                @else
                                    —
                                @endif
                            </div>
                            <p class="kpi-tiny" style="margin-top:10px;">MTBF/MTTR dependem de datas e horários preenchidos nas OS. Disponibilidade = MTBF ÷ (MTBF + MTTR).</p>
                        </div>
                    </div>
                </div>

                <div class="section-title">Financeiro (mês selecionado)</div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">Saldo operacional</div>
                                <div class="stat-value">R$ {{ number_format($saldoOperacional, 2, ',', '.') }}</div>
                            </div>
                            <div class="stat-icon blue">💰</div>
                        </div>
                        <div class="stat-change positive"><span>Entradas − saídas</span></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">Entradas</div>
                                <div class="stat-value">R$ {{ number_format($entradas, 2, ',', '.') }}</div>
                            </div>
                            <div class="stat-icon green">📈</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">Saídas</div>
                                <div class="stat-value">R$ {{ number_format($saidas, 2, ',', '.') }}</div>
                            </div>
                            <div class="stat-icon red">📉</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">Resultado líquido</div>
                                <div class="stat-value">R$ {{ number_format($resultadoLiquido, 2, ',', '.') }}</div>
                            </div>
                            <div class="stat-icon orange">📊</div>
                        </div>
                    </div>
                </div>

                <div class="charts-grid">
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-title">Fluxo financeiro</div>
                            <div class="chart-subtitle">Últimos 4 meses</div>
                        </div>
                        <canvas id="monthlyChart"></canvas>
                    </div>
                    <div class="team-card">
                        <div class="chart-header">
                            <div class="chart-title">Equipe técnica</div>
                            <div class="chart-subtitle">Cadastro</div>
                        </div>
                        <div class="team-stats">
                            <div class="team-stat">
                                <div class="team-stat-value">{{ $tecnicosAtivos }}</div>
                                <div class="team-stat-label">Ativos</div>
                            </div>
                            <div class="team-stat">
                                <div class="team-stat-value">{{ $totalTecnicos }}</div>
                                <div class="team-stat-label">Total</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        Chart.defaults.font.family = 'Inter';
        const chartOpts = { responsive: true, maintainAspectRatio: true };

        @php
            $geoCj = array_slice($operacao['geo_clientes_uf'] ?? [], 0, 12, true);
            $geoAj = array_slice($operacao['geo_servicos_abertos_uf'] ?? [], 0, 12, true);
            if ($geoCj === []) { $geoCj = ['—' => 0]; }
            if ($geoAj === []) { $geoAj = ['—' => 0]; }
        @endphp
        const geoC = @json($geoCj);
        const geoA = @json($geoAj);
        new Chart(document.getElementById('chartGeoClientes'), {
            type: 'bar',
            data: {
                labels: Object.keys(geoC),
                datasets: [{ label: 'Clientes', data: Object.values(geoC), backgroundColor: 'rgba(37, 99, 235, 0.65)' }]
            },
            options: { ...chartOpts, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
        new Chart(document.getElementById('chartGeoAbertos'), {
            type: 'bar',
            data: {
                labels: Object.keys(geoA),
                datasets: [{ label: 'OS abertas', data: Object.values(geoA), backgroundColor: 'rgba(234, 88, 12, 0.7)' }]
            },
            options: { ...chartOpts, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });

        const pct = {{ json_encode((float) $operacao['pct_conclusao']) }};
        const rest = Math.max(0.1, 100 - pct);
        new Chart(document.getElementById('gaugeConclusao'), {
            type: 'doughnut',
            data: {
                labels: ['Concluído', 'Restante'],
                datasets: [{ data: [pct, rest], backgroundColor: ['#16a34a', '#e2e8f0'], borderWidth: 0 }]
            },
            options: {
                ...chartOpts,
                cutout: '72%',
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => c.parsed + '%' } } }
            }
        });

        new Chart(document.getElementById('chartPlanejado'), {
            type: 'bar',
            data: {
                labels: ['Planejado (OS)', 'Concluídas'],
                datasets: [{
                    label: 'Quantidade',
                    data: [{{ (int) $operacao['planejado_os'] }}, {{ (int) $operacao['executado_os'] }}],
                    backgroundColor: ['#6366f1', '#22c55e']
                }]
            },
            options: { ...chartOpts, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('chartEquipStatus'), {
            type: 'doughnut',
            data: {
                labels: ['Ativos', 'Inativos'],
                datasets: [{
                    data: [{{ (int) $operacao['total_equipamentos'] }}, {{ (int) $operacao['equipamentos_inativos'] }}],
                    backgroundColor: ['#0ea5e9', '#cbd5e1']
                }]
            },
            options: { ...chartOpts, plugins: { legend: { position: 'bottom' } } }
        });

        const serie = @json($operacao['horas_manutencao_serie']);
        new Chart(document.getElementById('chartHorasLinha'), {
            type: 'line',
            data: {
                labels: serie.labels,
                datasets: [{
                    label: 'Horas',
                    data: serie.valores,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.12)',
                    fill: true,
                    tension: 0.35
                }]
            },
            options: { ...chartOpts, scales: { y: { beginAtZero: true } } }
        });

        const topF = @json($operacao['top_falhas']);
        const topLabels = topF.length ? topF.map(x => x.tipo) : ['Sem registros'];
        const topData = topF.length ? topF.map(x => x.total) : [0];
        new Chart(document.getElementById('chartTopFalhas'), {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [{ label: 'Ocorrências', data: topData, backgroundColor: '#dc2626' }]
            },
            options: {
                indexAxis: 'y',
                ...chartOpts,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });

        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($meses),
                datasets: [
                    {
                        label: 'Receitas',
                        data: @json($receitas),
                        borderColor: '#4fc3f7',
                        backgroundColor: 'rgba(79, 195, 247, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3
                    },
                    {
                        label: 'Despesas',
                        data: @json($despesas),
                        borderColor: '#ef5350',
                        backgroundColor: 'rgba(239, 83, 80, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: true, position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': R$ ' + Number(context.parsed.y).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(value) { return 'R$ ' + Number(value).toLocaleString('pt-BR'); } }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>

