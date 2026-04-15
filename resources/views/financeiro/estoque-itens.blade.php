<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cadastro de itens de estoque - Financeiro</title>
    @include('financeiro.partials.styles')
</head>
<body>
    @include('components.sidebar')

    <div class="main-content">
        <div class="top-header">
            <h1>Cadastro de itens de estoque</h1>
            <div class="header-actions">
                <a class="btn btn-primary" href="{{ route('crm.financeiro.estoque.baixo') }}">Ver estoque baixo</a>
                <a class="btn" href="{{ route('crm.financeiro.dashboard') }}">Dashboard</a>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="card">
                <div class="card-title">
                    <div>
                        <h3>Adicionar item</h3>
                        <div class="subtle">Preencha e salve. Itens com quantidade atual ≤ estoque mínimo aparecem em “Itens com estoque baixo”.</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('crm.financeiro.estoque.itens.store') }}" class="toolbar" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; max-width: 900px;">
                    @csrf
                    <div class="field">
                        <label>Nome *</label>
                        <input type="text" name="nome" required placeholder="Ex: Filtro de ar">
                    </div>
                    <div class="field">
                        <label>Código</label>
                        <input type="text" name="codigo" placeholder="Ex: FIL-001">
                    </div>
                    <div class="field">
                        <label>Fornecedor</label>
                        <input type="text" name="fornecedor" list="listFornecedores" placeholder="Nome do fornecedor">
                        <datalist id="listFornecedores">
                            @foreach($fornecedores as $f)
                                <option value="{{ $f }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="field">
                        <label>Qtd. atual *</label>
                        <input type="number" name="quantidade_atual" step="0.001" min="0" value="0" required>
                    </div>
                    <div class="field">
                        <label>Estoque mínimo *</label>
                        <input type="number" name="estoque_minimo" step="0.001" min="0" value="0" required>
                    </div>
                    <div class="field">
                        <label>Unidade</label>
                        <input type="text" name="unidade" placeholder="Ex: un, cx, kg">
                    </div>
                    <div class="actions-inline" style="align-self: end;">
                        <button class="btn btn-primary" type="submit">Adicionar</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-title">
                    <div>
                        <h3>Itens cadastrados</h3>
                        <div class="subtle">Edite ou remova itens. Filtre por fornecedor ou busca.</div>
                    </div>
                </div>
                <form method="GET" class="toolbar" style="margin-bottom: 14px;">
                    <div class="field">
                        <label>Fornecedor</label>
                        <select name="fornecedor">
                            <option value="">Todos</option>
                            @foreach($fornecedores as $f)
                                <option value="{{ $f }}" {{ request('fornecedor') === $f ? 'selected' : '' }}>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Busca (nome/código)</label>
                        <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Buscar">
                    </div>
                    <div class="actions-inline" style="align-self: end;">
                        <button class="btn btn-primary" type="submit">Filtrar</button>
                        <a class="btn" href="{{ route('crm.financeiro.estoque.itens') }}">Limpar</a>
                    </div>
                </form>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Código</th>
                                <th>Fornecedor</th>
                                <th>Qtd.</th>
                                <th>Mín.</th>
                                <th>Un.</th>
                                <th>Ações</th>
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
                                    <td>
                                        <button type="button" class="btn btn-ghost" style="padding: 6px 10px; font-size: 11px;" onclick="editarItem('{{ route('crm.financeiro.estoque.itens.update', $item) }}', {{ json_encode($item->nome) }}, {{ json_encode($item->codigo) }}, {{ json_encode($item->fornecedor) }}, {{ $item->quantidade_atual }}, {{ $item->estoque_minimo }}, {{ json_encode($item->unidade) }})">Editar</button>
                                        <form method="POST" action="{{ route('crm.financeiro.estoque.itens.destroy', $item) }}" style="display:inline;" onsubmit="return confirm('Remover este item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 6px 10px; font-size: 11px;">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7">Nenhum item cadastrado. Use o formulário acima para adicionar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($itens->hasPages())
                    <div style="margin-top: 14px;">{{ $itens->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <div id="modalEditar" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 100; align-items: center; justify-content: center;">
        <div class="card" style="max-width: 480px; width: 90%; margin: 20px;">
            <h3 style="margin-bottom: 12px;">Editar item</h3>
            <form method="POST" id="formEditar">
                @csrf
                @method('PUT')
                <div class="field" style="margin-bottom: 10px;"><label>Nome *</label><input type="text" name="nome" id="editNome" required></div>
                <div class="field" style="margin-bottom: 10px;"><label>Código</label><input type="text" name="codigo" id="editCodigo"></div>
                <div class="field" style="margin-bottom: 10px;"><label>Fornecedor</label><input type="text" name="fornecedor" id="editFornecedor"></div>
                <div class="field" style="margin-bottom: 10px;"><label>Qtd. atual *</label><input type="number" name="quantidade_atual" id="editQtd" step="0.001" min="0" required></div>
                <div class="field" style="margin-bottom: 10px;"><label>Estoque mínimo *</label><input type="number" name="estoque_minimo" id="editMin" step="0.001" min="0" required></div>
                <div class="field" style="margin-bottom: 14px;"><label>Unidade</label><input type="text" name="unidade" id="editUnidade"></div>
                <div class="actions-inline">
                    <button class="btn btn-primary" type="submit">Salvar</button>
                    <button type="button" class="btn" onclick="fecharModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editarItem(url, nome, codigo, fornecedor, qtd, min, unidade) {
            document.getElementById('formEditar').action = url;
            document.getElementById('editNome').value = nome || '';
            document.getElementById('editCodigo').value = codigo || '';
            document.getElementById('editFornecedor').value = fornecedor || '';
            document.getElementById('editQtd').value = qtd;
            document.getElementById('editMin').value = min;
            document.getElementById('editUnidade').value = unidade || '';
            document.getElementById('modalEditar').style.display = 'flex';
        }
        function fecharModal() {
            document.getElementById('modalEditar').style.display = 'none';
        }
        document.getElementById('modalEditar').addEventListener('click', function(e) {
            if (e.target === this) fecharModal();
        });
    </script>
</body>
</html>
