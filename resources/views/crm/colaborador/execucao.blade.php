<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Execução — O.S {{ $servico->numero_os ?? $servico->id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; color: #0f172a; display: flex; min-height: 100vh; }
        .main-wrapper { margin-left: 280px; width: calc(100% - 280px); padding: 20px; max-width: 1100px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 2px 10px rgba(15,23,42,.04); margin-bottom: 16px; }
        .header { padding: 20px; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 14px; align-items: flex-start; }
        .title { font-size: 22px; font-weight: 800; }
        .subtitle { color: #64748b; font-size: 13px; margin-top: 6px; line-height: 1.4; }
        .cronobox { background: #0f172a; color: #fff; border-radius: 14px; padding: 16px 22px; text-align: center; min-width: 200px; }
        .cronobox .label { font-size: 11px; opacity: .85; text-transform: uppercase; letter-spacing: .06em; }
        .cronobox .time { font-size: 32px; font-weight: 800; font-variant-numeric: tabular-nums; margin-top: 4px; }
        .info { padding: 0 20px 16px; font-size: 14px; color: #475569; line-height: 1.5; }
        .banner-done { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; margin: 0 20px 16px; border-radius: 10px; font-weight: 600; }
        .tabs { display: flex; flex-wrap: wrap; gap: 8px; padding: 14px 20px 0; }
        .tab-btn { border: 1px solid #cbd5e1; background: #fff; border-radius: 10px; padding: 8px 14px; font-size: 13px; font-weight: 700; color: #334155; cursor: pointer; }
        .tab-btn.active { background: #0f172a; color: #fff; border-color: #0f172a; }
        .tab-pane { display: none; padding: 20px; }
        .tab-pane.active { display: block; }
        label { font-size: 12px; color: #475569; font-weight: 700; margin-bottom: 5px; display: block; }
        input, textarea, select { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 14px; font-family: inherit; }
        textarea { min-height: 120px; resize: vertical; }
        .btn { border: none; border-radius: 10px; padding: 10px 16px; font-size: 13px; font-weight: 700; cursor: pointer; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-ghost { background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; }
        .actions { margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .full { grid-column: 1 / -1; }
        .alert { margin-top: 10px; font-size: 13px; font-weight: 700; }
        .ok { color: #166534; }
        .error { color: #991b1b; }
        .anexo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px; margin-top: 14px; }
        .anexo-card { border: 1px solid #e2e8f0; background: #f8fafc; border-radius: 10px; padding: 10px; }
        .small { font-size: 12px; color: #64748b; }
        .questionario-card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin-top: 10px; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55); z-index: 2000; align-items: center; justify-content: center; padding: 20px; }
        .modal-overlay.open { display: flex; }
        .modal-box { background: #fff; border-radius: 16px; max-width: 520px; width: 100%; padding: 22px; box-shadow: 0 20px 50px rgba(0,0,0,.2); }
        .modal-box h3 { font-size: 18px; margin-bottom: 10px; }
        .modal-box p { color: #64748b; font-size: 14px; margin-bottom: 14px; line-height: 1.45; }
        #sigPad { border: 2px dashed #cbd5e1; border-radius: 10px; width: 100%; height: 180px; touch-action: none; cursor: crosshair; background: #fafafa; }
        @media (max-width: 1100px) { .main-wrapper { margin-left: 0; width: 100%; } .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    @include('components.sidebar')
    <div class="main-wrapper">
        <div class="card">
            <div class="header">
                <div>
                    <div class="title">Serviço em campo</div>
                    <div class="subtitle">O.S <strong>{{ $servico->numero_os ?? $servico->id }}</strong> · {{ $servico->cliente->nome ?? '—' }}<br>{{ $servico->tipo_tarefa ?: 'Atividade' }} · {{ $servico->status_operacional_label }}</div>
                </div>
                @if(!in_array($servico->status_operacional, ['concluido', 'cancelado'], true))
                    <div class="cronobox" id="cronobox" data-inicio="{{ $inicioCronometro?->toIso8601String() }}">
                        <div class="label">Tempo neste serviço</div>
                        <div class="time" id="cronometroTxt">00:00:00</div>
                    </div>
                @endif
            </div>
            @if($servico->orientacao)
                <div class="info"><strong>Orientação:</strong> {{ $servico->orientacao }}</div>
            @endif
            @if(in_array($servico->status_operacional, ['concluido', 'cancelado'], true))
                <div class="banner-done">Este serviço já foi encerrado. Você pode consultar os dados abaixo.</div>
            @endif
        </div>

        <div class="card">
            <div class="tabs">
                <button type="button" class="tab-btn active" data-tab="relato">Relato</button>
                <button type="button" class="tab-btn" data-tab="anexos">Anexos</button>
                <button type="button" class="tab-btn" data-tab="questionarios">Questionários</button>
                <button type="button" class="tab-btn" data-tab="horas">Pausa / horas</button>
            </div>

            <div id="relato" class="tab-pane active">
                <form id="formRelato" class="grid">
                    <div class="full">
                        <label>Relato da execução</label>
                        <textarea id="relatoTexto" name="relato_execucao" maxlength="5000">{{ $servico->relato_execucao }}</textarea>
                        <div class="small" id="relatoContador">0/5000</div>
                    </div>
                    <div class="full"><label>Observações</label><textarea name="checklist_pmoc[obs]">{{ $servico->checklist_pmoc['obs'] ?? '' }}</textarea></div>
                </form>
                <div class="actions">
                    <button type="button" class="btn btn-primary" onclick="submitForm('formRelato', '{{ route('crm.relatorios.relato', $servico->id) }}')">Salvar relato</button>
                    @if(!in_array($servico->status_operacional, ['concluido', 'cancelado'], true))
                        <button type="button" class="btn btn-success" onclick="abrirModalFinalizar()">Finalizar serviço (com assinatura)</button>
                    @endif
                </div>
                <div id="msgRelato" class="alert"></div>
            </div>

            <div id="anexos" class="tab-pane">
                <form id="formAnexos" class="grid" enctype="multipart/form-data">
                    <div><label>Arquivos</label><input type="file" name="anexos[]" multiple></div>
                    <div><label>Fotos</label><input type="file" name="fotos[]" multiple accept="image/*"></div>
                </form>
                <div class="actions">
                    <button type="button" class="btn btn-primary" onclick="submitForm('formAnexos', '{{ route('crm.relatorios.anexos', $servico->id) }}', true)">Enviar anexos</button>
                </div>
                <div id="msgAnexos" class="alert"></div>
                <div class="anexo-grid">
                    @foreach($servico->anexos as $anexo)
                        <div class="anexo-card">
                            <div><strong>{{ $anexo->nome_original }}</strong></div>
                            <div class="small">{{ $anexo->created_at?->format('d/m/Y H:i') }}</div>
                            <div class="actions">
                                <a class="btn btn-ghost" href="{{ asset('storage/' . $anexo->path) }}" target="_blank">Abrir</a>
                                <button type="button" class="btn btn-danger" onclick="removerAnexo('{{ route('crm.relatorios.anexos.destroy', [$servico->id, $anexo->id]) }}')">Excluir</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="questionarios" class="tab-pane">
                <div class="grid">
                    <div>
                        <label>Adicionar questionário</label>
                        <select id="questionario_id">
                            <option value="">Selecione…</option>
                            @foreach($questionariosDisponiveis as $q)
                                <option value="{{ $q->id }}">{{ $q->titulo }} ({{ $q->perguntas_count }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex; align-items:flex-end;">
                        <button type="button" class="btn btn-primary" onclick="vincularQuestionario()">Incluir</button>
                    </div>
                </div>
                <div id="msgQuestionarios" class="alert"></div>
                @foreach($servico->questionariosVinculados as $vinculo)
                    <div class="questionario-card">
                        <div style="font-weight:700; margin-bottom:8px;">{{ $vinculo->questionario->titulo }}</div>
                        <form id="formRespostas{{ $vinculo->id }}" class="grid">
                            @foreach($vinculo->questionario->perguntas as $pergunta)
                                @php $resp = $vinculo->respostas[$pergunta->id] ?? ''; @endphp
                                <div class="full">
                                    <label>{{ $pergunta->ordem }}) {{ $pergunta->texto }} {{ $pergunta->resposta_obrigatoria ? '*' : '' }}</label>
                                    @if($pergunta->tipo_resposta === 'sim_nao')
                                        <select name="respostas[{{ $pergunta->id }}]">
                                            <option value="">—</option>
                                            <option value="Sim" {{ $resp === 'Sim' ? 'selected' : '' }}>Sim</option>
                                            <option value="Não" {{ $resp === 'Não' ? 'selected' : '' }}>Não</option>
                                        </select>
                                    @elseif($pergunta->tipo_resposta === 'textarea')
                                        <textarea name="respostas[{{ $pergunta->id }}]">{{ $resp }}</textarea>
                                    @elseif($pergunta->tipo_resposta === 'numero')
                                        <input type="number" step="any" name="respostas[{{ $pergunta->id }}]" value="{{ $resp }}">
                                    @elseif($pergunta->tipo_resposta === 'data')
                                        <input type="date" name="respostas[{{ $pergunta->id }}]" value="{{ $resp }}">
                                    @elseif($pergunta->tipo_resposta === 'selecionar')
                                        <select name="respostas[{{ $pergunta->id }}]">
                                            <option value="">—</option>
                                            @foreach(($pergunta->opcoes ?? []) as $opcao)
                                                <option value="{{ $opcao }}" {{ $resp === $opcao ? 'selected' : '' }}>{{ $opcao }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" name="respostas[{{ $pergunta->id }}]" value="{{ $resp }}">
                                    @endif
                                </div>
                            @endforeach
                        </form>
                        <div class="actions">
                            <button type="button" class="btn btn-primary" onclick="submitForm('formRespostas{{ $vinculo->id }}', '{{ route('crm.relatorios.questionarios.respostas', [$servico->id, $vinculo->id]) }}')">Salvar respostas</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="horas" class="tab-pane">
                <form id="formHoras" class="grid">
                    <input type="hidden" name="colaborador_ids[]" value="{{ $colab->id }}">
                    <div>
                        <label>Registro</label>
                        <select name="monitoramento">
                            <option value="check_in">Check-in</option>
                            <option value="check_out">Check-out</option>
                            <option value="pausa">Pausa</option>
                            <option value="retorno">Retorno</option>
                            <option value="ajuste">Ajuste manual</option>
                        </select>
                    </div>
                    <div><label>Horário</label><input type="datetime-local" name="horario" value="{{ now()->format('Y-m-d\TH:i') }}"></div>
                    <div class="full"><label>Motivo (obrigatório em pausa)</label><input type="text" name="motivo" placeholder="Ex.: Almoço, deslocamento…"></div>
                    <div class="full"><label>Justificativa (ajuste manual)</label><textarea name="justificativa"></textarea></div>
                </form>
                <div class="actions">
                    <button type="button" class="btn btn-primary" onclick="submitForm('formHoras', '{{ route('crm.relatorios.horas', $servico->id) }}')">Registrar</button>
                </div>
                <div id="msgHoras" class="alert"></div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modalFinalizar">
        <div class="modal-box">
            <h3>Finalizar serviço</h3>
            <p>Confirme o encerramento. <strong>Assine abaixo</strong> com o dedo ou caneta (celular/tablet). O sistema grava a assinatura no relatório.</p>
            <canvas id="sigPad" width="460" height="180"></canvas>
            <div class="actions" style="margin-top:14px;">
                <button type="button" class="btn btn-ghost" onclick="limparAssinatura()">Limpar assinatura</button>
                <button type="button" class="btn btn-ghost" onclick="fecharModalFinalizar()">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarFinalizar()">Confirmar e encerrar</button>
            </div>
            <div id="msgFinalizar" class="alert"></div>
        </div>
    </div>

    <script>
        const token = document.querySelector('meta[name="csrf-token"]').content;

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById(btn.dataset.tab).classList.add('active');
            });
        });

        const relato = document.getElementById('relatoTexto');
        const contador = document.getElementById('relatoContador');
        if (relato && contador) {
            const upd = () => contador.textContent = relato.value.length + '/5000';
            relato.addEventListener('input', upd);
            upd();
        }

        const box = document.getElementById('cronobox');
        const elCrono = document.getElementById('cronometroTxt');
        if (box && elCrono && box.dataset.inicio) {
            const t0 = new Date(box.dataset.inicio).getTime();
            function tick() {
                const s = Math.max(0, Math.floor((Date.now() - t0) / 1000));
                const h = String(Math.floor(s / 3600)).padStart(2, '0');
                const m = String(Math.floor((s % 3600) / 60)).padStart(2, '0');
                const sec = String(s % 60).padStart(2, '0');
                elCrono.textContent = h + ':' + m + ':' + sec;
            }
            tick();
            setInterval(tick, 1000);
        } else if (elCrono) {
            elCrono.textContent = '—';
        }

        function showMsg(id, ok, text) {
            const el = document.getElementById(id);
            if (!el) return;
            el.className = 'alert ' + (ok ? 'ok' : 'error');
            el.textContent = text;
        }

        async function submitForm(formId, url, isMultipart = false) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            const msgId = { formRelato: 'msgRelato', formAnexos: 'msgAnexos', formHoras: 'msgHoras' }[formId] || 'msgQuestionarios';
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        ...(isMultipart ? {} : { 'X-Requested-With': 'XMLHttpRequest' })
                    },
                    body: formData
                });
                const data = await response.json();
                if (!response.ok || !data.success) throw new Error(data.message || 'Erro ao salvar.');
                showMsg(msgId, true, data.message || 'Salvo.');
                if (formId === 'formAnexos' || formId === 'formHoras') setTimeout(() => window.location.reload(), 700);
            } catch (e) {
                showMsg(msgId, false, e.message);
            }
        }

        async function removerAnexo(url) {
            if (!confirm('Remover este anexo?')) return;
            const response = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } });
            const data = await response.json();
            if (data.success) window.location.reload();
        }

        async function vincularQuestionario() {
            const id = document.getElementById('questionario_id').value;
            if (!id) return;
            const fd = new FormData();
            fd.append('questionario_id', id);
            const response = await fetch('{{ route('crm.relatorios.questionarios.vincular', $servico->id) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: fd
            });
            const data = await response.json();
            if (data.success) {
                showMsg('msgQuestionarios', true, data.message);
                setTimeout(() => window.location.reload(), 700);
            } else {
                showMsg('msgQuestionarios', false, data.message || 'Erro.');
            }
        }

        const canvas = document.getElementById('sigPad');
        let drawing = false, ctx = null, hasInk = false;
        if (canvas) {
            ctx = canvas.getContext('2d');
            const pos = (e) => {
                const r = canvas.getBoundingClientRect();
                const x = (e.touches ? e.touches[0].clientX : e.clientX) - r.left;
                const y = (e.touches ? e.touches[0].clientY : e.clientY) - r.top;
                return { x: x * (canvas.width / r.width), y: y * (canvas.height / r.height) };
            };
            const start = (e) => { drawing = true; ctx.beginPath(); const p = pos(e); ctx.moveTo(p.x, p.y); e.preventDefault(); };
            const move = (e) => {
                if (!drawing) return;
                hasInk = true;
                const p = pos(e);
                ctx.lineTo(p.x, p.y);
                ctx.strokeStyle = '#0f172a';
                ctx.lineWidth = 2;
                ctx.stroke();
                e.preventDefault();
            };
            const end = () => { drawing = false; };
            canvas.addEventListener('mousedown', start);
            canvas.addEventListener('mousemove', move);
            window.addEventListener('mouseup', end);
            canvas.addEventListener('touchstart', start, { passive: false });
            canvas.addEventListener('touchmove', move, { passive: false });
            canvas.addEventListener('touchend', end);
        }

        function abrirModalFinalizar() {
            document.getElementById('modalFinalizar').classList.add('open');
        }
        function fecharModalFinalizar() {
            document.getElementById('modalFinalizar').classList.remove('open');
        }
        function limparAssinatura() {
            if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasInk = false;
        }

        async function confirmarFinalizar() {
            const form = document.getElementById('formRelato');
            const fd = new FormData(form);
            if (!hasInk) {
                showMsg('msgFinalizar', false, 'Desenhe sua assinatura no quadro.');
                return;
            }
            fd.append('assinatura_base64', canvas.toDataURL('image/png'));
            fd.append('status_operacional', 'concluido');
            try {
                const response = await fetch('{{ route('crm.relatorios.relato', $servico->id) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: fd
                });
                const data = await response.json();
                if (!response.ok || !data.success) throw new Error(data.message || 'Erro ao finalizar.');
                window.location.reload();
            } catch (e) {
                showMsg('msgFinalizar', false, e.message);
            }
        }
    </script>
</body>
</html>
