<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Relatório de Atendimento - O.S #{{ $servico->numero_os ?? $servico->id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; color: #0f172a; display: flex; min-height: 100vh; }
        .main-wrapper { margin-left: 280px; width: calc(100% - 280px); padding: 20px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 2px 10px rgba(15,23,42,.04); }
        .header { padding: 20px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .title { font-size: 22px; font-weight: 800; }
        .subtitle { margin-top: 6px; color: #64748b; font-size: 13px; }
        .status-chip { padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; background: #eef2ff; color: #3730a3; }
        .tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
        .tab-btn { border: 1px solid #cbd5e1; background: #fff; border-radius: 10px; padding: 8px 12px; font-size: 13px; font-weight: 700; color: #334155; cursor: pointer; }
        .tab-btn.active { background: #0f172a; color: #fff; border-color: #0f172a; }
        .tab-pane { display: none; padding: 20px; }
        .tab-pane.active { display: block; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .full { grid-column: 1 / -1; }
        label { font-size: 12px; color: #475569; font-weight: 700; margin-bottom: 5px; display: block; }
        input, textarea, select { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 14px; font-family: inherit; }
        textarea { min-height: 120px; resize: vertical; }
        .btn { border: none; border-radius: 10px; padding: 10px 14px; font-size: 13px; font-weight: 700; cursor: pointer; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-ghost { background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; text-decoration: none; display: inline-block; }
        .actions { margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap; }
        .info-row { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-top: 12px; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px; font-size: 13px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        th { background: #f8fafc; font-size: 12px; text-transform: uppercase; color: #64748b; }
        .anexo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px; margin-top: 14px; }
        .anexo-card { border: 1px solid #e2e8f0; background: #f8fafc; border-radius: 10px; padding: 10px; }
        .small { font-size: 12px; color: #64748b; }
        .alert { margin-top: 10px; font-size: 13px; font-weight: 700; }
        .ok { color: #166534; }
        .error { color: #991b1b; }
        .questionario-card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin-top: 10px; }
        @media (max-width: 1200px) { .main-wrapper { margin-left: 0; width: 100%; } .info-row, .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    @include('components.sidebar')
    <div class="main-wrapper">
        <div class="card header">
            <div>
                <div class="title">Relatório de Atendimento</div>
                <div class="subtitle">O.S #{{ $servico->numero_os ?? $servico->id }} • {{ $servico->cliente->nome ?? '-' }} • {{ $servico->conteudoTipoTarefaParaFormulario() ?: 'Atividade' }}</div>
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
                <span class="status-chip">{{ $servico->status_efetivo === 'pendencia' ? 'Pendência' : $servico->status_operacional_label }}</span>
                <a class="btn btn-ghost" href="{{ route('crm.servicos.relatorio', $servico->id) }}" target="_blank">Abrir impressão</a>
            </div>
        </div>

        <div class="card" style="padding: 14px;">
            <div class="tabs">
                <button class="tab-btn active" data-tab="detalhes">Detalhes</button>
                <button class="tab-btn" data-tab="relato">Relato do usuário</button>
                <button class="tab-btn" data-tab="anexos">Anexos</button>
                <button class="tab-btn" data-tab="questionarios">Questionários</button>
                <button class="tab-btn" data-tab="equipamentos">Equipamentos</button>
                <button class="tab-btn" data-tab="horas">Controle de horas</button>
            </div>

            <div id="detalhes" class="tab-pane active">
                <form id="formDetalhes" class="grid">
                    <div><label>Nº O.S</label><input type="text" value="{{ $servico->numero_os ?? ('OS-' . $servico->id) }}" readonly style="background:#f1f5f9; cursor:not-allowed;" title="Gerado automaticamente"></div>
                    <div><label>Tipo da tarefa</label><input type="text" name="tipo_tarefa" value="{{ $servico->conteudoTipoTarefaParaFormulario() }}"></div>
                    <div>
                        <label>Equipamento vinculado</label>
                        <select name="equipamento_id">
                            <option value="">Sem equipamento</option>
                            @foreach($equipamentosCliente as $equip)
                                <option value="{{ $equip->id }}" {{ (int)$servico->equipamento_id === (int)$equip->id ? 'selected' : '' }}>{{ $equip->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label>Horário agendamento</label><input type="datetime-local" name="horario_agendamento" value="{{ $servico->horarioAgendamentoInputValue() }}"></div>
                    <div><label>Status</label>
                        <select name="status_operacional">
                            @foreach(['pendente' => 'Pendente', 'em_andamento' => 'Em Andamento', 'pausado' => 'Pausado', 'concluido' => 'Concluído', 'cancelado' => 'Cancelado'] as $k => $v)
                                <option value="{{ $k }}" {{ $servico->status_operacional === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label>Entrada <span class="small" style="font-weight:400;">(início serviço / cronômetro)</span></label><input type="datetime-local" name="horario_inicio_execucao" value="{{ $servico->horarioInicioExecucaoInputValue() }}"></div>
                    <div><label>Saída <span class="small" style="font-weight:400;">(fim serviço / cronômetro)</span></label><input type="datetime-local" name="horario_fim_execucao" value="{{ $servico->horarioFimExecucaoInputValue() }}"></div>
                    <div><label>Início deslocamento</label><input type="time" name="inicio_deslocamento" value="{{ $servico->inicio_deslocamento ? \Carbon\Carbon::parse($servico->inicio_deslocamento)->format('H:i') : '' }}"></div>
                    <div><label>Duração deslocamento (min)</label><input type="number" min="0" name="duracao_deslocamento_minutos" value="{{ $servico->duracao_deslocamento_minutos ?? '' }}"></div>
                    <div class="full"><label>Orientação / instruções</label><textarea name="orientacao">{{ $servico->conteudoOrientacaoParaFormulario() }}</textarea></div>
                    <div class="full"><label>Palavras-chave / descrição</label><input type="text" name="descricao" value="{{ $servico->descricao }}"></div>
                </form>
                <div class="actions">
                    <button class="btn btn-primary" onclick="submitForm('formDetalhes', '{{ route('crm.relatorios.detalhes', $servico->id) }}')">Salvar Detalhes</button>
                </div>
                <div id="msgDetalhes" class="alert"></div>
            </div>

            <div id="relato" class="tab-pane">
                <form id="formRelato" class="grid">
                    <div class="full">
                        <label>Relato de execução (máx. 5000)</label>
                        <textarea id="relatoTexto" name="relato_execucao" maxlength="5000">{{ $servico->relato_execucao }}</textarea>
                        <div class="small" id="relatoContador">0/5000</div>
                    </div>
                    <div class="full"><label>Funcionamento geral</label><input type="text" name="checklist_pmoc[funcionamento_geral]" value="{{ $servico->checklist_pmoc['funcionamento_geral'] ?? '' }}"></div>
                    <div><label>Avaliação final</label><input type="text" name="checklist_pmoc[avaliacao_final]" value="{{ $servico->checklist_pmoc['avaliacao_final'] ?? '' }}"></div>
                    <div><label>Colaboradores envolvidos</label><input type="text" name="checklist_pmoc[colaboradores]" value="{{ $servico->checklist_pmoc['colaboradores'] ?? $servico->tecnicos->pluck('nome_profissional')->join(', ') }}"></div>
                    <div class="full"><label>Observações</label><textarea name="checklist_pmoc[obs]">{{ $servico->checklist_pmoc['obs'] ?? '' }}</textarea></div>
                </form>
                <div class="actions">
                    <button class="btn btn-primary" onclick="submitForm('formRelato', '{{ route('crm.relatorios.relato', $servico->id) }}')">Salvar Relato</button>
                    <button class="btn btn-success" onclick="finalizarServico()">Finalizar Serviço</button>
                </div>
                <div id="msgRelato" class="alert"></div>
            </div>

            <div id="anexos" class="tab-pane">
                <form id="formAnexos" class="grid" enctype="multipart/form-data">
                    <div><label>Arquivos</label><input type="file" name="anexos[]" multiple></div>
                    <div><label>Fotos</label><input type="file" name="fotos[]" multiple accept="image/*"></div>
                </form>
                <div class="actions">
                    <button class="btn btn-primary" onclick="submitForm('formAnexos', '{{ route('crm.relatorios.anexos', $servico->id) }}', true)">Adicionar anexos/fotos</button>
                </div>
                <div id="msgAnexos" class="alert"></div>

                <div class="anexo-grid">
                    @foreach($servico->anexos as $anexo)
                        <div class="anexo-card">
                            <div><strong>{{ $anexo->nome_original }}</strong></div>
                            <div class="small">Tipo: {{ ucfirst($anexo->tipo) }}</div>
                            <div class="small">Por: {{ $anexo->usuario->name ?? 'Usuário' }} • {{ $anexo->created_at?->format('d/m/Y H:i') }}</div>
                            <div class="actions">
                                <a class="btn btn-ghost" href="{{ asset('storage/' . $anexo->path) }}" target="_blank">Visualizar</a>
                                <button class="btn btn-danger" onclick="removerAnexo('{{ route('crm.relatorios.anexos.destroy', [$servico->id, $anexo->id]) }}')">Remover</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="questionarios" class="tab-pane">
                <div class="grid">
                    <div>
                        <label>Vincular questionário existente</label>
                        <select id="questionario_id">
                            <option value="">Selecione...</option>
                            @foreach($questionariosDisponiveis as $q)
                                <option value="{{ $q->id }}">{{ $q->titulo }} ({{ $q->perguntas_count }} perguntas)</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex; align-items:end; gap:8px;">
                        <button class="btn btn-primary" onclick="vincularQuestionario()">Adicionar Questionário</button>
                        <a class="btn btn-ghost" href="{{ route('crm.questionarios.index') }}">Gerenciar biblioteca</a>
                    </div>
                </div>
                <div id="msgQuestionarios" class="alert"></div>

                @foreach($servico->questionariosVinculados as $vinculo)
                    <div class="questionario-card">
                        <div style="font-weight: 700; margin-bottom: 8px;">{{ $vinculo->questionario->titulo }}</div>
                        <form id="formRespostas{{ $vinculo->id }}" class="grid">
                            @foreach($vinculo->questionario->perguntas as $pergunta)
                                @php
                                    $mapaResp = is_array($vinculo->respostas) ? $vinculo->respostas : [];
                                    $resp = $mapaResp[$pergunta->id] ?? $mapaResp[(string) $pergunta->id] ?? '';
                                @endphp
                                <div class="full">
                                    <label>{{ $pergunta->ordem }}) {{ $pergunta->texto }} {{ $pergunta->resposta_obrigatoria ? '*' : '' }}</label>
                                    @if($pergunta->tipo_resposta === 'sim_nao')
                                        <select name="respostas[{{ $pergunta->id }}]">
                                            <option value="">Selecione...</option>
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
                                            <option value="">Selecione...</option>
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
                            <button class="btn btn-primary" onclick="submitForm('formRespostas{{ $vinculo->id }}', '{{ route('crm.relatorios.questionarios.respostas', [$servico->id, $vinculo->id]) }}')">Salvar respostas</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="equipamentos" class="tab-pane">
                <div class="info-row">
                    <div class="info-box"><strong>Equipamento:</strong><br>{{ $servico->equipamento->nome ?? '-' }}</div>
                    <div class="info-box"><strong>Cliente:</strong><br>{{ $servico->cliente->nome ?? '-' }}</div>
                    <div class="info-box"><strong>Técnicos:</strong><br>{{ $servico->tecnicos->pluck('nome_profissional')->join(', ') ?: '-' }}</div>
                    <div class="info-box"><strong>Código VE:</strong><br>{{ $servico->codigo_ve ?? '-' }}</div>
                </div>
            </div>

            <div id="horas" class="tab-pane">
                <form id="formHoras" class="grid">
                    <div>
                        <label>Colaboradores (aceita dupla/equipe)</label>
                        <select name="colaborador_ids[]" multiple size="5">
                            @foreach($colaboradores as $c)
                                <option value="{{ $c->id }}">{{ $c->nome_profissional }}</option>
                            @endforeach
                        </select>
                        <div class="small">Segure Ctrl (Windows) para selecionar mais de um.</div>
                    </div>
                    <div>
                        <label>Monitoramento</label>
                        <select name="monitoramento">
                            <option value="check_in">Check-In</option>
                            <option value="check_out">Check-Out</option>
                            <option value="pausa">Pausa</option>
                            <option value="retorno">Retorno</option>
                            <option value="ajuste">Ajuste manual</option>
                        </select>
                    </div>
                    <div><label>Horário</label><input type="datetime-local" name="horario" value="{{ now()->format('Y-m-d\TH:i') }}"></div>
                    <div><label>Motivo</label><input type="text" name="motivo" placeholder="Obrigatório em pausa e ajuste"></div>
                    <div class="full"><label>Justificativa</label><textarea name="justificativa" placeholder="Obrigatório em ajuste"></textarea></div>
                </form>
                <div class="actions"><button class="btn btn-primary" onclick="submitForm('formHoras', '{{ route('crm.relatorios.horas', $servico->id) }}')">Adicionar atividade</button></div>
                <div id="msgHoras" class="alert"></div>

                @php
                    $liqPorColab = $servico->minutosTrabalhoLiquidosPorColaborador();
                    $liqTotal = $servico->minutosTrabalhoLiquidosTotal();
                    $somaBruta = (int) $servico->horas->sum('tempo_corrido_minutos');
                @endphp
                <div class="info-row" style="margin-top: 16px;">
                    <div class="info-box" style="grid-column: 1 / -1;">
                        <strong>Tempo efetivo de trabalho</strong> <span class="small">(soma dos trechos em serviço, <strong>sem</strong> contar intervalo entre <em>Pausa</em> e <em>Retorno</em>)</span>
                        <div style="font-size: 18px; font-weight: 800; margin-top: 8px; color: #0f172a;">
                            {{ \App\Models\Servico::formatarDuracaoMinutos($liqTotal) }}
                        </div>
                        @if($liqTotal > 0)
                            <div class="small" style="margin-top: 10px;">
                                @foreach($liqPorColab as $cid => $min)
                                    @if((int) $min <= 0)
                                        @continue
                                    @endif
                                    @php
                                        if ($cid === '__sem_colab__') {
                                            $nomeCol = 'Sem colaborador no registro';
                                        } else {
                                            $hr = $servico->horas->firstWhere('colaborador_id', (int) $cid);
                                            $nomeCol = $hr && $hr->colaborador ? $hr->colaborador->nome_profissional : ('Colaborador #' . $cid);
                                        }
                                    @endphp
                                    <div>{{ $nomeCol }}: <strong>{{ \App\Models\Servico::formatarDuracaoMinutos((int) $min) }}</strong></div>
                                @endforeach
                            </div>
                        @endif
                        @if($somaBruta > 0 && $somaBruta !== $liqTotal)
                            <div class="small" style="margin-top: 8px; color: #64748b;">Referência — soma bruta dos “tempo corrido” na tabela: {{ \App\Models\Servico::formatarDuracaoMinutos($somaBruta) }} (inclui pausas entre registros)</div>
                        @endif
                    </div>
                </div>

                <div class="table-wrap" style="margin-top: 14px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Colaborador</th>
                                <th>Monitoramento</th>
                                <th>Horário</th>
                                <th>Tempo corrido</th>
                                <th>Motivo</th>
                                <th>Justificativa</th>
                                <th>Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servico->horas as $hora)
                                <tr>
                                    <td>{{ $hora->colaborador->nome_profissional ?? '-' }}</td>
                                    <td>{{ $hora->monitoramento_label }}</td>
                                    <td>{{ $hora->horario?->format('d/m/Y H:i') }}</td>
                                    <td>{{ $hora->tempo_corrido_minutos ? $hora->tempo_corrido_minutos . ' min' : '-' }}</td>
                                    <td>{{ $hora->motivo ?: '-' }}</td>
                                    <td>{{ $hora->justificativa ?: '-' }}</td>
                                    <td>{{ $hora->usuario->name ?? '-' }} em {{ $hora->created_at?->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7">Sem registros ainda.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

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
            const update = () => contador.textContent = `${relato.value.length}/5000`;
            relato.addEventListener('input', update);
            update();
        }

        function showMsg(id, ok, text) {
            const el = document.getElementById(id);
            if (!el) return;
            el.className = `alert ${ok ? 'ok' : 'error'}`;
            el.textContent = text;
        }

        async function submitForm(formId, url, isMultipart = false) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            const msgId = {
                formDetalhes: 'msgDetalhes',
                formRelato: 'msgRelato',
                formAnexos: 'msgAnexos',
                formHoras: 'msgHoras',
            }[formId] || 'msgQuestionarios';

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
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Erro ao salvar.');
                }
                showMsg(msgId, true, data.message || 'Salvo com sucesso.');
                if (formId === 'formAnexos' || formId === 'formHoras') {
                    setTimeout(() => window.location.reload(), 700);
                }
            } catch (error) {
                showMsg(msgId, false, error.message);
            }
        }

        async function removerAnexo(url) {
            if (!confirm('Deseja remover este anexo?')) return;
            const response = await fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.success) window.location.reload();
        }

        async function vincularQuestionario() {
            const id = document.getElementById('questionario_id').value;
            if (!id) return;
            const formData = new FormData();
            formData.append('questionario_id', id);
            const response = await fetch('{{ route('crm.relatorios.questionarios.vincular', $servico->id) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                showMsg('msgQuestionarios', true, data.message);
                setTimeout(() => window.location.reload(), 700);
            } else {
                showMsg('msgQuestionarios', false, data.message || 'Erro ao vincular questionário.');
            }
        }

        function finalizarServico() {
            const form = document.getElementById('formRelato');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status_operacional';
            input.value = 'concluido';
            form.appendChild(input);
            submitForm('formRelato', '{{ route('crm.relatorios.relato', $servico->id) }}');
        }
    </script>
</body>
</html>
