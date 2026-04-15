<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Colaboradores - Sistema VIDEIRA</title>
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

        /* Sidebar Styles - Mesmo padrão das outras páginas */
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

        .menu-icon {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .notification-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .notification-icon:hover {
            background: #f7fafc;
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

        .page-header {
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title-section {
            display: flex;
            flex-direction: column;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #718096;
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

        .new-colaborador-btn {
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
            text-transform: uppercase;
            font-size: 13px;
        }

        .new-colaborador-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4);
        }

        .colaboradores-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            min-height: 400px;
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

        .colaboradores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .colaborador-card {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s;
        }

        .colaborador-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            border-color: #4a90e2;
        }

        .colaborador-card.card-cert-expirando {
            border-color: #fd7e14;
            background: linear-gradient(180deg, #fff8f0 0%, #f7fafc 100%);
            box-shadow: 0 0 0 2px rgba(253, 126, 20, 0.22);
        }

        .colaborador-card.card-cert-expirando:hover {
            border-color: #e8590c;
        }

        .colaborador-card.card-cert-vencido {
            border-color: #dc3545;
            background: linear-gradient(180deg, #fff5f5 0%, #f7fafc 100%);
            box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.28);
        }

        .colaborador-card.card-cert-vencido:hover {
            border-color: #c82333;
        }

        .cert-chip {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 4px 8px;
            border-radius: 6px;
            margin-left: 8px;
            vertical-align: middle;
        }

        .cert-chip.expirando {
            background: #ffe8d6;
            color: #c2410c;
        }

        .cert-chip.vencido {
            background: #fee2e2;
            color: #b91c1c;
        }

        .colaborador-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
        }

        .colaborador-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .colaborador-info {
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

        .departamento-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: #e6f2ff;
            color: #4a90e2;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .ativo-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .inativo-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: #ffebee;
            color: #c62828;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
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
            max-width: 700px;
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
            font-size: 20px;
            color: #718096;
        }

        .modal-close:hover {
            background: #edf2f7;
        }

        .modal-tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            padding: 0 24px;
        }

        .modal-tab {
            padding: 12px 24px;
            background: none;
            border: none;
            font-size: 13px;
            font-weight: 600;
            color: #718096;
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            text-transform: uppercase;
        }

        .modal-tab:hover {
            color: #4a5568;
        }

        .modal-tab.active {
            color: #4a90e2;
            border-bottom-color: #4a90e2;
        }

        .tab-content {
            display: none;
            padding: 24px;
        }

        .tab-content.active {
            display: block;
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

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f7fafc;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .form-checkbox:hover {
            background: #edf2f7;
        }

        .form-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .form-checkbox label {
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #4a5568;
        }

        .documento-form {
            background: #f7fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .documento-form-title {
            font-size: 13px;
            font-weight: 600;
            color: #4a90e2;
            margin-bottom: 16px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .file-input-wrapper {
            position: relative;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-input-label:hover {
            border-color: #4a90e2;
            background: #f7fafc;
        }

        .registrar-documento-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 13px;
        }

        .registrar-documento-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        .documentos-list {
            margin-top: 20px;
        }

        .explorer-folder {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 12px;
            background: #ffffff;
        }

        .explorer-folder-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            background: #f7fafc;
            border-bottom: 1px solid #edf2f7;
            font-weight: 600;
            color: #2d3748;
        }

        .explorer-row {
            display: grid;
            grid-template-columns: minmax(220px, 1.8fr) minmax(120px, 0.8fr) minmax(80px, 0.6fr) auto;
            gap: 12px;
            align-items: center;
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .explorer-row:last-child {
            border-bottom: none;
        }

        .explorer-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1a202c;
            word-break: break-word;
        }

        .explorer-meta {
            color: #718096;
            font-size: 12px;
        }

        .explorer-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .documento-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .documento-info {
            flex: 1;
        }

        .documento-nome {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .documento-data {
            font-size: 12px;
            color: #718096;
        }

        .documento-actions {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border: none;
            background: #f7fafc;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-icon:hover {
            background: #edf2f7;
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
            text-transform: uppercase;
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
                <div class="notification-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <div style="width: 1px; height: 24px; background: #e2e8f0;"></div>
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
            <div class="page-header">
                <div class="page-title-section">
                    <h1 class="page-title">Equipe</h1>
                    <p class="page-subtitle">GESTÃO DE CAPITAL HUMANO</p>
                    <p style="font-size:12px;color:#64748b;margin-top:10px;max-width:720px;line-height:1.45;">
                        Documentos com <strong>data de validade</strong> (ex.: NR-35): cartão <strong style="color:#fd7e14;">laranja</strong> = a vencer nos próximos {{ $diasCert ?? 30 }} dias; <strong style="color:#dc3545;">vermelho</strong> = vencida. Atualize em <strong>Detalhes</strong>. O sistema envia alertas diários (8h) na área de notificações; para também receber e-mail, ative no <code>.env</code>: <code>COLAB_CERT_NOTIFICAR_EMAIL=true</code> (com <code>MAIL_*</code> configurado).
                    </p>
                </div>
                <div class="search-action-bar">
                    <form method="GET" action="{{ route('crm.colaboradores.index') }}" style="display: flex; gap: 12px;">
                        <input type="text" name="search" class="search-input" placeholder="🔍 Buscar por nome ou CPF..." value="{{ request('search') }}">
                        <button type="button" onclick="openColaboradorModal()" class="new-colaborador-btn">
                            + NOVO PERFIL
                        </button>
                    </form>
                </div>
            </div>

            <div class="colaboradores-container">
                @if($colaboradores->count() > 0)
                    <div class="colaboradores-grid">
                        @foreach($colaboradores as $colaborador)
                            @php $certNivel = $colaborador->certificacao_alerta_nivel ?? 0; @endphp
                            <div class="colaborador-card {{ $certNivel === 2 ? 'card-cert-vencido' : ($certNivel === 1 ? 'card-cert-expirando' : '') }}">
                                <div class="colaborador-header">
                                    <div>
                                        <div class="colaborador-name">{{ $colaborador->nome_profissional }}
                                            @if($certNivel === 2)
                                                <span class="cert-chip vencido">Cert. vencida</span>
                                            @elseif($certNivel === 1)
                                                <span class="cert-chip expirando">Cert. a vencer</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        @if($colaborador->ativo)
                                            <span class="ativo-badge">Ativo</span>
                                        @else
                                            <span class="inativo-badge">Inativo</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="colaborador-info">
                                    <div class="info-item">
                                        <span class="info-label">Departamento:</span>
                                        <span class="departamento-badge">{{ $colaborador->departamento_label }}</span>
                                    </div>
                                    @if($colaborador->valor_hora)
                                        <div class="info-item">
                                            <span class="info-label">Valor/Hora:</span>
                                            <span>R$ {{ number_format($colaborador->valor_hora, 2, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if($colaborador->cpf)
                                        <div class="info-item">
                                            <span class="info-label">CPF:</span>
                                            <span>{{ $colaborador->cpf }}</span>
                                        </div>
                                    @endif
                                    @if($colaborador->documentos_count > 0)
                                        <div class="info-item">
                                            <span class="info-label">Documentos:</span>
                                            <span>{{ $colaborador->documentos_count }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div style="display: flex; gap: 8px; margin-top: 14px;">
                                    <a href="{{ route('crm.colaboradores.details', $colaborador) }}" style="flex: 1; text-align: center; padding: 9px 10px; border: 1px solid #94a3b8; background: #f8fafc; color: #334155; border-radius: 8px; font-weight: 600; text-decoration: none;">
                                        📁 Detalhes
                                    </a>
                                    <button type="button" onclick="editColaborador({{ $colaborador->id }})" style="flex: 1; padding: 9px 10px; border: 1px solid #4a90e2; background: #e6f2ff; color: #2563eb; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                        ✏️ Editar
                                    </button>
                                    <button type="button" onclick='deleteColaborador({{ $colaborador->id }}, @json($colaborador->nome_profissional))' style="padding: 9px 12px; border: 1px solid #ef9a9a; background: #ffebee; color: #c62828; border-radius: 8px; font-weight: 700; cursor: pointer;">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($colaboradores->hasPages())
                        <div style="margin-top: 24px; display: flex; justify-content: center;">
                            {{ $colaboradores->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-state-text">NENHUM COLABORADOR CADASTRADO OU ENCONTRADO.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Novo Colaborador -->
    <div class="modal-overlay" id="colaboradorModalOverlay" onclick="closeColaboradorModalOnOverlay(event)">
        <div class="modal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title">Novo Colaborador</h2>
                    <p class="modal-subtitle">DADOS FUNCIONAIS</p>
                </div>
                <button class="modal-close" onclick="closeColaboradorModal()">×</button>
            </div>
            
            <div class="modal-tabs">
                <button class="modal-tab active" data-tab="informacoes-gerais" onclick="switchTab('informacoes-gerais')">INFORMAÇÕES GERAIS</button>
                <button class="modal-tab" data-tab="documentos" onclick="switchTab('documentos')">DOCUMENTOS</button>
            </div>

            <form id="colaboradorForm" onsubmit="submitColaborador(event)">
                <div id="tab-informacoes-gerais" class="tab-content active">
                    <div id="colaboradorAlertContainer"></div>
                    
                    <div class="form-group">
                        <label class="form-label">Nome Profissional *</label>
                        <input type="text" name="nome_profissional" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Departamento *</label>
                        <select name="departamento" class="form-select">
                            <option value="operacional">Operacional</option>
                            <option value="comercial">Comercial</option>
                            <option value="administrativo">Administrativo</option>
                            <option value="tecnico">Técnico</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Valor da Hora</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="color: #718096; font-weight: 600;">R$</span>
                            <input type="number" name="valor_hora" class="form-input" step="0.01" min="0" placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" name="ativo" id="ativo" checked>
                        <label for="ativo">Colaborador Ativo no Sistema</label>
                    </div>
                </div>

                <div id="tab-documentos" class="tab-content">
                    <div id="documentosContainer">
                        <div class="documento-form">
                            <div class="documento-form-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                NOVO REGISTRO
                            </div>
                            <div id="documentoForm">
                                <div class="form-group">
                                    <label class="form-label">Nome do Documento (Ex: CNH, NR10...)</label>
                                    <input type="text" name="nome_documento" class="form-input">
                                </div>
                                <div class="form-group" style="margin-top: -6px;">
                                    <small style="color: #718096;">Dica: se selecionar arquivo(s) ou pasta, o nome pode ficar em branco e será gerado automaticamente.</small>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">Data de Vencimento</label>
                                        <input type="date" name="data_vencimento" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Arquivo</label>
                                        <div class="file-input-wrapper">
                                            <input type="file" name="arquivo" id="arquivoInput" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            <label for="arquivoInput" class="file-input-label">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                                </svg>
                                                <span id="arquivoLabel">Selecionar arquivo...</span>
                                            </label>
                                        </div>
                                        <div class="file-input-wrapper" style="margin-top: 8px;">
                                            <input type="file" name="arquivos[]" id="arquivosInput" multiple webkitdirectory directory>
                                            <label for="arquivosInput" class="file-input-label">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"></path>
                                                </svg>
                                                <span id="arquivosLabel">Selecionar pasta/arquivos...</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="registrar-documento-btn" onclick="submitDocumento(event)">REGISTRAR DOCUMENTO</button>
                            </div>
                        </div>
                        <div id="documentosList" class="documentos-list">
                            <p style="text-align: center; color: #a0aec0; padding: 20px;">Nenhum documento registrado ainda.</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeColaboradorModal()">DESCARTAR</button>
                    <button type="submit" class="btn btn-primary">SALVAR ALTERAÇÕES</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let colaboradorId = null;
        let documentos = [];

        function openColaboradorModal() {
            const overlay = document.getElementById('colaboradorModalOverlay');
            if (!overlay) return;
            
            overlay.classList.add('active');
            colaboradorId = null;
            documentos = [];
            const modalTitle = document.querySelector('.modal-title');
            if (modalTitle) modalTitle.textContent = 'Novo Colaborador';
            const saveButton = document.querySelector('#colaboradorForm .modal-footer .btn-primary');
            if (saveButton) saveButton.textContent = 'SALVAR ALTERAÇÕES';
            
            // Reset formulários se existirem
            const colaboradorForm = document.getElementById('colaboradorForm');
            if (colaboradorForm) {
                colaboradorForm.reset();
            }
            
            const documentoForm = document.getElementById('documentoForm');
            if (documentoForm) {
                const nomeInput = documentoForm.querySelector('[name="nome_documento"]');
                const dataInput = documentoForm.querySelector('[name="data_vencimento"]');
                const fileInput = documentoForm.querySelector('[name="arquivo"]');
                const filesInput = documentoForm.querySelector('[name="arquivos[]"]');
                if (nomeInput) nomeInput.value = '';
                if (dataInput) dataInput.value = '';
                if (fileInput) fileInput.value = '';
                if (filesInput) filesInput.value = '';
            }

            const arquivoLabelEl = document.getElementById('arquivoLabel');
            if (arquivoLabelEl) arquivoLabelEl.textContent = 'Selecionar arquivo...';
            const arquivosLabelEl = document.getElementById('arquivosLabel');
            if (arquivosLabelEl) arquivosLabelEl.textContent = 'Selecionar pasta/arquivos...';
            
            const alertContainer = document.getElementById('colaboradorAlertContainer');
            if (alertContainer) {
                alertContainer.innerHTML = '';
            }
            
            const documentosList = document.getElementById('documentosList');
            if (documentosList) {
                documentosList.innerHTML = '<p style="text-align: center; color: #a0aec0; padding: 20px;">Nenhum documento registrado ainda.</p>';
            }
            
            switchTab('informacoes-gerais');
        }

        function editColaborador(id) {
            const overlay = document.getElementById('colaboradorModalOverlay');
            if (!overlay) return;

            openColaboradorModal();
            colaboradorId = id;
            overlay.classList.add('active');

            const modalTitle = document.querySelector('.modal-title');
            if (modalTitle) modalTitle.textContent = 'Editar Colaborador';

            fetch(`/crm/colaboradores/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success || !data.colaborador) {
                    throw new Error('Erro ao carregar colaborador.');
                }

                const c = data.colaborador;
                const form = document.getElementById('colaboradorForm');
                form.querySelector('[name="nome_profissional"]').value = c.nome_profissional || '';
                form.querySelector('[name="departamento"]').value = c.departamento || 'operacional';
                form.querySelector('[name="valor_hora"]').value = c.valor_hora || '';
                const ativoEl = form.querySelector('[name="ativo"]');
                if (ativoEl) ativoEl.checked = !!c.ativo;

                documentos = Array.isArray(c.documentos) ? c.documentos : [];
                updateDocumentosList();
            })
            .catch(() => {
                alert('Não foi possível carregar os dados do colaborador.');
            });
        }

        function deleteColaborador(id, nome) {
            if (!confirm(`Deseja excluir o colaborador "${nome}"?`)) return;

            fetch(`/crm/colaboradores/${id}`, {
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
                    alert(data.message || 'Erro ao excluir colaborador.');
                }
            })
            .catch(() => {
                alert('Erro ao excluir colaborador.');
            });
        }

        function closeColaboradorModal() {
            document.getElementById('colaboradorModalOverlay').classList.remove('active');
        }

        function closeColaboradorModalOnOverlay(e) {
            if (e.target.id === 'colaboradorModalOverlay') {
                closeColaboradorModal();
            }
        }

        function switchTab(tabName) {
            document.querySelectorAll('.modal-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            const tabButton = document.querySelector(`[data-tab="${tabName}"]`);
            const tabContent = document.getElementById(`tab-${tabName}`);
            
            if (tabButton) {
                tabButton.classList.add('active');
            }
            if (tabContent) {
                tabContent.classList.add('active');
            }
        }

        // Listener para o input de arquivo
        const arquivoInput = document.getElementById('arquivoInput');
        const arquivoLabel = document.getElementById('arquivoLabel');
        if (arquivoInput && arquivoLabel) {
            arquivoInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Selecionar arquivo...';
                arquivoLabel.textContent = fileName;
            });
        }

        const arquivosInput = document.getElementById('arquivosInput');
        const arquivosLabel = document.getElementById('arquivosLabel');
        if (arquivosInput && arquivosLabel) {
            arquivosInput.addEventListener('change', function(e) {
                const total = e.target.files?.length || 0;
                arquivosLabel.textContent = total > 0 ? `${total} arquivo(s) selecionado(s)` : 'Selecionar pasta/arquivos...';
            });
        }

        function submitDocumento(e) {
            if (e) e.preventDefault();
            
            if (!colaboradorId) {
                alert('Por favor, salve o colaborador primeiro antes de adicionar documentos.');
                return;
            }

            const documentoForm = document.getElementById('documentoForm');
            if (!documentoForm) {
                alert('Formulário de documento não encontrado.');
                return;
            }

            const nomeDocumento = documentoForm.querySelector('[name="nome_documento"]')?.value?.trim() || '';

            const formData = new FormData();
            if (nomeDocumento) {
                formData.append('nome_documento', nomeDocumento);
            }
            const dataVencimento = documentoForm.querySelector('[name="data_vencimento"]')?.value || '';
            if (dataVencimento) {
                formData.append('data_vencimento', dataVencimento);
            }
            const arquivoInputEl = documentoForm.querySelector('[name="arquivo"]');
            const arquivosInputEl = documentoForm.querySelector('[name="arquivos[]"]');
            let hasArquivoSelecionado = false;
            if (arquivoInputEl && arquivoInputEl.files && arquivoInputEl.files[0]) {
                formData.append('arquivo', arquivoInputEl.files[0]);
                hasArquivoSelecionado = true;
            }
            if (arquivosInputEl && arquivosInputEl.files && arquivosInputEl.files.length > 0) {
                Array.from(arquivosInputEl.files).forEach(file => {
                    formData.append('arquivos[]', file);
                    formData.append('caminhos[]', file.webkitRelativePath || file.name);
                });
                hasArquivoSelecionado = true;
            }

            if (!nomeDocumento && !hasArquivoSelecionado) {
                alert('Informe o nome do documento ou selecione arquivo/pasta.');
                return;
            }
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch(`/crm/colaboradores/${colaboradorId}/documentos`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const novosDocumentos = Array.isArray(data.documentos)
                        ? data.documentos
                        : (data.documento ? [data.documento] : []);
                    documentos = documentos.concat(novosDocumentos);
                    updateDocumentosList();
                    const nomeInput = documentoForm.querySelector('[name="nome_documento"]');
                    const dataInput = documentoForm.querySelector('[name="data_vencimento"]');
                    const fileInput = documentoForm.querySelector('[name="arquivo"]');
                    const filesInput = documentoForm.querySelector('[name="arquivos[]"]');
                    if (nomeInput) nomeInput.value = '';
                    if (dataInput) dataInput.value = '';
                    if (fileInput) fileInput.value = '';
                    if (filesInput) filesInput.value = '';
                    document.getElementById('arquivoLabel').textContent = 'Selecionar arquivo...';
                    const arquivosLabelEl = document.getElementById('arquivosLabel');
                    if (arquivosLabelEl) arquivosLabelEl.textContent = 'Selecionar pasta/arquivos...';
                } else {
                    alert('Erro ao registrar documento: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                alert('Erro ao registrar documento.');
            });
        }

        function updateDocumentosList() {
            const list = document.getElementById('documentosList');
            if (documentos.length === 0) {
                list.innerHTML = '<p style="text-align: center; color: #a0aec0; padding: 20px;">Nenhum documento registrado ainda.</p>';
                return;
            }

            const normalizePath = (doc) => {
                const path = (doc.caminho_relativo || doc.arquivo_nome_original || doc.nome_documento || '').replace(/\\/g, '/');
                return path || 'Raiz/' + (doc.nome_documento || ('arquivo-' + doc.id));
            };

            const bytesToText = (bytes) => {
                if (!bytes || bytes <= 0) return '-';
                const units = ['B', 'KB', 'MB', 'GB'];
                let value = bytes;
                let unitIndex = 0;
                while (value >= 1024 && unitIndex < units.length - 1) {
                    value /= 1024;
                    unitIndex += 1;
                }
                return `${value.toFixed(value < 10 && unitIndex > 0 ? 1 : 0)} ${units[unitIndex]}`;
            };

            const docsOrdenados = documentos
                .map((doc, index) => ({ doc, index, fullPath: normalizePath(doc) }))
                .sort((a, b) => a.fullPath.localeCompare(b.fullPath, 'pt-BR'));

            const grupos = {};
            docsOrdenados.forEach(item => {
                const partes = item.fullPath.split('/');
                const pasta = partes.length > 1 ? partes.slice(0, -1).join('/') : 'Raiz';
                if (!grupos[pasta]) grupos[pasta] = [];
                grupos[pasta].push(item);
            });

            list.innerHTML = Object.keys(grupos).sort((a, b) => a.localeCompare(b, 'pt-BR')).map(pasta => {
                const rows = grupos[pasta].map(({ doc, index, fullPath }) => {
                    const nomeArquivo = fullPath.split('/').pop() || doc.nome_documento || 'Arquivo';
                    const dataVenc = doc.data_vencimento ? new Date(doc.data_vencimento).toLocaleDateString('pt-BR') : '-';
                    return `
                        <div class="explorer-row">
                            <div class="explorer-name">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <span>${nomeArquivo}</span>
                            </div>
                            <div class="explorer-meta">Venc.: ${dataVenc}</div>
                            <div class="explorer-meta">${bytesToText(doc.arquivo_tamanho)}</div>
                            <div class="explorer-actions">
                                ${doc.arquivo_path ? `<a href="/crm/colaboradores/documentos/${doc.id}/arquivo" target="_blank" class="btn-icon" title="Abrir">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>` : ''}
                                <button type="button" onclick="moverDocumento(${doc.id}, ${index})" class="btn-icon" title="Mover para pasta">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"></path>
                                    </svg>
                                </button>
                                <button type="button" onclick="deleteDocumento(${doc.id}, ${index})" class="btn-icon" title="Excluir">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');

                return `
                    <div class="explorer-folder">
                        <div class="explorer-folder-header">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"></path>
                            </svg>
                            <span>${pasta}</span>
                        </div>
                        ${rows}
                    </div>
                `;
            }).join('');
        }

        function moverDocumento(docId, index) {
            const documento = documentos[index];
            if (!documento) return;

            const caminhoAtual = (documento.caminho_relativo || documento.arquivo_nome_original || '').replace(/\\/g, '/');
            const pastaAtual = caminhoAtual.includes('/') ? caminhoAtual.split('/').slice(0, -1).join('/') : '';
            const novaPasta = prompt('Informe a pasta de destino (ex.: NR10/2026). Deixe vazio para mover para a raiz:', pastaAtual || '');
            if (novaPasta === null) return;

            const formData = new FormData();
            formData.append('pasta_destino', novaPasta.trim());
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch(`/crm/colaboradores/documentos/${docId}/mover`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.documento) {
                    documentos[index] = data.documento;
                    updateDocumentosList();
                } else {
                    alert('Erro ao mover documento.');
                }
            })
            .catch(() => {
                alert('Erro ao mover documento.');
            });
        }

        function deleteDocumento(docId, index) {
            if (!confirm('Deseja realmente excluir este documento?')) return;

            fetch(`/crm/colaboradores/documentos/${docId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    documentos.splice(index, 1);
                    updateDocumentosList();
                } else {
                    alert('Erro ao excluir documento.');
                }
            })
            .catch(error => {
                alert('Erro ao excluir documento.');
            });
        }

        function submitColaborador(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const alertContainer = document.getElementById('colaboradorAlertContainer');
            
            if (!alertContainer) {
                return;
            }

            const nomeProfissional = e.target.querySelector('[name="nome_profissional"]')?.value?.trim() || '';
            const departamento = e.target.querySelector('[name="departamento"]')?.value || '';
            const valorHora = e.target.querySelector('[name="valor_hora"]')?.value || '';
            const cpf = e.target.querySelector('[name="cpf"]')?.value || '';
            const telefone = e.target.querySelector('[name="telefone"]')?.value || '';
            const email = e.target.querySelector('[name="email"]')?.value || '';
            const observacoes = e.target.querySelector('[name="observacoes"]')?.value || '';

            formData.append('nome_profissional', nomeProfissional);
            if (departamento) formData.append('departamento', departamento);
            if (valorHora !== '') formData.append('valor_hora', valorHora);
            if (cpf) formData.append('cpf', cpf);
            if (telefone) formData.append('telefone', telefone);
            if (email) formData.append('email', email);
            if (observacoes) formData.append('observacoes', observacoes);
            
            // Garantir que o campo ativo seja enviado corretamente
            const ativoCheckbox = e.target.querySelector('[name="ativo"]');
            if (ativoCheckbox) {
                if (ativoCheckbox.checked) {
                    formData.set('ativo', '1');
                } else {
                    formData.delete('ativo');
                }
            }
            
            const url = colaboradorId 
                ? `/crm/colaboradores/${colaboradorId}`
                : '/crm/colaboradores';
            if (colaboradorId) {
                // Evita problemas de multipart com PUT em alguns servidores/PHP
                formData.append('_method', 'PUT');
            }
            
            // Mostrar loading
            alertContainer.innerHTML = '<div class="alert alert-info">Salvando colaborador...</div>';
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(JSON.stringify(err));
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alertContainer.innerHTML = '<div class="alert alert-success">Colaborador salvo com sucesso!</div>';
                    if (!colaboradorId) {
                        colaboradorId = data.colaborador.id;
                        if (data.colaborador.documentos) {
                            documentos = data.colaborador.documentos;
                            updateDocumentosList();
                        }
                    }
                    setTimeout(() => {
                        if (documentos.length === 0) {
                            closeColaboradorModal();
                            location.reload();
                        }
                    }, 1500);
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
                    }
                    errors += '</ul>';
                    alertContainer.innerHTML = `<div class="alert alert-error">${errors}</div>`;
                }
            })
            .catch(error => {
                try {
                    const errorData = JSON.parse(error.message);
                    if (errorData.errors) {
                        let errors = '<ul style="list-style: none; padding: 0;">';
                        Object.values(errorData.errors).forEach(error => {
                            if (Array.isArray(error)) {
                                error.forEach(err => errors += `<li>${err}</li>`);
                            } else {
                                errors += `<li>${error}</li>`;
                            }
                        });
                        errors += '</ul>';
                        alertContainer.innerHTML = `<div class="alert alert-error">${errors}</div>`;
                    } else {
                        alertContainer.innerHTML = '<div class="alert alert-error">Erro ao salvar colaborador. Tente novamente.</div>';
                    }
                } catch (e) {
                    alertContainer.innerHTML = '<div class="alert alert-error">Erro ao salvar colaborador. Tente novamente.</div>';
                }
            });
        }
    </script>
</body>
</html>
