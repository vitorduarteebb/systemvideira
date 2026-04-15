<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Serviços - Sistema VIDEIRA</title>
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

        /* Sidebar - Usar componente */
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

        .new-servico-btn {
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

        .new-servico-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4);
        }

        .servicos-table-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        .servicos-table {
            width: 100%;
            border-collapse: collapse;
        }

        .servicos-table thead {
            background: #f7fafc;
        }

        .servicos-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
        }

        .servicos-table td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #4a5568;
        }

        .servicos-table tbody tr:hover {
            background: #f7fafc;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pendente {
            background: #fff3cd;
            color: #856404;
        }

        .status-em_andamento {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-concluido {
            background: #d4edda;
            color: #155724;
        }

        .status-pausado {
            background: #f8d7da;
            color: #721c24;
        }

        .status-cancelado {
            background: #e2e8f0;
            color: #4a5568;
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
            max-width: 900px;
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .info-text {
            font-size: 12px;
            color: #718096;
            margin-top: 4px;
        }

        .currency-input {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .currency-input span {
            color: #718096;
            font-weight: 600;
        }

        .periodo-config {
            background: #f7fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .periodo-title {
            font-size: 13px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .periodo-desc {
            font-size: 12px;
            color: #718096;
            margin-bottom: 16px;
        }

        .duracao-input {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .duracao-input input {
            width: 80px;
        }

        .equipe-section {
            margin-bottom: 24px;
        }

        .equipe-title {
            font-size: 13px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 12px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .equipe-empty {
            background: #f7fafc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            color: #718096;
            font-size: 13px;
        }

        .tecnicos-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .tecnico-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #e6f2ff;
            color: #4a90e2;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
        }

        .tecnico-badge button {
            background: none;
            border: none;
            color: #4a90e2;
            cursor: pointer;
            padding: 0;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dias-trabalho-section {
            margin-top: 24px;
        }

        .dias-trabalho-title {
            font-size: 13px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 12px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dia-trabalho-item {
            background: #f7fafc;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dia-trabalho-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .dia-numero {
            background: #e2e8f0;
            color: #4a5568;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .dia-data {
            font-weight: 600;
            color: #1a202c;
        }

        .dia-horario {
            color: #718096;
            font-size: 13px;
        }

        .dia-escalavel {
            color: #718096;
            font-size: 12px;
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
            display: flex;
            align-items: center;
            gap: 8px;
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
                    <h1 class="page-title">Serviços</h1>
                    <p class="page-subtitle">GESTÃO DE ORDENS E ATENDIMENTOS</p>
                </div>
                <div class="search-action-bar">
                    <form method="GET" action="{{ route('crm.servicos.index') }}" style="display: flex; gap: 12px;">
                        <input type="text" name="search" class="search-input" placeholder="🔍 Buscar VE, descrição ou cliente..." value="{{ request('search') }}">
                        <button type="button" onclick="openServicoModal()" class="new-servico-btn">
                            + NOVO SERVIÇO
                        </button>
                    </form>
                </div>
            </div>

            <div class="servicos-table-container">
                @if($servicos->count() > 0)
                    <table class="servicos-table">
                        <thead>
                            <tr>
                                <th>VE</th>
                                <th>SERVIÇO</th>
                                <th>CLIENTE</th>
                                <th>STATUS</th>
                                <th>DATA</th>
                                <th>AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servicos as $servico)
                                <tr>
                                    <td>{{ $servico->codigo_ve ?? '-' }}</td>
                                    <td>{{ Str::limit($servico->descricao ?? '-', 50) }}</td>
                                    <td>{{ $servico->cliente->nome ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $servico->status_operacional }}">
                                            {{ $servico->status_operacional_label }}
                                        </span>
                                    </td>
                                    <td>{{ $servico->data_inicio->format('d/m/Y') }}</td>
                                    <td>
                                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                            <a href="{{ route('crm.relatorios.show', $servico->id) }}" style="padding: 6px 12px; background: #10b981; border: 1px solid #059669; border-radius: 6px; cursor: pointer; font-size: 12px; color: white; font-weight: 600; text-decoration: none;">🗂 Atendimento</a>
                                            <a href="{{ route('crm.servicos.relatorio', $servico->id) }}" target="_blank" style="padding: 6px 12px; background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 6px; cursor: pointer; font-size: 12px; color: #0369a1; font-weight: 600; text-decoration: none;">📄 Relatório</a>
                                            <button onclick="editServico({{ $servico->id }})" style="padding: 6px 12px; background: #e6f2ff; border: 1px solid #4a90e2; border-radius: 6px; cursor: pointer; font-size: 12px; color: #4a90e2; font-weight: 600;">Editar</button>
                                            <button onclick="deleteServico({{ $servico->id }})" style="padding: 6px 12px; background: #ffebee; border: 1px solid #f44336; border-radius: 6px; cursor: pointer; font-size: 12px; color: #c62828; font-weight: 600;">Excluir</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($servicos->hasPages())
                        <div style="margin-top: 24px; display: flex; justify-content: center;">
                            {{ $servicos->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-state-text">NENHUM SERVIÇO REGISTRADO</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Novo Serviço -->
    <div class="modal-overlay" id="servicoModalOverlay" onclick="closeServicoModalOnOverlay(event)">
        <div class="modal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title">Novo Serviço</h2>
                    <p class="modal-subtitle">DADOS FUNCIONAIS</p>
                </div>
                <button class="modal-close" onclick="closeServicoModal()">×</button>
            </div>
            
            <div class="modal-tabs">
                <button class="modal-tab active" data-tab="dados-principais" onclick="switchTab('dados-principais')">DADOS PRINCIPAIS</button>
                <button class="modal-tab" data-tab="agenda-horas" onclick="switchTab('agenda-horas')">AGENDA & HORAS</button>
            </div>

            {{-- Fora das abas: mensagens de erro/sucesso ficam sempre visíveis ao salvar --}}
            <div id="servicoAlertContainer" style="padding: 0 24px; margin-top: 12px;"></div>

            <form id="servicoForm" onsubmit="submitServico(event)" novalidate>
                <div id="tab-dados-principais" class="tab-content active">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Código VE</label>
                            <input type="text" name="codigo_ve" class="form-input" placeholder="Opcional">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descrição do Serviço</label>
                            <input type="text" name="descricao" class="form-input" placeholder="Opcional">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Cliente</label>
                            <select name="cliente_id" id="clienteSelect" class="form-select" onchange="loadEquipamentos()">
                                <option value="">Selecione um cliente (Opcional)</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="9" y1="3" x2="9" y2="21"></line>
                                </svg>
                                Equipamento Relacionado
                            </label>
                            <select name="equipamento_id" id="equipamentoSelect" class="form-select">
                                <option value="">Nenhum equipamento (Opcional)</option>
                            </select>
                            <p class="info-text">* Selecione um cliente para listar equipamentos</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Data Início *</label>
                            <input type="date" name="data_inicio" class="form-input" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status Operacional *</label>
                            <select name="status_operacional" class="form-select" required>
                                <option value="pendente">Pendente</option>
                                <option value="em_andamento">Em Andamento</option>
                                <option value="pausado">Pausado</option>
                                <option value="concluido">Concluído</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="tab-agenda-horas" class="tab-content">
                    <div class="periodo-config">
                        <div class="periodo-title">CONFIGURAÇÃO DE PERÍODO</div>
                        <div class="periodo-desc">Define a quantidade de dias úteis projetados para este serviço.</div>
                        <div class="duracao-input">
                            <label class="form-label" style="margin: 0;">Duração:</label>
                            <input type="number" name="duracao_dias" id="duracaoDias" class="form-input" min="1" value="1" required onchange="updateDiasTrabalho()">
                            <span style="color: #718096; font-weight: 600;">Dias</span>
                        </div>
                        <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center; margin-top: 12px;">
                            <label style="display:flex; gap:10px; align-items:center; margin:0; font-size: 13px; color:#4a5568; font-weight:600;">
                                <input type="checkbox" id="apenasDiasUteis" checked style="transform: translateY(1px); width:auto;">
                                Gerar somente dias úteis (Seg–Sex)
                            </label>
                            <button type="button" class="btn btn-secondary" onclick="regenerarDiasTrabalho()">Gerar novamente</button>
                            <span class="info-text" style="margin:0;">Você pode editar as datas/horários abaixo antes de salvar.</span>
                        </div>
                    </div>

                    <div class="equipe-section">
                        <div class="equipe-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            EQUIPE DO SERVIÇO
                        </div>
                        <div id="equipeEmpty" class="equipe-empty">
                            Nenhum técnico escalado para o serviço ainda.
                        </div>
                        <input type="text" id="tecnicoSearch" class="form-input" placeholder="🔍 Pesquisar técnico..." style="margin-top: 12px;" onkeyup="searchTecnico()">
                        <div id="tecnicosList" class="tecnicos-list"></div>
                        <input type="hidden" id="tecnicosInput" value="">
                    </div>

                    <div class="dias-trabalho-section">
                        <div class="dias-trabalho-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            ENTRADAS DE TEMPO / DIAS DE TRABALHO
                        </div>
                        <div id="diasTrabalhoList"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeServicoModal()">DESCARTAR</button>
                    <button type="submit" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        SALVAR SERVIÇO
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let servicoId = null;
        let tecnicosSelecionados = [];
        let todosTecnicos = []; // Inicializado como array vazio
        let dataInicio = new Date();
        let diasTrabalhoCustom = null; // quando edição carrega dias do backend

        function openServicoModal() {
            document.getElementById('servicoModalOverlay').classList.add('active');
            servicoId = null;
            tecnicosSelecionados = [];
            document.getElementById('servicoForm').reset();
            document.getElementById('servicoAlertContainer').innerHTML = '';
            document.getElementById('equipamentoSelect').innerHTML = '<option value="">Nenhum equipamento (Opcional)</option>';
            document.getElementById('tecnicosList').innerHTML = '';
            document.getElementById('equipeEmpty').style.display = 'block';
            document.querySelector('.modal-title').textContent = 'Novo Serviço';
            switchTab('dados-principais');
            
            // Inicializar dropdown de técnicos
            setTimeout(() => {
                initTecnicoDropdown();
            }, 100);
            
            // Carregar técnicos disponíveis
            loadTecnicos();
            
            diasTrabalhoCustom = null;
            updateDiasTrabalho();
        }

        function editServico(id) {
            servicoId = id;
            document.getElementById('servicoModalOverlay').classList.add('active');
            document.querySelector('.modal-title').textContent = 'Editar Serviço';
            document.getElementById('servicoAlertContainer').innerHTML = '';
            switchTab('dados-principais');
            
            // Carregar dados do serviço
            fetch(`/crm/servicos/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.servico) {
                    const servico = data.servico;
                    
                    // Preencher formulário
                    document.querySelector('input[name="codigo_ve"]').value = servico.codigo_ve || '';
                    document.querySelector('input[name="descricao"]').value = servico.descricao || '';
                    {
                        const raw = servico.data_inicio || '';
                        document.querySelector('input[name="data_inicio"]').value = typeof raw === 'string' && raw.length >= 10
                            ? raw.slice(0, 10)
                            : (raw && String(raw).slice(0, 10)) || '';
                    }
                    document.querySelector('select[name="status_operacional"]').value = servico.status_operacional || 'pendente';
                    document.querySelector('input[name="duracao_dias"]').value = servico.duracao_dias || 1;
                    
                    // Cliente
                    if (servico.cliente_id) {
                        document.querySelector('select[name="cliente_id"]').value = servico.cliente_id;
                        loadEquipamentos();
                        
                        // Aguardar carregar equipamentos antes de selecionar
                        setTimeout(() => {
                            if (servico.equipamento_id) {
                                document.querySelector('select[name="equipamento_id"]').value = servico.equipamento_id;
                            }
                        }, 500);
                    }
                    
                    // Técnicos
                    tecnicosSelecionados = [];
                    if (servico.tecnicos && servico.tecnicos.length > 0) {
                        servico.tecnicos.forEach(tecnico => {
                            tecnicosSelecionados.push({
                                id: tecnico.id,
                                nome: tecnico.nome_profissional || tecnico.nome
                            });
                        });
                    }
                    loadTecnicos().then(() => {
                        updateTecnicosList();
                        setTimeout(() => {
                            initTecnicoDropdown();
                        }, 100);
                    });
                    
                    // Dias de trabalho
                    if ((servico.dias_trabalho && Array.isArray(servico.dias_trabalho) && servico.dias_trabalho.length) ||
                        (servico.diasTrabalho && Array.isArray(servico.diasTrabalho) && servico.diasTrabalho.length)) {
                        const dias = (servico.dias_trabalho || servico.diasTrabalho).map(d => ({
                            data: (d.data || '').toString().slice(0, 10),
                            hora_inicio: (d.hora_inicio || '08:00').toString().slice(0, 5),
                            hora_fim: (d.hora_fim || '17:00').toString().slice(0, 5),
                            intervalo_minutos: d.intervalo_minutos ?? 60,
                            escalavel: d.escalavel ?? true,
                        }));
                        diasTrabalhoCustom = dias;
                        document.querySelector('input[name="duracao_dias"]').value = dias.length || (servico.duracao_dias || 1);
                        renderDiasTrabalho(diasTrabalhoCustom);
                    } else {
                        diasTrabalhoCustom = null;
                        updateDiasTrabalho();
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao carregar serviço:', error);
                alert('Erro ao carregar dados do serviço');
            });
        }

        function deleteServico(id) {
            if (!confirm('Tem certeza que deseja excluir este serviço?')) {
                return;
            }
            
            fetch(`/crm/servicos/${id}`, {
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
                    alert('Erro ao excluir serviço');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao excluir serviço');
            });
        }

        function closeServicoModal() {
            document.getElementById('servicoModalOverlay').classList.remove('active');
        }

        function closeServicoModalOnOverlay(e) {
            if (e.target.id === 'servicoModalOverlay') {
                closeServicoModal();
            }
        }

        function switchTab(tabName) {
            document.querySelectorAll('.modal-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
            document.getElementById(`tab-${tabName}`).classList.add('active');
        }

        function loadEquipamentos() {
            const clienteId = document.getElementById('clienteSelect').value;
            const equipamentoSelect = document.getElementById('equipamentoSelect');
            
            if (!clienteId) {
                equipamentoSelect.innerHTML = '<option value="">Nenhum equipamento (Opcional)</option>';
                return;
            }

            fetch(`/crm/servicos/equipamentos?cliente_id=${clienteId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar equipamentos');
                }
                return response.json();
            })
            .then(equipamentos => {
                equipamentoSelect.innerHTML = '<option value="">Nenhum equipamento (Opcional)</option>';
                
                // Garantir que seja um array
                if (Array.isArray(equipamentos)) {
                    equipamentos.forEach(equip => {
                        const option = document.createElement('option');
                        option.value = equip.id;
                        option.textContent = equip.nome || equip.tag || `Equipamento #${equip.id}`;
                        equipamentoSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Erro ao carregar equipamentos:', error);
                equipamentoSelect.innerHTML = '<option value="">Erro ao carregar equipamentos</option>';
            });
        }

        function loadTecnicos() {
            return fetch('/crm/servicos/tecnicos', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar técnicos');
                }
                return response.json();
            })
            .then(tecnicos => {
                // Garantir que seja um array
                todosTecnicos = Array.isArray(tecnicos) ? tecnicos : [];
                updateTecnicosSearch();
                return todosTecnicos;
            })
            .catch(error => {
                console.error('Erro ao carregar técnicos:', error);
                todosTecnicos = [];
                return [];
            });
        }

        function searchTecnico() {
            updateTecnicosSearch();
        }

        let tecnicoDropdown = null;

        function initTecnicoDropdown() {
            const searchInput = document.getElementById('tecnicoSearch');
            if (!searchInput || tecnicoDropdown) return;
            
            tecnicoDropdown = document.createElement('div');
            tecnicoDropdown.id = 'tecnicoDropdown';
            tecnicoDropdown.style.cssText = 'position: absolute; background: white; border: 1px solid #e2e8f0; border-radius: 8px; max-height: 200px; overflow-y: auto; z-index: 1000; margin-top: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: none; width: 100%;';
            searchInput.parentElement.style.position = 'relative';
            searchInput.parentElement.appendChild(tecnicoDropdown);
        }

        function updateTecnicosSearch() {
            const searchInput = document.getElementById('tecnicoSearch');
            if (!searchInput) return;
            
            // Garantir que todosTecnicos seja um array
            if (!Array.isArray(todosTecnicos)) {
                todosTecnicos = [];
            }
            
            const search = searchInput.value.toLowerCase();
            const filtered = todosTecnicos.filter(t => {
                const nome = (t.nome_profissional || '').toLowerCase();
                const cpf = (t.cpf || '').toLowerCase();
                return nome.includes(search) || cpf.includes(search);
            });
            
            // Inicializar dropdown se não existir
            if (!tecnicoDropdown) {
                initTecnicoDropdown();
            }
            
            if (search.length > 0 && filtered.length > 0 && tecnicoDropdown) {
                tecnicoDropdown.innerHTML = '';
                filtered.slice(0, 10).forEach(t => {
                    const row = document.createElement('div');
                    row.style.cssText = 'padding: 12px; cursor: pointer; border-bottom: 1px solid #f7fafc; transition: background 0.2s;';
                    row.addEventListener('mouseenter', () => { row.style.background = '#f7fafc'; });
                    row.addEventListener('mouseleave', () => { row.style.background = 'white'; });
                    row.addEventListener('mousedown', ev => ev.preventDefault());
                    const nome = t.nome_profissional || 'Sem nome';
                    row.addEventListener('click', () => addTecnico(t.id, nome));
                    const title = document.createElement('div');
                    title.style.cssText = 'font-weight: 600; color: #1a202c;';
                    title.textContent = nome;
                    row.appendChild(title);
                    if (t.departamento) {
                        const sub = document.createElement('div');
                        sub.style.cssText = 'font-size: 12px; color: #718096;';
                        sub.textContent = t.departamento;
                        row.appendChild(sub);
                    }
                    tecnicoDropdown.appendChild(row);
                });
                tecnicoDropdown.style.display = 'block';
            } else if (tecnicoDropdown) {
                tecnicoDropdown.style.display = 'none';
            }
        }

        // Fechar dropdown ao clicar fora
        document.addEventListener('click', function(e) {
            if (tecnicoDropdown && !tecnicoDropdown.contains(e.target) && e.target.id !== 'tecnicoSearch') {
                tecnicoDropdown.style.display = 'none';
            }
        });

        function addTecnico(tecnicoId, tecnicoNome) {
            if (tecnicosSelecionados.find(t => t.id === tecnicoId)) return;
            
            tecnicosSelecionados.push({ id: tecnicoId, nome: tecnicoNome });
            updateTecnicosList();
            
            // Limpar busca e fechar dropdown
            document.getElementById('tecnicoSearch').value = '';
            if (tecnicoDropdown) {
                tecnicoDropdown.style.display = 'none';
            }
        }

        function removeTecnico(tecnicoId) {
            tecnicosSelecionados = tecnicosSelecionados.filter(t => t.id !== tecnicoId);
            updateTecnicosList();
        }

        function updateTecnicosList() {
            const list = document.getElementById('tecnicosList');
            const empty = document.getElementById('equipeEmpty');
            const input = document.getElementById('tecnicosInput');
            
            if (tecnicosSelecionados.length === 0) {
                list.innerHTML = '';
                empty.style.display = 'block';
                input.value = '';
            } else {
                empty.style.display = 'none';
                list.innerHTML = tecnicosSelecionados.map(t => `
                    <div class="tecnico-badge">
                        <span>${t.nome}</span>
                        <button type="button" onclick="removeTecnico(${t.id})" title="Remover">×</button>
                    </div>
                `).join('');
                input.value = JSON.stringify(tecnicosSelecionados.map(t => t.id));
            }
        }

        function updateDiasTrabalho() {
            const duracao = parseInt(document.getElementById('duracaoDias').value) || 1;
            const dataInicioInput = document.querySelector('input[name="data_inicio"]');
            const dataInicio = dataInicioInput ? new Date(dataInicioInput.value) : new Date();

            // se mudou duração/data início, consideramos que o usuário quer regenerar a agenda
            diasTrabalhoCustom = null;
            const apenasUteis = document.getElementById('apenasDiasUteis')?.checked !== false;
            const dias = gerarDiasTrabalho(duracao, dataInicio, apenasUteis);
            renderDiasTrabalho(dias);
        }

        document.querySelector('input[name="data_inicio"]')?.addEventListener('change', function() {
            updateDiasTrabalho();
        });

        document.getElementById('apenasDiasUteis')?.addEventListener('change', function() {
            updateDiasTrabalho();
        });

        function regenerarDiasTrabalho() {
            diasTrabalhoCustom = null;
            updateDiasTrabalho();
        }

        function formatDateYMD(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        }

        function gerarDiasTrabalho(duracao, dataInicio, apenasDiasUteis) {
            const dias = [];
            const cursor = new Date(dataInicio);
            cursor.setHours(12, 0, 0, 0); // evita bugs de fuso/dst

            while (dias.length < duracao) {
                const day = cursor.getDay(); // 0 dom, 6 sab
                const isWeekend = (day === 0 || day === 6);

                if (!apenasDiasUteis || !isWeekend) {
                    dias.push({
                        data: formatDateYMD(cursor),
                        hora_inicio: '08:00',
                        hora_fim: '17:00',
                        intervalo_minutos: 60,
                        escalavel: true,
                    });
                }

                cursor.setDate(cursor.getDate() + 1);
            }

            return dias;
        }

        function renderDiasTrabalho(dias) {
            const list = document.getElementById('diasTrabalhoList');
            if (!list) return;
            list.innerHTML = '';

            dias.forEach((dia, i) => {
                const diaItem = document.createElement('div');
                diaItem.className = 'dia-trabalho-item';
                diaItem.style.flexDirection = 'column';
                diaItem.style.alignItems = 'stretch';
                diaItem.innerHTML = `
                    <div style="display:flex; justify-content: space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
                        <div class="dia-trabalho-info" style="flex-wrap: wrap;">
                            <span class="dia-numero">D${i + 1}</span>
                            <div style="display:flex; gap: 10px; flex-wrap: wrap; align-items: end;">
                                <div style="min-width: 180px;">
                                    <label class="form-label" style="margin-bottom:6px;">Data</label>
                                    <input type="date" name="dias_trabalho[${i}][data]" class="form-input" required value="${dia.data || ''}">
                                </div>
                                <div style="min-width: 140px;">
                                    <label class="form-label" style="margin-bottom:6px;">Início</label>
                                    <input type="time" name="dias_trabalho[${i}][hora_inicio]" class="form-input" required value="${(dia.hora_inicio || '08:00').slice(0,5)}">
                                </div>
                                <div style="min-width: 140px;">
                                    <label class="form-label" style="margin-bottom:6px;">Fim</label>
                                    <input type="time" name="dias_trabalho[${i}][hora_fim]" class="form-input" required value="${(dia.hora_fim || '17:00').slice(0,5)}">
                                </div>
                                <div style="min-width: 170px;">
                                    <label class="form-label" style="margin-bottom:6px;">Intervalo (min)</label>
                                    <input type="number" name="dias_trabalho[${i}][intervalo_minutos]" class="form-input" min="0" value="${dia.intervalo_minutos ?? 60}">
                                </div>
                            </div>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="hidden" name="dias_trabalho[${i}][escalavel]" id="dias_trabalho_esc_${i}" value="${dia.escalavel === false ? '0' : '1'}">
                            <label style="display:flex; gap:10px; align-items:center; margin:0; font-size: 12px; color:#718096; font-weight:700;">
                                <input type="checkbox" ${dia.escalavel === false ? '' : 'checked'}
                                    onchange="document.getElementById('dias_trabalho_esc_${i}').value = this.checked ? '1' : '0'"
                                    style="transform: translateY(1px); width:auto;">
                                ESCALÁVEL
                            </label>
                        </div>
                    </div>
                `;
                list.appendChild(diaItem);
            });
        }

        function submitServico(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const alertContainer = document.getElementById('servicoAlertContainer');
            alertContainer.innerHTML = '';
            
            formData.delete('tecnicos');
            formData.append('tecnicos', JSON.stringify(tecnicosSelecionados.map(t => t.id)));
            
            const url = servicoId 
                ? `/crm/servicos/${servicoId}`
                : '/crm/servicos';
            
            if (servicoId) {
                formData.append('_method', 'PUT');
            }
            
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
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (err) {
                    console.error('Resposta não-JSON:', text.slice(0, 500));
                    throw new Error('O servidor não retornou JSON (sessão expirou ou erro de rota). Atualize a página e tente de novo.');
                }
                if (!response.ok && !data.errors && !data.success) {
                    throw new Error(data.message || ('Erro HTTP ' + response.status));
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    alertContainer.innerHTML = '<div class="alert alert-success">Serviço salvo com sucesso!</div>';
                    setTimeout(() => {
                        closeServicoModal();
                        location.reload();
                    }, 1000);
                } else {
                    let body = '';
                    if (data.errors) {
                        body = '<ul style="list-style: none; padding: 0;">';
                        Object.values(data.errors).forEach(error => {
                            if (Array.isArray(error)) {
                                error.forEach(err => { body += `<li>${err}</li>`; });
                            } else {
                                body += `<li>${error}</li>`;
                            }
                        });
                        body += '</ul>';
                    } else if (data.message) {
                        body = `<p style="margin:0;">${data.message}</p>`;
                    } else {
                        body = '<p style="margin:0;">Não foi possível salvar. Verifique os campos e tente novamente.</p>';
                    }
                    alertContainer.innerHTML = `<div class="alert alert-error">${body}</div>`;
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alertContainer.innerHTML = `<div class="alert alert-error">${error.message || 'Erro ao salvar serviço. Tente novamente.'}</div>`;
            });
        }
    </script>
</body>
</html>
