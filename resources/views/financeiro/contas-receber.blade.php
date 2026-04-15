<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Financeiro - Contas a Receber</title>
    @include('financeiro.partials.styles')
</head>
<body>
    @include('components.sidebar')

    <div class="main-content">
        <div class="top-header">
            <h1>Contas a Receber</h1>
            <div class="header-actions">
                <a class="btn" href="{{ route('crm.financeiro.dashboard') }}">Dashboard</a>
                <a class="btn" href="{{ route('crm.financeiro.contas-pagar.index') }}">Contas a Pagar</a>
                <a class="btn" href="{{ route('crm.financeiro.dre') }}">DRE</a>
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
                        <h3>Filtros</h3>
                        <div class="subtle">Busque por cliente, status e inadimplência.</div>
                    </div>
                    <div class="actions-inline">
                        <a class="btn btn-ghost" href="{{ route('crm.financeiro.dashboard') }}">Dashboard</a>
                        <a class="btn btn-ghost" href="{{ route('crm.financeiro.contas-pagar.index') }}">Pagar</a>
                        <a class="btn btn-ghost" href="{{ route('crm.financeiro.dre') }}">DRE</a>
                    </div>
                </div>

                <form method="GET" class="toolbar">
                    <div class="field">
                    <label>Cliente</label>
                    <input type="text" name="cliente" value="{{ request('cliente') }}">
                    </div>
                    <div class="field">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Todos</option>
                        <option value="aberto" {{ request('status') === 'aberto' ? 'selected' : '' }}>Aberto</option>
                        <option value="parcial" {{ request('status') === 'parcial' ? 'selected' : '' }}>Parcial</option>
                        <option value="recebido" {{ request('status') === 'recebido' ? 'selected' : '' }}>Recebido</option>
                    </select>
                    </div>
                    <div class="field">
                    <label>Vencimento de</label>
                    <input type="date" name="vencimento_de" value="{{ request('vencimento_de') }}">
                    </div>
                    <div class="field">
                    <label>Vencimento até</label>
                    <input type="date" name="vencimento_ate" value="{{ request('vencimento_ate') }}">
                    </div>
                    <div class="field" style="min-width: 220px;">
                        <label>&nbsp;</label>
                        <label style="display:flex; gap:10px; align-items:center; margin:0; font-size:12px;">
                            <input type="checkbox" name="apenas_inadimplentes" value="1" {{ request()->boolean('apenas_inadimplentes') ? 'checked' : '' }} style="width:auto; transform: translateY(1px);">
                            Apenas inadimplentes
                        </label>
                    </div>
                    <div class="actions-inline" style="align-self: end;">
                        <button class="btn btn-primary" type="submit">Aplicar filtros</button>
                        <a class="btn" href="{{ route('crm.financeiro.contas-receber.index') }}">Limpar</a>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-title">
                    <div>
                        <h3>Lançamentos</h3>
                        <div class="subtle">Baixa parcial/total, edição e exclusão.</div>
                    </div>
                </div>
                <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Documento</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Saldo</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contas as $conta)
                            <tr>
                                <td>{{ $conta->cliente_nome ?: optional($conta->cliente)->razao_social ?: '-' }}</td>
                                <td>{{ $conta->numero_documento ?: '-' }}</td>
                                <td class="nowrap">{{ optional($conta->data_vencimento)->format('d/m/Y') }}</td>
                                <td class="nowrap">R$ {{ number_format($conta->valor_total, 2, ',', '.') }}</td>
                                <td class="nowrap">R$ {{ number_format($conta->saldo, 2, ',', '.') }}</td>
                                <td>
                                    @if($conta->status === 'recebido')
                                        <span class="badge success">Recebido</span>
                                    @elseif($conta->total_parcelas > 1 && $conta->esta_vencida)
                                        <span class="badge warning">Duplicata vencida</span>
                                    @elseif($conta->inadimplente)
                                        <span class="badge danger">Inadimplente • {{ $conta->dias_atraso }}d</span>
                                    @else
                                        <span class="badge info">{{ ucfirst($conta->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions-inline">
                                        <button
                                            type="button"
                                            class="btn btn-primary js-open-modal"
                                            data-modal="modalBaixarReceber"
                                            data-action="{{ route('crm.financeiro.contas-receber.baixar', $conta) }}"
                                            data-saldo="{{ $conta->saldo }}"
                                        >Baixar</button>

                                        <button
                                            type="button"
                                            class="btn js-open-modal"
                                            data-modal="modalEditarReceber"
                                            data-action="{{ route('crm.financeiro.contas-receber.update', $conta) }}"
                                            data-cliente_id="{{ $conta->cliente_id }}"
                                            data-cliente_nome="{{ e($conta->cliente_nome) }}"
                                            data-descricao="{{ e($conta->descricao) }}"
                                            data-categoria="{{ e($conta->categoria) }}"
                                            data-numero_documento="{{ e($conta->numero_documento) }}"
                                            data-grupo_duplicata="{{ e($conta->grupo_duplicata) }}"
                                            data-parcela="{{ $conta->parcela }}"
                                            data-total_parcelas="{{ $conta->total_parcelas }}"
                                            data-valor_total="{{ $conta->valor_total }}"
                                            data-valor_recebido="{{ $conta->valor_recebido }}"
                                            data-data_emissao="{{ optional($conta->data_emissao)->format('Y-m-d') }}"
                                            data-data_vencimento="{{ optional($conta->data_vencimento)->format('Y-m-d') }}"
                                            data-data_recebimento="{{ optional($conta->data_recebimento)->format('Y-m-d') }}"
                                            data-observacoes="{{ e($conta->observacoes) }}"
                                        >Editar</button>

                                        <form method="POST" action="{{ route('crm.financeiro.contas-receber.destroy', $conta) }}" onsubmit="return confirm('Excluir este lançamento?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7">Nenhuma conta a receber cadastrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                <div class="pagination">
                    {{ $contas->links() }}
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary fab js-open-modal" data-modal="modalNovoReceber">+ Nova conta</button>

    <!-- Modal: Novo -->
    <div class="modal" id="modalNovoReceber" aria-hidden="true">
        <div class="modal-backdrop js-close-modal" data-modal="modalNovoReceber"></div>
        <div class="modal-panel" role="dialog" aria-modal="true" aria-label="Nova conta a receber">
            <div class="modal-header">
                <div class="modal-title">
                    <strong>Nova conta a receber</strong>
                    <span class="subtle">Cadastre com cliente cadastrado ou livre.</span>
                </div>
                <button type="button" class="btn btn-ghost js-close-modal" data-modal="modalNovoReceber">Fechar</button>
            </div>
            <form method="POST" action="{{ route('crm.financeiro.contas-receber.store') }}" id="formNovoReceber">
                @csrf
                <div class="modal-body">
                    <div class="grid-3">
                        <div>
                            <label>Cliente cadastrado</label>
                            <select name="cliente_id" id="novoReceberClienteId">
                                <option value="">Selecione</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->razao_social }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Cliente livre</label>
                            <input name="cliente_nome" id="novoReceberClienteNome" placeholder="Se não tiver cadastro">
                        </div>
                        <div><label>Descrição *</label><input required name="descricao"></div>
                        <div><label>Categoria</label><input name="categoria"></div>
                        <div><label>Nº documento</label><input name="numero_documento"></div>
                        <div><label>Grupo duplicata</label><input name="grupo_duplicata"></div>
                        <div><label>Parcela</label><input name="parcela" type="number" min="1" value="1"></div>
                        <div><label>Total parcelas</label><input name="total_parcelas" type="number" min="1" value="1"></div>
                        <div><label>Valor total *</label><input required name="valor_total" type="number" step="0.01" min="0.01" inputmode="decimal"></div>
                        <div><label>Data emissão</label><input name="data_emissao" type="date"></div>
                        <div><label>Data vencimento *</label><input required name="data_vencimento" type="date"></div>
                        <div style="grid-column: 1 / -1;"><label>Observações</label><textarea name="observacoes"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn js-close-modal" data-modal="modalNovoReceber">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Baixar -->
    <div class="modal" id="modalBaixarReceber" aria-hidden="true">
        <div class="modal-backdrop js-close-modal" data-modal="modalBaixarReceber"></div>
        <div class="modal-panel" role="dialog" aria-modal="true" aria-label="Baixar recebimento">
            <div class="modal-header">
                <div class="modal-title">
                    <strong>Dar baixa (recebimento)</strong>
                    <span class="subtle">Baixa parcial ou total.</span>
                </div>
                <button type="button" class="btn btn-ghost js-close-modal" data-modal="modalBaixarReceber">Fechar</button>
            </div>
            <form method="POST" id="formBaixarReceber" action="#">
                @csrf
                <div class="modal-body">
                    <div class="grid-3">
                        <div>
                            <label>Valor</label>
                            <input id="baixarReceberValor" type="number" name="valor" step="0.01" min="0.01" required inputmode="decimal">
                            <div class="subtle" id="baixarReceberHint" style="margin-top:6px;"></div>
                        </div>
                        <div>
                            <label>Data recebimento</label>
                            <input type="date" name="data_recebimento">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn js-close-modal" data-modal="modalBaixarReceber">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Confirmar baixa</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Editar -->
    <div class="modal" id="modalEditarReceber" aria-hidden="true">
        <div class="modal-backdrop js-close-modal" data-modal="modalEditarReceber"></div>
        <div class="modal-panel" role="dialog" aria-modal="true" aria-label="Editar conta a receber">
            <div class="modal-header">
                <div class="modal-title">
                    <strong>Editar conta a receber</strong>
                    <span class="subtle">Ajuste os dados e salve.</span>
                </div>
                <button type="button" class="btn btn-ghost js-close-modal" data-modal="modalEditarReceber">Fechar</button>
            </div>
            <form method="POST" id="formEditarReceber" action="#">
                @csrf
                <div class="modal-body">
                    <div class="grid-3">
                        <div>
                            <label>Cliente cadastrado</label>
                            <select name="cliente_id" id="editReceberClienteId">
                                <option value="">Selecione</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->razao_social }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Cliente livre</label>
                            <input name="cliente_nome" id="editReceberClienteNome">
                        </div>
                        <div><label>Descrição *</label><input required name="descricao" id="editReceberDescricao"></div>
                        <div><label>Categoria</label><input name="categoria" id="editReceberCategoria"></div>
                        <div><label>Nº documento</label><input name="numero_documento" id="editReceberNumeroDocumento"></div>
                        <div><label>Grupo duplicata</label><input name="grupo_duplicata" id="editReceberGrupoDuplicata"></div>
                        <div><label>Parcela</label><input name="parcela" type="number" min="1" id="editReceberParcela"></div>
                        <div><label>Total parcelas</label><input name="total_parcelas" type="number" min="1" id="editReceberTotalParcelas"></div>
                        <div><label>Valor total *</label><input required name="valor_total" type="number" step="0.01" min="0.01" inputmode="decimal" id="editReceberValorTotal"></div>
                        <div><label>Valor recebido</label><input name="valor_recebido" type="number" step="0.01" min="0" inputmode="decimal" id="editReceberValorRecebido"></div>
                        <div><label>Data emissão</label><input name="data_emissao" type="date" id="editReceberDataEmissao"></div>
                        <div><label>Data vencimento *</label><input required name="data_vencimento" type="date" id="editReceberDataVencimento"></div>
                        <div><label>Data recebimento</label><input name="data_recebimento" type="date" id="editReceberDataRecebimento"></div>
                        <div style="grid-column: 1 / -1;"><label>Observações</label><textarea name="observacoes" id="editReceberObservacoes"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn js-close-modal" data-modal="modalEditarReceber">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Salvar alterações</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const openBtns = document.querySelectorAll('.js-open-modal');
            const closeBtns = document.querySelectorAll('.js-close-modal');

            function openModal(id) {
                const el = document.getElementById(id);
                if (!el) return;
                el.classList.add('is-open');
                el.setAttribute('aria-hidden', 'false');
                const first = el.querySelector('input, select, textarea, button');
                if (first) setTimeout(() => first.focus(), 50);
            }

            function closeModal(id) {
                const el = document.getElementById(id);
                if (!el) return;
                el.classList.remove('is-open');
                el.setAttribute('aria-hidden', 'true');
            }

            openBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modalId = btn.dataset.modal;
                    if (!modalId) return;

                    if (modalId === 'modalBaixarReceber') {
                        const form = document.getElementById('formBaixarReceber');
                        const valor = document.getElementById('baixarReceberValor');
                        const hint = document.getElementById('baixarReceberHint');
                        const action = btn.dataset.action || '#';
                        const saldo = Number(btn.dataset.saldo || '0');
                        form.action = action;
                        valor.max = String(saldo);
                        valor.value = saldo > 0 ? String(saldo) : '';
                        hint.textContent = saldo > 0 ? `Saldo disponível: R$ ${saldo.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}` : '';
                    }

                    if (modalId === 'modalEditarReceber') {
                        const form = document.getElementById('formEditarReceber');
                        form.action = btn.dataset.action || '#';
                        const set = (id, val) => { const el = document.getElementById(id); if (el) el.value = val ?? ''; };
                        set('editReceberClienteId', btn.dataset.cliente_id);
                        set('editReceberClienteNome', btn.dataset.cliente_nome);
                        set('editReceberDescricao', btn.dataset.descricao);
                        set('editReceberCategoria', btn.dataset.categoria);
                        set('editReceberNumeroDocumento', btn.dataset.numero_documento);
                        set('editReceberGrupoDuplicata', btn.dataset.grupo_duplicata);
                        set('editReceberParcela', btn.dataset.parcela || 1);
                        set('editReceberTotalParcelas', btn.dataset.total_parcelas || 1);
                        set('editReceberValorTotal', btn.dataset.valor_total);
                        set('editReceberValorRecebido', btn.dataset.valor_recebido);
                        set('editReceberDataEmissao', btn.dataset.data_emissao);
                        set('editReceberDataVencimento', btn.dataset.data_vencimento);
                        set('editReceberDataRecebimento', btn.dataset.data_recebimento);
                        set('editReceberObservacoes', btn.dataset.observacoes);
                    }

                    openModal(modalId);
                });
            });

            closeBtns.forEach(btn => {
                btn.addEventListener('click', () => closeModal(btn.dataset.modal));
            });

            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;
                document.querySelectorAll('.modal.is-open').forEach(m => {
                    m.classList.remove('is-open');
                    m.setAttribute('aria-hidden', 'true');
                });
            });

            // UX: cliente cadastrado vs cliente livre
            const bindClienteFields = (selectId, inputId) => {
                const sel = document.getElementById(selectId);
                const inp = document.getElementById(inputId);
                if (!sel || !inp) return;
                sel.addEventListener('change', () => {
                    if (sel.value) inp.value = '';
                });
                inp.addEventListener('input', () => {
                    if (inp.value && sel.value) sel.value = '';
                });
            };
            bindClienteFields('novoReceberClienteId', 'novoReceberClienteNome');
            bindClienteFields('editReceberClienteId', 'editReceberClienteNome');
        })();
    </script>
</body>
</html>
