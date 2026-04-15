<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Funil de Vendas - Sistema VIDEIRA</title>
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

        /* Sidebar */
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

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* Top Header */
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
            border-radius: 10px;
            background: #f7fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .notification-icon:hover {
            background: #edf2f7;
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
            font-size: 14px;
            font-weight: 600;
            color: #1a202c;
        }

        .user-role {
            font-size: 11px;
            color: #718096;
            text-transform: uppercase;
        }

        /* Content Area */
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
            align-items: center;
            gap: 16px;
            margin-bottom: 8px;
        }

        .page-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            flex: 1;
        }

        .page-subtitle {
            font-size: 14px;
            color: #718096;
            margin-left: 64px;
        }

        /* Metrics Cards */
        .metrics-row {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            flex: 1;
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .metric-icon.blue {
            background: #e6f2ff;
            color: #4a90e2;
        }

        .metric-icon.yellow {
            background: #fff8e1;
            color: #ffa726;
        }

        .metric-icon.green {
            background: #e8f5e9;
            color: #66bb6a;
        }

        .metric-icon.grey {
            background: #f5f5f5;
            color: #757575;
        }

        .metric-content {
            flex: 1;
        }

        .metric-label {
            font-size: 12px;
            color: #718096;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .metric-value {
            font-size: 24px;
            font-weight: 700;
            color: #1a202c;
        }

        .new-proposal-btn {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        .new-proposal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4);
        }

        /* Filters */
        .filters-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .filters-row {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 170px;
        }

        .filter-label {
            font-size: 12px;
            color: #718096;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .filter-input, .filter-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .clear-btn {
            padding: 10px 20px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #4a5568;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .clear-btn:hover {
            background: #edf2f7;
        }

        /* Kanban Board */
        .kanban-board {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-top: 24px;
        }

        .kanban-column {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 500px);
            min-height: 400px;
        }

        .column-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f7fafc;
        }

        .column-title {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .column-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .column-dot.grey { background: #9e9e9e; }
        .column-dot.orange { background: #ff9800; }
        .column-dot.green { background: #4caf50; }
        .column-dot.red { background: #f44336; }
        .column-dot.dark-grey { background: #616161; }

        .column-count {
            background: #f7fafc;
            color: #4a5568;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .column-actions {
            display: flex;
            gap: 8px;
        }

        .column-action-btn {
            width: 24px;
            height: 24px;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #718096;
            transition: all 0.3s;
        }

        .column-action-btn:hover {
            background: #f7fafc;
            color: #4a5568;
        }

        .column-content {
            flex: 1;
            overflow-y: auto;
            min-height: 200px;
        }

        .column-content.drag-over {
            background: #e6f2ff;
            border: 2px dashed #4a90e2;
        }

        .empty-column {
            text-align: center;
            padding: 40px 20px;
            color: #a0aec0;
            font-size: 13px;
        }

        .proposal-card {
            background: #f7fafc;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            cursor: grab;
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .proposal-card:active {
            cursor: grabbing;
        }

        .proposal-card.dragging {
            opacity: 0.5;
            transform: rotate(5deg);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .proposal-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .proposal-code {
            font-size: 12px;
            font-weight: 600;
            color: #4a90e2;
            margin-bottom: 8px;
        }

        .proposal-client {
            font-size: 14px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .proposal-value {
            font-size: 16px;
            font-weight: 700;
            color: #1a202c;
            margin-top: 8px;
        }

        .proposal-actions {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            gap: 4px;
        }

        .proposal-action-btn {
            width: 20px;
            height: 20px;
            border: none;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: all 0.3s;
        }

        .proposal-action-btn:hover {
            background: rgba(0, 0, 0, 0.1);
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

        /* Modal Cliente */
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

        .modal-tab:hover {
            color: #4a5568;
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
            flex-shrink: 0;
        }

        .add-email-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
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
            transition: all 0.3s;
        }

        .remove-email-btn:hover {
            background: #cbd5e0;
            color: #4a5568;
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

        .upload-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .upload-text {
            font-size: 14px;
            color: #718096;
            margin-bottom: 8px;
        }

        .upload-hint {
            font-size: 12px;
            color: #a0aec0;
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

        .planta-card-body {
            padding: 12px;
        }

        .planta-card-name {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .planta-card-desc {
            font-size: 12px;
            color: #718096;
        }

        .empty-planta-state {
            text-align: center;
            padding: 40px;
            color: #a0aec0;
        }
    </style>
</head>
<body>
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="top-header-left">PORTAL ADMINISTRATIVO</div>
            <div class="top-header-right">
                <div class="notification-icon">🔔</div>
                <div class="user-profile">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">ACESSO TOTAL</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <div class="page-header">
                <div class="page-title-section">
                    <div class="page-icon">🎯</div>
                    <div>
                        <h1 class="page-title">Funil de Vendas CRM</h1>
                        <p class="page-subtitle">ACOMPANHAMENTO COMERCIAL</p>
                    </div>
                    <button onclick="openModal()" class="new-proposal-btn">
                        + NOVA PROPOSTA
                    </button>
                </div>
            </div>

            <!-- Metrics -->
            <div class="metrics-row">
                <div class="metric-card">
                    <div class="metric-icon blue">💰</div>
                    <div class="metric-content">
                        <div class="metric-label">TOTAL GERAL</div>
                        <div class="metric-value">R$ {{ number_format($totalGeral, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon yellow">📊</div>
                    <div class="metric-content">
                        <div class="metric-label">EM NEGOCIAÇÃO</div>
                        <div class="metric-value">R$ {{ number_format($emNegociacao, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon green">✅</div>
                    <div class="metric-content">
                        <div class="metric-label">VALOR FECHADO</div>
                        <div class="metric-value">R$ {{ number_format($valorFechado, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon grey">⏰</div>
                    <div class="metric-content">
                        <div class="metric-label">AÇÕES PENDENTES</div>
                        <div class="metric-value">{{ $acoesPendentes }}</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('crm.funil') }}" class="filters-section">
                <div class="filters-row">
                    <div class="filter-group">
                        <div class="filter-label">CLIENTE</div>
                        <input type="text" name="cliente_search" class="filter-input" placeholder="🔍 Filtrar por Cliente..." value="{{ request('cliente_search') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">CÓDIGO</div>
                        <input type="text" name="codigo_proposta" class="filter-input" placeholder="# Cód. Proposta..." value="{{ request('codigo_proposta') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">RESPONSÁVEL</div>
                        <select name="responsavel_id" class="filter-select">
                            <option value="">Todos</option>
                            @foreach($responsaveis as $responsavel)
                                <option value="{{ $responsavel->id }}" {{ request('responsavel_id') == $responsavel->id ? 'selected' : '' }}>
                                    {{ $responsavel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">STATUS</div>
                        <select name="estado" class="filter-select">
                            <option value="">Todos</option>
                            <option value="primeiro_contato" {{ request('estado') === 'primeiro_contato' ? 'selected' : '' }}>Primeiro contato</option>
                            <option value="em_analise" {{ request('estado') === 'em_analise' ? 'selected' : '' }}>Em negociação</option>
                            <option value="fechado" {{ request('estado') === 'fechado' ? 'selected' : '' }}>Ganhou</option>
                            <option value="perdido" {{ request('estado') === 'perdido' ? 'selected' : '' }}>Perdeu</option>
                            <option value="outros" {{ request('estado') === 'outros' ? 'selected' : '' }}>Outros</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">TIPO DE DATA</div>
                        <select name="tipo_data" class="filter-select">
                            <option value="criacao" {{ ($tipoDataFiltro ?? request('tipo_data', 'criacao')) === 'criacao' ? 'selected' : '' }}>Abertura/Criação</option>
                            <option value="fechamento" {{ ($tipoDataFiltro ?? request('tipo_data', 'criacao')) === 'fechamento' ? 'selected' : '' }}>Fechamento</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">PERÍODO RÁPIDO</div>
                        <select name="periodo" class="filter-select">
                            <option value="">Personalizado</option>
                            <option value="hoje" {{ request('periodo') === 'hoje' ? 'selected' : '' }}>Hoje</option>
                            <option value="7dias" {{ request('periodo') === '7dias' ? 'selected' : '' }}>Últimos 7 dias</option>
                            <option value="30dias" {{ request('periodo') === '30dias' ? 'selected' : '' }}>Últimos 30 dias</option>
                            <option value="mes_atual" {{ request('periodo') === 'mes_atual' ? 'selected' : '' }}>Mês atual</option>
                            <option value="trimestre" {{ request('periodo') === 'trimestre' ? 'selected' : '' }}>Trimestre</option>
                            <option value="ano_atual" {{ request('periodo') === 'ano_atual' ? 'selected' : '' }}>Ano atual</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">DATA DE</div>
                        <input type="date" name="data_de" class="filter-input" value="{{ request('data_de') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">DATA ATÉ</div>
                        <input type="date" name="data_ate" class="filter-input" value="{{ request('data_ate') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">VALOR MÍN.</div>
                        <input type="number" step="0.01" min="0" name="valor_min" class="filter-input" value="{{ request('valor_min') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">VALOR MÁX.</div>
                        <input type="number" step="0.01" min="0" name="valor_max" class="filter-input" value="{{ request('valor_max') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">MOTIVO GANHO</div>
                        <input type="text" name="motivo_ganho" class="filter-input" placeholder="Ex.: preço, prazo..." value="{{ request('motivo_ganho') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">MOTIVO PERDA</div>
                        <input type="text" name="motivo_perda" class="filter-input" placeholder="Ex.: concorrente, preço..." value="{{ request('motivo_perda') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">MOTIVO NEGOCIAÇÃO</div>
                        <input type="text" name="motivo_negociacao" class="filter-input" placeholder="Ex.: aguardando aprovação..." value="{{ request('motivo_negociacao') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">TEMPO FECH. (dias) MIN</div>
                        <input type="number" min="0" name="tempo_fechamento_min" class="filter-input" value="{{ request('tempo_fechamento_min') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">TEMPO FECH. (dias) MÁX</div>
                        <input type="number" min="0" name="tempo_fechamento_max" class="filter-input" value="{{ request('tempo_fechamento_max') }}">
                    </div>
                    <button type="submit" class="clear-btn" style="background: #4a90e2; color: #fff; border-color: #4a90e2;">✅ APLICAR</button>
                    <button type="button" onclick="window.location.href='{{ route('crm.funil') }}'" class="clear-btn">🧹 LIMPAR</button>
                </div>
            </form>

            <!-- Kanban Board -->
            <div class="kanban-board" id="kanbanBoard">
                @php
                    $estados = [
                        'primeiro_contato' => ['label' => 'PRIMEIRO CONTATO', 'dot' => 'grey', 'count' => $primeiroContato],
                        'em_analise' => ['label' => 'EM ANÁLISE', 'dot' => 'orange', 'count' => $emAnalise],
                        'fechado' => ['label' => 'FECHADO', 'dot' => 'green', 'count' => $fechado],
                        'perdido' => ['label' => 'PERDIDO', 'dot' => 'red', 'count' => $perdido],
                        'outros' => ['label' => 'OUTROS', 'dot' => 'dark-grey', 'count' => 0],
                    ];
                @endphp

                @foreach($estados as $estadoKey => $estadoInfo)
                    <div class="kanban-column" data-estado="{{ $estadoKey }}">
                        <div class="column-header">
                            <div class="column-title">
                                <span class="column-dot {{ $estadoInfo['dot'] }}"></span>
                                {{ $estadoInfo['label'] }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="column-count">{{ $estadoInfo['count'] }}</div>
                                @if($estadoKey !== 'outros')
                                    <button class="column-action-btn" onclick="deleteColumn('{{ $estadoKey }}')" title="Excluir coluna">×</button>
                                @endif
                            </div>
                        </div>
                        <div class="column-content" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
                            @php
                                $estadoPropostas = $propostas->where('estado', $estadoKey);
                            @endphp
                            @forelse($estadoPropostas as $proposta)
                                <div class="proposal-card" draggable="true" ondragstart="handleDragStart(event)" data-proposta-id="{{ $proposta->id }}" onclick="viewProposta({{ $proposta->id }})" style="cursor: pointer;">
                                    <div class="proposal-actions" onclick="event.stopPropagation();">
                                        <button class="proposal-action-btn" onclick="viewProposta({{ $proposta->id }})" title="Ver Detalhes">👁️</button>
                                        <button class="proposal-action-btn" onclick="deleteProposta({{ $proposta->id }})" title="Excluir">×</button>
                                    </div>
                                    <div class="proposal-code">
                                        {{ $proposta->codigo_proposta }}
                                        <a href="{{ route('crm.propostas.pdf', $proposta) }}" title="Baixar PDF" style="margin-left:6px; font-size:12px; text-decoration:none;">📄</a>
                                    </div>
                                    <div class="proposal-client">{{ $proposta->cliente->nome }}</div>
                                    <div class="proposal-value">R$ {{ number_format($proposta->valor_final, 2, ',', '.') }}</div>
                                </div>
                            @empty
                                <div class="empty-column">Nenhuma proposta</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
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
                        <div class="form-group">
                            <label class="form-label">Identificar Nova Planta</label>
                            <input type="text" id="plantaNome" class="form-input" placeholder="Ex: Térreo, Cobertura...">
                        </div>

                        <div class="upload-area" id="uploadArea" onclick="document.getElementById('plantaImagem').click()">
                            <div class="upload-icon">📷</div>
                            <div class="upload-text">Carregar Imagem</div>
                            <div class="upload-hint">Clique ou arraste uma imagem aqui</div>
                            <input type="file" id="plantaImagem" accept="image/*" style="display: none;" onchange="handlePlantaImage(event)">
                        </div>

                        <div id="plantaPreview" class="planta-preview"></div>

                        <div style="margin-top: 24px;">
                            <label class="form-label">Plantas Ativas</label>
                            <div id="plantasAtivas" class="empty-planta-state">
                                Nenhuma planta cadastrada para este cliente.
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

    <!-- Modal Nova Proposta -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModalOnOverlay(event)">
        <div class="modal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">Nova Proposta CRM</h2>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <form id="propostaForm" onsubmit="submitProposta(event)">
                <div class="modal-body">
                    <div id="alertContainer"></div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Cliente / Prospect <span class="required">*</span>
                        </label>
                        <select name="cliente_id" class="form-select" required>
                            <option value="">Selecione um cliente...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">
                                    {{ $cliente->nome }} @if($cliente->empresa) - {{ $cliente->empresa }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Valor Final (R$) <span class="required">*</span>
                            </label>
                            <input type="number" name="valor_final" class="form-input" step="0.01" min="0" value="0.00" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Estado do Funil <span class="required">*</span>
                            </label>
                            <select name="estado" class="form-select" required>
                                <option value="primeiro_contato">Primeiro Contato</option>
                                <option value="em_analise">Em Análise</option>
                                <option value="fechado">Fechado</option>
                                <option value="perdido">Perdido</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Título / Descrição Inicial</label>
                        <input type="text" name="titulo" class="form-input" placeholder="Título da proposta...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descrição Inicial</label>
                        <textarea name="descricao_inicial" class="form-textarea" placeholder="Descreva a proposta..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Configurações Técnicas (Interno)</label>
                        <textarea name="configuracoes_tecnicas" class="form-textarea" placeholder="Detalhes técnicos internos..."></textarea>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Data de Criação <span class="required">*</span>
                            </label>
                            <input type="date" name="data_criacao" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Responsável Comercial</label>
                            <select name="responsavel_id" class="form-select">
                                <option value="">Selecione...</option>
                                @foreach($responsaveis as $responsavel)
                                    <option value="{{ $responsavel->id }}">
                                        {{ $responsavel->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Proposta</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let draggedElement = null;
        let draggedPropostaId = null;

        // Drag and Drop
        function handleDragStart(e) {
            draggedElement = e.target;
            draggedPropostaId = e.target.dataset.propostaId;
            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            e.currentTarget.classList.add('drag-over');
        }

        function handleDragLeave(e) {
            e.currentTarget.classList.remove('drag-over');
        }

        function handleDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.remove('drag-over');
            
            if (draggedElement && draggedPropostaId) {
                const newEstado = e.currentTarget.closest('.kanban-column').dataset.estado;
                
                // Atualizar via AJAX
                updatePropostaEstado(draggedPropostaId, newEstado);
                
                // Mover visualmente
                e.currentTarget.appendChild(draggedElement);
                draggedElement.classList.remove('dragging');
                
                // Atualizar contadores
                updateCounters();
                
                draggedElement = null;
                draggedPropostaId = null;
            }
        }

        function updatePropostaEstado(propostaId, novoEstado) {
            const formData = new FormData();
            formData.append('estado', novoEstado);
            formData.append('_method', 'PUT');
            
            fetch(`/crm/propostas/${propostaId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                location.reload();
            });
        }

        function updateCounters() {
            document.querySelectorAll('.kanban-column').forEach(column => {
                const count = column.querySelectorAll('.proposal-card').length;
                const countElement = column.querySelector('.column-count');
                if (countElement) {
                    countElement.textContent = count;
                }
            });
        }

        // Modal
        function openModal() {
            document.getElementById('modalOverlay').classList.add('active');
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.getElementById('propostaForm').reset();
            document.getElementById('alertContainer').innerHTML = '';
        }

        function closeModalOnOverlay(e) {
            if (e.target.id === 'modalOverlay') {
                closeModal();
            }
        }

        function submitProposta(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const alertContainer = document.getElementById('alertContainer');
            
            fetch('{{ route("crm.propostas.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertContainer.innerHTML = '<div class="alert alert-success">Proposta criada com sucesso!</div>';
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    let errors = '<ul>';
                    if (data.errors) {
                        Object.values(data.errors).forEach(error => {
                            errors += `<li>${error[0]}</li>`;
                        });
                    }
                    errors += '</ul>';
                    alertContainer.innerHTML = `<div class="alert alert-error">${errors}</div>`;
                }
            })
            .catch(error => {
                alertContainer.innerHTML = '<div class="alert alert-error">Erro ao criar proposta. Tente novamente.</div>';
            });
        }

        function deleteProposta(id) {
            if (confirm('Tem certeza que deseja excluir esta proposta?')) {
                fetch(`/crm/propostas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(() => {
                    location.reload();
                });
            }
        }

        function deleteColumn(estado) {
            if (confirm('Tem certeza que deseja excluir esta coluna? As propostas serão movidas para "OUTROS".')) {
                // Implementar lógica de exclusão de coluna
                alert('Funcionalidade em desenvolvimento');
            }
        }

        // Partículas animadas na sidebar
        function initParticles() {
            const canvas = document.getElementById('particlesCanvas');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            const sidebar = document.getElementById('sidebar');
            
            canvas.width = sidebar.offsetWidth;
            canvas.height = sidebar.offsetHeight;
            
            const particles = [];
            const particleCount = 30;
            
            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 2 + 1;
                    this.speedX = (Math.random() - 0.5) * 0.5;
                    this.speedY = (Math.random() - 0.5) * 0.5;
                    this.opacity = Math.random() * 0.5 + 0.2;
                }
                
                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;
                    
                    if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                    if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
                }
                
                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(135, 206, 235, ${this.opacity})`;
                    ctx.fill();
                }
            }
            
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
            
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                particles.forEach(particle => {
                    particle.update();
                    particle.draw();
                });
                
                // Conectar partículas próximas
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        
                        if (distance < 100) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = `rgba(135, 206, 235, ${0.1 * (1 - distance / 100)})`;
                            ctx.lineWidth = 1;
                            ctx.stroke();
                        }
                    }
                }
                
                requestAnimationFrame(animate);
            }
            
            animate();
            
            window.addEventListener('resize', () => {
                canvas.width = sidebar.offsetWidth;
                canvas.height = sidebar.offsetHeight;
            });
        }
        
        // Inicializar partículas quando a página carregar
        document.addEventListener('DOMContentLoaded', initParticles);

        // Modal Cliente
        let emailsList = [];
        let plantasList = [];

        function openClienteModal() {
            document.getElementById('clienteModalOverlay').classList.add('active');
            switchTab('dados');
        }

        function closeClienteModal() {
            document.getElementById('clienteModalOverlay').classList.remove('active');
            document.getElementById('clienteForm').reset();
            emailsList = [];
            plantasList = [];
            updateEmailsList();
            updatePlantasPreview();
            document.getElementById('clienteAlertContainer').innerHTML = '';
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
                    const nome = document.getElementById('plantaNome').value || `Planta ${plantasList.length + 1}`;
                    plantasList.push({
                        nome: nome,
                        imagem: file,
                        preview: e.target.result
                    });
                    document.getElementById('plantaNome').value = '';
                    event.target.value = '';
                    updatePlantasPreview();
                };
                reader.readAsDataURL(file);
            }
        }

        function removePlanta(index) {
            plantasList.splice(index, 1);
            updatePlantasPreview();
        }

        function updatePlantasPreview() {
            const container = document.getElementById('plantaPreview');
            if (plantasList.length === 0) {
                container.innerHTML = '';
                return;
            }
            
            container.innerHTML = plantasList.map((planta, index) => `
                <div class="planta-card">
                    <img src="${planta.preview}" alt="${planta.nome}">
                    <div class="planta-card-body">
                        <div class="planta-card-name">${planta.nome}</div>
                        <button type="button" class="remove-email-btn" onclick="removePlanta(${index})" style="margin-top: 8px;">Remover</button>
                    </div>
                </div>
            `).join('');
        }

        // Drag and drop para upload
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
            
            plantasList.forEach((planta, index) => {
                formData.append(`plantas[${index}][nome]`, planta.nome);
                formData.append(`plantas[${index}][imagem]`, planta.imagem);
            });
            
            const alertContainer = document.getElementById('clienteAlertContainer');
            
            fetch('{{ route("crm.clientes.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertContainer.innerHTML = '<div class="alert alert-success">Cliente cadastrado com sucesso!</div>';
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
                    }
                    errors += '</ul>';
                    alertContainer.innerHTML = `<div class="alert alert-error">${errors}</div>`;
                }
            })
            .catch(error => {
                alertContainer.innerHTML = '<div class="alert alert-error">Erro ao cadastrar cliente. Tente novamente.</div>';
            });
        }

        // Função para visualizar proposta
        function viewProposta(propostaId) {
            event.stopPropagation();
            
            // Carregar dados da proposta
            fetch(`/crm/propostas/${propostaId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const proposta = data.proposta;
                    openPropostaModal(proposta);
                } else {
                    alert('Erro ao carregar proposta');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao carregar proposta');
            });
        }

        function openPropostaModal(proposta) {
            const modal = document.getElementById('propostaModal');
            const modalContent = document.getElementById('propostaModalContent');
            
            modalContent.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #e2e8f0;">
                    <div>
                        <h2 style="font-size: 24px; font-weight: 700; color: #1a202c; margin-bottom: 4px;">${proposta.codigo_proposta}</h2>
                        <p style="font-size: 14px; color: #718096;">${proposta.cliente?.nome || 'Cliente não informado'}</p>
                    </div>
                    <button onclick="closePropostaModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #718096; padding: 8px;">×</button>
                </div>

                <div style="margin-bottom: 24px;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="font-size: 12px; font-weight: 600; color: #4a5568; text-transform: uppercase; margin-bottom: 4px;">Valor</label>
                            <div style="font-size: 18px; font-weight: 700; color: #10b981;">R$ ${parseFloat(proposta.valor_final).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                        </div>
                        <div>
                            <label style="font-size: 12px; font-weight: 600; color: #4a5568; text-transform: uppercase; margin-bottom: 4px;">Estado</label>
                            <div style="font-size: 14px; font-weight: 600; color: #4a5568; text-transform: capitalize;">${proposta.estado?.replace('_', ' ') || '—'}</div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 12px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 4px;">Data de fechamento</label>
                            <input type="date" id="propostaDataFechamento" value="${proposta.data_fechamento || ''}" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 4px;">Status atual</label>
                            <select id="propostaEstado" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                                <option value="primeiro_contato" ${proposta.estado === 'primeiro_contato' ? 'selected' : ''}>Primeiro contato</option>
                                <option value="em_analise" ${proposta.estado === 'em_analise' ? 'selected' : ''}>Em negociação</option>
                                <option value="fechado" ${proposta.estado === 'fechado' ? 'selected' : ''}>Ganhou</option>
                                <option value="perdido" ${proposta.estado === 'perdido' ? 'selected' : ''}>Perdeu</option>
                                <option value="outros" ${proposta.estado === 'outros' ? 'selected' : ''}>Outros</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 4px;">Motivo de ganho</label>
                        <textarea id="propostaMotivoGanho" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; min-height: 70px;" placeholder="Ex.: melhor prazo, melhor proposta técnica...">${proposta.motivo_ganho || ''}</textarea>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 4px;">Motivo de perda</label>
                        <textarea id="propostaMotivoPerda" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; min-height: 70px;" placeholder="Ex.: preço, concorrente, sem orçamento...">${proposta.motivo_perda || ''}</textarea>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 4px;">Motivo da negociação</label>
                        <textarea id="propostaMotivoNegociacao" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; min-height: 70px;" placeholder="Ex.: aguardando retorno do diretor, revisando escopo...">${proposta.motivo_negociacao || ''}</textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end; margin-bottom: 8px;">
                        <button type="button" onclick="savePropostaInsights(${proposta.id})" style="padding: 10px 16px; background: #4a90e2; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Salvar status e motivos</button>
                    </div>
                    ${proposta.descricao_inicial ? `
                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; font-weight: 600; color: #4a5568; text-transform: uppercase; margin-bottom: 4px;">Descrição</label>
                            <div style="padding: 12px; background: #f7fafc; border-radius: 8px; white-space: pre-wrap; font-size: 14px; color: #4a5568;">${proposta.descricao_inicial}</div>
                        </div>
                    ` : ''}
                </div>

                <div style="margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1a202c;">Acompanhamentos</h3>
                        <button onclick="showAddAcompanhamentoForm(${proposta.id})" style="padding: 8px 16px; background: #4a90e2; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">+ Adicionar</button>
                    </div>
                    <div id="acompanhamentosList" style="max-height: 300px; overflow-y: auto;">
                        <div style="text-align: center; padding: 20px; color: #a0aec0;">Carregando...</div>
                    </div>
                </div>

                <div id="addAcompanhamentoForm" style="display: none; padding: 16px; background: #f7fafc; border-radius: 8px; margin-top: 16px;">
                    <h4 style="font-size: 16px; font-weight: 700; color: #1a202c; margin-bottom: 16px;">Novo Acompanhamento</h4>
                    <form id="formAcompanhamento" onsubmit="saveAcompanhamento(event, ${proposta.id})">
                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 4px;">Descrição *</label>
                            <textarea id="acompanhamentoDescricao" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical; min-height: 80px;" placeholder="Ex: Cliente vai dar retorno dia 15/02/2024"></textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 12px;">
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 4px;">Data de Retorno</label>
                                <input type="date" id="acompanhamentoDataRetorno" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 4px;">Data do Evento</label>
                                <input type="date" id="acompanhamentoDataEvento" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                            </div>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 4px;">Tipo</label>
                            <select id="acompanhamentoTipo" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                                <option value="acompanhamento">Acompanhamento</option>
                                <option value="retorno">Retorno</option>
                                <option value="fechamento">Fechamento</option>
                                <option value="contato">Contato</option>
                                <option value="reuniao">Reunião</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <button type="button" onclick="hideAddAcompanhamentoForm()" style="padding: 10px 20px; background: #e2e8f0; color: #4a5568; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Cancelar</button>
                            <button type="submit" style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Salvar</button>
                        </div>
                    </form>
                </div>
            `;
            
            modal.style.display = 'flex';
            loadAcompanhamentos(proposta.id);
        }

        function closePropostaModal() {
            document.getElementById('propostaModal').style.display = 'none';
        }

        function loadAcompanhamentos(propostaId) {
            const list = document.getElementById('acompanhamentosList');
            
            fetch(`/crm/propostas/${propostaId}/acompanhamentos`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const acompanhamentos = data.acompanhamentos;
                    
                    if (acompanhamentos.length === 0) {
                        list.innerHTML = '<div style="text-align: center; padding: 20px; color: #a0aec0;">Nenhum acompanhamento registrado ainda.</div>';
                    } else {
                        list.innerHTML = acompanhamentos.map(acomp => `
                            <div style="padding: 16px; background: white; border-radius: 8px; margin-bottom: 12px; border-left: 4px solid #4a90e2;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                    <div>
                                        <div style="font-size: 12px; font-weight: 600; color: #4a5568; text-transform: uppercase; margin-bottom: 4px;">${acomp.tipo || 'Acompanhamento'}</div>
                                        <div style="font-size: 14px; color: #1a202c; white-space: pre-wrap;">${acomp.descricao}</div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 16px; margin-top: 12px; font-size: 12px; color: #718096;">
                                    ${acomp.data_retorno ? `<span>📅 Retorno: ${new Date(acomp.data_retorno).toLocaleDateString('pt-BR')}</span>` : ''}
                                    ${acomp.data_evento ? `<span>📆 Evento: ${new Date(acomp.data_evento).toLocaleDateString('pt-BR')}</span>` : ''}
                                    <span>👤 ${acomp.usuario?.name || 'Sistema'}</span>
                                    <span>🕐 ${new Date(acomp.created_at).toLocaleString('pt-BR')}</span>
                                </div>
                            </div>
                        `).join('');
                    }
                } else {
                    list.innerHTML = '<div style="text-align: center; padding: 20px; color: #f44336;">Erro ao carregar acompanhamentos</div>';
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                list.innerHTML = '<div style="text-align: center; padding: 20px; color: #f44336;">Erro ao carregar acompanhamentos</div>';
            });
        }

        function showAddAcompanhamentoForm(propostaId) {
            document.getElementById('addAcompanhamentoForm').style.display = 'block';
            document.getElementById('acompanhamentoDescricao').focus();
        }

        function hideAddAcompanhamentoForm() {
            document.getElementById('addAcompanhamentoForm').style.display = 'none';
            document.getElementById('formAcompanhamento').reset();
        }

        function saveAcompanhamento(event, propostaId) {
            event.preventDefault();
            
            const formData = {
                descricao: document.getElementById('acompanhamentoDescricao').value,
                data_retorno: document.getElementById('acompanhamentoDataRetorno').value || null,
                data_evento: document.getElementById('acompanhamentoDataEvento').value || null,
                tipo: document.getElementById('acompanhamentoTipo').value,
            };
            
            fetch(`/crm/propostas/${propostaId}/acompanhamentos`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideAddAcompanhamentoForm();
                    loadAcompanhamentos(propostaId);
                    alert('✅ Acompanhamento adicionado com sucesso!');
                } else {
                    let errorMsg = 'Erro ao salvar acompanhamento.';
                    if (data.errors) {
                        errorMsg += '\n\n' + Object.values(data.errors).flat().join('\n');
                    }
                    alert('❌ ' + errorMsg);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('❌ Erro ao salvar acompanhamento. Tente novamente.');
            });
        }

        function savePropostaInsights(propostaId) {
            const formData = new FormData();
            formData.append('estado', document.getElementById('propostaEstado').value);
            formData.append('motivo_ganho', document.getElementById('propostaMotivoGanho').value || '');
            formData.append('motivo_perda', document.getElementById('propostaMotivoPerda').value || '');
            formData.append('motivo_negociacao', document.getElementById('propostaMotivoNegociacao').value || '');
            formData.append('data_fechamento', document.getElementById('propostaDataFechamento').value || '');

            fetch(`/crm/propostas/${propostaId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Status e motivos salvos com sucesso!');
                    setTimeout(() => location.reload(), 400);
                } else {
                    let msg = 'Erro ao salvar status e motivos.';
                    if (data.errors) {
                        msg += '\n\n' + Object.values(data.errors).flat().join('\n');
                    }
                    alert('❌ ' + msg);
                }
            })
            .catch(error => {
                console.error(error);
                alert('❌ Erro ao salvar status e motivos.');
            });
        }
    </script>

    <!-- Modal de Detalhes da Proposta -->
    <div id="propostaModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 2000; align-items: center; justify-content: center;" onclick="if(event.target.id === 'propostaModal') closePropostaModal();">
        <div id="propostaModalContent" style="background: white; border-radius: 16px; padding: 32px; max-width: 800px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
            <!-- Conteúdo será inserido via JavaScript -->
        </div>
    </div>
</body>
</html>
