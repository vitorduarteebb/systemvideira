<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Itens com estoque baixo - {{ $fornecedorLabel }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; color: #1a1a1a; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        .subtle { color: #666; font-size: 11px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 8px 10px; text-align: left; }
        th { background: #f0f0f0; font-weight: 700; }
        tr:nth-child(even) { background: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Lista de itens com estoque baixo</h1>
    <p class="subtle">Fornecedor: {{ $fornecedorLabel }} — Emitido em {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Código</th>
                <th>Fornecedor</th>
                <th>Qtd. atual</th>
                <th>Estoque mínimo</th>
                <th>Unidade</th>
            </tr>
        </thead>
        <tbody>
            @forelse($itens as $item)
                <tr>
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->codigo ?? '-' }}</td>
                    <td>{{ $item->fornecedor ?? '-' }}</td>
                    <td>{{ number_format($item->quantidade_atual, 3, ',', '.') }}</td>
                    <td>{{ number_format($item->estoque_minimo, 3, ',', '.') }}</td>
                    <td>{{ $item->unidade ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Nenhum item com estoque baixo.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
