<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nova Proposta - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a1929 0%, #1a3a5a 50%, #0f4c75 100%);
            min-height: 100vh;
            color: #fff;
            padding: 30px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #87ceeb 0%, #b0e0e6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #87ceeb;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-label .required {
            color: #ff6b6b;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #87ceeb;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.1);
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .error-message {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff6b6b;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .success-message {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #51cf66;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="header-title">Nova Proposta CRM</h1>
        </div>

        <div class="form-container">
            @if($errors->any())
                <div class="error-message">
                    <ul style="list-style: none; padding-left: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('crm.propostas.store') }}">
                @csrf

                <div class="form-section">
                    <h2 class="section-title">Cliente / Prospect</h2>
                    <div class="form-group full-width">
                        <label class="form-label">
                            Cliente <span class="required">*</span>
                        </label>
                        <select name="cliente_id" class="form-select" required>
                            <option value="">Selecione um cliente...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }} @if($cliente->empresa) - {{ $cliente->empresa }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">Informações da Proposta</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Cód. Proposta</label>
                            <input type="text" class="form-input" value="Gerado automaticamente" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Valor Final (R$) <span class="required">*</span>
                            </label>
                            <input type="number" name="valor_final" class="form-input" step="0.01" min="0" value="{{ old('valor_final', '0.00') }}" required placeholder="0,00">
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Título / Descrição Inicial</label>
                        <input type="text" name="titulo" class="form-input" value="{{ old('titulo') }}" placeholder="Título da proposta...">
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Descrição Inicial</label>
                        <textarea name="descricao_inicial" class="form-textarea" placeholder="Descreva a proposta...">{{ old('descricao_inicial') }}</textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">Configurações Técnicas (Interno)</h2>
                    <div class="form-group full-width">
                        <label class="form-label">Configurações Técnicas</label>
                        <textarea name="configuracoes_tecnicas" class="form-textarea" placeholder="Detalhes técnicos internos...">{{ old('configuracoes_tecnicas') }}</textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">Estado do Funil</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Estado do Funil <span class="required">*</span>
                            </label>
                            <select name="estado" class="form-select" required>
                                <option value="primeiro_contato" {{ old('estado', 'primeiro_contato') == 'primeiro_contato' ? 'selected' : '' }}>Primeiro Contato</option>
                                <option value="em_analise" {{ old('estado') == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                                <option value="fechado" {{ old('estado') == 'fechado' ? 'selected' : '' }}>Fechado</option>
                                <option value="perdido" {{ old('estado') == 'perdido' ? 'selected' : '' }}>Perdido</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Data de Criação <span class="required">*</span>
                            </label>
                            <input type="date" name="data_criacao" class="form-input" value="{{ old('data_criacao', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Responsável Comercial</label>
                        <select name="responsavel_id" class="form-select">
                            <option value="">Selecione...</option>
                            @foreach($responsaveis as $responsavel)
                                <option value="{{ $responsavel->id }}" {{ old('responsavel_id') == $responsavel->id ? 'selected' : '' }}>
                                    {{ $responsavel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('crm.funil') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Salvar Proposta</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
