<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Proposta {{ $proposta->codigo_proposta ?? $proposta->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111827; line-height: 1.5; }
        .page { padding: 32px 40px; }
        .header { border-bottom: 2px solid #111827; padding-bottom: 10px; margin-bottom: 16px; }
        .header-title { font-size: 20px; font-weight: bold; }
        .header-sub { font-size: 11px; color: #6b7280; margin-top: 4px; }
        .section { margin-bottom: 16px; }
        .section-title { font-size: 13px; font-weight: bold; margin-bottom: 6px; border-bottom: 1px solid #e5e7eb; padding-bottom: 3px; }
        .grid-2 { display: table; width: 100%; }
        .grid-2 .col { display: table-cell; width: 50%; vertical-align: top; padding-right: 12px; }
        .label { font-size: 11px; color: #6b7280; }
        .value { font-size: 12px; font-weight: 600; margin-bottom: 4px; }
        .box { border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; white-space: pre-wrap; }
        .footer { margin-top: 24px; font-size: 10px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 6px; }
        .mt-2 { margin-top: 8px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="header-title">Proposta {{ $proposta->codigo_proposta ?? $proposta->id }}</div>
            <div class="header-sub">
                Cliente: {{ $proposta->cliente->nome ?? '-' }}<br>
                Data: {{ optional($proposta->data_criacao)->format('d/m/Y') ?? $proposta->created_at->format('d/m/Y') }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">Resumo</div>
            <div class="grid-2">
                <div class="col">
                    <div class="label">Título</div>
                    <div class="value">{{ $proposta->titulo ?: 'Proposta comercial' }}</div>
                </div>
                <div class="col">
                    <div class="label">Valor</div>
                    <div class="value">
                        R$ {{ number_format((float)($proposta->valor_final ?? 0), 2, ',', '.') }}
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <div class="label">Status</div>
                <div class="value" style="text-transform: capitalize;">
                    {{ str_replace('_', ' ', $proposta->estado ?? '—') }}
                </div>
            </div>
        </div>

        @if($proposta->descricao_inicial)
            <div class="section">
                <div class="section-title">Escopo / Observações</div>
                <div class="box">{{ $proposta->descricao_inicial }}</div>
            </div>
        @endif

        @if($proposta->configuracoes_tecnicas)
            <div class="section">
                <div class="section-title">Configurações técnicas</div>
                <div class="box">{{ $proposta->configuracoes_tecnicas }}</div>
            </div>
        @endif

        <div class="footer">
            Gerado pelo Sistema VIDEIRA em {{ now()->format('d/m/Y H:i') }}.
        </div>
    </div>
</body>
</html>

