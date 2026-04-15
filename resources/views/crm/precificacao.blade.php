<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Precificação - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8edf5 100%);
            color: #1a202c;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
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
            background: transparent;
            animation: fadeIn 0.5s ease-out;
        }

        .content-area::-webkit-scrollbar {
            width: 8px;
        }

        .content-area::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .content-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .content-area::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Seção Identificação e Pipeline */
        .identificacao-section {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: slideIn 0.6s ease-out;
            border: 1px solid rgba(74, 144, 226, 0.1);
        }

        .identificacao-section:hover {
            box-shadow: 0 8px 30px rgba(74, 144, 226, 0.12);
            transform: translateY(-2px);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            position: relative;
        }

        .section-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, #4a90e2 0%, #357abd 100%);
            border-radius: 2px;
        }

        .section-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
            transition: all 0.3s ease;
        }

        .section-icon:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.4);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            letter-spacing: -0.5px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:hover, .form-select:hover {
            border-color: #cbd5e1;
            background: #fafbfc;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.1);
            background: white;
            transform: translateY(-1px);
        }

        .form-input[readonly] {
            background: #f7fafc;
            color: #718096;
        }

        /* Custo Operacional Global */
        .custo-operacional-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            color: white;
            box-shadow: 0 8px 32px rgba(30, 58, 138, 0.3);
            position: relative;
            overflow: hidden;
            animation: slideIn 0.7s ease-out;
            transition: all 0.3s ease;
        }

        .custo-operacional-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        .custo-operacional-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(30, 58, 138, 0.4);
        }

        .custo-operacional-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            opacity: 0.9;
        }

        .custo-operacional-desc {
            font-size: 12px;
            opacity: 0.7;
            margin-bottom: 16px;
        }

        .custo-operacional-value {
            font-size: 42px;
            font-weight: 800;
            text-align: right;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            letter-spacing: -1px;
            transition: all 0.3s ease;
        }

        .custo-operacional-value:hover {
            transform: scale(1.05);
        }

        /* Cards de Estratégia */
        .estrategias-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-bottom: 32px;
        }

        .estrategia-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(226, 232, 240, 0.8);
            animation: fadeIn 0.8s ease-out;
            animation-fill-mode: both;
        }

        .estrategia-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .estrategia-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .estrategia-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .estrategia-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .estrategia-card.estrategico {
            border-top: 5px solid #4a90e2;
            background: linear-gradient(to bottom, rgba(74, 144, 226, 0.02) 0%, white 10%);
        }

        .estrategia-card.estrategico:hover {
            border-top-color: #357abd;
            box-shadow: 0 12px 40px rgba(74, 144, 226, 0.2);
        }

        .estrategia-card.equilibrio {
            border-top: 5px solid #f97316;
            background: linear-gradient(to bottom, rgba(249, 115, 22, 0.02) 0%, white 10%);
        }

        .estrategia-card.equilibrio:hover {
            border-top-color: #ea580c;
            box-shadow: 0 12px 40px rgba(249, 115, 22, 0.2);
        }

        .estrategia-card.simulacao {
            border-top: 5px solid #10b981;
            background: linear-gradient(to bottom, rgba(16, 185, 129, 0.02) 0%, white 10%);
        }

        .estrategia-card.simulacao:hover {
            border-top-color: #059669;
            box-shadow: 0 12px 40px rgba(16, 185, 129, 0.2);
        }

        .estrategia-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .estrategia-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .estrategia-alvo {
            font-size: 12px;
            color: #718096;
            font-weight: 600;
        }

        .alert-banner {
            background: linear-gradient(90deg, #ffebee 0%, #ffe0e0 100%);
            border-left: 4px solid #f44336;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            font-weight: 600;
            color: #c62828;
            animation: slideIn 0.5s ease-out;
            box-shadow: 0 2px 8px rgba(244, 67, 54, 0.15);
        }

        .margem-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 10px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            position: relative;
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .margem-circle:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .margem-circle.estrategico {
            border-color: #4a90e2;
        }

        .margem-circle.equilibrio {
            border-color: #f97316;
        }

        .margem-circle.simulacao {
            border-color: #10b981;
        }

        .margem-value {
            font-size: 28px;
            font-weight: 800;
            color: #1a202c;
            transition: all 0.3s ease;
        }

        .margem-circle:hover .margem-value {
            transform: scale(1.1);
        }

        .margem-label {
            font-size: 13px;
            color: #718096;
            margin-top: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .preco-venda {
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f7fafc;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .preco-venda:hover {
            background: #e8edf5;
        }

        .preco-venda input {
            border: none;
            background: transparent;
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
            width: 160px;
            transition: all 0.3s ease;
        }

        .preco-venda input:focus {
            outline: none;
            border-bottom: 2px solid #4a90e2;
            background: white;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .breakdown-list {
            list-style: none;
            margin-top: 16px;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f7fafc;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .breakdown-item:hover {
            background: #fafbfc;
            padding-left: 8px;
            padding-right: 8px;
            margin: 0 -8px;
            border-radius: 6px;
        }

        .breakdown-label {
            color: #718096;
        }

        .breakdown-value {
            font-weight: 600;
            color: #1a202c;
        }

        .margem-slider {
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: linear-gradient(90deg, #e2e8f0 0%, #cbd5e1 100%);
            outline: none;
            -webkit-appearance: none;
            margin: 20px 0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .margem-slider:hover {
            height: 10px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .margem-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
            transition: all 0.3s ease;
        }

        .margem-slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.6);
        }

        .margem-slider::-moz-range-thumb {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
            transition: all 0.3s ease;
        }

        .margem-slider::-moz-range-thumb:hover {
            transform: scale(1.2);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.6);
        }

        /* Composição Detalhada */
        .composicao-section {
            margin-bottom: 24px;
        }

        .composicao-title {
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            color: #4a5568;
            margin: 32px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            padding: 16px 0;
        }

        .composicao-title::before,
        .composicao-title::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        }

        .composicao-title::before {
            left: 0;
        }

        .composicao-title::after {
            right: 0;
        }

        .composicao-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .composicao-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(226, 232, 240, 0.8);
            animation: fadeIn 0.9s ease-out;
        }

        .composicao-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .composicao-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            position: relative;
        }

        .composicao-card-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background: linear-gradient(90deg, #4a90e2 0%, #357abd 100%);
            border-radius: 2px;
        }

        .composicao-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a202c;
        }

        .subsection {
            margin-bottom: 24px;
        }

        .subsection-title {
            font-size: 13px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 12px;
        }

        .input-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 12px;
        }

        .subtotal {
            background: linear-gradient(135deg, #f7fafc 0%, #e8edf5 100%);
            padding: 16px;
            border-radius: 10px;
            margin-top: 16px;
            font-weight: 700;
            color: #1a202c;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .subtotal:hover {
            background: linear-gradient(135deg, #e8edf5 0%, #dbeafe 100%);
            border-color: #4a90e2;
            transform: translateX(4px);
        }

        .toggle-switch {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 12px;
        }

        .switch {
            position: relative;
            width: 50px;
            height: 28px;
            background: #e2e8f0;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .switch:hover {
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .switch.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        }

        .switch-slider {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 22px;
            height: 22px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .switch.active .switch-slider {
            transform: translateX(22px);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
        }

        .logistica-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 32px;
            transition: all 0.3s ease;
            border: 1px solid rgba(226, 232, 240, 0.8);
            animation: fadeIn 1s ease-out;
        }

        .logistica-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        /* Memorando */
        .memorando-section {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(226, 232, 240, 0.8);
            animation: fadeIn 1.1s ease-out;
        }

        .memorando-section:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .memorando-textarea {
            width: 100%;
            min-height: 180px;
            padding: 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            resize: vertical;
            transition: all 0.3s ease;
            background: #fafbfc;
            line-height: 1.6;
        }

        .memorando-textarea:hover {
            border-color: #cbd5e1;
            background: white;
        }

        .memorando-textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.1);
            background: white;
        }

        .despesas-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .despesa-item {
            display: flex;
            flex-direction: column;
        }

        .lock-icon {
            color: #718096;
            font-size: 14px;
        }

        /* Animações de valores */
        @keyframes valueUpdate {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
                color: #4a90e2;
            }
            100% {
                transform: scale(1);
            }
        }

        .value-updated {
            animation: valueUpdate 0.5s ease;
        }

        /* Melhorias nos cards de estratégia */
        .estrategia-header {
            transition: all 0.3s ease;
        }

        .estrategia-card:hover .estrategia-header {
            transform: translateX(4px);
        }

        /* Efeito de brilho nos valores importantes */
        .custo-operacional-value,
        .margem-value,
        .preco-venda {
            position: relative;
        }

        /* Melhor contraste e legibilidade */
        .breakdown-value {
            font-weight: 700;
            color: #1a202c;
            transition: all 0.3s ease;
        }

        .breakdown-item:hover .breakdown-value {
            color: #4a90e2;
            transform: scale(1.05);
        }

        /* Melhorias nos inputs */
        .input-row .form-input:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.1);
        }

        /* Efeito de loading sutil */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        /* Melhor espaçamento e organização */
        .form-grid {
            animation: fadeIn 0.5s ease-out;
        }

        .form-group {
            transition: all 0.3s ease;
        }

        .form-group:hover {
            transform: translateY(-2px);
        }

        /* Melhorias no título da composição */
        .composicao-title {
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.05) 0%, transparent 100%);
            padding: 20px;
            border-radius: 12px;
            margin: 40px 0;
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
            <!-- Identificação e Pipeline -->
            <div class="identificacao-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <line x1="9" y1="3" x2="9" y2="21"></line>
                        </svg>
                    </div>
                    <div class="section-title">IDENTIFICAÇÃO E PIPELINE</div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Número do Registro</label>
                        <input type="text" class="form-input" value="Automático" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Executivo de Vendas</label>
                        <input type="text" class="form-input" placeholder="Nome do executivo">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cliente (Razão Social)</label>
                        <select class="form-select" id="clienteSelect">
                            <option value="">Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Projeto / Site</label>
                        <input type="text" class="form-input" placeholder="Nome do projeto">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fase Atual</label>
                        <select class="form-select" id="faseAtual">
                            <option value="prospeccao">Prospecção</option>
                            <option value="qualificacao">Qualificação</option>
                            <option value="proposta">Proposta</option>
                            <option value="negociacao">Negociação</option>
                            <option value="fechamento">Fechamento</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confiança Comercial (%)</label>
                        <input type="number" class="form-input" value="50" min="0" max="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta de Fechamento</label>
                        <input type="date" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Semana de Protocolo</label>
                        <input type="text" class="form-input" placeholder="Ex: S15/2024">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Categoria de Serviço</label>
                        <select class="form-select">
                            <option value="">Engenharia...</option>
                            <option value="engenharia">Engenharia</option>
                            <option value="consultoria">Consultoria</option>
                            <option value="manutencao">Manutenção</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nome do Contato</label>
                        <input type="text" class="form-input" placeholder="Nome completo">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contato Telefônico</label>
                        <input type="tel" class="form-input" placeholder="(00) 00000-0000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">E-mail Corporativo</label>
                        <input type="email" class="form-input" placeholder="email@empresa.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cidade</label>
                        <input type="text" class="form-input" placeholder="Nome da cidade">
                    </div>
                    <div class="form-group">
                        <label class="form-label">UF / Localidade</label>
                        <input type="text" class="form-input" placeholder="UF" maxlength="2">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mercado Alvo</label>
                        <select class="form-select">
                            <option value="">Industrial, Óleo & Gás...</option>
                            <option value="industrial">Industrial</option>
                            <option value="oleo_gas">Óleo & Gás</option>
                            <option value="energia">Energia</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Custo Operacional Global -->
            <div class="custo-operacional-card">
                <div class="custo-operacional-title">CUSTO OPERACIONAL GLOBAL</div>
                <div class="custo-operacional-desc">Base de cálculo consolidada incluindo encargos, provisões e 2% de overhead administrativo.</div>
                <div class="custo-operacional-value" id="custoOperacionalGlobal">R$ 0,00</div>
            </div>

            <!-- Cards de Estratégia -->
            <div class="estrategias-container">
                <!-- Estratégico -->
                <div class="estrategia-card estrategico">
                    <div class="estrategia-header">
                        <div class="estrategia-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Estratégico
                        </div>
                        <div class="estrategia-alvo">Alvo: 45.0%</div>
                    </div>
                    <div class="alert-banner" id="alertEstrategico" style="display: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        ATENÇÃO: Margem < 30%
                    </div>
                    <div class="margem-circle estrategico">
                        <div>
                            <div class="margem-value" id="margemEstrategico">0,00%</div>
                            <div class="margem-label">Margem</div>
                        </div>
                    </div>
                    <div class="preco-venda">
                        <span>Preço de Venda</span>
                        <span id="precoVendaEstrategico">R$ 0,00</span>
                    </div>
                    <ul class="breakdown-list">
                        <li class="breakdown-item">
                            <span class="breakdown-label">Custo</span>
                            <span class="breakdown-value" id="custoEstrategico">R$ 0,00</span>
                        </li>
                        <li class="breakdown-item">
                            <span class="breakdown-label">Impostos</span>
                            <span class="breakdown-value" id="impostosEstrategico">R$ 0,00</span>
                        </li>
                        <li class="breakdown-item">
                            <span class="breakdown-label">Lucro</span>
                            <span class="breakdown-value" id="lucroEstrategico">R$ 0,00</span>
                        </li>
                        <li class="breakdown-item">
                            <span class="breakdown-label">Receita Líquida</span>
                            <span class="breakdown-value" id="receitaEstrategico">R$ 0,00</span>
                        </li>
                    </ul>
                </div>

                <!-- Ponto de Equilíbrio -->
                <div class="estrategia-card equilibrio">
                    <div class="estrategia-header">
                        <div class="estrategia-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            </svg>
                            Ponto de Equilíbrio
                        </div>
                        <div class="estrategia-alvo">Alvo: 30.0%</div>
                    </div>
                    <div class="alert-banner" id="alertEquilibrio" style="display: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        ATENÇÃO: Margem < 30%
                    </div>
                    <div class="margem-circle equilibrio">
                        <div>
                            <div class="margem-value" id="margemEquilibrio">0,00%</div>
                            <div class="margem-label">Margem</div>
                        </div>
                    </div>
                    <div class="preco-venda">
                        <span>Preço de Venda</span>
                        <span id="precoVendaEquilibrio">R$ 0,00</span>
                    </div>
                    <ul class="breakdown-list">
                        <li class="breakdown-item">
                            <span class="breakdown-label">Custo</span>
                            <span class="breakdown-value" id="custoEquilibrio">R$ 0,00</span>
                        </li>
                        <li class="breakdown-item">
                            <span class="breakdown-label">Impostos</span>
                            <span class="breakdown-value" id="impostosEquilibrio">R$ 0,00</span>
                        </li>
                        <li class="breakdown-item">
                            <span class="breakdown-label">Lucro</span>
                            <span class="breakdown-value" id="lucroEquilibrio">R$ 0,00</span>
                        </li>
                        <li class="breakdown-item">
                            <span class="breakdown-label">Receita Líquida</span>
                            <span class="breakdown-value" id="receitaEquilibrio">R$ 0,00</span>
                        </li>
                    </ul>
                </div>

                <!-- Simulação Comercial -->
                <div class="estrategia-card simulacao">
                    <div class="estrategia-header">
                        <div class="estrategia-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 6 13.5 15.5 8.5 10.5 2 17"></polyline>
                                <polyline points="16 6 22 6 22 12"></polyline>
                            </svg>
                            Simulação Comercial
                        </div>
                        <div class="estrategia-alvo">Alvo: 35.0%</div>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <div style="font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 8px;">AJUSTE DE MARGEM</div>
                        <input type="range" class="margem-slider" id="margemSlider" min="0" max="50" value="35" oninput="updateMargem(this.value)">
                    </div>
                    <div class="alert-banner" id="alertSimulacao" style="display: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        ATENÇÃO: Margem < 30%
                    </div>
                    <div class="margem-circle simulacao">
                        <div>
                            <div class="margem-value" id="margemSimulacao">0,00%</div>
                            <div class="margem-label">Margem</div>
                        </div>
                    </div>
                    <div class="preco-venda">
                        <span>Preço de Venda</span>
                        <input type="text" id="precoVendaSimulacaoInput" value="R$ 0,00" onchange="updatePrecoVenda(this.value)">
                    </div>
                    <ul class="breakdown-list">
                        <li class="breakdown-item">
                            <span class="breakdown-label">Custo</span>
                            <span class="breakdown-value" id="custoSimulacao">R$ 0,00</span>
                        </li>
                        <li class="breakdown-item">
                            <span class="breakdown-label">Impostos</span>
                            <span class="breakdown-value" id="impostosSimulacao">R$ 0,00</span>
                        </li>
                        <li class="breakdown-item">
                            <span class="breakdown-label">Lucro</span>
                            <span class="breakdown-value" id="lucroSimulacao">R$ 0,00</span>
                        </li>
                        <li class="breakdown-item">
                            <span class="breakdown-label">Receita Líquida</span>
                            <span class="breakdown-value" id="receitaSimulacao">R$ 0,00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Composição Detalhada de Custos -->
            <div class="composicao-section">
                <div class="composicao-title">COMPOSIÇÃO DETALHADA DE CUSTOS</div>
                
                <div class="composicao-grid">
                    <!-- Recursos Humanos -->
                    <div class="composicao-card">
                        <div class="composicao-card-header">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            </svg>
                            <div class="composicao-card-title">RECURSOS HUMANOS</div>
                        </div>

                        <div class="subsection">
                            <div class="subsection-title">EQUIPE TÉCNICA OPERACIONAL</div>
                            <div class="input-row">
                                <div class="form-group">
                                    <label class="form-label">Custo HH (R$)</label>
                                    <input type="number" class="form-input" id="custoHH" value="0.00" step="0.01" onchange="calcular()">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Total Integrantes</label>
                                    <input type="number" class="form-input" id="totalIntegrantes" value="1" min="1" onchange="calcular()">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Cronograma (Horas)</label>
                                    <input type="number" class="form-input" id="cronogramaHoras" value="0.00" step="0.01" onchange="calcular()">
                                </div>
                            </div>
                            <div class="subtotal" id="subtotalDiretoMO">SUBTOTAL DIRETO MO: R$ 0,00</div>
                        </div>

                        <div class="subsection">
                            <div class="toggle-switch">
                                <div class="switch" id="jornadaAdicional" onclick="toggleSwitch(this)">
                                    <div class="switch-slider"></div>
                                </div>
                                <label class="form-label" style="margin: 0;">JORNADA ADICIONAL (H.E)</label>
                            </div>
                            <div class="toggle-switch">
                                <div class="switch" id="postoResidente" onclick="toggleSwitch(this)">
                                    <div class="switch-slider"></div>
                                </div>
                                <label class="form-label" style="margin: 0;">POSTO RESIDENTE (FIXO)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Despesas -->
                    <div class="composicao-card">
                        <div class="composicao-card-header">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <div class="composicao-card-title">DESPESAS</div>
                        </div>

                        <div class="despesas-grid">
                            <div class="despesa-item">
                                <label class="form-label">Hospedagem</label>
                                <input type="number" class="form-input" id="hospedagem" value="0.00" step="0.01" onchange="calcular()">
                            </div>
                            <div class="despesa-item">
                                <label class="form-label">Insumos / EPIs</label>
                                <input type="number" class="form-input" id="insumos" value="0.00" step="0.01" onchange="calcular()">
                            </div>
                            <div class="despesa-item">
                                <label class="form-label">Combustível / Pedágios</label>
                                <input type="number" class="form-input" id="combustivel" value="0.00" step="0.01" onchange="calcular()">
                            </div>
                            <div class="despesa-item">
                                <label class="form-label">Passagens / Traslado</label>
                                <input type="number" class="form-input" id="passagens" value="0.00" step="0.01" onchange="calcular()">
                            </div>
                            <div class="despesa-item">
                                <label class="form-label">Serviços Terceirizados</label>
                                <input type="number" class="form-input" id="servicosTerceirizados" value="0.00" step="0.01" onchange="calcular()">
                            </div>
                            <div class="despesa-item">
                                <label class="form-label">Materiais / Ferragens</label>
                                <input type="number" class="form-input" id="materiais" value="0.00" step="0.01" onchange="calcular()">
                            </div>
                            <div class="despesa-item">
                                <label class="form-label">Taxas / ART / Documentos</label>
                                <input type="number" class="form-input" id="taxas" value="0.00" step="0.01" onchange="calcular()">
                            </div>
                            <div class="despesa-item">
                                <label class="form-label">OVERHEAD ADM (2%)</label>
                                <input type="number" class="form-input" id="overhead" value="0.00" step="0.01" readonly style="background: #f7fafc;">
                            </div>
                            <div class="despesa-item">
                                <label class="form-label">Diversos / Outros</label>
                                <input type="number" class="form-input" id="diversos" value="0.00" step="0.01" onchange="calcular()">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logística & Mobilização -->
                <div class="logistica-card">
                    <div class="composicao-card-header">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <div class="composicao-card-title">LOGÍSTICA & MOBILIZAÇÃO</div>
                    </div>

                    <div class="input-row">
                        <div class="form-group">
                            <label class="form-label">Verba Diária / Gratif.</label>
                            <input type="number" class="form-input" id="verbaDiaria" value="0.00" step="0.01" onchange="calcular()">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nº de Viajantes</label>
                            <input type="number" class="form-input" id="numViajantes" value="1" min="1" onchange="calcular()">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Duração (Dias)</label>
                            <input type="number" class="form-input" id="duracaoDias" value="0.00" step="0.01" onchange="calcular()">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Memorando da Proposta -->
            <div class="memorando-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </div>
                    <div class="section-title">MEMORANDO DA PROPOSTA</div>
                </div>
                <textarea id="memorandoProposta" class="memorando-textarea" placeholder="Escopo técnico, prazos de validade, condições de pagamento e observações críticas de engenharia..."></textarea>
            </div>

            <!-- Botão Salvar -->
            <div style="padding: 24px; background: white; border-top: 2px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 16px; position: sticky; bottom: 0; z-index: 100; box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);">
                <button type="button" onclick="salvarProposta()" id="btnSalvarProposta" style="padding: 14px 32px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 15px; cursor: pointer; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s ease; display: flex; align-items: center; gap: 10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    💾 SALVAR PROPOSTA
                </button>
            </div>
        </div>
    </div>

    <script>
        const parametros = @json($parametros);
        let margemAlvo = 35;

        function formatCurrency(value) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value);
        }

        function formatPercent(value) {
            return value.toFixed(2).replace('.', ',') + '%';
        }

        function animateValue(element, newValue, isCurrency = false) {
            if (!element) return;
            
            const oldValue = isCurrency 
                ? parseFloat(element.textContent.replace(/[^\d,]/g, '').replace(',', '.')) || 0
                : parseFloat(element.textContent.replace(/[^\d,]/g, '').replace(',', '.')) || 0;
            
            element.classList.add('value-updated');
            
            setTimeout(() => {
                element.classList.remove('value-updated');
            }, 500);
        }

        function updateElementWithAnimation(elementId, value, formatter, isCurrency = false) {
            const element = document.getElementById(elementId);
            if (element) {
                animateValue(element, value, isCurrency);
                element.textContent = formatter(value);
            }
        }

        function toggleSwitch(element) {
            element.classList.toggle('active');
            calcular();
        }

        function updateMargem(value) {
            margemAlvo = parseFloat(value);
            calcular();
        }

        function updatePrecoVenda(value) {
            const valor = parseFloat(value.replace(/[^\d,]/g, '').replace(',', '.')) || 0;
            calcularComPrecoVenda(valor);
        }

        function calcular() {
            // Recursos Humanos
            const custoHH = parseFloat(document.getElementById('custoHH').value) || 0;
            const totalIntegrantes = parseFloat(document.getElementById('totalIntegrantes').value) || 1;
            const cronogramaHoras = parseFloat(document.getElementById('cronogramaHoras').value) || 0;
            const subtotalMO = custoHH * totalIntegrantes * cronogramaHoras;
            const subtotalElement = document.getElementById('subtotalDiretoMO');
            if (subtotalElement) {
                subtotalElement.textContent = `SUBTOTAL DIRETO MO: ${formatCurrency(subtotalMO)}`;
                subtotalElement.classList.add('value-updated');
                setTimeout(() => subtotalElement.classList.remove('value-updated'), 500);
            }

            // Despesas
            const hospedagem = parseFloat(document.getElementById('hospedagem').value) || 0;
            const insumos = parseFloat(document.getElementById('insumos').value) || 0;
            const combustivel = parseFloat(document.getElementById('combustivel').value) || 0;
            const passagens = parseFloat(document.getElementById('passagens').value) || 0;
            const servicosTerceirizados = parseFloat(document.getElementById('servicosTerceirizados').value) || 0;
            const materiais = parseFloat(document.getElementById('materiais').value) || 0;
            const taxas = parseFloat(document.getElementById('taxas').value) || 0;
            const diversos = parseFloat(document.getElementById('diversos').value) || 0;

            // Logística
            const verbaDiaria = parseFloat(document.getElementById('verbaDiaria').value) || 0;
            const numViajantes = parseFloat(document.getElementById('numViajantes').value) || 1;
            const duracaoDias = parseFloat(document.getElementById('duracaoDias').value) || 0;
            const totalLogistica = verbaDiaria * numViajantes * duracaoDias;

            // Custo Operacional Global
            const custoOperacional = subtotalMO + hospedagem + insumos + combustivel + passagens + 
                                   servicosTerceirizados + materiais + taxas + diversos + totalLogistica;
            
            // Overhead 2%
            const overhead = custoOperacional * 0.02;
            document.getElementById('overhead').value = overhead.toFixed(2);
            
            const custoOperacionalGlobal = custoOperacional + overhead;
            updateElementWithAnimation('custoOperacionalGlobal', custoOperacionalGlobal, formatCurrency, true);

            // Calcular estratégias
            calcularEstrategico(custoOperacionalGlobal);
            calcularEquilibrio(custoOperacionalGlobal);
            calcularSimulacao(custoOperacionalGlobal, margemAlvo);
        }

        function calcularEstrategico(custo) {
            const margemAlvo = 45.0;
            const aliquotaImpostos = parametros.aliquota_impostos / 100;
            const taxaAdm = parametros.taxa_adm_fixa / 100;
            
            const precoVenda = custo / (1 - margemAlvo/100 - aliquotaImpostos - taxaAdm);
            const impostos = precoVenda * aliquotaImpostos;
            const admin = precoVenda * taxaAdm;
            const lucro = precoVenda - custo - impostos - admin;
            const receitaLiquida = precoVenda - impostos - admin;
            const margem = (lucro / precoVenda) * 100;

            updateElementWithAnimation('margemEstrategico', margem, formatPercent);
            updateElementWithAnimation('precoVendaEstrategico', precoVenda, formatCurrency, true);
            updateElementWithAnimation('custoEstrategico', custo, formatCurrency, true);
            updateElementWithAnimation('impostosEstrategico', impostos + admin, formatCurrency, true);
            updateElementWithAnimation('lucroEstrategico', lucro, formatCurrency, true);
            updateElementWithAnimation('receitaEstrategico', receitaLiquida, formatCurrency, true);

            document.getElementById('alertEstrategico').style.display = margem < 30 ? 'flex' : 'none';
        }

        function calcularEquilibrio(custo) {
            const margemAlvo = 30.0;
            const aliquotaImpostos = parametros.aliquota_impostos / 100;
            const taxaAdm = parametros.taxa_adm_fixa / 100;
            
            const precoVenda = custo / (1 - margemAlvo/100 - aliquotaImpostos - taxaAdm);
            const impostos = precoVenda * aliquotaImpostos;
            const admin = precoVenda * taxaAdm;
            const lucro = precoVenda - custo - impostos - admin;
            const receitaLiquida = precoVenda - impostos - admin;
            const margem = (lucro / precoVenda) * 100;

            updateElementWithAnimation('margemEquilibrio', margem, formatPercent);
            updateElementWithAnimation('precoVendaEquilibrio', precoVenda, formatCurrency, true);
            updateElementWithAnimation('custoEquilibrio', custo, formatCurrency, true);
            updateElementWithAnimation('impostosEquilibrio', impostos + admin, formatCurrency, true);
            updateElementWithAnimation('lucroEquilibrio', lucro, formatCurrency, true);
            updateElementWithAnimation('receitaEquilibrio', receitaLiquida, formatCurrency, true);

            document.getElementById('alertEquilibrio').style.display = margem < 30 ? 'flex' : 'none';
        }

        function calcularSimulacao(custo, margemAlvoPercent) {
            const aliquotaImpostos = parametros.aliquota_impostos / 100;
            const taxaAdm = parametros.taxa_adm_fixa / 100;
            
            const precoVenda = custo / (1 - margemAlvoPercent/100 - aliquotaImpostos - taxaAdm);
            const impostos = precoVenda * aliquotaImpostos;
            const admin = precoVenda * taxaAdm;
            const lucro = precoVenda - custo - impostos - admin;
            const receitaLiquida = precoVenda - impostos - admin;
            const margem = (lucro / precoVenda) * 100;

            updateElementWithAnimation('margemSimulacao', margem, formatPercent);
            const precoInput = document.getElementById('precoVendaSimulacaoInput');
            if (precoInput) {
                precoInput.value = formatCurrency(precoVenda);
            }
            updateElementWithAnimation('custoSimulacao', custo, formatCurrency, true);
            updateElementWithAnimation('impostosSimulacao', impostos + admin, formatCurrency, true);
            updateElementWithAnimation('lucroSimulacao', lucro, formatCurrency, true);
            updateElementWithAnimation('receitaSimulacao', receitaLiquida, formatCurrency, true);

            document.getElementById('alertSimulacao').style.display = margem < 30 ? 'flex' : 'none';
        }

        function calcularComPrecoVenda(precoVenda) {
            const custo = parseFloat(document.getElementById('custoOperacionalGlobal').textContent.replace(/[^\d,]/g, '').replace(',', '.')) || 0;
            const aliquotaImpostos = parametros.aliquota_impostos / 100;
            const taxaAdm = parametros.taxa_adm_fixa / 100;
            
            const impostos = precoVenda * aliquotaImpostos;
            const admin = precoVenda * taxaAdm;
            const lucro = precoVenda - custo - impostos - admin;
            const receitaLiquida = precoVenda - impostos - admin;
            const margem = (lucro / precoVenda) * 100;

            updateElementWithAnimation('margemSimulacao', margem, formatPercent);
            updateElementWithAnimation('custoSimulacao', custo, formatCurrency, true);
            updateElementWithAnimation('impostosSimulacao', impostos + admin, formatCurrency, true);
            updateElementWithAnimation('lucroSimulacao', lucro, formatCurrency, true);
            updateElementWithAnimation('receitaSimulacao', receitaLiquida, formatCurrency, true);

            document.getElementById('alertSimulacao').style.display = margem < 30 ? 'flex' : 'none';
        }

        // Função para salvar proposta
        function salvarProposta() {
            const clienteId = document.getElementById('clienteSelect')?.value;
            const memorando = document.getElementById('memorandoProposta')?.value || '';
            
            // Pegar o valor final da proposta (usar o preço de venda da simulação comercial)
            const precoVendaInput = document.getElementById('precoVendaSimulacaoInput');
            const precoVendaText = precoVendaInput?.value || 'R$ 0,00';
            const valorFinal = parseFloat(precoVendaText.replace(/[^\d,]/g, '').replace(',', '.')) || 0;
            
            if (!clienteId || clienteId === '') {
                alert('⚠️ Por favor, selecione um cliente antes de salvar a proposta.');
                return;
            }
            
            if (valorFinal <= 0) {
                alert('⚠️ O valor da proposta deve ser maior que zero. Por favor, preencha os campos e calcule antes de salvar.');
                return;
            }
            
            // Coletar dados adicionais
            const numeroRegistro = document.getElementById('numeroRegistro')?.value || '';
            const executivoVendas = document.getElementById('executivoVendas')?.value || '';
            const projetoSite = document.getElementById('projetoSite')?.value || '';
            const faseAtual = document.getElementById('faseAtual')?.value || '';
            const confiancaComercial = document.getElementById('confiancaComercial')?.value || '';
            const metaFechamento = document.getElementById('metaFechamento')?.value || '';
            const semanaProtocolo = document.getElementById('semanaProtocolo')?.value || '';
            const categoriaServico = document.getElementById('categoriaServico')?.value || '';
            const nomeContato = document.getElementById('nomeContato')?.value || '';
            const telefoneContato = document.getElementById('telefoneContato')?.value || '';
            const emailCorporativo = document.getElementById('emailCorporativo')?.value || '';
            const cidade = document.getElementById('cidade')?.value || '';
            const ufLocalidade = document.getElementById('ufLocalidade')?.value || '';
            const mercadoAlvo = document.getElementById('mercadoAlvo')?.value || '';
            
            // Montar título da proposta
            const titulo = projetoSite || `Proposta ${numeroRegistro || 'Nova'}`;
            
            // Montar descrição inicial com todos os dados
            let descricaoInicial = '';
            if (numeroRegistro) descricaoInicial += `Número do Registro: ${numeroRegistro}\n`;
            if (executivoVendas) descricaoInicial += `Executivo de Vendas: ${executivoVendas}\n`;
            if (projetoSite) descricaoInicial += `Projeto/Site: ${projetoSite}\n`;
            if (faseAtual) descricaoInicial += `Fase Atual: ${faseAtual}\n`;
            if (confiancaComercial) descricaoInicial += `Confiança Comercial: ${confiancaComercial}%\n`;
            if (metaFechamento) descricaoInicial += `Meta de Fechamento: ${metaFechamento}\n`;
            if (semanaProtocolo) descricaoInicial += `Semana de Protocolo: ${semanaProtocolo}\n`;
            if (categoriaServico) descricaoInicial += `Categoria de Serviço: ${categoriaServico}\n`;
            if (nomeContato) descricaoInicial += `Nome do Contato: ${nomeContato}\n`;
            if (telefoneContato) descricaoInicial += `Telefone: ${telefoneContato}\n`;
            if (emailCorporativo) descricaoInicial += `E-mail: ${emailCorporativo}\n`;
            if (cidade) descricaoInicial += `Cidade: ${cidade}\n`;
            if (ufLocalidade) descricaoInicial += `UF/Localidade: ${ufLocalidade}\n`;
            if (mercadoAlvo) descricaoInicial += `Mercado Alvo: ${mercadoAlvo}\n`;
            if (memorando) descricaoInicial += `\nMemorando:\n${memorando}\n`;
            
            // Adicionar informações de cálculo
            const custoOperacional = document.getElementById('custoOperacionalGlobal')?.textContent || 'R$ 0,00';
            const margemEstrategica = document.getElementById('margemEstrategico')?.textContent || '0,00%';
            const precoVendaEstrategico = document.getElementById('precoVendaEstrategico')?.textContent || 'R$ 0,00';
            
            descricaoInicial += `\n--- DADOS DE PRECIFICAÇÃO ---\n`;
            descricaoInicial += `Custo Operacional Global: ${custoOperacional}\n`;
            descricaoInicial += `Margem Estratégica: ${margemEstrategica}\n`;
            descricaoInicial += `Preço de Venda Estratégico: ${precoVendaEstrategico}\n`;
            descricaoInicial += `Valor Final da Proposta: ${formatCurrency(valorFinal)}\n`;
            
            // Confirmar antes de salvar
            if (!confirm(`💾 Deseja salvar esta proposta?\n\nCliente: ${document.getElementById('clienteSelect')?.selectedOptions[0]?.text || 'Não selecionado'}\nValor: ${formatCurrency(valorFinal)}\n\nA proposta será enviada para o Funil CRM.`)) {
                return;
            }
            
            // Enviar para o servidor
            fetch('{{ route("crm.precificacao.enviar-funil") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    cliente_id: clienteId,
                    valor_final: valorFinal,
                    titulo: titulo,
                    descricao_inicial: descricaoInicial.trim()
                })
            })
            .then(response => {
                // Verificar se a resposta foi bem-sucedida
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Erro na requisição');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    let mensagem = '✅ ' + data.message;
                    if (data.codigo_proposta) {
                        mensagem += '\n\nCódigo da Proposta: ' + data.codigo_proposta;
                    }
                    alert(mensagem);
                    // Opcional: redirecionar para o funil
                    // window.location.href = '{{ route("crm.funil") }}';
                } else {
                    let errorMsg = '❌ Erro ao salvar proposta.';
                    if (data.message) {
                        errorMsg = '❌ ' + data.message;
                    }
                    if (data.errors) {
                        errorMsg += '\n\n' + Object.values(data.errors).flat().join('\n');
                    }
                    if (data.error) {
                        errorMsg += '\n\nDetalhes: ' + data.error;
                    }
                    alert(errorMsg);
                    console.error('Erro completo:', data);
                }
            })
            .catch(error => {
                console.error('Erro completo:', error);
                let errorMsg = '❌ Erro ao salvar proposta.';
                if (error.message) {
                    errorMsg += '\n\n' + error.message;
                }
                alert(errorMsg + '\n\nPor favor, verifique o console do navegador (F12) para mais detalhes.');
            });
        }

        // Adicionar hover effect ao botão de salvar
        document.addEventListener('DOMContentLoaded', function() {
            calcular();
            
            const btnSalvar = document.getElementById('btnSalvarProposta');
            if (btnSalvar) {
                btnSalvar.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 6px 20px rgba(16, 185, 129, 0.4)';
                });
                btnSalvar.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 12px rgba(16, 185, 129, 0.3)';
                });
            }
        });
    </script>
</body>
</html>
