<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clientes - Sistema VIDEIRA</title>
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

        /* Sidebar - Mesmo estilo do funil */
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

        .logo-text {
            font-size: 22px;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
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
            position: relative;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
        }

        .new-cliente-btn {
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

        .new-cliente-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4);
        }

        .filters-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .search-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .clientes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .cliente-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }

        .cliente-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .cliente-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
        }

        .cliente-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .cliente-razao {
            font-size: 13px;
            color: #718096;
        }

        .cliente-info {
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
            min-width: 80px;
        }

        .plantas-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            background: #e6f2ff;
            color: #4a90e2;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        /* Modal - Mesmo estilo do funil */
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
            max-width: 800px;
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

        .modal-tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 24px;
        }

        .modal-tab {
            padding: 12px 24px;
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 600;
            color: #718096;
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }

        .modal-tab.active {
            color: #4a90e2;
            border-bottom-color: #4a90e2;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .email-input-group {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .email-input-group input {
            flex: 1;
        }

        .add-email-btn {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .add-email-btn:hover {
            transform: scale(1.05);
        }

        .email-list {
            margin-top: 16px;
        }

        .email-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .email-item span {
            flex: 1;
            font-size: 14px;
            color: #4a5568;
        }

        .remove-email-btn {
            width: 24px;
            height: 24px;
            border: none;
            background: #e2e8f0;
            border-radius: 4px;
            color: #718096;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-email-btn:hover {
            background: #cbd5e0;
        }

        .upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f7fafc;
        }

        .upload-area:hover {
            border-color: #4a90e2;
            background: #e6f2ff;
        }

        .upload-area.dragover {
            border-color: #4a90e2;
            background: #e6f2ff;
        }

        .planta-preview {
            margin-top: 16px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        .planta-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .planta-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
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
                <h1 class="page-title">Clientes</h1>
                <button onclick="openClienteModal()" class="new-cliente-btn">
                    + Novo Cliente
                </button>
            </div>

            <div class="filters-section">
                <form method="GET" action="{{ route('crm.clientes.index') }}">
                    <input type="text" name="search" class="search-input" placeholder="🔍 Buscar por nome, CNPJ, CPF, telefone, e-mail..." value="{{ request('search') }}">
                </form>
            </div>

            @if($clientes->count() > 0)
                <div class="clientes-grid">
                    @foreach($clientes as $cliente)
                        <div class="cliente-card">
                            <div class="cliente-header">
                                <div>
                                    <div class="cliente-name">{{ $cliente->nome }}</div>
                                    @if($cliente->razao_social)
                                        <div class="cliente-razao">{{ $cliente->razao_social }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="cliente-info">
                                @if($cliente->cnpj)
                                    <div class="info-item">
                                        <span class="info-label">CNPJ:</span>
                                        <span>{{ $cliente->cnpj }}</span>
                                    </div>
                                @endif
                                @if($cliente->cpf)
                                    <div class="info-item">
                                        <span class="info-label">CPF:</span>
                                        <span>{{ $cliente->cpf }}</span>
                                    </div>
                                @endif
                                @if($cliente->telefone)
                                    <div class="info-item">
                                        <span class="info-label">Telefone:</span>
                                        <span>{{ $cliente->telefone }}</span>
                                    </div>
                                @endif
                                @if($cliente->email)
                                    <div class="info-item">
                                        <span class="info-label">E-mail:</span>
                                        <span>{{ $cliente->email }}</span>
                                    </div>
                                @endif
                                @if($cliente->endereco_completo)
                                    <div class="info-item">
                                        <span class="info-label">Endereço:</span>
                                        <span style="font-size: 12px;">{{ Str::limit($cliente->endereco_completo, 50) }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($cliente->plantasBaixas->count() > 0)
                                <div class="plantas-badge">
                                    📐 {{ $cliente->plantasBaixas->count() }} Planta(s)
                                </div>
                            @endif

                            <div style="display: flex; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                                <button onclick="openClienteModal({{ $cliente->id }})" style="flex: 1; padding: 10px; background: #e6f2ff; border: 1px solid #4a90e2; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; color: #4a90e2; transition: all 0.3s;">
                                    ✏️ Editar
                                </button>
                                <button onclick="viewCliente({{ $cliente->id }})" style="flex: 1; padding: 10px; background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; color: #0ea5e9; transition: all 0.3s;">
                                    👁️ Ver Detalhes
                                </button>
                                <button onclick='deleteCliente({{ $cliente->id }}, @json($cliente->nome))' style="padding: 10px 12px; background: #ffebee; border: 1px solid #ef9a9a; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 700; color: #c62828; transition: all 0.3s;" title="Excluir cliente">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($clientes->hasPages())
                    <div style="margin-top: 24px; display: flex; justify-content: center;">
                        {{ $clientes->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">🏢</div>
                    <h3>Nenhum cliente encontrado</h3>
                    <p style="margin-top: 8px;">Comece cadastrando seu primeiro cliente</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Novo Cliente -->
    <div class="modal-overlay" id="clienteModalOverlay" onclick="closeClienteModalOnOverlay(event)">
        <div class="modal" onclick="event.stopPropagation()" style="max-width: 800px;">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title">Novo Cliente</h2>
                    <p style="font-size: 12px; color: #718096; margin-top: 4px;">GESTÃO DE ATIVOS E LOCALIZAÇÕES</p>
                </div>
                <button class="modal-close" onclick="closeClienteModal()">×</button>
            </div>
            <form id="clienteForm" onsubmit="submitCliente(event)" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="clienteAlertContainer"></div>
                    
                    <div class="modal-tabs">
                        <button type="button" class="modal-tab active" onclick="switchTab('dados')">DADOS CADASTRAIS</button>
                        <button type="button" class="modal-tab" onclick="switchTab('planta')">PLANTA BAIXA</button>
                    </div>

                    <!-- Aba Dados Cadastrais -->
                    <div id="tabDados" class="tab-content active">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Razão Social / Nome <span class="required">*</span>
                                </label>
                                <input type="text" name="nome" class="form-input" required placeholder="Nome ou Razão Social">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Razão Social</label>
                                <input type="text" name="razao_social" class="form-input" placeholder="Razão Social (se diferente)">
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">CNPJ / CPF</label>
                                <input type="text" name="cnpj" class="form-input" placeholder="CNPJ ou CPF">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Telefone Principal</label>
                                <input type="text" name="telefone" class="form-input" placeholder="(00) 00000-0000">
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Endereço Completo</label>
                            <textarea name="endereco_completo" class="form-textarea" placeholder="Rua, número, bairro, cidade, CEP..."></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                E-mails dos Responsáveis
                            </label>
                            <div class="email-input-group">
                                <input type="email" id="emailInput" class="form-input" placeholder="Adicionar e-mail...">
                                <button type="button" class="add-email-btn" onclick="addEmail()">+</button>
                            </div>
                            <div class="email-list" id="emailsList"></div>
                        </div>
                    </div>

                    <!-- Aba Planta Baixa -->
                    <div id="tabPlanta" class="tab-content">
                        <p style="font-size: 13px; color: #4a5568; line-height: 1.5; margin-bottom: 16px; padding: 12px; background: #edf2f7; border-radius: 8px; border-left: 4px solid #4a90e2;">
                            Após salvar o cliente com a imagem da planta, use <strong>Parque na planta</strong> para posicionar bolinhas dos equipamentos cadastrados (ex.: split na sala) sobre o desenho — mapa visual do parque no local.
                        </p>
                        <div class="form-group">
                            <label class="form-label">Nome da Planta *</label>
                            <input type="text" id="plantaNome" class="form-input" placeholder="Ex: Térreo, Cobertura, Andar 1...">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Descrição (Opcional)</label>
                            <textarea id="plantaDescricao" class="form-textarea" placeholder="Descrição adicional da planta..." rows="2"></textarea>
                        </div>
                        
                        <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                            <button type="button" onclick="addPlanta()" style="flex: 1; padding: 12px; background: #4a90e2; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                                ➕ Adicionar Planta
                            </button>
                        </div>

                        <div class="upload-area" id="uploadArea" onclick="document.getElementById('plantaImagem').click()">
                            <div style="font-size: 48px; margin-bottom: 16px;">📷</div>
                            <div style="font-size: 14px; color: #718096; margin-bottom: 8px;">Carregar Imagem (Opcional)</div>
                            <div style="font-size: 12px; color: #a0aec0;">Clique ou arraste uma imagem aqui</div>
                            <input type="file" id="plantaImagem" accept="image/*" style="display: none;" onchange="handlePlantaImage(event)">
                        </div>
                        <div id="plantaImagemSelecionadaInfo" style="margin-top: 8px; font-size: 12px; color: #4a5568;"></div>

                        <div id="plantaPreview" class="planta-preview"></div>

                        <div style="margin-top: 24px;">
                            <label class="form-label">Plantas Adicionadas</label>
                            <div id="plantasAtivas" style="min-height: 50px;">
                                <div class="empty-state" style="padding: 20px; text-align: center; color: #a0aec0;">
                                    Nenhuma planta adicionada ainda.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeClienteModal()">DESCARTAR</button>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        SALVAR CADASTRO
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let emailsList = [];
        let plantasList = [];
        let clienteId = null;
        let plantaImagemSelecionada = null;
        let plantaImagemPreview = null;
        const storageBaseUrl = "{{ asset('storage') }}";

        function openClienteModal(id = null) {
            clienteId = id;
            document.getElementById('clienteModalOverlay').classList.add('active');
            document.getElementById('clienteForm').reset();
            emailsList = [];
            plantasList = [];
            plantaImagemSelecionada = null;
            plantaImagemPreview = null;
            updateEmailsList();
            updatePlantasPreview();
            updatePlantaImagemSelecionadaInfo();
            document.getElementById('clienteAlertContainer').innerHTML = '';
            
            if (id) {
                document.querySelector('.modal-title').textContent = 'Editar Cliente';
                loadClienteData(id);
            } else {
                document.querySelector('.modal-title').textContent = 'Novo Cliente';
            }
            
            switchTab('dados');
        }

        function loadClienteData(id) {
            fetch(`/crm/clientes/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.cliente) {
                    const cliente = data.cliente;
                    
                    // Preencher formulário
                    document.querySelector('input[name="nome"]').value = cliente.nome || '';
                    document.querySelector('input[name="razao_social"]').value = cliente.razao_social || '';
                    document.querySelector('input[name="cnpj"]').value = cliente.cnpj || '';
                    document.querySelector('input[name="telefone"]').value = cliente.telefone || '';
                    document.querySelector('textarea[name="endereco_completo"]').value = cliente.endereco_completo || '';
                    
                    // Preencher emails
                    if (cliente.emails_responsaveis && Array.isArray(cliente.emails_responsaveis)) {
                        emailsList = cliente.emails_responsaveis;
                        updateEmailsList();
                    }
                    
                    // Preencher plantas
                    if (cliente.plantas_baixas && Array.isArray(cliente.plantas_baixas)) {
                        plantasList = cliente.plantas_baixas.map(planta => ({
                            id: planta.id,
                            nome: planta.nome || '',
                            descricao: planta.descricao || '',
                            preview: planta.imagem_url || (planta.imagem_path ? `${String(storageBaseUrl).replace(/\/$/, '')}/${String(planta.imagem_path).replace(/^\//, '')}` : null)
                        }));
                        updatePlantasPreview();
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao carregar cliente:', error);
                alert('Erro ao carregar dados do cliente');
            });
        }

        function closeClienteModal() {
            document.getElementById('clienteModalOverlay').classList.remove('active');
            document.getElementById('clienteForm').reset();
            emailsList = [];
            plantasList = [];
            plantaImagemSelecionada = null;
            plantaImagemPreview = null;
            clienteId = null;
            updateEmailsList();
            updatePlantasPreview();
            updatePlantaImagemSelecionadaInfo();
            document.getElementById('clienteAlertContainer').innerHTML = '';
            document.querySelector('.modal-title').textContent = 'Novo Cliente';
        }

        function closeClienteModalOnOverlay(e) {
            if (e.target.id === 'clienteModalOverlay') {
                closeClienteModal();
            }
        }

        function switchTab(tab) {
            document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            if (tab === 'dados') {
                document.querySelector('.modal-tab:first-child').classList.add('active');
                document.getElementById('tabDados').classList.add('active');
            } else {
                document.querySelector('.modal-tab:last-child').classList.add('active');
                document.getElementById('tabPlanta').classList.add('active');
            }
        }

        function addEmail() {
            const input = document.getElementById('emailInput');
            const email = input.value.trim();
            
            if (email && isValidEmail(email)) {
                if (!emailsList.includes(email)) {
                    emailsList.push(email);
                    input.value = '';
                    updateEmailsList();
                }
            }
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function removeEmail(index) {
            emailsList.splice(index, 1);
            updateEmailsList();
        }

        function updateEmailsList() {
            const container = document.getElementById('emailsList');
            if (emailsList.length === 0) {
                container.innerHTML = '';
                return;
            }
            
            container.innerHTML = emailsList.map((email, index) => `
                <div class="email-item">
                    <span>${email}</span>
                    <button type="button" class="remove-email-btn" onclick="removeEmail(${index})">×</button>
                </div>
            `).join('');
        }

        document.getElementById('emailInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addEmail();
            }
        });

        function handlePlantaImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    plantaImagemSelecionada = file;
                    plantaImagemPreview = e.target.result;
                    updatePlantaImagemSelecionadaInfo();
                };
                reader.readAsDataURL(file);
            }
        }

        function addPlanta() {
            const nome = document.getElementById('plantaNome').value.trim();
            const descricao = document.getElementById('plantaDescricao')?.value.trim() || '';
            
            if (nome) {
                plantasList.push({
                    nome: nome,
                    descricao: descricao,
                    imagem: plantaImagemSelecionada,
                    preview: plantaImagemPreview
                });
                document.getElementById('plantaNome').value = '';
                if (document.getElementById('plantaDescricao')) {
                    document.getElementById('plantaDescricao').value = '';
                }
                const inputImagem = document.getElementById('plantaImagem');
                if (inputImagem) {
                    inputImagem.value = '';
                }
                plantaImagemSelecionada = null;
                plantaImagemPreview = null;
                updatePlantaImagemSelecionadaInfo();
                updatePlantasPreview();
            } else {
                alert('Por favor, informe um nome para a planta');
            }
        }

        // Compatibilidade com chamadas antigas
        function addPlantaSemImagem() {
            addPlanta();
        }

        function updatePlantaImagemSelecionadaInfo() {
            const info = document.getElementById('plantaImagemSelecionadaInfo');
            if (!info) return;
            if (plantaImagemSelecionada) {
                info.innerHTML = `Imagem selecionada: <strong>${plantaImagemSelecionada.name}</strong> (clique em "Adicionar Planta" para incluir)`;
            } else {
                info.innerHTML = '';
            }
        }

        function removePlanta(index) {
            plantasList.splice(index, 1);
            updatePlantasPreview();
        }

        function updatePlantasPreview() {
            const container = document.getElementById('plantaPreview');
            const plantasAtivas = document.getElementById('plantasAtivas');
            
            if (plantasList.length === 0) {
                container.innerHTML = '';
                if (plantasAtivas) {
                    plantasAtivas.innerHTML = 'Nenhuma planta cadastrada para este cliente.';
                }
                return;
            }
            
            container.innerHTML = plantasList.map((planta, index) => `
                <div class="planta-card">
                    ${planta.preview ? `<img src="${planta.preview}" alt="${planta.nome}">` : '<div style="height: 150px; background: #f7fafc; display: flex; align-items: center; justify-content: center; color: #718096;">Sem imagem</div>'}
                    <div style="padding: 12px;">
                        <div style="font-weight: 600; color: #1a202c; margin-bottom: 4px;">${planta.nome || 'Planta sem nome'}</div>
                        ${planta.descricao ? `<div style="font-size: 12px; color: #718096; margin-bottom: 8px;">${planta.descricao}</div>` : ''}
                        <button type="button" class="remove-email-btn" onclick="removePlanta(${index})" style="margin-top: 8px;">Remover</button>
                    </div>
                </div>
            `).join('');
            
            if (plantasAtivas) {
                if (plantasList.length === 0) {
                    plantasAtivas.innerHTML = '<div class="empty-state" style="padding: 20px; text-align: center; color: #a0aec0;">Nenhuma planta adicionada ainda.</div>';
                } else {
                    plantasAtivas.innerHTML = plantasList.map((planta, index) => `
                        <div style="padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #1a202c; margin-bottom: 4px;">
                                    ${planta.nome || 'Planta sem nome'}
                                    ${planta.imagem ? ' 📷' : ''}
                                </div>
                                ${planta.descricao ? `<div style="font-size: 12px; color: #718096;">${planta.descricao}</div>` : ''}
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                ${planta.id ? `<a href="/crm/plantas/${planta.id}" target="_blank" class="btn-planta-parque" style="font-size: 12px; font-weight: 600; color: #fff; background: #2b6cb0; padding: 6px 10px; border-radius: 6px; text-decoration: none; white-space: nowrap;">📍 Parque na planta</a>` : '<span style="font-size:11px;color:#a0aec0;">Salve o cliente para mapear equipamentos</span>'}
                                <button type="button" class="remove-email-btn" onclick="removePlanta(${index})" style="margin-left: 4px;" title="Remover planta">×</button>
                            </div>
                        </div>
                    `).join('');
                }
            }
        }

        function viewCliente(id) {
            window.location.href = `/crm/clientes/${id}`;
        }

        function deleteCliente(id, nome) {
            if (!confirm(`Deseja excluir o cliente "${nome}"? Esta ação não pode ser desfeita.`)) {
                return;
            }

            fetch(`/crm/clientes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Erro ao excluir cliente.');
                }
            })
            .catch(() => {
                alert('Erro ao excluir cliente.');
            });
        }

        const uploadArea = document.getElementById('uploadArea');
        if (uploadArea) {
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    const input = document.getElementById('plantaImagem');
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    handlePlantaImage({ target: input });
                }
            });
        }

        function submitCliente(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            
            // Enviar emails como array
            emailsList.forEach((email, index) => {
                formData.append(`emails_responsaveis[${index}]`, email);
            });
            
            // Na edição, sincronizar plantas no servidor (remover as que sumiram da lista)
            if (clienteId) {
                formData.append('plantas_sincronizar', '1');
            }

            // Processar plantas — enviar todas as válidas (lista vazia = remove todas, se plantas_sincronizar)
            if (plantasList && plantasList.length > 0) {
                console.log('Plantas a serem enviadas:', plantasList);
                plantasList.forEach((planta, index) => {
                    // Validar se a planta tem nome antes de enviar
                    if (!planta.nome || !planta.nome.trim()) {
                        console.warn(`Planta no índice ${index} não tem nome, será ignorada`);
                        return;
                    }
                    
                    if (planta.id) {
                        // Planta existente (edição)
                        formData.append(`plantas[${index}][id]`, planta.id);
                        formData.append(`plantas[${index}][nome]`, planta.nome.trim());
                        if (planta.descricao && planta.descricao.trim()) {
                            formData.append(`plantas[${index}][descricao]`, planta.descricao.trim());
                        }
                        if (planta.imagem && planta.imagem instanceof File) {
                            formData.append(`plantas[${index}][imagem]`, planta.imagem);
                        }
                    } else {
                        // Nova planta
                        formData.append(`plantas[${index}][nome]`, planta.nome.trim());
                        if (planta.descricao && planta.descricao.trim()) {
                            formData.append(`plantas[${index}][descricao]`, planta.descricao.trim());
                        }
                        if (planta.imagem && planta.imagem instanceof File) {
                            formData.append(`plantas[${index}][imagem]`, planta.imagem);
                        }
                    }
                });
                console.log('FormData preparado. Total de plantas válidas:', plantasList.filter(p => p.nome && p.nome.trim()).length);
            } else {
                console.log('Nenhuma planta para enviar');
            }
            
            const alertContainer = document.getElementById('clienteAlertContainer');
            
            const url = clienteId 
                ? `/crm/clientes/${clienteId}`
                : '{{ route("crm.clientes.store") }}';
            
            const method = clienteId ? 'POST' : 'POST';
            if (clienteId) {
                formData.append('_method', 'PUT');
            }
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Erro na requisição');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const message = clienteId ? 'Cliente atualizado com sucesso!' : 'Cliente cadastrado com sucesso!';
                    alertContainer.innerHTML = `<div class="alert alert-success">${message}</div>`;
                    setTimeout(() => {
                        closeClienteModal();
                        location.reload();
                    }, 1000);
                } else {
                    let errors = '<ul style="list-style: none; padding: 0;">';
                    if (data.errors) {
                        Object.values(data.errors).forEach(error => {
                            if (Array.isArray(error)) {
                                error.forEach(err => errors += `<li>${err}</li>`);
                            } else {
                                errors += `<li>${error}</li>`;
                            }
                        });
                    } else if (data.message) {
                        errors += `<li>${data.message}</li>`;
                    }
                    errors += '</ul>';
                    alertContainer.innerHTML = `<div class="alert alert-error">${errors}</div>`;
                }
            })
            .catch(error => {
                console.error('Erro completo:', error);
                let errorMsg = 'Erro ao cadastrar cliente. Tente novamente.';
                if (error.message) {
                    errorMsg = error.message;
                }
                alertContainer.innerHTML = `<div class="alert alert-error">${errorMsg}</div>`;
            });
        }
    </script>
</body>
</html>
