<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cliente - {{ $cliente->nome }} | Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8edf5 100%);
            color: #0f172a;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

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
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .top-header {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            padding: 16px 32px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .top-header-left {
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            letter-spacing: 0.5px;
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
            font-weight: 700;
            font-size: 14px;
        }

        .user-name { font-weight: 700; font-size: 14px; color: #0f172a; }
        .user-role { font-size: 11px; color: #64748b; text-transform: uppercase; }

        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 28px 32px;
        }

        .content-area::-webkit-scrollbar { width: 8px; }
        .content-area::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 16px;
        }

        .breadcrumb a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 700;
        }

        .page-hero {
            background: white;
            border-radius: 18px;
            padding: 26px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.9);
            position: relative;
            overflow: hidden;
            margin-bottom: 22px;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -40%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(74, 144, 226, 0.12) 0%, transparent 65%);
        }

        .hero-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            position: relative;
            z-index: 1;
        }

        .company {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .company-badge {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 60%, #5ba3f5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 20px;
            box-shadow: 0 10px 24px rgba(74, 144, 226, 0.35);
        }

        .company-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .company-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 3px;
        }

        .hero-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .btn {
            border: none;
            cursor: pointer;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 13px;
            transition: all .25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            box-shadow: 0 10px 22px rgba(74, 144, 226, 0.25);
        }

        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(74, 144, 226, 0.32); }

        .btn-ghost {
            background: #f1f5f9;
            color: #0f172a;
            border: 1px solid #e2e8f0;
        }

        .btn-ghost:hover { transform: translateY(-2px); background: #e8edf5; }

        .info-grid {
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            position: relative;
            z-index: 1;
        }

        .info-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }

        .info-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 700;
        }

        .info-value {
            margin-top: 8px;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .info-hint {
            margin-top: 4px;
            font-size: 12px;
            color: #94a3b8;
        }

        .section {
            background: white;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.9);
            margin-bottom: 18px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 2px solid #e2e8f0;
            position: relative;
        }

        .section-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 70px;
            height: 2px;
            background: linear-gradient(90deg, #4a90e2 0%, #357abd 100%);
            border-radius: 2px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 18px;
        }

        .kv {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .kv-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
        }

        .kv-k { font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 800; }
        .kv-v { margin-top: 6px; font-size: 13px; color: #0f172a; font-weight: 700; }

        .list {
            display: grid;
            gap: 10px;
        }

        .list-item {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
            transition: all .2s ease;
            background: white;
        }

        .list-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
        }

        .list-main { display: flex; flex-direction: column; gap: 4px; }
        .list-title { font-weight: 800; color: #0f172a; font-size: 14px; }
        .list-subtitle { color: #64748b; font-size: 12px; }

        .pill {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #334155;
            white-space: nowrap;
        }

        .pill.ok { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
        .pill.warn { background: #fff7ed; border-color: #fed7aa; color: #9a3412; }
        .pill.bad { background: #fef2f2; border-color: #fecaca; color: #991b1b; }

        .gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .thumb {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            background: #f8fafc;
        }

        .thumb img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .thumb-meta {
            padding: 10px 12px;
        }

        .thumb-title {
            font-weight: 800;
            font-size: 13px;
            color: #0f172a;
        }

        .thumb-desc {
            margin-top: 4px;
            font-size: 12px;
            color: #64748b;
        }

        .planta-parque-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%);
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            margin-bottom: 16px;
        }

        .planta-parque-bar label {
            font-size: 13px;
            font-weight: 700;
            color: #1e40af;
        }

        .planta-parque-bar select {
            flex: 1;
            min-width: 200px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            background: #fff;
        }

        .btn-parque {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #fff !important;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
            white-space: nowrap;
        }

        .btn-parque:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .btn-parque-secondary {
            background: #fff;
            color: #1d4ed8 !important;
            border: 2px solid #3b82f6;
            box-shadow: none;
            font-weight: 700;
        }

        .thumb .btn-parque {
            width: 100%;
            margin-top: 8px;
            box-sizing: border-box;
        }

        .empty {
            padding: 22px;
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            color: #64748b;
            background: #f8fafc;
            font-weight: 700;
            text-align: center;
        }

        @media (max-width: 1200px) {
            .info-grid { grid-template-columns: repeat(2, 1fr); }
            .grid-2 { grid-template-columns: 1fr; }
            .gallery { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 700px) {
            .info-grid { grid-template-columns: 1fr; }
            .gallery { grid-template-columns: 1fr; }
            .content-area { padding: 20px; }
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
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">ACESSO TOTAL</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-area">
            <div class="breadcrumb">
                <a href="{{ route('crm.clientes.index') }}">Clientes</a>
                <span>›</span>
                <span>{{ $cliente->nome }}</span>
            </div>

            <div class="page-hero">
                <div class="hero-top">
                    <div class="company">
                        <div class="company-badge">{{ strtoupper(substr($cliente->nome ?? 'C', 0, 1)) }}</div>
                        <div>
                            <div class="company-title">{{ $cliente->nome }}</div>
                            <div class="company-subtitle">
                                {{ $cliente->razao_social ? $cliente->razao_social . ' • ' : '' }}
                                {{ $cliente->cnpj ?? $cliente->cpf ?? 'Documento não informado' }}
                            </div>
                        </div>
                    </div>

                    <div class="hero-actions">
                        <a class="btn btn-ghost" href="{{ route('crm.clientes.index') }}">← Voltar</a>
                        <a class="btn btn-primary" href="{{ route('crm.clientes.index') }}#cliente={{ $cliente->id }}">✏️ Editar</a>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Serviços (total)</div>
                        <div class="info-value">{{ $cliente->servicos_count ?? ($cliente->servicos->count() ?? 0) }}</div>
                        <div class="info-hint">Últimos 10 listados abaixo</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Equipamentos ativos</div>
                        <div class="info-value">{{ $cliente->equipamentos_ativos_count ?? ($cliente->equipamentos->count() ?? 0) }}</div>
                        <div class="info-hint">Somente ativos</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Plantas ativas</div>
                        <div class="info-value">{{ $cliente->plantas_ativas_count ?? ($cliente->plantasBaixas->count() ?? 0) }}</div>
                        <div class="info-hint">Selecione a planta e mapeie os equipamentos</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Propostas (total)</div>
                        <div class="info-value">{{ $cliente->propostas_count ?? ($cliente->propostas->count() ?? 0) }}</div>
                        <div class="info-hint">Últimas 10</div>
                    </div>
                </div>
            </div>

            <div class="grid-2">
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">📌 Resumo do cliente</div>
                    </div>

                    <div class="kv">
                        <div class="kv-item">
                            <div class="kv-k">Telefone</div>
                            <div class="kv-v">{{ $cliente->telefone ?? '—' }}</div>
                        </div>
                        <div class="kv-item">
                            <div class="kv-k">E-mail</div>
                            <div class="kv-v">{{ $cliente->email ?? '—' }}</div>
                        </div>
                        <div class="kv-item" style="grid-column: 1 / -1;">
                            <div class="kv-k">Endereço</div>
                            <div class="kv-v">{{ $cliente->endereco_completo ?? '—' }}</div>
                        </div>
                        <div class="kv-item" style="grid-column: 1 / -1;">
                            <div class="kv-k">E-mails responsáveis</div>
                            <div class="kv-v">
                                @if(is_array($cliente->emails_responsaveis) && count($cliente->emails_responsaveis) > 0)
                                    {{ implode(' • ', $cliente->emails_responsaveis) }}
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-header">
                        <div class="section-title">⚡ Ações rápidas</div>
                    </div>
                    <div class="list">
                        <a class="btn btn-ghost" href="{{ route('crm.servicos.index') }}" style="justify-content: space-between;">
                            <span>Ir para Serviços</span><span>→</span>
                        </a>
                        <a class="btn btn-ghost" href="{{ route('crm.equipamentos.index') }}" style="justify-content: space-between;">
                            <span>Ir para Equipamentos</span><span>→</span>
                        </a>
                        <a class="btn btn-ghost" href="{{ route('crm.funil') }}" style="justify-content: space-between;">
                            <span>Ir para Funil CRM</span><span>→</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">
                    <div class="section-title">🧾 Serviços (últimos 10)</div>
                    <span class="pill">{{ $cliente->servicos_count ?? ($cliente->servicos->count() ?? 0) }} total</span>
                </div>

                @if($cliente->servicos && $cliente->servicos->count() > 0)
                    <div class="list">
                        @foreach($cliente->servicos as $servico)
                            @php
                                $status = $servico->status_operacional ?? 'pendente';
                                $pillClass = in_array($status, ['concluido']) ? 'ok' : (in_array($status, ['pausado','cancelado']) ? 'bad' : 'warn');
                            @endphp
                            <div class="list-item">
                                <div class="list-main">
                                    <div class="list-title">{{ $servico->codigo_ve ?? '—' }} • {{ $servico->descricao ?? 'Serviço' }}</div>
                                    <div class="list-subtitle">
                                        Início: {{ optional($servico->data_inicio)->format('d/m/Y') ?? '—' }}
                                        @if($servico->equipamento)
                                            • Equip.: {{ $servico->equipamento->nome ?? ('#' . $servico->equipamento->id) }}
                                        @endif
                                        @if($servico->tecnicos && $servico->tecnicos->count() > 0)
                                            • Técnicos: {{ $servico->tecnicos->pluck('nome_profissional')->filter()->take(3)->implode(', ') }}{{ $servico->tecnicos->count() > 3 ? '…' : '' }}
                                        @endif
                                    </div>
                                </div>
                                <span class="pill {{ $pillClass }}">{{ strtoupper(str_replace('_', ' ', $status)) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty">Nenhum serviço registrado para este cliente ainda.</div>
                @endif
            </div>

            <div class="section">
                <div class="section-header">
                    <div class="section-title">🧰 Equipamentos ativos</div>
                    <span class="pill">{{ $cliente->equipamentos_ativos_count ?? ($cliente->equipamentos->count() ?? 0) }} ativo(s)</span>
                </div>

                @if($cliente->equipamentos && $cliente->equipamentos->count() > 0)
                    <div class="list">
                        @foreach($cliente->equipamentos as $equip)
                            <div class="list-item">
                                <div class="list-main">
                                    <div class="list-title">{{ $equip->nome ?? ('Equipamento #' . $equip->id) }}</div>
                                    <div class="list-subtitle">
                                        {{ $equip->tag ? 'TAG: ' . $equip->tag . ' • ' : '' }}
                                        {{ $equip->tipo ?? '—' }}
                                    </div>
                                </div>
                                <span class="pill ok">ATIVO</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty">Nenhum equipamento ativo cadastrado.</div>
                @endif
            </div>

            <div class="section">
                <div class="section-header">
                    <div class="section-title">📐 Plantas baixas (ativas)</div>
                    <span class="pill">{{ $cliente->plantas_ativas_count ?? ($cliente->plantasBaixas->count() ?? 0) }} planta(s)</span>
                </div>

                @if($cliente->plantasBaixas && $cliente->plantasBaixas->count() > 0)
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 12px; line-height: 1.5;">
                        Escolha qual planta deseja usar e abra o <strong>mapa do parque</strong>: selecione cada equipamento no topo e clique no desenho onde ele fica no cliente.
                    </p>
                    <div class="planta-parque-bar">
                        <label for="selectPlantaParque">Planta para mapear</label>
                        <select id="selectPlantaParque">
                            @foreach($cliente->plantasBaixas as $planta)
                                <option value="{{ $planta->id }}">{{ $planta->nome ?? 'Planta #' . $planta->id }}{{ $planta->imagem_path ? '' : ' (sem imagem)' }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn-parque" id="btnAbrirParquePlanta">📍 Mapear equipamentos</button>
                    </div>
                    <div class="gallery">
                        @foreach($cliente->plantasBaixas as $planta)
                            <div class="thumb">
                                @if($planta->imagem_path)
                                    <img src="{{ $planta->imagem_url ?? asset('storage/' . ltrim(str_replace('\\', '/', $planta->imagem_path), '/')) }}" alt="{{ $planta->nome ?? 'Planta' }}">
                                @else
                                    <div style="height: 120px; display:flex; align-items:center; justify-content:center; color:#64748b;">Sem imagem</div>
                                @endif
                                <div class="thumb-meta">
                                    <div class="thumb-title">{{ $planta->nome ?? 'Planta' }}</div>
                                    @if($planta->descricao)
                                        <div class="thumb-desc">{{ $planta->descricao }}</div>
                                    @endif
                                    <a class="btn-parque btn-parque-secondary" href="{{ route('crm.plantas.show', $planta) }}">📍 Equipamentos nesta planta</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty">Nenhuma planta ativa cadastrada. Edite o cliente na lista e adicione na aba <strong>Planta baixa</strong>.</div>
                @endif
            </div>

            @if($cliente->plantasBaixas && $cliente->plantasBaixas->count() > 0)
            <script>
            (function() {
                var base = @json(url('/crm/plantas'));
                var sel = document.getElementById('selectPlantaParque');
                var btn = document.getElementById('btnAbrirParquePlanta');
                if (btn && sel) {
                    btn.addEventListener('click', function() {
                        var id = sel.value;
                        if (!id) {
                            alert('Selecione uma planta na lista.');
                            return;
                        }
                        window.location.href = base.replace(/\/$/, '') + '/' + encodeURIComponent(id);
                    });
                }
            })();
            </script>
            @endif

            <div class="section">
                <div class="section-header">
                    <div class="section-title">📄 Propostas (últimas 10)</div>
                    <span class="pill">{{ $cliente->propostas_count ?? ($cliente->propostas->count() ?? 0) }} total</span>
                </div>

                @if($cliente->propostas && $cliente->propostas->count() > 0)
                    <div class="list">
                        @foreach($cliente->propostas as $proposta)
                            <div class="list-item">
                                <div class="list-main">
                                    <div class="list-title">{{ $proposta->titulo ?? ('Proposta #' . $proposta->id) }}</div>
                                    <div class="list-subtitle">
                                        Valor: R$ {{ number_format((float)($proposta->valor_final ?? 0), 2, ',', '.') }}
                                        • Data: {{ optional($proposta->data_criacao)->format('d/m/Y') ?? '—' }}
                                    </div>
                                </div>
                                <span class="pill">{{ strtoupper(str_replace('_',' ', $proposta->estado ?? '—')) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty">Nenhuma proposta registrada para este cliente.</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>

