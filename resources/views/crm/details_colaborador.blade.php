<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $colaborador->nome_profissional }} - Documentos</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #1e1e1e;
            color: #cccccc;
            height: 100%;
            overflow: hidden;
            max-width: 100vw;
            font-size: 12px;
        }

        /* === Barra superior (título + navegação + busca) === */
        .explorer-header {
            background: #252526;
            border-bottom: 1px solid #3c3c3c;
            display: flex;
            flex-direction: column;
        }
        .header-row {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            min-height: 36px;
        }
        .nav-buttons {
            display: flex;
            gap: 2px;
        }
        .nav-buttons .btn {
            width: 28px;
            height: 28px;
            border: none;
            background: transparent;
            color: #cccccc;
            cursor: pointer;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        .nav-buttons .btn:hover { background: #3c3c3c; }
        .nav-buttons .btn:disabled { color: #6e6e6e; cursor: default; }
        .breadcrumb-bar {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 0 12px;
            min-width: 0;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            overflow-x: auto;
            white-space: nowrap;
        }
        .breadcrumb a { color: #3794ff; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb span { color: #6e6e6e; }
        .search-box {
            width: 200px;
            padding: 4px 8px 4px 28px;
            background: #3c3c3c;
            border: 1px solid #555;
            border-radius: 4px;
            color: #cccccc;
            font-size: 12px;
        }
        .search-box::placeholder { color: #6e6e6e; }
        .search-wrap { position: relative; }
        .search-wrap::before { content: '🔍'; position: absolute; left: 8px; top: 6px; font-size: 10px; }

        /* === Toolbar (ícones de ação) === */
        .toolbar {
            display: flex;
            align-items: center;
            gap: 2px;
            padding: 4px 12px;
            background: #252526;
            border-bottom: 1px solid #3c3c3c;
        }
        .toolbar .btn {
            padding: 4px 8px;
            border: none;
            background: transparent;
            color: #cccccc;
            cursor: pointer;
            border-radius: 4px;
            font-size: 11px;
        }
        .toolbar .btn:hover { background: #3c3c3c; }
        .toolbar .btn.primary { color: #3794ff; }
        .toolbar-sep { width: 1px; height: 20px; background: #3c3c3c; margin: 0 4px; }
        .toolbar .upload-inline {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: 8px;
        }
        .toolbar .upload-inline input { font-size: 10px; color: #cccccc; }
        .toolbar .upload-inline input[type="text"],
        .toolbar .upload-inline input[type="date"] {
            padding: 3px 6px;
            background: #3c3c3c;
            border: 1px solid #555;
            border-radius: 4px;
            color: #cccccc;
            font-size: 11px;
            max-width: 90px;
        }

        /* === Layout principal === */
        .main-layout {
            display: flex;
            height: calc(100vh - 90px);
            max-width: 100vw;
            overflow: hidden;
        }

        /* === Painel esquerdo - Navegação (tema escuro) === */
        .nav-pane {
            width: 220px;
            min-width: 180px;
            flex-shrink: 0;
            background: #252526;
            border-right: 1px solid #3c3c3c;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .nav-section {
            padding: 8px 0;
        }
        .nav-section-title {
            padding: 4px 12px;
            font-size: 11px;
            color: #6e6e6e;
            text-transform: uppercase;
        }
        .tree-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            cursor: pointer;
            font-size: 12px;
            user-select: none;
            color: #cccccc;
        }
        .tree-item:hover { background: #2a2d2e; }
        .tree-item.active { background: #094771; color: #fff; }
        .tree-item .tree-icon { font-size: 16px; }
        .tree-item .tree-toggle {
            width: 16px;
            padding: 0;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 10px;
            color: #6e6e6e;
        }
        .tree-item .tree-toggle.collapsed::before { content: '▶'; }
        .tree-item .tree-toggle.expanded::before { content: '▼'; }
        .tree-children { padding-left: 8px; border-left: 1px solid #3c3c3c; margin-left: 12px; }
        .tree-item.drop-target { background: #094771; box-shadow: inset 0 0 0 1px #3794ff; }
        .btn-new-folder {
            margin: 8px 12px;
            padding: 6px 10px;
            border: 1px solid #555;
            background: #3c3c3c;
            color: #cccccc;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
        }
        .btn-new-folder:hover { background: #505050; border-color: #3794ff; color: #3794ff; }
        .nav-pane-body { flex: 1; overflow-y: auto; padding: 4px 0; }

        /* === Área de conteúdo === */
        .content-pane {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
            background: #1e1e1e;
        }

        /* Lista de arquivos - colunas como Explorer */
        .file-list {
            flex: 1;
            min-height: 0;
            overflow: auto;
        }
        .file-list.drag-over { background: #2d2d30; box-shadow: inset 0 0 0 2px #3794ff; }
        .file-list-header {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) 150px 88px 110px 100px 64px 120px;
            gap: 0;
            padding: 0;
            background: #252526;
            font-size: 11px;
            font-weight: 500;
            color: #cccccc;
            border-bottom: 1px solid #3c3c3c;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .file-list-header > div {
            padding: 8px 12px;
            border-right: 1px solid #3c3c3c;
        }
        .file-list-header > div:last-child { border-right: none; }
        .file-row {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) 150px 88px 110px 100px 64px 120px;
            gap: 0;
            padding: 0;
            align-items: center;
            font-size: 12px;
            border-bottom: 1px solid #2d2d30;
            cursor: default;
            color: #cccccc;
        }
        .file-row:hover { background: #2a2d2e; }
        .file-row.selected { background: #094771; color: #fff; }
        .file-row.selected .file-actions .btn { color: #fff; }
        .file-row.draggable { cursor: grab; }
        .file-row.draggable:active { cursor: grabbing; }
        .file-row > div {
            padding: 6px 12px;
            border-right: 1px solid transparent;
        }
        .file-row .file-name {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            overflow: hidden;
            flex-wrap: wrap;
        }
        .btn-tiny {
            padding: 2px 6px;
            font-size: 10px;
            border: 1px solid #555;
            background: #3c3c3c;
            color: #aaa;
            border-radius: 3px;
            cursor: pointer;
            flex-shrink: 0;
        }
        .btn-tiny:hover { background: #505050; color: #fff; }
        .row-validade {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }
        .row-validade input[type="date"] {
            padding: 3px 4px;
            background: #3c3c3c;
            border: 1px solid #555;
            border-radius: 3px;
            color: #ccc;
            font-size: 10px;
            max-width: 118px;
        }
        .cell-muted { color: #6e6e6e; font-size: 11px; }
        .file-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 4px; }
        .file-row .file-name span.text { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .file-row .file-name .icon { font-size: 18px; flex-shrink: 0; }
        .file-row .file-name.folder { cursor: pointer; }
        .file-row .file-name.folder:hover { color: #3794ff; }
        .file-row .status-icon { font-size: 12px; opacity: 0.7; text-align: center; }
        .file-row .file-actions {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }
        .file-row .file-actions .btn {
            padding: 2px 6px;
            font-size: 10px;
            border: 1px solid #555;
            background: #3c3c3c;
            color: #cccccc;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
        }
        .file-row .file-actions .btn:hover { background: #505050; }
        .file-row .file-actions .btn.open { color: #3794ff; }
        .file-row .file-actions .btn.danger:hover { background: #5a2020; color: #ff6b6b; }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #6e6e6e;
            font-size: 13px;
        }
        .empty-state .icon { font-size: 48px; margin-bottom: 12px; opacity: 0.4; }

        /* === Barra de status === */
        .status-bar {
            padding: 4px 12px;
            background: #007acc;
            color: #fff;
            font-size: 11px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .status-bar.ok { background: #007acc; }
        .status-bar.error { background: #c5282c; }
        .status-bar .count { font-weight: 500; }

        .toolbar-hint {
            font-size: 10px;
            color: #9e9e9e;
            max-width: 420px;
            line-height: 1.35;
            margin-right: 8px;
        }
        .cert-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }
        .cert-badge.ok { background: #1e3a2f; color: #4ade80; }
        .cert-badge.expirando { background: #4a3000; color: #ffb84d; }
        .cert-badge.vencido { background: #4a1c1c; color: #ff6b6b; }
        .cert-badge.muted { background: #3c3c3c; color: #888; }

        @media (max-width: 1100px) {
            .file-list-header, .file-row {
                grid-template-columns: minmax(0, 1fr) 1fr;
                grid-auto-flow: dense;
            }
            .file-list-header > div:nth-child(n+3) { display: none; }
        }

        @media (max-width: 900px) {
            .main-layout { flex-direction: column; }
            .nav-pane { width: 100%; max-height: 200px; }
        }
    </style>
</head>
<body>
<div class="explorer-header">
    <div class="header-row">
        <div class="nav-buttons">
            <a class="btn" href="{{ route('crm.colaboradores.index') }}" title="Voltar">←</a>
            <button class="btn" type="button" onclick="subirPasta()" title="Subir">↑</button>
            <button class="btn" type="button" disabled title="Avançar">→</button>
        </div>
        <div class="breadcrumb-bar">
            <div class="breadcrumb" id="breadcrumb"></div>
        </div>
        <div class="search-wrap">
            <input class="search-box" type="text" id="searchInput" placeholder="Pesquisar em {{ $colaborador->nome_profissional }}...">
        </div>
    </div>

    <div class="toolbar">
        <button class="btn" type="button" onclick="criarPasta()" title="Nova pasta">📁 Nova pasta</button>
        <span class="toolbar-sep"></span>
        <button class="btn" type="button" onclick="renomearPastaAtual()" title="Renomear">Renomear</button>
        <button class="btn" type="button" onclick="excluirSelecionados()" title="Excluir selecionados">Excluir</button>
        <span class="toolbar-sep"></span>
        <span class="toolbar-hint" title="Fluxo simples">Crie uma pasta (ex.: NRS) → abra a pasta → envie o arquivo → use <strong>Renomear</strong> e a <strong>data</strong> na linha do arquivo.</span>
        <div class="upload-inline">
            <input id="arquivoInput" type="file" style="max-width:120px" title="Arquivo para a pasta atual">
            <input id="dataVencimentoOpcional" type="date" title="Opcional: já grava validade ao enviar">
            <button class="btn primary" type="button" onclick="enviarUpload()">Enviar para esta pasta</button>
        </div>
    </div>
</div>

<div class="main-layout">
    <aside class="nav-pane">
        <div class="nav-section">
            <div class="nav-section-title">{{ $colaborador->nome_profissional }}</div>
            <div class="nav-section-title" style="font-size:10px;color:#6e6e6e">{{ $colaborador->departamento_label ?: 'Ativo' }}</div>
        </div>
        <button class="btn-new-folder" type="button" onclick="criarPasta()">+ Nova pasta</button>
        <div class="nav-pane-body" id="folderTree"></div>
    </aside>

    <main class="content-pane">
        <div class="file-list">
            <div class="file-list-header">
                <div>Nome</div>
                <div>Status</div>
                <div>Data de modificação</div>
                <div>Tipo</div>
                <div>Tamanho</div>
            </div>
            <div id="explorerRows"></div>
        </div>

        <div id="statusMsg" class="status-bar"></div>
    </main>
</div>

<script>
    const colaboradorId = {{ $colaborador->id }};
    const DIAS_ALERTA = {{ (int) $diasAlerta }};
    let documentos = @json($colaborador->documentos->values());
    let pastas = @json($colaborador->pastas->values());
    let currentPath = '';
    let expandedPaths = new Set(['']);
    let selectedItems = new Set();

    function setStatus(msg, type = 'ok') {
        const el = document.getElementById('statusMsg');
        el.className = `status-bar ${type}`;
        el.innerHTML = msg;
    }

    function updateStatusBar() {
        const rows = document.getElementById('explorerRows');
        const totalItems = rows ? rows.querySelectorAll('.file-row').length : 0;
        const sel = selectedItems.size;
        const msg = document.getElementById('statusMsg');
        msg.className = 'status-bar ok';
        msg.innerHTML = `<span class="count">${totalItems} itens</span>${sel ? ` | <span class="count">${sel} itens selecionados</span>` : ''}`;
    }

    function normalize(path) {
        return (path || '').toString().replace(/\\/g, '/').replace(/^\/+|\/+$/g, '');
    }

    function filePath(doc) {
        return normalize(doc.caminho_relativo || doc.arquivo_nome_original || doc.nome_documento || ('arquivo-' + doc.id));
    }

    function fileFolder(doc) {
        const full = filePath(doc);
        if (!full.includes('/')) return '';
        return full.split('/').slice(0, -1).join('/');
    }

    function parseYmd(v) {
        if (!v) return '';
        return String(v).slice(0, 10);
    }

    function estadoCertDoc(d) {
        const raw = d.data_vencimento;
        if (!raw) return 'sem_data';
        const v = new Date(parseYmd(raw));
        if (Number.isNaN(v.getTime())) return 'sem_data';
        v.setHours(0, 0, 0, 0);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (v < today) return 'vencido';
        const lim = new Date(today);
        lim.setDate(lim.getDate() + DIAS_ALERTA);
        if (v <= lim) return 'expirando';
        return 'ok';
    }

    function escapeHtml(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function displayName(doc) {
        const full = filePath(doc);
        const base = (full.split('/').pop() || '').trim();
        const nd = doc.nome_documento != null ? String(doc.nome_documento).trim() : '';
        return nd || base || ('arquivo-' + doc.id);
    }

    function certBadgeHtml(doc) {
        const st = estadoCertDoc(doc);
        if (st === 'vencido') return '<span class="cert-badge vencido">Vencida</span>';
        if (st === 'expirando') return '<span class="cert-badge expirando">A vencer</span>';
        if (st === 'ok') return '<span class="cert-badge ok">OK</span>';
        return '<span class="cert-badge muted">Sem data</span>';
    }

    async function salvarCert(id) {
        const row = document.querySelector(`.file-row[data-doc-id="${id}"]`);
        const inp = row ? row.querySelector('.row-cert-date') : null;
        const dataVenc = inp ? inp.value : '';
        const idx = documentos.findIndex(x => x.id === id);
        const payload = { data_vencimento: dataVenc || null };
        try {
            const resp = await fetch('/crm/colaboradores/documentos/' + id + '/certificacao', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });
            const data = await resp.json();
            if (!resp.ok || !data.success) throw new Error(data.message || (data.errors && JSON.stringify(data.errors)) || 'Falha ao salvar.');
            if (data.documento && idx >= 0) documentos[idx] = data.documento;
            setStatus('Validade atualizada.');
            renderAll();
        } catch (e) {
            setStatus(e.message || 'Erro ao salvar.', 'error');
        }
    }

    function formatBytes(bytes) {
        const n = Number(bytes || 0);
        if (!n) return '-';
        const u = ['B', 'KB', 'MB', 'GB'];
        let v = n, i = 0;
        while (v >= 1024 && i < u.length - 1) { v /= 1024; i++; }
        return `${v.toFixed(v < 10 && i > 0 ? 1 : 0)} ${u[i]}`;
    }

    function getFileType(name) {
        if (!name || !name.includes('.')) return 'Documento';
        const ext = name.split('.').pop().toLowerCase();
        const types = { pdf: 'Documento PDF', doc: 'Documento Word', docx: 'Documento Word', xls: 'Planilha', xlsx: 'Planilha Excel', jpg: 'Imagem JPEG', jpeg: 'Imagem JPEG', png: 'Imagem PNG' };
        return types[ext] || `Arquivo ${ext.toUpperCase()}`;
    }

    function collectAllFolders() {
        const set = new Set();
        set.add('');
        pastas.forEach(p => {
            const c = normalize(p.caminho_relativo);
            if (c) set.add(c);
        });
        documentos.forEach(d => {
            const folder = fileFolder(d);
            if (!folder) return;
            const parts = folder.split('/');
            let cursor = '';
            parts.forEach(part => {
                cursor = cursor ? `${cursor}/${part}` : part;
                set.add(cursor);
            });
        });
        return Array.from(set).sort((a, b) => a.localeCompare(b, 'pt-BR'));
    }

    function buildTree(paths) {
        const root = { path: '', name: 'Raiz', children: [] };
        paths.forEach(path => {
            if (!path) return;
            const parts = path.split('/');
            let node = root;
            let cursor = '';
            parts.forEach(part => {
                cursor = cursor ? `${cursor}/${part}` : part;
                let child = node.children.find(c => c.path === cursor);
                if (!child) {
                    child = { path: cursor, name: part, children: [] };
                    node.children.push(child);
                }
                node = child;
            });
        });
        root.children.sort((a, b) => a.name.localeCompare(b.name, 'pt-BR'));
        return root;
    }

    function renderTreeItem(node, depth = 0) {
        const hasChildren = node.children.length > 0;
        const isExpanded = expandedPaths.has(node.path);
        const active = normalize(node.path) === normalize(currentPath) ? 'active' : '';
        const pathEnc = encodeURIComponent(node.path || '');
        let html = `<div class="tree-item ${active}" data-path="${pathEnc}" style="padding-left: ${12 + depth * 12}px">
            <button class="tree-toggle ${hasChildren ? (isExpanded ? 'expanded' : 'collapsed') : ''}" style="visibility: ${hasChildren ? 'visible' : 'hidden'}" data-path="${pathEnc}"></button>
            <span class="tree-icon">📁</span>
            <span>${(node.name || 'Raiz').replace(/</g, '&lt;')}</span>
        </div>`;
        if (hasChildren && isExpanded) {
            html += '<div class="tree-children">';
            node.children.forEach(c => html += renderTreeItem(c, depth + 1));
            html += '</div>';
        }
        return html;
    }

    function renderTree() {
        const list = collectAllFolders();
        const tree = buildTree(list);
        const root = document.getElementById('folderTree');
        root.innerHTML = renderTreeItem(tree);
        root.querySelectorAll('.tree-item').forEach(el => {
            el.addEventListener('click', (e) => {
                if (e.target.classList.contains('tree-toggle')) return;
                openFolder(decodeURIComponent(el.dataset.path || ''));
            });
        });
        root.querySelectorAll('.tree-toggle').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleFolder(decodeURIComponent(btn.dataset.path || ''));
            });
        });
    }

    function toggleFolder(path) {
        const n = normalize(path);
        if (expandedPaths.has(n)) expandedPaths.delete(n);
        else expandedPaths.add(n);
        renderTree();
    }

    function ensurePathExpanded() {
        if (!currentPath) return;
        const parts = currentPath.split('/');
        let cursor = '';
        parts.forEach(part => {
            cursor = cursor ? cursor + '/' + part : part;
            expandedPaths.add(cursor);
        });
    }

    function renderBreadcrumb() {
        const bc = document.getElementById('breadcrumb');
        const parts = normalize(currentPath) ? normalize(currentPath).split('/') : [];
        let cursor = '';
        const nodes = [`<a href="javascript:void(0)" onclick="openFolder('')">Iniciar</a>`];
        parts.forEach(part => {
            cursor = cursor ? `${cursor}/${part}` : part;
            nodes.push(`<span> › </span><a href="javascript:void(0)" onclick="openFolder('${cursor.replace(/'/g, "\\'")}')">${part}</a>`);
        });
        bc.innerHTML = nodes.join('');
    }

    function renderRows() {
        const rows = document.getElementById('explorerRows');
        const search = (document.getElementById('searchInput')?.value || '').toLowerCase();
        const allFolders = collectAllFolders();
        const prefix = normalize(currentPath);
        const folderChildren = allFolders.filter(path => {
            if (!path || path === prefix) return false;
            if (prefix && !path.startsWith(prefix + '/')) return false;
            if (!prefix && !path) return false;
            const rest = prefix ? path.slice(prefix.length + 1) : path;
            return rest && !rest.includes('/');
        });
        const filesHere = documentos.map((doc, idx) => ({ doc, idx })).filter(x => normalize(fileFolder(x.doc)) === prefix);

        const filteredFolders = folderChildren.filter(path => {
            const name = path.split('/').pop().toLowerCase();
            return !search || name.includes(search);
        });
        const filteredFiles = filesHere.filter(({ doc }) => {
            const full = filePath(doc).toLowerCase();
            const name = displayName(doc).toLowerCase();
            return !search || name.includes(search) || full.includes(search);
        });

        const folderRows = filteredFolders.map(path => {
            const name = path.split('/').pop().replace(/</g, '&lt;');
            return `<div class="file-row" data-folder-path="${encodeURIComponent(path)}">
                <div class="file-name folder"><span class="icon">📁</span><span class="text">${name}</span></div>
                <div class="cell-muted">—</div>
                <div class="cell-muted">—</div>
                <div class="cell-muted">—</div>
                <div>Pasta</div>
                <div class="cell-muted">—</div>
                <div class="cell-muted">Abra com duplo clique</div>
            </div>`;
        }).join('');

        const fileRows = filteredFiles.map(({ doc, idx }) => {
            const full = filePath(doc);
            const fileBase = (full.split('/').pop() || '').replace(/</g, '&lt;');
            const label = escapeHtml(displayName(doc));
            const ymd = parseYmd(doc.data_vencimento);
            const modDate = doc.updated_at ? new Date(doc.updated_at).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : (doc.data_vencimento ? new Date(doc.data_vencimento).toLocaleDateString('pt-BR') : '-');
            const tipo = getFileType(fileBase);
            const abrir = doc.arquivo_path
                ? `<button type="button" class="btn-tiny act-open" data-doc-id="${doc.id}">Abrir</button>`
                : '';
            return `<div class="file-row draggable" data-doc-id="${doc.id}" data-doc-idx="${idx}" data-doc-key="doc-${doc.id}">
                <div class="file-name"><span class="icon">📄</span><span class="text" title="${escapeHtml(fileBase)}">${label}</span>
                    <button type="button" class="btn-tiny doc-rename" data-doc-id="${doc.id}">Renomear</button>
                </div>
                <div class="row-validade" data-stop-row-click="1">
                    <input type="date" class="row-cert-date" value="${ymd}">
                    <button type="button" class="btn-tiny cert-save-row" data-id="${doc.id}">OK</button>
                </div>
                <div>${certBadgeHtml(doc)}</div>
                <div>${modDate}</div>
                <div>${tipo}</div>
                <div>${formatBytes(doc.arquivo_tamanho)}</div>
                <div class="file-actions" data-stop-row-click="1">${abrir}<button type="button" class="btn-tiny act-del" data-doc-id="${doc.id}">Excluir</button></div>
            </div>`;
        }).join('');

        const total = filteredFolders.length + filteredFiles.length;
        if (total === 0) {
            rows.innerHTML = `<div class="empty-state"><div class="icon">📂</div>${search ? 'Nenhum resultado encontrado.' : 'Nesta pasta ainda não há arquivos. Use <strong>Enviar para esta pasta</strong> na barra acima ou arraste arquivos aqui. Depois defina a validade na linha do arquivo.'}</div>`;
        } else {
            rows.innerHTML = folderRows + fileRows;
            rows.querySelectorAll('[data-folder-path]').forEach(row => {
                const path = decodeURIComponent(row.dataset.folderPath || '');
                const key = 'folder-' + (row.dataset.folderPath || '');
                row.querySelector('.file-name.folder').ondblclick = () => openFolder(path);
                row.onclick = (e) => {
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        row.classList.toggle('selected');
                        if (selectedItems.has(key)) selectedItems.delete(key);
                        else selectedItems.add(key);
                    } else {
                        document.querySelectorAll('.file-row.selected').forEach(r => r.classList.remove('selected'));
                        selectedItems.clear();
                        row.classList.add('selected');
                        selectedItems.add(key);
                    }
                    updateStatusBar();
                };
            });
            rows.querySelectorAll('.cert-save-row').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    salvarCert(parseInt(btn.getAttribute('data-id'), 10));
                });
            });
            rows.querySelectorAll('.doc-rename').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    renomearDocumento(parseInt(btn.dataset.docId, 10));
                });
            });
            rows.querySelectorAll('.act-open').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const id = parseInt(btn.dataset.docId, 10);
                    window.open(`/crm/colaboradores/documentos/${id}/arquivo`, '_blank');
                });
            });
            rows.querySelectorAll('.act-del').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const id = parseInt(btn.dataset.docId, 10);
                    const ix = documentos.findIndex(d => d.id === id);
                    if (ix >= 0) excluirDocumento(id, ix, false);
                });
            });
            rows.querySelectorAll('.file-row.draggable').forEach(row => {
                const docId = parseInt(row.dataset.docId, 10);
                const idx = parseInt(row.dataset.docIdx, 10);
                const doc = documentos[idx];
                const key = 'doc-' + docId;
                row.ondblclick = (e) => {
                    if (e.target.closest('[data-stop-row-click]')) return;
                    if (doc && doc.arquivo_path) window.open(`/crm/colaboradores/documentos/${docId}/arquivo`, '_blank');
                };
                row.onclick = (e) => {
                    if (e.target.closest('[data-stop-row-click]')) return;
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        row.classList.toggle('selected');
                        if (selectedItems.has(key)) selectedItems.delete(key);
                        else selectedItems.add(key);
                    } else {
                        document.querySelectorAll('.file-row.selected').forEach(r => r.classList.remove('selected'));
                        selectedItems.clear();
                        row.classList.add('selected');
                        selectedItems.add(key);
                    }
                    updateStatusBar();
                };
                row.oncontextmenu = (e) => {
                    if (e.target.closest('[data-stop-row-click]')) return;
                    e.preventDefault();
                    document.querySelectorAll('.file-row.selected').forEach(r => r.classList.remove('selected'));
                    selectedItems.clear();
                    row.classList.add('selected');
                    selectedItems.add(key);
                    updateStatusBar();
                };
                setupDragFile(row, docId, idx);
            });
        }
        updateStatusBar();
    }

    function setupDragFile(row, docId, idx) {
        row.draggable = true;
        row.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('application/json', JSON.stringify({ docId, idx, type: 'document' }));
            e.dataTransfer.effectAllowed = 'move';
        });
    }

    function setupDropZone() {
        const fileList = document.querySelector('.file-list');
        if (!fileList) return;
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(ev => {
            fileList.addEventListener(ev, (e) => { e.preventDefault(); e.stopPropagation(); });
        });
        ['dragenter', 'dragover'].forEach(ev => { fileList.addEventListener(ev, () => fileList.classList.add('drag-over')); });
        ['dragleave', 'drop'].forEach(ev => { fileList.addEventListener(ev, () => fileList.classList.remove('drag-over')); });
        fileList.addEventListener('drop', (e) => {
            if (e.dataTransfer.getData('application/json')) return;
            const files = e.dataTransfer.files;
            if (files && files.length > 0) uploadDroppedFiles(files);
        });
    }

    function setupTreeDropTargets() {
        document.querySelectorAll('.tree-item').forEach(el => {
            el.addEventListener('dragover', (e) => {
                try {
                    const dt = e.dataTransfer.getData('application/json');
                    if (JSON.parse(dt || '{}').type === 'document') {
                        e.preventDefault();
                        e.dataTransfer.dropEffect = 'move';
                        el.classList.add('drop-target');
                    }
                } catch (_) {}
            });
            el.addEventListener('dragleave', () => el.classList.remove('drop-target'));
            el.addEventListener('drop', (e) => {
                e.preventDefault();
                el.classList.remove('drop-target');
                try {
                    const { docId, idx } = JSON.parse(e.dataTransfer.getData('application/json') || '{}');
                    moverDocumentoParaPasta(docId, idx, decodeURIComponent(el.dataset.path || ''));
                } catch (_) {}
            });
        });
    }

    async function excluirSelecionados() {
        const docs = Array.from(selectedItems).filter(k => k.startsWith('doc-')).map(k => parseInt(k.replace('doc-','')));
        if (docs.length === 0) return setStatus('Selecione itens para excluir.', 'error');
        if (!confirm(`Excluir ${docs.length} documento(s)?`)) return;
        for (const id of docs) {
            const idx = documentos.findIndex(d => d.id === id);
            if (idx >= 0) await excluirDocumento(id, idx, true);
        }
    }

    async function moverDocumentoParaPasta(docId, idx, destPath) {
        const fd = new FormData();
        fd.append('pasta_destino', normalize(destPath));
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        try {
            const resp = await fetch(`/crm/colaboradores/documentos/${docId}/mover`, { method: 'POST', body: fd });
            const data = await resp.json();
            if (!resp.ok || !data.success) throw new Error(data.message || 'Falha ao mover.');
            documentos[idx] = data.documento;
            setStatus('Documento movido.');
            renderAll();
        } catch (e) {
            setStatus(e.message || 'Erro ao mover documento.', 'error');
        }
    }

    async function uploadDroppedFiles(files) {
        const fd = new FormData();
        const dataVenc = document.getElementById('dataVencimentoOpcional')?.value || '';
        if (dataVenc) fd.append('data_vencimento', dataVenc);
        let count = 0;
        Array.from(files).forEach(f => {
            if (f instanceof File && !f.name.startsWith('.')) {
                const rel = normalize(f.webkitRelativePath || f.name);
                const full = normalize(currentPath) ? `${normalize(currentPath)}/${rel}` : rel;
                fd.append('arquivos[]', f);
                fd.append('caminhos[]', full);
                count++;
            }
        });
        if (count === 0) return setStatus('Nenhum arquivo válido para enviar.', 'error');
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        try {
            const resp = await fetch(`/crm/colaboradores/${colaboradorId}/documentos`, { method: 'POST', body: fd });
            const data = await resp.json();
            if (!resp.ok || !data.success) throw new Error(data.message || 'Falha no upload.');
            const novos = Array.isArray(data.documentos) ? data.documentos : (data.documento ? [data.documento] : []);
            documentos = documentos.concat(novos);
            setStatus(`${count} arquivo(s) enviado(s).`);
            renderAll();
        } catch (e) {
            setStatus(e.message || 'Erro no upload.', 'error');
        }
    }

    function renderAll() {
        ensurePathExpanded();
        renderTree();
        renderBreadcrumb();
        renderRows();
        setupDropZone();
        setupTreeDropTargets();
    }

    function openFolder(path) {
        currentPath = normalize(path);
        expandedPaths.add(path);
        renderAll();
    }

    function subirPasta() {
        if (!currentPath) return;
        const parts = currentPath.split('/');
        parts.pop();
        openFolder(parts.join('/'));
    }

    async function criarPasta() {
        const nome = prompt('Nome da nova pasta (ex.: NRS):', 'NRS');
        if (nome === null) return;
        const limpo = nome.trim();
        if (!limpo) return setStatus('Nome da pasta inválido.', 'error');
        const fd = new FormData();
        fd.append('nome', limpo);
        fd.append('pasta_pai', currentPath);
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        try {
            const resp = await fetch(`/crm/colaboradores/${colaboradorId}/pastas`, { method: 'POST', body: fd });
            const data = await resp.json();
            if (!resp.ok || !data.success) throw new Error(data.message || 'Falha ao criar pasta.');
            pastas = Array.isArray(data.pastas) ? data.pastas : pastas;
            setStatus('Pasta criada com sucesso.');
            renderAll();
        } catch (e) {
            setStatus(e.message || 'Erro ao criar pasta.', 'error');
        }
    }

    async function renomearPasta(path) {
        const original = normalize(path);
        if (!original) return;
        const parts = original.split('/');
        const atual = parts[parts.length - 1];
        const novo = prompt(`Novo nome para "${atual}"`, atual);
        if (novo === null) return;
        const limpo = novo.trim();
        if (!limpo) return setStatus('Nome da pasta inválido.', 'error');
        const novoPath = [...parts.slice(0, -1), limpo].filter(Boolean).join('/');
        const fd = new FormData();
        fd.append('pasta_atual', original);
        fd.append('pasta_nova', novoPath);
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        try {
            const resp = await fetch(`/crm/colaboradores/${colaboradorId}/pastas/renomear`, { method: 'POST', body: fd });
            const data = await resp.json();
            if (!resp.ok || !data.success) throw new Error(data.message || 'Falha ao renomear pasta.');
            documentos = Array.isArray(data.documentos) ? data.documentos : documentos;
            pastas = Array.isArray(data.pastas) ? data.pastas : pastas;
            if (currentPath === original || currentPath.startsWith(original + '/')) {
                currentPath = currentPath.replace(original, novoPath);
            }
            setStatus('Pasta renomeada com sucesso.');
            renderAll();
        } catch (e) {
            setStatus(e.message || 'Erro ao renomear pasta.', 'error');
        }
    }

    function renomearPastaAtual() {
        if (!currentPath) return setStatus('Selecione uma pasta (não a raiz).', 'error');
        renomearPasta(currentPath);
    }

    async function enviarUpload() {
        const dataVencimento = document.getElementById('dataVencimentoOpcional')?.value || '';
        const arquivoInput = document.getElementById('arquivoInput');
        const fd = new FormData();
        if (dataVencimento) fd.append('data_vencimento', dataVencimento);
        let count = 0;
        if (arquivoInput.files[0]) {
            const f = arquivoInput.files[0];
            fd.append('arquivos[]', f);
            fd.append('caminhos[]', normalize(currentPath) ? `${normalize(currentPath)}/${f.name}` : f.name);
            count++;
        }
        if (count === 0) return setStatus('Escolha um arquivo para enviar à pasta atual.', 'error');
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        try {
            const resp = await fetch(`/crm/colaboradores/${colaboradorId}/documentos`, { method: 'POST', body: fd });
            const data = await resp.json();
            if (!resp.ok || !data.success) throw new Error(data.message || 'Falha no upload.');
            const novos = Array.isArray(data.documentos) ? data.documentos : (data.documento ? [data.documento] : []);
            documentos = documentos.concat(novos);
            arquivoInput.value = '';
            setStatus('Upload concluído. Ajuste o nome e a validade na linha do arquivo, se precisar.');
            renderAll();
        } catch (e) {
            setStatus(e.message || 'Erro no upload.', 'error');
        }
    }

    async function renomearDocumento(docId) {
        const idx = documentos.findIndex(d => d.id === docId);
        const doc = idx >= 0 ? documentos[idx] : null;
        if (!doc) return;
        const atual = displayName(doc);
        const novo = prompt('Nome que aparece na lista (ex.: NR-35, ASO):', atual);
        if (novo === null) return;
        const limpo = novo.trim();
        if (!limpo) return setStatus('Nome inválido.', 'error');
        try {
            const resp = await fetch('/crm/colaboradores/documentos/' + docId + '/certificacao', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ nome_documento: limpo }),
            });
            const data = await resp.json();
            if (!resp.ok || !data.success) throw new Error(data.message || (data.errors && JSON.stringify(data.errors)) || 'Falha ao renomear.');
            if (data.documento && idx >= 0) documentos[idx] = data.documento;
            setStatus('Nome atualizado.');
            renderAll();
        } catch (e) {
            setStatus(e.message || 'Erro ao renomear.', 'error');
        }
    }

    async function moverDocumento(docId, index) {
        const dest = prompt('Mover para pasta (vazio = raiz):', currentPath || '');
        if (dest === null) return;
        const fd = new FormData();
        fd.append('pasta_destino', normalize(dest));
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        try {
            const resp = await fetch(`/crm/colaboradores/documentos/${docId}/mover`, { method: 'POST', body: fd });
            const data = await resp.json();
            if (!resp.ok || !data.success) throw new Error(data.message || 'Falha ao mover.');
            documentos[index] = data.documento;
            setStatus('Documento movido.');
            renderAll();
        } catch (e) {
            setStatus(e.message || 'Erro ao mover documento.', 'error');
        }
    }

    async function excluirDocumento(docId, index, skipConfirm = false) {
        if (!skipConfirm && !confirm('Deseja excluir este documento?')) return;
        try {
            const resp = await fetch(`/crm/colaboradores/documentos/${docId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            const data = await resp.json();
            if (!resp.ok || !data.success) throw new Error(data.message || 'Falha ao excluir.');
            documentos.splice(index, 1);
            setStatus('Documento excluído.');
            renderAll();
        } catch (e) {
            setStatus(e.message || 'Erro ao excluir documento.', 'error');
        }
    }

    document.getElementById('searchInput')?.addEventListener('input', () => renderRows());

    renderAll();
</script>
</body>
</html>
