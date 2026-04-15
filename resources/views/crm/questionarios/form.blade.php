<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $questionario->exists ? 'Editar' : 'Novo' }} questionário - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            color: #0f172a;
            display: flex;
            min-height: 100vh;
        }
        .main {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 24px;
            max-width: 720px;
        }
        .builder {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(15,23,42,.06);
            padding: 24px 26px 28px;
        }
        .builder-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .builder-lead {
            font-size: 15px;
            color: #475569;
            margin: 8px 0 22px;
            line-height: 1.45;
        }
        .steps {
            font-size: 13px;
            color: #64748b;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 22px;
            line-height: 1.5;
        }
        .steps strong { color: #334155; }
        .alert-errors {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 13px;
        }
        .alert-errors ul { margin: 8px 0 0 18px; }
        .group { margin-bottom: 18px; }
        .group > label {
            display: block;
            font-size: 13px;
            color: #334155;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .hint { font-size: 12px; color: #94a3b8; margin-top: 6px; line-height: 1.4; }
        input[type="text"], textarea, select {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 15px;
            font-family: inherit;
        }
        input[type="text"]:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }
        textarea { min-height: 88px; resize: vertical; }
        .nome-input { font-size: 17px; font-weight: 500; }
        .add-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
        }
        .add-row .btn-add {
            flex: 1;
            min-width: 140px;
            border: 2px dashed #94a3b8;
            background: #fff;
            border-radius: 12px;
            padding: 14px 12px;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }
        .add-row .btn-add:hover {
            border-color: #2563eb;
            background: #eff6ff;
            color: #1d4ed8;
        }
        .add-row .btn-add span { display: block; font-size: 12px; font-weight: 500; color: #64748b; margin-top: 4px; }
        .add-row-after {
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px dashed #cbd5e1;
        }
        .add-row-label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 10px;
        }
        .section-label {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }
        .q-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 14px;
            background: #fafbfc;
        }
        .q-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }
        .q-num { font-size: 14px; font-weight: 800; color: #2563eb; }
        .q-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e0e7ff;
            color: #3730a3;
        }
        .q-opcoes-wrap { margin-top: 12px; }
        .q-opcoes-wrap.is-hidden { display: none; }
        .q-opcoes-wrap label { font-size: 12px; font-weight: 600; color: #475569; }
        .q-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 14px 18px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .q-footer label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: #334155;
            font-weight: 500;
        }
        .q-footer input[type="checkbox"] { width: 18px; height: 18px; accent-color: #2563eb; }
        .btn-remove {
            margin-left: auto;
            border: none;
            background: transparent;
            color: #dc2626;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
        }
        .btn-remove:hover { color: #991b1b; }
        .empty-hint {
            text-align: center;
            padding: 28px 16px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            margin-bottom: 14px;
            background: #f8fafc;
        }
        details.advanced {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 14px;
            margin: 20px 0;
            background: #f8fafc;
        }
        details.advanced summary {
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            padding: 12px 0;
            list-style: none;
        }
        details.advanced summary::-webkit-details-marker { display: none; }
        details.advanced[open] summary { color: #334155; margin-bottom: 8px; }
        .advanced-inner { padding-bottom: 14px; font-size: 13px; }
        .advanced-inner .group { margin-bottom: 12px; }
        .advanced-inner label { font-weight: 600; color: #475569; }
        .pill-toggle {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .pill-toggle label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            font-weight: 500;
            color: #334155;
        }
        .pill-toggle input { margin-top: 3px; width: 17px; height: 17px; accent-color: #2563eb; flex-shrink: 0; }
        .tipo-avancado {
            margin-top: 10px;
            font-size: 12px;
            color: #64748b;
        }
        .tipo-avancado select { font-size: 13px; padding: 8px 10px; margin-top: 4px; }
        .actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }
        .btn {
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-primary {
            background: linear-gradient(135deg,#2563eb,#1d4ed8);
            color: #fff;
            box-shadow: 0 4px 14px rgba(37,99,235,.35);
        }
        .btn-primary:hover { filter: brightness(1.05); }
        .btn-ghost {
            background: #fff;
            border: 1px solid #cbd5e1;
            color: #475569;
        }
        @media (max-width: 1200px) {
            .main { margin-left: 0; width: 100%; max-width: none; }
        }
    </style>
</head>
<body>
    @include('components.sidebar')
    <div class="main">
        <div class="builder">
            <h1 class="builder-title">{{ $questionario->exists ? 'Editar questionário' : 'Novo questionário' }}</h1>
            <p class="builder-lead">Pense em um nome claro, monte as perguntas em poucos cliques e salve. Não precisa preencher tudo que não for usar.</p>
            <div class="steps">
                <strong>1.</strong> Nome do questionário &nbsp;·&nbsp; <strong>2.</strong> Botões <strong>Sim/Não</strong>, <strong>Lista</strong> ou <strong>Texto</strong> &nbsp;·&nbsp; <strong>3.</strong> Escrever cada pergunta &nbsp;·&nbsp; <strong>4.</strong> <strong>Salvar</strong>
            </div>

            @if ($errors->any())
                <div class="alert-errors">
                    Corrija os itens abaixo e tente de novo.
                    <ul>
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $questionario->exists ? route('crm.questionarios.update', $questionario->id) : route('crm.questionarios.store') }}" id="formQuestionario">
                @csrf
                @if($questionario->exists) @method('PUT') @endif

                <div class="group">
                    <label for="titulo">Nome do questionário</label>
                    <input class="nome-input" id="titulo" type="text" name="titulo" value="{{ old('titulo', $questionario->titulo) }}" required placeholder="Ex.: Checklist de atendimento" autocomplete="off">
                    <p class="hint">Esse nome aparece na lista quando for vincular à ordem de serviço.</p>
                </div>

                <div class="section-label">Perguntas</div>
                <p class="hint" style="margin-top:-6px;margin-bottom:12px;">Escolha o <strong>tipo de resposta</strong> primeiro. Só em <strong>Lista</strong> você precisa preencher as opções embaixo. Você pode adicionar <strong>quantas perguntas quiser</strong> — use os botões no topo ou no fim da lista.</p>

                <div class="add-row" id="addRowTop" aria-label="Adicionar pergunta no início">
                    <button type="button" class="btn-add" onclick="adicionarPergunta('sim_nao')">
                        + Sim / Não
                        <span>Resposta só Sim ou Não</span>
                    </button>
                    <button type="button" class="btn-add" onclick="adicionarPergunta('selecionar')">
                        + Lista
                        <span>Escolher uma opção da lista</span>
                    </button>
                    <button type="button" class="btn-add" onclick="adicionarPergunta('texto')">
                        + Texto
                        <span>Campo para escrever livre</span>
                    </button>
                </div>

                @php
                    $oldPerguntas = old('perguntas');
                    if ($oldPerguntas !== null) {
                        $listaPerguntas = $oldPerguntas;
                    } elseif ($questionario->exists || $perguntas->isNotEmpty()) {
                        $listaPerguntas = $perguntas->map(fn ($p) => [
                            'texto' => $p->texto,
                            'tipo_resposta' => $p->tipo_resposta,
                            'opcoes' => is_array($p->opcoes) ? implode("\n", $p->opcoes) : '',
                            'resposta_obrigatoria' => $p->resposta_obrigatoria,
                            'descricao_pergunta' => $p->descricao_pergunta,
                        ])->values()->all();
                    } else {
                        $listaPerguntas = [];
                    }
                    $tiposSimples = ['sim_nao', 'selecionar', 'texto'];
                    $labelsTipo = [
                        'texto' => 'Texto curto',
                        'textarea' => 'Texto longo',
                        'numero' => 'Número',
                        'data' => 'Data',
                        'sim_nao' => 'Sim ou Não',
                        'selecionar' => 'Lista de opções',
                    ];
                @endphp

                <div id="perguntasContainer">
                    @if (count($listaPerguntas) === 0)
                        <div class="empty-hint" id="emptyPerguntas">
                            Nenhuma pergunta ainda.<br>
                            Toque em <strong>Sim / Não</strong>, <strong>Lista</strong> ou <strong>Texto</strong> (acima ou abaixo) para adicionar a primeira.
                        </div>
                    @endif
                    @foreach ($listaPerguntas as $i => $p)
                        @php
                            $tipo = $p['tipo_resposta'] ?? 'texto';
                            $simples = in_array($tipo, $tiposSimples, true);
                        @endphp
                        <div class="question q-card" data-q-index="{{ $i }}">
                            <div class="q-card-head">
                                <span class="q-num">Pergunta <span class="q-index-num">{{ $i + 1 }}</span></span>
                                @if ($simples)
                                    <span class="q-badge" data-role="tipo-badge">{{ $labelsTipo[$tipo] ?? $tipo }}</span>
                                @endif
                            </div>
                            @if ($simples)
                                <input type="hidden" name="perguntas[{{ $i }}][tipo_resposta]" value="{{ $tipo }}" data-role="tipo-hidden">
                            @endif
                            <div class="group" style="margin-bottom:0;">
                                <label for="pq-{{ $i }}">O que você quer perguntar?</label>
                                <textarea id="pq-{{ $i }}" name="perguntas[{{ $i }}][texto]" required placeholder="Ex.: Equipamento em funcionamento normal?">{{ $p['texto'] ?? '' }}</textarea>
                            </div>
                            @if (!$simples)
                                <div class="tipo-avancado">
                                    <label for="tipo-{{ $i }}">Tipo de resposta (pergunta já existente)</label>
                                    <select id="tipo-{{ $i }}" name="perguntas[{{ $i }}][tipo_resposta]" class="q-tipo-select">
                                        @foreach ($labelsTipo as $k => $v)
                                            <option value="{{ $k }}" {{ $tipo === $k ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="q-opcoes-wrap {{ $tipo === 'selecionar' ? '' : 'is-hidden' }}" data-role="opcoes-wrap">
                                <label for="op-{{ $i }}">Opções da lista (uma em cada linha)</label>
                                <textarea id="op-{{ $i }}" name="perguntas[{{ $i }}][opcoes]" rows="4" placeholder="Conforme&#10;Não conforme&#10;Não se aplica">{{ $p['opcoes'] ?? '' }}</textarea>
                            </div>
                            <div class="q-footer">
                                <label><input type="checkbox" name="perguntas[{{ $i }}][resposta_obrigatoria]" value="1" {{ !empty($p['resposta_obrigatoria']) ? 'checked' : '' }}> Obrigatória responder</label>
                                <label><input type="checkbox" name="perguntas[{{ $i }}][descricao_pergunta]" value="1" {{ !empty($p['descricao_pergunta']) ? 'checked' : '' }}> Permitir observação</label>
                                <button type="button" class="btn-remove" onclick="removerPergunta(this)">Remover esta pergunta</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="add-row-label" id="addMoreLabel" @if(count($listaPerguntas) === 0) style="display:none" @endif>Adicionar outra pergunta</div>
                <div class="add-row add-row-after" id="addRowBottom" aria-label="Adicionar mais perguntas" @if(count($listaPerguntas) === 0) style="display:none" @endif>
                    <button type="button" class="btn-add" onclick="adicionarPergunta('sim_nao')">
                        + Sim / Não
                        <span>Mais uma pergunta Sim/Não</span>
                    </button>
                    <button type="button" class="btn-add" onclick="adicionarPergunta('selecionar')">
                        + Lista
                        <span>Mais uma lista de opções</span>
                    </button>
                    <button type="button" class="btn-add" onclick="adicionarPergunta('texto')">
                        + Texto
                        <span>Mais um campo de texto</span>
                    </button>
                </div>

                <details class="advanced">
                    <summary>Ajustes extras — cabeçalho, layout e onde o questionário aparece (opcional)</summary>
                    <div class="advanced-inner">
                        <div class="group">
                            <div style="display:flex;flex-wrap:wrap;gap:14px;">
                                <label style="display:inline-flex;align-items:center;gap:8px;font-weight:500;"><input type="checkbox" name="incluir_cabecalho" value="1" {{ old('incluir_cabecalho', $questionario->incluir_cabecalho) ? 'checked' : '' }}> Incluir cabeçalho na impressão / PDF</label>
                                <label style="display:inline-flex;align-items:center;gap:8px;font-weight:500;"><input type="checkbox" name="incluir_rodape" value="1" {{ old('incluir_rodape', $questionario->incluir_rodape) ? 'checked' : '' }}> Incluir rodapé</label>
                            </div>
                        </div>
                        <div class="group">
                            <label for="perguntas_mesma_linha">Quantas perguntas por linha na tela</label>
                            <select id="perguntas_mesma_linha" name="perguntas_mesma_linha">
                                @for ($n = 1; $n <= 4; $n++)
                                    <option value="{{ $n }}" {{ (int) old('perguntas_mesma_linha', $questionario->perguntas_mesma_linha ?: 1) === $n ? 'selected' : '' }}>{{ $n }} — {{ $n === 1 ? 'uma de cada vez (mais fácil de ler)' : $n . ' por linha' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="group">
                            <label>Onde usar</label>
                            <div class="pill-toggle">
                                <label>
                                    <input type="checkbox" name="exibir_na_os_digital" value="1" {{ old('exibir_na_os_digital', $questionario->exibir_na_os_digital ?? true) ? 'checked' : '' }}>
                                    <span>Mostrar na OS digital (tela do técnico)</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="exibir_nao_respondidas_relatorio" value="1" {{ old('exibir_nao_respondidas_relatorio', $questionario->exibir_nao_respondidas_relatorio ?? true) ? 'checked' : '' }}>
                                    <span>No relatório, mostrar também perguntas ainda sem resposta</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="exibir_pergunta_resposta_mesma_linha" value="1" {{ old('exibir_pergunta_resposta_mesma_linha', $questionario->exibir_pergunta_resposta_mesma_linha) ? 'checked' : '' }}>
                                    <span>Pergunta e resposta na mesma linha (layout mais compacto)</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="questionario_pmoc" value="1" {{ old('questionario_pmoc', $questionario->questionario_pmoc) ? 'checked' : '' }}>
                                    <span>Questionário PMOC</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="habilitar_resposta_equipamento" value="1" {{ old('habilitar_resposta_equipamento', $questionario->habilitar_resposta_equipamento) ? 'checked' : '' }}>
                                    <span>Responder separado por equipamento</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </details>

                <div class="actions">
                    <a href="{{ route('crm.questionarios.index') }}" class="btn btn-ghost">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar questionário</button>
                </div>
            </form>

            <script>window.__LABELS_TIPO = @json($labelsTipo);</script>
            <script>
        const LABELS_TIPO = window.__LABELS_TIPO || {};

        function toggleEmptyState() {
            const c = document.getElementById('perguntasContainer');
            const empty = document.getElementById('emptyPerguntas');
            const bottom = document.getElementById('addRowBottom');
            const bottomLabel = document.getElementById('addMoreLabel');
            const n = c.querySelectorAll('.question.q-card').length;
            if (n === 0) {
                if (!empty) {
                    const d = document.createElement('div');
                    d.className = 'empty-hint';
                    d.id = 'emptyPerguntas';
                    d.innerHTML = 'Nenhuma pergunta ainda.<br>Toque em <strong>Sim / Não</strong>, <strong>Lista</strong> ou <strong>Texto</strong> (acima ou abaixo) para adicionar a primeira.';
                    c.prepend(d);
                }
                if (bottom) bottom.style.display = 'none';
                if (bottomLabel) bottomLabel.style.display = 'none';
            } else {
                if (empty) empty.remove();
                if (bottom) bottom.style.display = '';
                if (bottomLabel) bottomLabel.style.display = '';
            }
        }

        function reindexPerguntas() {
            const cards = document.querySelectorAll('#perguntasContainer .question.q-card');
            cards.forEach((card, index) => {
                card.querySelectorAll('.q-index-num').forEach(el => { el.textContent = String(index + 1); });
                card.querySelectorAll('[name^="perguntas["]').forEach(el => {
                    const n = el.getAttribute('name');
                    if (n) el.setAttribute('name', n.replace(/perguntas\[\d+]/, 'perguntas[' + index + ']'));
                });
                const ta = card.querySelector('textarea[id^="pq-"]');
                if (ta) {
                    ta.id = 'pq-' + index;
                    card.querySelectorAll('label[for^="pq-"]').forEach(l => l.setAttribute('for', 'pq-' + index));
                }
                const op = card.querySelector('textarea[id^="op-"]');
                if (op) {
                    op.id = 'op-' + index;
                    card.querySelectorAll('label[for^="op-"]').forEach(l => l.setAttribute('for', 'op-' + index));
                }
                const sel = card.querySelector('select[id^="tipo-"]');
                if (sel) {
                    sel.id = 'tipo-' + index;
                    card.querySelectorAll('label[for^="tipo-"]').forEach(l => l.setAttribute('for', 'tipo-' + index));
                }
            });
            toggleEmptyState();
        }

        function syncOpcoesVis(card) {
            const wrap = card.querySelector('[data-role="opcoes-wrap"]');
            const hidden = card.querySelector('[data-role="tipo-hidden"]');
            const select = card.querySelector('select.q-tipo-select');
            let tipo = hidden ? hidden.value : (select ? select.value : 'texto');
            if (wrap) wrap.classList.toggle('is-hidden', tipo !== 'selecionar');
        }

        document.getElementById('perguntasContainer')?.addEventListener('change', (e) => {
            const sel = e.target.closest('select.q-tipo-select');
            if (sel) syncOpcoesVis(sel.closest('.q-card'));
        });

        document.querySelectorAll('.question.q-card').forEach(card => syncOpcoesVis(card));

        function adicionarPergunta(tipo) {
            const container = document.getElementById('perguntasContainer');
            const index = container.querySelectorAll('.question.q-card').length;
            const sugestao = tipo === 'sim_nao'
                ? 'Equipamento em funcionamento normal?'
                : (tipo === 'selecionar' ? 'Como está a condição do item?' : 'Descreva o que foi verificado.');
            const opDefault = tipo === 'selecionar' ? 'Conforme\nNão conforme\nNão se aplica' : '';
            const bloco = document.createElement('div');
            bloco.className = 'question q-card';
            bloco.innerHTML = `
                <div class="q-card-head">
                    <span class="q-num">Pergunta <span class="q-index-num">${index + 1}</span></span>
                    <span class="q-badge" data-role="tipo-badge">${LABELS_TIPO[tipo] || tipo}</span>
                </div>
                <input type="hidden" name="perguntas[${index}][tipo_resposta]" value="${tipo}" data-role="tipo-hidden">
                <div class="group" style="margin-bottom:0;">
                    <label for="pq-${index}">O que você quer perguntar?</label>
                    <textarea id="pq-${index}" name="perguntas[${index}][texto]" required placeholder="Ex.: Equipamento em funcionamento normal?"></textarea>
                </div>
                <div class="q-opcoes-wrap ${tipo === 'selecionar' ? '' : 'is-hidden'}" data-role="opcoes-wrap">
                    <label for="op-${index}">Opções da lista (uma em cada linha)</label>
                    <textarea id="op-${index}" name="perguntas[${index}][opcoes]" rows="4" placeholder="Conforme&#10;Não conforme&#10;Não se aplica"></textarea>
                </div>
                <div class="q-footer">
                    <label><input type="checkbox" name="perguntas[${index}][resposta_obrigatoria]" value="1"> Obrigatória responder</label>
                    <label><input type="checkbox" name="perguntas[${index}][descricao_pergunta]" value="1"> Permitir observação</label>
                    <button type="button" class="btn-remove" onclick="removerPergunta(this)">Remover esta pergunta</button>
                </div>
            `;
            container.appendChild(bloco);
            const taNew = bloco.querySelector('textarea[name$="[texto]"]');
            if (taNew) taNew.value = sugestao;
            const opNew = bloco.querySelector('textarea[name$="[opcoes]"]');
            if (opNew && opDefault) opNew.value = opDefault;
            reindexPerguntas();
            syncOpcoesVis(bloco);
            bloco.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            const focusTa = bloco.querySelector('textarea[name$="[texto]"]');
            if (focusTa) {
                focusTa.focus({ preventScroll: true });
                try { focusTa.setSelectionRange(focusTa.value.length, focusTa.value.length); } catch (_) {}
            }
        }

        function removerPergunta(btn) {
            const card = btn.closest('.question.q-card');
            if (card) card.remove();
            reindexPerguntas();
        }

        document.getElementById('formQuestionario')?.addEventListener('submit', (e) => {
            const n = document.querySelectorAll('#perguntasContainer .question.q-card').length;
            if (n < 1) {
                e.preventDefault();
                alert('Adicione pelo menos uma pergunta antes de salvar. Use os botões Sim/Não, Lista ou Texto.');
            }
        });

        toggleEmptyState();
            </script>
        </div>
    </div>

</body>
</html>
