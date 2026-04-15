<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Itens com estoque baixo - Financeiro</title>
    @include('financeiro.partials.styles')
</head>
<body>
    @include('components.sidebar')

    <div class="main-content">
        <div class="top-header">
            <h1>Itens com estoque baixo</h1>
            <div class="header-actions">
                <a class="btn" href="{{ route('crm.financeiro.dashboard') }}">Dashboard</a>
                <a class="btn" href="{{ route('crm.financeiro.estoque.itens') }}">Cadastrar itens</a>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-title">
                    <div>
                        <h3>Filtrar por fornecedor</h3>
                        <div class="subtle">Escolha o fornecedor para emitir a lista ou PDF e enviar para aquele fornecedor via WhatsApp.</div>
                    </div>
                </div>
                <form method="GET" action="{{ route('crm.financeiro.estoque.baixo') }}" class="toolbar" id="formFiltro">
                    <div class="field" style="min-width: 220px;">
                        <label>Fornecedor</label>
                        <select name="fornecedor" id="selectFornecedor">
                            <option value="">Todos os fornecedores</option>
                            @foreach($fornecedores as $f)
                                <option value="{{ $f }}" {{ ($fornecedorFiltro ?? '') === $f ? 'selected' : '' }}>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="actions-inline" style="align-self: end;">
                        <button class="btn btn-primary" type="submit">Filtrar</button>
                        <a class="btn" href="{{ route('crm.financeiro.estoque.baixo') }}">Limpar</a>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-title" style="flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h3>Lista de itens (estoque atual ≤ mínimo)</h3>
                        <div class="subtle">{{ $itens->count() }} item(ns) — Use os botões abaixo para emitir PDF ou enviar via WhatsApp.</div>
                    </div>
                    <div class="actions-inline" style="margin-left: auto;">
                        @php
                            $queryPdf = $fornecedorFiltro ? ['fornecedor' => $fornecedorFiltro] : [];
                        @endphp
                        <a class="btn btn-primary" href="{{ route('crm.financeiro.estoque.baixo.pdf', $queryPdf) }}" target="_blank">📄 Emitir PDF</a>
                        <button type="button" class="btn btn-primary" id="btnWhatsApp" {{ $itens->isEmpty() ? 'disabled' : '' }}>📱 Enviar via WhatsApp</button>
                    </div>
                </div>
                <div class="table-wrap">
                    <table class="table" id="tabelaItens">
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
                                <tr data-nome="{{ e($item->nome) }}" data-qtd="{{ $item->quantidade_atual }}" data-min="{{ $item->estoque_minimo }}" data-unidade="{{ e($item->unidade ?? '-') }}" data-fornecedor="{{ e($item->fornecedor ?? '') }}">
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->codigo ?? '-' }}</td>
                                    <td>{{ $item->fornecedor ?? '-' }}</td>
                                    <td>{{ number_format($item->quantidade_atual, 3, ',', '.') }}</td>
                                    <td>{{ number_format($item->estoque_minimo, 3, ',', '.') }}</td>
                                    <td>{{ $item->unidade ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Nenhum item com estoque baixo no momento. Ajuste o filtro de fornecedor ou cadastre itens em “Cadastrar itens”.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
(function() {
    const btn = document.getElementById('btnWhatsApp');
    const tabela = document.getElementById('tabelaItens');
    if (!btn || !tabela) return;

    btn.addEventListener('click', function() {
        const linhas = tabela.querySelectorAll('tbody tr');
        if (linhas.length === 0) return;
        const partes = ['*Lista de itens com estoque baixo*', ''];
        linhas.forEach(function(tr) {
            const nome = tr.dataset.nome || tr.cells[0]?.textContent?.trim() || '';
            const qtd = tr.dataset.qtd ?? tr.cells[3]?.textContent?.trim() ?? '';
            const min = tr.dataset.min ?? tr.cells[4]?.textContent?.trim() ?? '';
            const un = tr.dataset.unidade || tr.cells[5]?.textContent?.trim() || '';
            if (nome) partes.push('• ' + nome + ' — Atual: ' + qtd + ' | Mín: ' + min + (un && un !== '-' ? ' ' + un : ''));
        });
        const texto = partes.join("\n");
        const url = 'https://wa.me/?text=' + encodeURIComponent(texto);
        window.open(url, '_blank', 'noopener');
    });
})();
    </script>
</body>
</html>
