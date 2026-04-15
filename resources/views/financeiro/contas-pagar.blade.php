<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Financeiro - Contas a Pagar</title>
    @include('financeiro.partials.styles')
</head>
<body>
    @include('components.sidebar')

    <div class="main-content">
        <div class="top-header">
            <h1>Contas a Pagar</h1>
            <div class="header-actions">
                <a class="btn" href="{{ route('crm.financeiro.dashboard') }}">Dashboard</a>
                <a class="btn" href="{{ route('crm.financeiro.contas-receber.index') }}">Contas a Receber</a>
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
                        <div class="subtle">Use para achar rápido e ver vencidas.</div>
                    </div>
                    <div class="actions-inline">
                        <a class="btn btn-ghost" href="{{ route('crm.financeiro.dashboard') }}">Dashboard</a>
                        <a class="btn btn-ghost" href="{{ route('crm.financeiro.contas-receber.index') }}">Receber</a>
                        <a class="btn btn-ghost" href="{{ route('crm.financeiro.dre') }}">DRE</a>
                    </div>
                </div>

                <form method="GET" class="toolbar">
                    <div class="field">
                    <label>Fornecedor</label>
                    <input type="text" name="fornecedor" value="{{ request('fornecedor') }}">
                    </div>
                    <div class="field">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Todos</option>
                        <option value="aberto" {{ request('status') === 'aberto' ? 'selected' : '' }}>Aberto</option>
                        <option value="parcial" {{ request('status') === 'parcial' ? 'selected' : '' }}>Parcial</option>
                        <option value="pago" {{ request('status') === 'pago' ? 'selected' : '' }}>Pago</option>
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
                    <div class="field" style="min-width: 210px;">
                        <label>&nbsp;</label>
                        <label style="display:flex; gap:10px; align-items:center; margin:0; font-size:12px;">
                            <input type="checkbox" name="apenas_vencidas" value="1" {{ request()->boolean('apenas_vencidas') ? 'checked' : '' }} style="width:auto; transform: translateY(1px);">
                            Apenas vencidas
                        </label>
                    </div>
                    <div class="actions-inline" style="align-self: end;">
                        <button class="btn btn-primary" type="submit">Aplicar filtros</button>
                        <a class="btn" href="{{ route('crm.financeiro.contas-pagar.index') }}">Limpar</a>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-title">
                    <div>
                        <h3>Lançamentos</h3>
                        <div class="subtle">Clique em “Baixar” para dar baixa parcial/total.</div>
                    </div>
                </div>
                <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fornecedor</th>
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
                                <td>{{ $conta->fornecedor }}</td>
                                <td>{{ $conta->numero_documento ?: '-' }}</td>
                                <td class="nowrap">{{ optional($conta->data_vencimento)->format('d/m/Y') }}</td>
                                <td class="nowrap">R$ {{ number_format($conta->valor_total, 2, ',', '.') }}</td>
                                <td class="nowrap">R$ {{ number_format($conta->saldo, 2, ',', '.') }}</td>
                                <td>
                                    @if($conta->status === 'pago')
                                        <span class="badge success">Pago</span>
                                    @elseif($conta->esta_vencida)
                                        <span class="badge danger">Vencida • {{ $conta->dias_atraso }}d</span>
                                    @else
                                        <span class="badge info">{{ ucfirst($conta->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions-inline">
                                        <button
                                            type="button"
                                            class="btn btn-primary js-open-modal"
                                            data-modal="modalBaixarPagar"
                                            data-action="{{ route('crm.financeiro.contas-pagar.baixar', $conta) }}"
                                            data-saldo="{{ $conta->saldo }}"
                                        >Baixar</button>

                                        <button
                                            type="button"
                                            class="btn js-open-modal"
                                            data-modal="modalEditarPagar"
                                            data-action="{{ route('crm.financeiro.contas-pagar.update', $conta) }}"
                                            data-fornecedor="{{ e($conta->fornecedor) }}"
                                            data-descricao="{{ e($conta->descricao) }}"
                                            data-categoria="{{ e($conta->categoria) }}"
                                            data-numero_documento="{{ e($conta->numero_documento) }}"
                                            data-grupo_duplicata="{{ e($conta->grupo_duplicata) }}"
                                            data-parcela="{{ $conta->parcela }}"
                                            data-total_parcelas="{{ $conta->total_parcelas }}"
                                            data-valor_total="{{ $conta->valor_total }}"
                                            data-valor_pago="{{ $conta->valor_pago }}"
                                            data-data_emissao="{{ optional($conta->data_emissao)->format('Y-m-d') }}"
                                            data-data_vencimento="{{ optional($conta->data_vencimento)->format('Y-m-d') }}"
                                            data-data_pagamento="{{ optional($conta->data_pagamento)->format('Y-m-d') }}"
                                            data-observacoes="{{ e($conta->observacoes) }}"
                                        >Editar</button>

                                        <form method="POST" action="{{ route('crm.financeiro.contas-pagar.destroy', $conta) }}" onsubmit="return confirm('Excluir este lançamento?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7">Nenhuma conta a pagar cadastrada.</td></tr>
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

    <button type="button" class="btn btn-primary fab js-open-modal" data-modal="modalNovoPagar">+ Nova conta</button>

    <!-- Modal: Novo -->
    <div class="modal" id="modalNovoPagar" aria-hidden="true">
        <div class="modal-backdrop js-close-modal" data-modal="modalNovoPagar"></div>
        <div class="modal-panel" role="dialog" aria-modal="true" aria-label="Nova conta a pagar">
            <div class="modal-header">
                <div class="modal-title">
                    <strong>Nova conta a pagar</strong>
                    <span class="subtle">Cadastre rápido e sem poluir a tela.</span>
                </div>
                <button type="button" class="btn btn-ghost js-close-modal" data-modal="modalNovoPagar">Fechar</button>
            </div>
            <form method="POST" action="{{ route('crm.financeiro.contas-pagar.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="grid-3">
                        <div><label>Fornecedor *</label><input required name="fornecedor"></div>
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
                    <button type="button" class="btn js-close-modal" data-modal="modalNovoPagar">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Baixar -->
    <div class="modal" id="modalBaixarPagar" aria-hidden="true">
        <div class="modal-backdrop js-close-modal" data-modal="modalBaixarPagar"></div>
        <div class="modal-panel" role="dialog" aria-modal="true" aria-label="Baixar pagamento">
            <div class="modal-header">
                <div class="modal-title">
                    <strong>Dar baixa (pagamento)</strong>
                    <span class="subtle">Você pode baixar parcial ou total.</span>
                </div>
                <button type="button" class="btn btn-ghost js-close-modal" data-modal="modalBaixarPagar">Fechar</button>
            </div>
            <form method="POST" id="formBaixarPagar" action="#">
                @csrf
                <div class="modal-body">
                    <div class="grid-3">
                        <div>
                            <label>Valor</label>
                            <input id="baixarPagarValor" type="number" name="valor" step="0.01" min="0.01" required inputmode="decimal">
                            <div class="subtle" id="baixarPagarHint" style="margin-top:6px;"></div>
                        </div>
                        <div>
                            <label>Data pagamento</label>
                            <input type="date" name="data_pagamento">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn js-close-modal" data-modal="modalBaixarPagar">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Confirmar baixa</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Editar -->
    <div class="modal" id="modalEditarPagar" aria-hidden="true">
        <div class="modal-backdrop js-close-modal" data-modal="modalEditarPagar"></div>
        <div class="modal-panel" role="dialog" aria-modal="true" aria-label="Editar conta a pagar">
            <div class="modal-header">
                <div class="modal-title">
                    <strong>Editar conta a pagar</strong>
                    <span class="subtle">Ajuste os dados e salve.</span>
                </div>
                <button type="button" class="btn btn-ghost js-close-modal" data-modal="modalEditarPagar">Fechar</button>
            </div>
            <form method="POST" id="formEditarPagar" action="#">
                @csrf
                <div class="modal-body">
                    <div class="grid-3">
                        <div><label>Fornecedor *</label><input required name="fornecedor" id="editPagarFornecedor"></div>
                        <div><label>Descrição *</label><input required name="descricao" id="editPagarDescricao"></div>
                        <div><label>Categoria</label><input name="categoria" id="editPagarCategoria"></div>
                        <div><label>Nº documento</label><input name="numero_documento" id="editPagarNumeroDocumento"></div>
                        <div><label>Grupo duplicata</label><input name="grupo_duplicata" id="editPagarGrupoDuplicata"></div>
                        <div><label>Parcela</label><input name="parcela" type="number" min="1" id="editPagarParcela"></div>
                        <div><label>Total parcelas</label><input name="total_parcelas" type="number" min="1" id="editPagarTotalParcelas"></div>
                        <div><label>Valor total *</label><input required name="valor_total" type="number" step="0.01" min="0.01" inputmode="decimal" id="editPagarValorTotal"></div>
                        <div><label>Valor pago</label><input name="valor_pago" type="number" step="0.01" min="0" inputmode="decimal" id="editPagarValorPago"></div>
                        <div><label>Data emissão</label><input name="data_emissao" type="date" id="editPagarDataEmissao"></div>
                        <div><label>Data vencimento *</label><input required name="data_vencimento" type="date" id="editPagarDataVencimento"></div>
                        <div><label>Data pagamento</label><input name="data_pagamento" type="date" id="editPagarDataPagamento"></div>
                        <div style="grid-column: 1 / -1;"><label>Observações</label><textarea name="observacoes" id="editPagarObservacoes"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn js-close-modal" data-modal="modalEditarPagar">Cancelar</button>
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
                const firstInput = el.querySelector('input, select, textarea, button');
                if (firstInput) setTimeout(() => firstInput.focus(), 50);
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

                    if (modalId === 'modalBaixarPagar') {
                        const form = document.getElementById('formBaixarPagar');
                        const valor = document.getElementById('baixarPagarValor');
                        const hint = document.getElementById('baixarPagarHint');
                        const action = btn.dataset.action || '#';
                        const saldo = Number(btn.dataset.saldo || '0');
                        form.action = action;
                        valor.max = String(saldo);
                        valor.value = saldo > 0 ? String(saldo) : '';
                        hint.textContent = saldo > 0 ? `Saldo disponível: R$ ${saldo.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}` : '';
                    }

                    if (modalId === 'modalEditarPagar') {
                        const form = document.getElementById('formEditarPagar');
                        form.action = btn.dataset.action || '#';

                        const set = (id, val) => { const el = document.getElementById(id); if (el) el.value = val ?? ''; };
                        set('editPagarFornecedor', btn.dataset.fornecedor);
                        set('editPagarDescricao', btn.dataset.descricao);
                        set('editPagarCategoria', btn.dataset.categoria);
                        set('editPagarNumeroDocumento', btn.dataset.numero_documento);
                        set('editPagarGrupoDuplicata', btn.dataset.grupo_duplicata);
                        set('editPagarParcela', btn.dataset.parcela || 1);
                        set('editPagarTotalParcelas', btn.dataset.total_parcelas || 1);
                        set('editPagarValorTotal', btn.dataset.valor_total);
                        set('editPagarValorPago', btn.dataset.valor_pago);
                        set('editPagarDataEmissao', btn.dataset.data_emissao);
                        set('editPagarDataVencimento', btn.dataset.data_vencimento);
                        set('editPagarDataPagamento', btn.dataset.data_pagamento);
                        set('editPagarObservacoes', btn.dataset.observacoes);
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
        })();
    </script>
</body>
</html>
