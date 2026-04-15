<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Preencher Relatório - O.S #{{ $servico->numero_os ?? $servico->id }}</title>
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
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .header {
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }
        
        .header-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 16px;
            font-size: 14px;
            color: #4a5568;
        }
        
        .section {
            margin-bottom: 32px;
            padding: 24px;
            background: #f7fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        label {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 6px;
        }
        
        input[type="text"],
        input[type="datetime-local"],
        input[type="time"],
        input[type="number"],
        select,
        textarea {
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }
        
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .fotos-upload {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }
        
        .foto-preview {
            position: relative;
            aspect-ratio: 1;
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .foto-preview:hover {
            border-color: #4a90e2;
        }
        
        .foto-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .foto-preview .remove-foto {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .foto-upload-btn {
            aspect-ratio: 1;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        
        .foto-upload-btn:hover {
            border-color: #4a90e2;
            background: #f0f9ff;
        }
        
        .checklist-item {
            margin-bottom: 20px;
            padding: 16px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .checklist-question {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .checklist-answer {
            margin-top: 8px;
        }
        
        .checklist-answer input[type="text"],
        .checklist-answer textarea,
        .checklist-answer select {
            width: 100%;
        }
        
        .assinatura-container {
            margin-top: 24px;
            padding: 24px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .assinatura-canvas-wrapper {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            background: white;
            margin-bottom: 16px;
        }
        
        #assinaturaCanvas {
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            cursor: crosshair;
            display: block;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .assinatura-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 12px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: #4a90e2;
            color: white;
        }
        
        .btn-primary:hover {
            background: #357abd;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
        }
        
        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }
        
        .btn-secondary:hover {
            background: #cbd5e0;
        }
        
        .btn-danger {
            background: #f44336;
            color: white;
        }
        
        .btn-danger:hover {
            background: #d32f2f;
        }
        
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid #e2e8f0;
        }
        
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status-select {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .status-select select {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Preencher Relatório de Atendimento</h1>
            <div class="header-info">
                <div>
                    <strong>Nº O.S:</strong> #{{ $servico->numero_os ?? $servico->id }}
                </div>
                <div>
                    <strong>Cliente:</strong> {{ $servico->cliente->nome ?? '-' }}
                </div>
                <div>
                    <strong>Tipo:</strong> {{ $servico->tipo_tarefa ?? '-' }}
                </div>
            </div>
        </div>

        <div id="alertContainer"></div>

        <form id="relatorioForm">
            <!-- Horários -->
            <div class="section">
                <div class="section-title">⏰ Horários</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Horário de entrada <span class="small" style="font-weight:400;">(quando iniciou o serviço e o cronômetro)</span></label>
                        <input type="datetime-local" name="horario_inicio_execucao" id="horario_inicio_execucao"
                               value="{{ $servico->horario_inicio_execucao ? $servico->horario_inicio_execucao->format('Y-m-d\TH:i') : ($servico->horario_chegada ? $servico->horario_chegada->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="form-group">
                        <label>Horário de saída <span class="small" style="font-weight:400;">(quando finalizou e parou o cronômetro)</span></label>
                        <input type="datetime-local" name="horario_fim_execucao" id="horario_fim_execucao"
                               value="{{ $servico->horario_fim_execucao ? $servico->horario_fim_execucao->format('Y-m-d\TH:i') : ($servico->horario_saida ? $servico->horario_saida->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="form-group">
                        <label>Início do Deslocamento</label>
                        <input type="time" name="inicio_deslocamento" id="inicio_deslocamento"
                               value="{{ $servico->inicio_deslocamento ? \Carbon\Carbon::parse($servico->inicio_deslocamento)->format('H:i') : '' }}">
                    </div>
                    <div class="form-group">
                        <label>Duração do Deslocamento (minutos)</label>
                        <input type="number" name="duracao_deslocamento_minutos" id="duracao_deslocamento_minutos"
                               value="{{ $servico->duracao_deslocamento_minutos ?? '' }}" min="0">
                    </div>
                </div>
            </div>

            <!-- Relato de Execução -->
            <div class="section">
                <div class="section-title">📝 Relato de Execução</div>
                <div class="form-group full-width">
                    <textarea name="relato_execucao" id="relato_execucao" placeholder="Descreva detalhadamente os serviços realizados...">{{ $servico->relato_execucao ?? '' }}</textarea>
                </div>
            </div>

            <!-- Fotos -->
            <div class="section">
                <div class="section-title">📷 Fotos</div>
                <div class="fotos-upload" id="fotosContainer">
                    @if($servico->fotos && is_array($servico->fotos))
                        @foreach($servico->fotos as $index => $foto)
                            <div class="foto-preview">
                                @if(str_starts_with($foto, 'data:image'))
                                    <img src="{{ $foto }}" alt="Foto {{ $index + 1 }}">
                                @else
                                    <img src="{{ asset('storage/' . $foto) }}" alt="Foto {{ $index + 1 }}">
                                @endif
                                <button type="button" class="remove-foto" onclick="removeFoto(this, '{{ $foto }}')">×</button>
                            </div>
                        @endforeach
                    @endif
                    <div class="foto-upload-btn" onclick="document.getElementById('fotosInput').click()">
                        <div style="font-size: 32px; margin-bottom: 8px;">📷</div>
                        <div style="font-size: 12px; color: #718096;">Adicionar Foto</div>
                    </div>
                </div>
                <input type="file" id="fotosInput" name="fotos[]" multiple accept="image/*" style="display: none;" onchange="handleFotosUpload(event)">
            </div>

            <!-- Checklist PMOC -->
            <div class="section">
                <div class="section-title">✅ Checklist PMOC</div>
                
                <div class="checklist-item">
                    <div class="checklist-question">1) Há riscos de segurança que impeça a realização da atividade?</div>
                    <div class="checklist-answer">
                        <select name="checklist_pmoc[riscos_seguranca]">
                            <option value="">Selecione...</option>
                            <option value="Sim" {{ isset($servico->checklist_pmoc['riscos_seguranca']) && $servico->checklist_pmoc['riscos_seguranca'] == 'Sim' ? 'selected' : '' }}>Sim</option>
                            <option value="Não" {{ isset($servico->checklist_pmoc['riscos_seguranca']) && $servico->checklist_pmoc['riscos_seguranca'] == 'Não' ? 'selected' : '' }}>Não</option>
                        </select>
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">2) Periodicidade do PMOC</div>
                    <div class="checklist-answer">
                        <select name="checklist_pmoc[periodicidade]">
                            <option value="">Selecione...</option>
                            <option value="Mensal" {{ isset($servico->checklist_pmoc['periodicidade']) && $servico->checklist_pmoc['periodicidade'] == 'Mensal' ? 'selected' : '' }}>Mensal</option>
                            <option value="Trimestral" {{ isset($servico->checklist_pmoc['periodicidade']) && $servico->checklist_pmoc['periodicidade'] == 'Trimestral' ? 'selected' : '' }}>Trimestral</option>
                            <option value="Semestral" {{ isset($servico->checklist_pmoc['periodicidade']) && $servico->checklist_pmoc['periodicidade'] == 'Semestral' ? 'selected' : '' }}>Semestral</option>
                            <option value="Anual" {{ isset($servico->checklist_pmoc['periodicidade']) && $servico->checklist_pmoc['periodicidade'] == 'Anual' ? 'selected' : '' }}>Anual</option>
                        </select>
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">3) Itens do PMOC foram executados? (Limpeza de filtro, verificação de bandeja, dreno, comando e outros).</div>
                    <div class="checklist-answer">
                        <select name="checklist_pmoc[itens_executados]">
                            <option value="">Selecione...</option>
                            <option value="Sim" {{ isset($servico->checklist_pmoc['itens_executados']) && $servico->checklist_pmoc['itens_executados'] == 'Sim' ? 'selected' : '' }}>Sim</option>
                            <option value="Não" {{ isset($servico->checklist_pmoc['itens_executados']) && $servico->checklist_pmoc['itens_executados'] == 'Não' ? 'selected' : '' }}>Não</option>
                            <option value="Parcial" {{ isset($servico->checklist_pmoc['itens_executados']) && $servico->checklist_pmoc['itens_executados'] == 'Parcial' ? 'selected' : '' }}>Parcial</option>
                        </select>
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">4) CAPACIDADE (BTU/H)</div>
                    <div class="checklist-answer">
                        <input type="text" name="checklist_pmoc[capacidade_btu]" 
                               value="{{ $servico->checklist_pmoc['capacidade_btu'] ?? '' }}" 
                               placeholder="Ex: 48000">
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">5) Colaboradores envolvidos</div>
                    <div class="checklist-answer">
                        <input type="text" name="checklist_pmoc[colaboradores]" 
                               value="{{ $servico->checklist_pmoc['colaboradores'] ?? ($servico->tecnicos ? $servico->tecnicos->pluck('nome_profissional')->join(', ') : '') }}" 
                               placeholder="Ex: Edvandro C, Lucas S">
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">6) Condições gerais</div>
                    <div class="checklist-answer">
                        <textarea name="checklist_pmoc[condicoes_gerais]" 
                                  placeholder="Ex: Equipamento limpo, Fixação adequada, Gabinete sem avarias, Isolamento térmico íntegro">{{ $servico->checklist_pmoc['condicoes_gerais'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">7) Ruído e vibração</div>
                    <div class="checklist-answer">
                        <select name="checklist_pmoc[ruido_vibracao]">
                            <option value="">Selecione...</option>
                            <option value="Normal" {{ isset($servico->checklist_pmoc['ruido_vibracao']) && $servico->checklist_pmoc['ruido_vibracao'] == 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Anormal" {{ isset($servico->checklist_pmoc['ruido_vibracao']) && $servico->checklist_pmoc['ruido_vibracao'] == 'Anormal' ? 'selected' : '' }}>Anormal</option>
                        </select>
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">8) Drenagem</div>
                    <div class="checklist-answer">
                        <input type="text" name="checklist_pmoc[drenagem]" 
                               value="{{ $servico->checklist_pmoc['drenagem'] ?? '' }}" 
                               placeholder="Ex: Bandeja de dreno limpa">
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">9) Ventilação e troca térmica</div>
                    <div class="checklist-answer">
                        <textarea name="checklist_pmoc[ventilacao_troca_termica]" 
                                  placeholder="Ex: Filtros limpos, Fluxo de ar adequado, Serpentina limpa, Ventilador funcionando corretamente">{{ $servico->checklist_pmoc['ventilacao_troca_termica'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">10) Parte elétrica</div>
                    <div class="checklist-answer">
                        <textarea name="checklist_pmoc[parte_eletrica]" 
                                  placeholder="Ex: Alimentação elétrica normal, Conexões elétricas firmes, Sensores (termistores) aparentes e fixos">{{ $servico->checklist_pmoc['parte_eletrica'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">11) Placas eletrônicas</div>
                    <div class="checklist-answer">
                        <textarea name="checklist_pmoc[placas_eletronicas]" 
                                  placeholder="Ex: LEDs de operação normais, Placa eletrônica em bom estado">{{ $servico->checklist_pmoc['placas_eletronicas'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">12) Funcionamento geral</div>
                    <div class="checklist-answer">
                        <textarea name="checklist_pmoc[funcionamento_geral]" 
                                  placeholder="Ex: Partida normal do equipamento, Resposta correta ao comando/controle, Temperatura de insuflamento adequada">{{ $servico->checklist_pmoc['funcionamento_geral'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">13) Avaliação final</div>
                    <div class="checklist-answer">
                        <input type="text" name="checklist_pmoc[avaliacao_final]" 
                               value="{{ $servico->checklist_pmoc['avaliacao_final'] ?? '' }}" 
                               placeholder="Ex: Equipamento em bom estado">
                    </div>
                </div>

                <div class="checklist-item">
                    <div class="checklist-question">14) Obs</div>
                    <div class="checklist-answer">
                        <textarea name="checklist_pmoc[obs]" 
                                  placeholder="Observações adicionais">{{ $servico->checklist_pmoc['obs'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Assinatura -->
            <div class="section">
                <div class="section-title">✍️ Assinatura do Cliente</div>
                <div class="assinatura-container">
                    <div class="assinatura-canvas-wrapper">
                        <canvas id="assinaturaCanvas" width="500" height="200"></canvas>
                    </div>
                    <div class="assinatura-actions">
                        <button type="button" class="btn btn-secondary" onclick="limparAssinatura()">Limpar</button>
                    </div>
                    @if($servico->assinatura_base64)
                        <div style="margin-top: 16px; text-align: center;">
                            <p style="font-size: 12px; color: #718096;">Assinatura já coletada</p>
                            <img src="{{ $servico->assinatura_base64 }}" alt="Assinatura" style="max-width: 300px; margin-top: 8px; border: 1px solid #e2e8f0; border-radius: 4px;">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status e Ações -->
            <div class="actions-bar">
                <div class="status-select">
                    <label style="margin: 0; white-space: nowrap;">Status:</label>
                    <select name="status_operacional" id="status_operacional">
                        <option value="em_andamento" {{ $servico->status_operacional == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="concluido" {{ $servico->status_operacional == 'concluido' ? 'selected' : '' }}>Concluído</option>
                        <option value="pausado" {{ $servico->status_operacional == 'pausado' ? 'selected' : '' }}>Pausado</option>
                    </select>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button type="button" class="btn btn-secondary" onclick="salvarRascunho()">💾 Salvar Rascunho</button>
                    <button type="button" class="btn btn-success" onclick="finalizarServico()">✅ Finalizar Serviço</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const servicoId = {{ $servico->id }};
        let fotosRemovidas = [];
        let fotosNovas = [];
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;
        const canvas = document.getElementById('assinaturaCanvas');
        const ctx = canvas.getContext('2d');
        
        // Configurar canvas
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        // Eventos do canvas para assinatura
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        
        // Touch events para mobile
        canvas.addEventListener('touchstart', handleTouch);
        canvas.addEventListener('touchmove', handleTouch);
        canvas.addEventListener('touchend', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            lastX = e.clientX - rect.left;
            lastY = e.clientY - rect.top;
        }

        function draw(e) {
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);
            ctx.stroke();
            
            lastX = x;
            lastY = y;
        }

        function stopDrawing() {
            isDrawing = false;
        }

        function handleTouch(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                             e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }

        function limparAssinatura() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function handleFotosUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fotoPreview = document.createElement('div');
                    fotoPreview.className = 'foto-preview';
                    fotoPreview.innerHTML = `
                        <img src="${e.target.result}" alt="Foto">
                        <button type="button" class="remove-foto" onclick="removeFotoNova(this)">×</button>
                    `;
                    fotoPreview.dataset.file = file.name;
                    fotosNovas.push({ file: file, preview: e.target.result });
                    
                    const container = document.getElementById('fotosContainer');
                    const uploadBtn = container.querySelector('.foto-upload-btn');
                    container.insertBefore(fotoPreview, uploadBtn);
                };
                reader.readAsDataURL(file);
            });
            event.target.value = '';
        }

        function removeFoto(button, fotoPath) {
            if (confirm('Deseja remover esta foto?')) {
                fotosRemovidas.push(fotoPath);
                button.closest('.foto-preview').remove();
            }
        }

        function removeFotoNova(button) {
            const preview = button.closest('.foto-preview');
            const fileName = preview.dataset.file;
            fotosNovas = fotosNovas.filter(f => f.file.name !== fileName);
            preview.remove();
        }

        function getAssinaturaBase64() {
            return canvas.toDataURL('image/png');
        }

        function showAlert(message, type = 'success') {
            const container = document.getElementById('alertContainer');
            container.className = `alert alert-${type}`;
            container.textContent = message;
            container.style.display = 'block';
            setTimeout(() => {
                container.style.display = 'none';
            }, 5000);
        }

        function salvarRascunho() {
            salvarRelatorio(false);
        }

        function finalizarServico() {
            if (confirm('Deseja finalizar este serviço? O status será alterado para "Concluído".')) {
                document.getElementById('status_operacional').value = 'concluido';
                salvarRelatorio(true);
            }
        }

        function salvarRelatorio(finalizar = false) {
            const formData = new FormData(document.getElementById('relatorioForm'));
            
            // Adicionar fotos novas
            fotosNovas.forEach((foto, index) => {
                formData.append(`fotos[]`, foto.file);
            });
            
            // Adicionar assinatura
            const assinatura = getAssinaturaBase64();
            if (assinatura && !assinatura.includes('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==')) {
                formData.append('assinatura_base64', assinatura);
            }
            
            // Processar checklist PMOC
            const checklist = {};
            document.querySelectorAll('[name^="checklist_pmoc"]').forEach(input => {
                const name = input.name.replace('checklist_pmoc[', '').replace(']', '');
                if (input.value) {
                    checklist[name] = input.value;
                }
            });
            formData.append('checklist_pmoc', JSON.stringify(checklist));
            
            // Adicionar status se finalizando
            if (finalizar) {
                formData.set('status_operacional', 'concluido');
            }

            fetch(`/crm/servicos/${servicoId}/salvar-relatorio`, {
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
                    showAlert(finalizar ? '✅ Serviço finalizado com sucesso!' : '💾 Rascunho salvo com sucesso!', 'success');
                    if (finalizar) {
                        setTimeout(() => {
                            window.location.href = '/crm/servicos';
                        }, 2000);
                    }
                } else {
                    let errorMsg = 'Erro ao salvar relatório.';
                    if (data.errors) {
                        errorMsg += '\n\n' + Object.values(data.errors).flat().join('\n');
                    }
                    showAlert(errorMsg, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showAlert('❌ Erro ao salvar relatório. Tente novamente.', 'error');
            });
        }
    </script>
</body>
</html>
