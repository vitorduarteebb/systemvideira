<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $certificado->nome_participante ?? 'Participante' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            background: white;
            width: 297mm;
            height: 210mm;
            position: relative;
            overflow: hidden;
        }

        /* Borda decorativa dourada */
        .certificate-border {
            position: absolute;
            top: 15mm;
            left: 15mm;
            right: 15mm;
            bottom: 15mm;
            border: 3mm solid #d4af37;
            border-radius: 5mm;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
        }

        .certificate-content {
            position: relative;
            padding: 20mm;
            height: 100%;
            display: flex;
            flex-direction: column;
            z-index: 1;
        }

        /* Cabeçalho */
        .certificate-header {
            text-align: center;
            margin-bottom: 15mm;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #10b981 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .company-info {
            font-size: 10px;
            color: #4b5563;
            margin-top: 5px;
            line-height: 1.4;
        }

        .qr-code {
            position: absolute;
            top: 20mm;
            right: 20mm;
            width: 40px;
            height: 40px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #6b7280;
        }

        /* Corpo do certificado */
        .certificate-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            padding: 0 20mm;
        }

        .certification-text {
            font-size: 16px;
            color: #374151;
            margin-bottom: 15mm;
        }

        .participant-name {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 15mm;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .completion-text {
            font-size: 16px;
            color: #374151;
            margin-bottom: 10mm;
        }

        /* Título do curso com quebra de linha centralizada */
        .course-title {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 10mm 0 15mm 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.6;
            max-width: 100%;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .course-title-line {
            display: block;
            text-align: center;
            width: 100%;
            margin: 2px 0;
        }

        /* Detalhes do treinamento */
        .training-details {
            text-align: left;
            margin: 15mm auto;
            max-width: 200mm;
            font-size: 12px;
            color: #4b5563;
            line-height: 2;
        }

        .training-details div {
            margin-bottom: 5px;
        }

        .detail-label {
            font-weight: bold;
            color: #1e3a8a;
            display: inline-block;
            min-width: 140px;
        }

        /* Rodapé com assinaturas */
        .certificate-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20mm;
            padding-top: 15mm;
            border-top: 1px solid #d1d5db;
        }

        .signature-box {
            flex: 1;
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #1e3a8a;
            width: 120mm;
            margin: 0 auto 5mm auto;
            padding-top: 5mm;
        }

        .signature-name {
            font-weight: bold;
            color: #1e3a8a;
            font-size: 12px;
        }

        .signature-role {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-border"></div>
    
    <div class="certificate-content">
        <!-- Cabeçalho -->
        <div class="certificate-header">
            <div class="logo-container">
                <div class="logo">PL</div>
                <div>
                    <div class="company-name">PAES LEME</div>
                </div>
            </div>
            <div class="company-info">
                Av. Padre Antônio Vann Ess, 1884 – Rosário Pirassununga / SP<br>
                CNPJ: 24.010.432/0001-41 / CREA SP 2157182<br>
                Tel / Whats: (19) 9.9642-4996
            </div>
            <div class="qr-code">QR CODE</div>
        </div>

        <!-- Corpo do Certificado -->
        <div class="certificate-body">
            <div class="certification-text">Certificamos que</div>
            
            <div class="participant-name">{{ $certificado->nome_participante ?? 'NOME DO PARTICIPANTE' }}</div>
            
            <div class="completion-text">concluiu com aproveitamento o treinamento</div>
            
            <!-- Título do curso com quebra de linha centralizada -->
            <div class="course-title" id="courseTitle">
                @php
                    $titulo = $certificado->titulo_curso ?? 'BRIGADA DE INCÊNDIO CONFORME RISCOS DA UNILEVER BRASIL HIGIENE PESSOAL E LIMPEZA LTD.';
                    // Quebrar o título em linhas de aproximadamente 50-55 caracteres, mantendo palavras inteiras
                    $palavras = explode(' ', $titulo);
                    $linhas = [];
                    $linhaAtual = '';
                    $maxCaracteres = 55; // Ajustado para melhor visualização
                    
                    foreach ($palavras as $palavra) {
                        $testeLinha = $linhaAtual ? $linhaAtual . ' ' . $palavra : $palavra;
                        
                        if (mb_strlen($testeLinha, 'UTF-8') <= $maxCaracteres) {
                            $linhaAtual = $testeLinha;
                        } else {
                            if ($linhaAtual) {
                                $linhas[] = trim($linhaAtual);
                            }
                            $linhaAtual = $palavra;
                        }
                    }
                    if ($linhaAtual) {
                        $linhas[] = trim($linhaAtual);
                    }
                    
                    // Se não quebrou em nenhuma linha, manter o título original
                    if (empty($linhas)) {
                        $linhas = [$titulo];
                    }
                @endphp
                @foreach($linhas as $linha)
                    <span class="course-title-line">{{ $linha }}</span>
                @endforeach
            </div>

            <!-- Detalhes do Treinamento -->
            <div class="training-details">
                <div><span class="detail-label">Carga horária:</span> {{ $certificado->carga_horaria ?? '12 horas' }}</div>
                <div><span class="detail-label">Data de realização:</span> {{ $certificado->data_realizacao ? \Carbon\Carbon::parse($certificado->data_realizacao)->format('d/m/Y') : date('d/m/Y') }}</div>
                <div><span class="detail-label">Local:</span> {{ $certificado->local ?? 'DO CENTRO DE TREINAMENTO' }}</div>
                <div><span class="detail-label">Instrutor Responsável:</span> {{ $certificado->instrutor_nome ?? 'ADMINISTRADOR' }} @if($certificado->instrutor_registro)(Registro nº {{ $certificado->instrutor_registro }} / SP)@endif</div>
                <div><span class="detail-label">Cidade/UF:</span> {{ $certificado->cidade ?? 'Pirassununga' }} - {{ $certificado->uf ?? 'SP' }}</div>
                <div><span class="detail-label">Certificado nº:</span> {{ $certificado->numero_certificado ?? 'PL-' . date('Y') . '-XXXXX' }}</div>
                <div><span class="detail-label">Validade:</span> {{ $certificado->validade ?? 'Conforme periodicidade da NR aplicável' }}</div>
            </div>
        </div>

        <!-- Rodapé com Assinaturas -->
        <div class="certificate-footer">
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-name">{{ $certificado->nome_participante ?? 'NOME DO PARTICIPANTE' }}</div>
                    <div class="signature-role">Participante</div>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-name">{{ $certificado->instrutor_nome ?? 'ADMINISTRADOR' }}</div>
                    <div class="signature-role">Administrador</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
