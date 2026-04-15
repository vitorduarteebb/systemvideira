<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Atendimento - {{ $servico->numero_os ?? 'Nº ' . $servico->id }}</title>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .empresa-info {
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .info-item {
            font-size: 13px;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 3px;
        }
        
        .info-value {
            color: #333;
        }
        
        .horarios-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .horario-item {
            font-size: 12px;
        }
        
        .relato {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            white-space: pre-wrap;
            font-size: 13px;
            line-height: 1.8;
        }
        
        .checklist {
            margin-top: 20px;
        }
        
        .checklist-item {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .checklist-question {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
            color: #333;
        }
        
        .checklist-answer {
            font-size: 12px;
            color: #666;
            margin-left: 20px;
        }
        
        .fotos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 20px;
        }
        
        .foto-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            aspect-ratio: 1;
        }
        
        .foto-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .assinatura {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .assinatura-img {
            max-width: 300px;
            margin: 0 auto 10px;
            opacity: 0.8;
        }
        
        .assinatura-nome {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
        
        .action-buttons {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-print {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-print:hover {
            background: #d0d0d0;
        }
        
        .btn-pdf {
            background: #7b2cbf;
            color: white;
        }
        
        .btn-pdf:hover {
            background: #6a1b9a;
        }
        
        .equipamento-info {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        
        .empty-value {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <h1>Relatório de Atendimento</h1>
            <div class="empresa-info">
                <strong>{{ $empresa['nome'] }}</strong><br>
                Telefone: {{ $empresa['telefone'] }}<br>
                CNPJ: {{ $empresa['cnpj'] }}<br>
                Email: {{ $empresa['email'] }}<br>
                Endereço: {{ $empresa['endereco'] }}
            </div>
        </div>

        <!-- Informações do Cliente -->
        <div class="section">
            <div class="section-title">Informações do Cliente</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Cliente</div>
                    <div class="info-value">{{ $servico->cliente->nome ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">CPF/CNPJ</div>
                    <div class="info-value">{{ $servico->cliente->cnpj ?? $servico->cliente->cpf ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">E-mail</div>
                    <div class="info-value">{{ $servico->cliente->email ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Telefone</div>
                    <div class="info-value">{{ $servico->cliente->telefone ?? '-' }}</div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Endereço</div>
                    <div class="info-value">{{ $servico->cliente->endereco_completo ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Falar com</div>
                    <div class="info-value">{{ $servico->cliente->nome ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Informações do Serviço -->
        @php
            $relNomeTec = $servico->nomeTecnicoResponsavelRelatorio();
            $relDtAg = $servico->dataHoraAgendamentoRelatorio();
            $relTipo = $servico->textoTipoTarefaRelatorio();
            $relOrient = $servico->textoOrientacaoRelatorio();
        @endphp
        <div class="section">
            <div class="section-title">Informações do Serviço</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nº O.S</div>
                    <div class="info-value">#{{ $servico->numero_os ?? $servico->id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Técnico responsável</div>
                    <div class="info-value">
                        @if($relNomeTec !== '-')
                            {{ $relNomeTec }}
                        @else
                            <span class="empty-value">-</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Horário agendamento</div>
                    <div class="info-value">
                        @if($relDtAg)
                            {{ $relDtAg->format('d/m/Y \à\s H:i') }}
                        @else
                            <span class="empty-value">-</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tipo de tarefa</div>
                    <div class="info-value">
                        @if($relTipo !== '-')
                            {{ $relTipo }}
                        @else
                            <span class="empty-value">-</span>
                        @endif
                    </div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Orientação</div>
                    <div class="info-value">
                        @if($relOrient !== '-')
                            {{ $relOrient }}
                        @else
                            <span class="empty-value">-</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Horários -->
        <div class="section">
            <div class="section-title">Horários</div>
            @php
                $entradaRelatorio = $servico->horario_inicio_execucao ?? $servico->horario_chegada;
                $saidaRelatorio = $servico->horario_fim_execucao ?? $servico->horario_saida;
            @endphp
            <div class="horarios-grid">
                <div class="horario-item">
                    <strong>Horário de entrada</strong>
                    <span style="font-weight:400;color:#64748b;"> (início do serviço / cronômetro):</span>
                    @if($entradaRelatorio)
                        {{ $entradaRelatorio->format('d/m/Y \à\s H:i:s') }}
                    @else
                        <span class="empty-value">-</span>
                    @endif
                </div>
                <div class="horario-item">
                    <strong>Horário de saída</strong>
                    <span style="font-weight:400;color:#64748b;"> (fim do serviço / cronômetro parado):</span>
                    @if($saidaRelatorio)
                        {{ $saidaRelatorio->format('d/m/Y \à\s H:i:s') }}
                    @else
                        <span class="empty-value">-</span>
                    @endif
                </div>
                <div class="horario-item">
                    <strong>Início do deslocamento:</strong> 
                    @if($servico->inicio_deslocamento)
                        {{ \Carbon\Carbon::parse($servico->inicio_deslocamento)->format('H:i') }}
                    @else
                        <span class="empty-value">-</span>
                    @endif
                </div>
                <div class="horario-item">
                    <strong>Duração do deslocamento:</strong> 
                    @if($servico->duracao_deslocamento_minutos)
                        {{ $servico->duracao_deslocamento_minutos }} minutos
                    @else
                        <span class="empty-value">-</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Relato de Execução -->
        @if($servico->relato_execucao)
        <div class="section">
            <div class="section-title">Relato de Execução</div>
            <div class="relato">{{ $servico->relato_execucao }}</div>
        </div>
        @endif

        <!-- Equipamento -->
        @if($servico->equipamento)
        <div class="section">
            <div class="section-title">Equipamento</div>
            <div class="equipamento-info">
                <div class="info-item">
                    <div class="info-label">Nome/Identificação</div>
                    <div class="info-value">{{ $servico->equipamento->nome ?? '-' }}</div>
                </div>
                @if($servico->equipamento->identificador)
                <div class="info-item">
                    <div class="info-label">Identificador</div>
                    <div class="info-value">{{ $servico->equipamento->identificador }}</div>
                </div>
                @endif
                @if($servico->equipamento->tipo)
                <div class="info-item">
                    <div class="info-label">Tipo</div>
                    <div class="info-value">{{ $servico->equipamento->tipo }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Questionários vinculados (checklist PMOC = perguntas e respostas) -->
        @if($servico->questionariosVinculados->isNotEmpty())
        <div class="section">
            <div class="section-title">Questionário / checklist PMOC</div>
            <p style="font-size: 12px; color: #666; margin: -8px 0 16px 0;">Checklist preenchido conforme os questionários vinculados ao serviço.</p>
            @foreach($servico->questionariosVinculados as $vinculo)
                @if($vinculo->questionario)
                <div class="checklist" style="margin-bottom: 24px;">
                    <div class="checklist-item" style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 12px;">
                        <div class="checklist-question" style="font-size: 14px;">{{ $vinculo->questionario->titulo }}</div>
                        @if($vinculo->updated_at)
                            <div class="checklist-answer" style="font-size: 11px; color: #666; margin-top: 4px;">
                                Atualizado em {{ $vinculo->updated_at->format('d/m/Y H:i') }}
                                @if($vinculo->usuario)
                                    — {{ $vinculo->usuario->name }}
                                @endif
                            </div>
                        @endif
                    </div>
                    @foreach($vinculo->questionario->perguntas as $pergunta)
                        @php
                            $mapa = is_array($vinculo->respostas) ? $vinculo->respostas : [];
                            $bruto = $mapa[$pergunta->id] ?? $mapa[(string) $pergunta->id] ?? null;
                            if (is_array($bruto)) {
                                $textoResposta = implode(', ', array_map(static fn ($x) => is_scalar($x) ? (string) $x : json_encode($x), $bruto));
                            } else {
                                $textoResposta = $bruto !== null && $bruto !== '' ? (string) $bruto : '';
                            }
                            $ocultarSemResposta = $vinculo->questionario->exibir_nao_respondidas_relatorio === false;
                            $exibirLinha = $textoResposta !== '' || ! $ocultarSemResposta;
                        @endphp
                        @if($exibirLinha)
                        <div class="checklist-item">
                            <div class="checklist-question">{{ $pergunta->ordem }}) {{ $pergunta->texto }}</div>
                            <div class="checklist-answer">{{ $textoResposta !== '' ? $textoResposta : '—' }}</div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
            @endforeach
        </div>
        @endif

        <!-- Campos extras do formulário de relatório (legado), quando não há só questionário -->
        @if($servico->checklist_pmoc && is_array($servico->checklist_pmoc) && count($servico->checklist_pmoc) > 0)
        <div class="section">
            <div class="section-title">
                @if($servico->questionariosVinculados->isNotEmpty())
                    Registro complementar (formulário)
                @else
                    Checklist PMOC
                @endif
            </div>
            <div class="checklist">
                @foreach($servico->checklist_pmoc as $pergunta => $resposta)
                <div class="checklist-item">
                    <div class="checklist-question">{{ is_string($pergunta) ? str_replace('_', ' ', ucfirst($pergunta)) : $pergunta }}</div>
                    <div class="checklist-answer">{{ is_array($resposta) ? json_encode($resposta, JSON_UNESCAPED_UNICODE) : $resposta }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Fotos -->
        @if($servico->fotos && is_array($servico->fotos) && count($servico->fotos) > 0)
        <div class="section">
            <div class="section-title">Fotos</div>
            <div class="fotos-grid">
                @foreach($servico->fotos as $foto)
                <div class="foto-item">
                    @if(is_string($foto) && str_starts_with($foto, 'data:image'))
                        <img src="{{ $foto }}" alt="Foto do serviço">
                    @elseif(is_string($foto))
                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto do serviço">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Assinatura -->
        @if($servico->assinatura_base64)
        <div class="assinatura">
            <img src="{{ $servico->assinatura_base64 }}" alt="Assinatura" class="assinatura-img">
            <div class="assinatura-nome">
                Assinado por: {{ $relNomeTec !== '-' ? $relNomeTec : 'Usuário' }}
            </div>
        </div>
        @endif

        <!-- Botões de Ação -->
        <div class="action-buttons no-print">
            <button class="btn btn-print" onclick="window.print()">
                🖨️ Imprimir
            </button>
            <a href="{{ route('crm.servicos.relatorio.pdf', $servico->id) }}" class="btn btn-pdf">
                📥 Download do PDF
            </a>
        </div>
    </div>
</body>
</html>
