<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Equipamentos - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

        /* Sidebar - Mesmo estilo */
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

        .sidebar-header {
            padding: 28px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 1;
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
            box-shadow: 0 8px 24px rgba(74, 144, 226, 0.4);
        }

        .logo-text {
            font-size: 22px;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            position: relative;
            z-index: 1;
        }

        .menu-section {
            margin-bottom: 32px;
        }

        .menu-section-title {
            font-size: 10px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.35);
            padding: 0 24px;
            margin-bottom: 16px;
            font-weight: 700;
            letter-spacing: 1.5px;
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
            transition: transform 0.3s;
        }

        .menu-item:hover {
            color: white;
            transform: translateX(4px);
            background: rgba(255, 255, 255, 0.05);
        }

        .menu-item:hover::before {
            transform: scaleY(1);
        }

        .menu-item.active {
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.15), rgba(53, 122, 189, 0.1));
            color: #87ceeb;
            box-shadow: 0 4px 16px rgba(74, 144, 226, 0.2);
        }

        .menu-item.active::before {
            transform: scaleY(1);
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

        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 32px;
            background: #f5f7fa;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-title-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
        }

        .page-subtitle {
            font-size: 14px;
            color: #718096;
            margin-top: 4px;
        }

        .search-action-bar {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-input {
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            width: 300px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .new-equipamento-btn {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        .new-equipamento-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4);
        }

        .filters-section {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .filters-label {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-select {
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .equipamentos-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            min-height: 400px;
            border: 2px dashed #e2e8f0;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }

        .empty-state-text {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .equipamentos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .equipamento-card {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s;
        }

        .equipamento-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            border-color: #4a90e2;
        }

        .equipamento-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
        }

        .equipamento-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .equipamento-tag {
            font-size: 12px;
            color: #718096;
        }

        .equipamento-info {
            margin-bottom: 12px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: 600;
            color: #718096;
            min-width: 100px;
        }

        .tipo-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: #e6f2ff;
            color: #4a90e2;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .equipamento-card-actions {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-edit-equip {
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #4a90e2;
            background: #fff;
            color: #4a90e2;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .btn-edit-equip:hover {
            background: #4a90e2;
            color: #fff;
        }

        .equipamento-card-actions .btn-row {
            display: flex;
            gap: 8px;
            margin-top: 0;
        }

        .equipamento-card-actions .btn-row .btn-edit-equip {
            flex: 1;
        }

        .btn-delete-equip {
            flex-shrink: 0;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #dc2626;
            background: #fff;
            color: #dc2626;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .btn-delete-equip:hover {
            background: #dc2626;
            color: #fff;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
        }

        .modal-subtitle {
            font-size: 12px;
            color: #4a90e2;
            text-transform: uppercase;
            margin-top: 4px;
            font-weight: 600;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: #f7fafc;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: #edf2f7;
        }

        .modal-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            color: #4a5568;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-label .required {
            color: #f44336;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .modal-footer {
            padding: 24px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #edf2f7;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #f44336;
        }
    </style>
</head>
<body>
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-header">
            <div class="top-header-left">PORTAL ADMINISTRATIVO</div>
            <div class="top-header-right">
                <div class="user-profile">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <div style="font-weight: 600; font-size: 14px;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 11px; color: #718096; text-transform: uppercase;">ACESSO TOTAL</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="padding: 8px 16px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer;">Sair</button>
                </form>
            </div>
        </div>

        <div class="content-area">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Equipamentos</h1>
                    <p class="page-subtitle">GESTÃO DE ATIVOS POR CLIENTE</p>
                </div>
                <div class="search-action-bar">
                    <form method="GET" action="{{ route('crm.equipamentos.index') }}" style="display: flex; gap: 12px;">
                        <input type="text" name="search" class="search-input" placeholder="🔍 Nome, BTU ou local..." value="{{ request('search') }}">
                        <button onclick="openEquipamentoModal(); return false;" class="new-equipamento-btn">
                            + NOVO ATIVO
                        </button>
                    </form>
                </div>
            </div>

            <div class="filters-section">
                <div class="filters-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    FILTROS:
                </div>
                <form method="GET" action="{{ route('crm.equipamentos.index') }}" style="display: flex; gap: 12px; flex: 1;">
                    <select name="cliente_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">Todos os Clientes</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }}
                            </option>
                        @endforeach
                    </select>
                    <select name="tipo_unidade" class="filter-select" onchange="this.form.submit()">
                        <option value="">Todos os Tipos</option>
                        <option value="condensadora" {{ request('tipo_unidade') == 'condensadora' ? 'selected' : '' }}>Condensadora</option>
                        <option value="evaporadora" {{ request('tipo_unidade') == 'evaporadora' ? 'selected' : '' }}>Evaporadora</option>
                        <option value="split" {{ request('tipo_unidade') == 'split' ? 'selected' : '' }}>Split</option>
                        <option value="chiller" {{ request('tipo_unidade') == 'chiller' ? 'selected' : '' }}>Chiller</option>
                        <option value="ar_condicionado" {{ request('tipo_unidade') == 'ar_condicionado' ? 'selected' : '' }}>Ar Condicionado</option>
                        <option value="outro" {{ request('tipo_unidade') == 'outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                    @if(request('cliente_id') || request('tipo_unidade'))
                        <a href="{{ route('crm.equipamentos.index') }}" style="padding: 10px 16px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; text-decoration: none; color: #4a5568; font-size: 14px;">Limpar</a>
                    @endif
                </form>
            </div>

            <div class="equipamentos-container">
                @if($equipamentos->count() > 0)
                    <div class="equipamentos-grid">
                        @foreach($equipamentos as $equipamento)
                            @php
                                $payloadEdit = $equipamento->only(['id', 'cliente_id', 'nome', 'tag', 'tipo_unidade', 'capacidade_btus', 'localizacao', 'observacoes_tecnicas']);
                                $payloadEdit['ultima_manutencao'] = $equipamento->ultima_manutencao?->format('Y-m-d');
                            @endphp
                            <div class="equipamento-card">
                                <div class="equipamento-header">
                                    <div>
                                        <div class="equipamento-name">{{ $equipamento->nome ?? $equipamento->tag ?? 'Sem nome' }}</div>
                                        @if($equipamento->tag)
                                            <div class="equipamento-tag">Tag: {{ $equipamento->tag }}</div>
                                        @endif
                                    </div>
                                    <span class="tipo-badge">{{ $equipamento->tipo_unidade_label }}</span>
                                </div>
                                
                                <div class="equipamento-info">
                                    <div class="info-item">
                                        <span class="info-label">Cliente:</span>
                                        <span>{{ $equipamento->cliente->nome }}</span>
                                    </div>
                                    @if($equipamento->capacidade_btus)
                                        <div class="info-item">
                                            <span class="info-label">Capacidade:</span>
                                            <span>{{ $equipamento->capacidade_btus }} BTUs</span>
                                        </div>
                                    @endif
                                    @if($equipamento->localizacao)
                                        <div class="info-item">
                                            <span class="info-label">Localização:</span>
                                            <span>{{ $equipamento->localizacao }}</span>
                                        </div>
                                    @endif
                                    @if($equipamento->ultima_manutencao)
                                        <div class="info-item">
                                            <span class="info-label">Última Manutenção:</span>
                                            <span>{{ $equipamento->ultima_manutencao->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="equipamento-card-actions">
                                    <div class="btn-row">
                                        {{-- JSON com aspas duplas quebra onclick="..."; usar aspas simples no atributo --}}
                                        <button type="button" class="btn-edit-equip" onclick='openEditEquipamentoModal(@json($payloadEdit))'>Editar</button>
                                        <button type="button" class="btn-delete-equip" title="Excluir equipamento" data-equip-nome="{{ e($equipamento->nome ?: ($equipamento->tag ?: ('#' . $equipamento->id))) }}" onclick="confirmarExcluirEquipamento({{ (int) $equipamento->id }}, this.getAttribute('data-equip-nome'))">Excluir</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($equipamentos->hasPages())
                        <div style="margin-top: 24px; display: flex; justify-content: center;">
                            {{ $equipamentos->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-state-text">NENHUM EQUIPAMENTO CADASTRADO OU ENCONTRADO.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Novo Equipamento -->
    <div class="modal-overlay" id="equipamentoModalOverlay" onclick="closeEquipamentoModalOnOverlay(event)">
        <div class="modal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="equipamentoModalTitle">Novo Equipamento</h2>
                    <p class="modal-subtitle" id="equipamentoModalSubtitle">DADOS TÉCNICOS DO ATIVO — obrigatórios: <strong>cliente</strong> e <strong>nome</strong>; demais campos opcionais.</p>
                </div>
                <button class="modal-close" onclick="closeEquipamentoModal()">×</button>
            </div>
            <form id="equipamentoForm" onsubmit="submitEquipamento(event)">
                <div class="modal-body">
                    <div id="equipamentoAlertContainer"></div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Vincular Cliente <span class="required">*</span>
                        </label>
                        <select name="cliente_id" class="form-select" required>
                            <option value="">Selecione um cliente...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">
                                    {{ $cliente->nome }} @if($cliente->razao_social) - {{ $cliente->razao_social }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Nome do Equipamento / Tag <span class="required">*</span>
                        </label>
                        <input type="text" name="nome" class="form-input" placeholder="Ex: Chiller 01 ou Split Sala Reunião" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tag</label>
                        <input type="text" name="tag" class="form-input" placeholder="Tag do equipamento (opcional)">
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Tipo de Unidade <span class="small" style="font-weight:500;color:#64748b;">(opcional)</span>
                            </label>
                            <select name="tipo_unidade" id="equipTipoUnidadeSelect" class="form-select">
                                <option value="">— Não informar (usa padrão) —</option>
                                <option value="condensadora">Condensadora</option>
                                <option value="evaporadora">Evaporadora</option>
                                <option value="split">Split</option>
                                <option value="chiller">Chiller</option>
                                <option value="ar_condicionado">Ar Condicionado</option>
                                <option value="outro">Outro</option>
                            </select>
                            <div style="display:flex;gap:8px;margin-top:10px;align-items:center;flex-wrap:wrap;">
                                <input type="text" id="novoTipoUnidadeInput" class="form-input" placeholder="Nova variedade (ex: VRF, fan coil)" style="flex:1;min-width:160px;" autocomplete="off">
                                <button type="button" class="btn btn-secondary" style="padding:10px 14px;" onclick="adicionarTipoUnidadeRapido()">Salvar na lista</button>
                            </div>
                            <p style="font-size:11px;color:#64748b;margin-top:6px;">Tipos extras ficam guardados neste navegador para usar de novo nos próximos cadastros.</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Capacidade (BTUs)</label>
                            <input type="text" name="capacidade_btus" class="form-input" placeholder="Ex: 12.000 ou 24k">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Última Manutenção</label>
                            <input type="date" name="ultima_manutencao" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Localização (Pavimento / Sala)</label>
                            <input type="text" name="localizacao" class="form-input" placeholder="Ex: Cobertura ou Setor T.I">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Observações Técnicas</label>
                        <textarea name="observacoes_tecnicas" class="form-textarea" placeholder="Detalhes como modelo, gás refrigerante, histórico..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEquipamentoModal()">DESCARTAR</button>
                    <button type="submit" class="btn btn-primary" id="equipamentoSubmitBtn">SALVAR EQUIPAMENTO</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let equipamentoEditId = null;
        const EQUIPAMENTO_STORE_URL = @json(route('crm.equipamentos.store'));
        @php
            $eqBasePath = parse_url((string) route('crm.equipamentos.index'), PHP_URL_PATH);
            $eqBasePath = is_string($eqBasePath) && $eqBasePath !== '' ? $eqBasePath : '/crm/equipamentos';
        @endphp
        /** Caminho relativo da listagem (sem barra final), para montar PUT/POST no mesmo host */
        const EQUIPAMENTO_INDEX_URL = @json(rtrim($eqBasePath, '/'));
        const EQUIP_MODAL_SUBTITLE = 'DADOS TÉCNICOS DO ATIVO — obrigatórios: <strong>cliente</strong> e <strong>nome</strong>; demais campos opcionais.';
        const LS_TIPOS_UNIDADE = 'videira_tipos_unidade_extra';

        function tiposUnidadeExtrasLer() {
            try {
                const a = JSON.parse(localStorage.getItem(LS_TIPOS_UNIDADE) || '[]');
                return Array.isArray(a) ? a.filter(Boolean) : [];
            } catch (e) {
                return [];
            }
        }

        function tiposUnidadeExtrasSalvar(arr) {
            localStorage.setItem(LS_TIPOS_UNIDADE, JSON.stringify([...new Set(arr)]));
        }

        function mesclarSelectTiposUnidade() {
            const sel = document.getElementById('equipTipoUnidadeSelect');
            if (!sel) return;
            const existentes = new Set(Array.from(sel.options).map(o => o.value).filter(Boolean));
            tiposUnidadeExtrasLer().forEach(function (slug) {
                if (!slug || existentes.has(slug)) return;
                const opt = document.createElement('option');
                opt.value = slug;
                opt.textContent = slug.replace(/_/g, ' ');
                sel.appendChild(opt);
                existentes.add(slug);
            });
        }

        function adicionarTipoUnidadeRapido() {
            const inp = document.getElementById('novoTipoUnidadeInput');
            const raw = (inp && inp.value || '').trim();
            if (!raw) return;
            const slug = raw.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
            if (!slug) {
                alert('Use apenas letras, números e espaços (convertidos para tipo).');
                return;
            }
            const list = tiposUnidadeExtrasLer();
            if (list.indexOf(slug) === -1) {
                list.push(slug);
                tiposUnidadeExtrasSalvar(list);
            }
            mesclarSelectTiposUnidade();
            const sel = document.getElementById('equipTipoUnidadeSelect');
            if (sel) sel.value = slug;
            if (inp) inp.value = '';
        }

        function confirmarExcluirEquipamento(id, nome) {
            const n = (nome != null && String(nome).trim() !== '') ? String(nome) : ('#' + id);
            if (!confirm('Excluir o equipamento "' + n + '"? Esta ação não pode ser desfeita.')) return;
            const url = EQUIPAMENTO_INDEX_URL + '/' + encodeURIComponent(id);
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: '_method=DELETE'
            })
            .then(async function (r) {
                let data = {};
                try { data = await r.json(); } catch (e) {}
                if (!r.ok || !data.success) {
                    alert((data && data.message) ? data.message : 'Não foi possível excluir.');
                    return;
                }
                location.reload();
            })
            .catch(function () {
                alert('Erro de rede ao excluir.');
            });
        }

        function resetEquipamentoModalChrome() {
            equipamentoEditId = null;
            document.getElementById('equipamentoModalTitle').textContent = 'Novo Equipamento';
            document.getElementById('equipamentoModalSubtitle').innerHTML = EQUIP_MODAL_SUBTITLE;
            document.getElementById('equipamentoSubmitBtn').textContent = 'SALVAR EQUIPAMENTO';
        }

        function openEquipamentoModal() {
            resetEquipamentoModalChrome();
            document.getElementById('equipamentoForm').reset();
            document.getElementById('equipamentoAlertContainer').innerHTML = '';
            mesclarSelectTiposUnidade();
            document.getElementById('equipamentoModalOverlay').classList.add('active');
        }

        function openEditEquipamentoModal(eq) {
            if (!eq || !eq.id) return;
            equipamentoEditId = eq.id;
            document.getElementById('equipamentoModalTitle').textContent = 'Editar Equipamento';
            document.getElementById('equipamentoModalSubtitle').innerHTML = EQUIP_MODAL_SUBTITLE;
            document.getElementById('equipamentoSubmitBtn').textContent = 'ATUALIZAR EQUIPAMENTO';
            document.getElementById('equipamentoAlertContainer').innerHTML = '';

            const form = document.getElementById('equipamentoForm');
            form.querySelector('[name="cliente_id"]').value = eq.cliente_id != null ? String(eq.cliente_id) : '';
            form.querySelector('[name="nome"]').value = eq.nome || '';
            form.querySelector('[name="tag"]').value = eq.tag || '';
            form.querySelector('[name="tipo_unidade"]').value = eq.tipo_unidade || '';
            form.querySelector('[name="capacidade_btus"]').value = eq.capacidade_btus || '';
            form.querySelector('[name="ultima_manutencao"]').value = eq.ultima_manutencao || '';
            form.querySelector('[name="localizacao"]').value = eq.localizacao || '';
            form.querySelector('[name="observacoes_tecnicas"]').value = eq.observacoes_tecnicas || '';

            mesclarSelectTiposUnidade();
            document.getElementById('equipamentoModalOverlay').classList.add('active');
        }

        function closeEquipamentoModal() {
            document.getElementById('equipamentoModalOverlay').classList.remove('active');
            document.getElementById('equipamentoForm').reset();
            document.getElementById('equipamentoAlertContainer').innerHTML = '';
            resetEquipamentoModalChrome();
        }

        function closeEquipamentoModalOnOverlay(e) {
            if (e.target.id === 'equipamentoModalOverlay') {
                closeEquipamentoModal();
            }
        }

        function submitEquipamento(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const alertContainer = document.getElementById('equipamentoAlertContainer');
            const isEdit = equipamentoEditId != null;
            // Rota aceita POST direto (Route::match put|post); evita depender de spoofing _method em FormData
            const url = isEdit ? (EQUIPAMENTO_INDEX_URL + '/' + encodeURIComponent(equipamentoEditId)) : EQUIPAMENTO_STORE_URL;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async response => {
                let data = {};
                try {
                    data = await response.json();
                } catch (err) {
                    alertContainer.innerHTML = '<div class="alert alert-error">Resposta inválida do servidor. Atualize a página e tente de novo.</div>';
                    return;
                }
                if (!response.ok || !data.success) {
                    let errors = '<ul style="list-style: none; padding: 0;">';
                    if (data.errors) {
                        Object.values(data.errors).forEach(error => {
                            if (Array.isArray(error)) {
                                error.forEach(err => { errors += `<li>${err}</li>`; });
                            } else {
                                errors += `<li>${error}</li>`;
                            }
                        });
                    } else if (data.message) {
                        errors += `<li>${data.message}</li>`;
                    } else {
                        errors += `<li>Não foi possível salvar (HTTP ${response.status}).</li>`;
                    }
                    errors += '</ul>';
                    alertContainer.innerHTML = `<div class="alert alert-error">${errors}</div>`;
                    return;
                }
                alertContainer.innerHTML = '<div class="alert alert-success">' + (isEdit ? 'Equipamento atualizado com sucesso!' : 'Equipamento cadastrado com sucesso!') + '</div>';
                setTimeout(() => {
                    closeEquipamentoModal();
                    location.reload();
                }, 1000);
            })
            .catch(() => {
                alertContainer.innerHTML = '<div class="alert alert-error">Erro de rede. Tente novamente.</div>';
            });
        }
    </script>
</body>
</html>
