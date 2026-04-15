<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Planta: {{ $planta->nome }} - Sistema VIDEIRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        .planta-page {
            font-family: 'Inter', sans-serif;
            background: #e9ecef;
            color: #212529;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .print-only { display: none; }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            .planta-page { background: #fff; }
            .map-card { box-shadow: none !important; border: 1px solid #ccc !important; }
            .canvas-inner { overflow: visible !important; }
        }

        .planta-header {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 14px 22px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }
        .hdr-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }
        .back-link {
            color: #495057;
            text-decoration: none;
            font-size: 22px;
            line-height: 1;
            padding: 4px 8px;
            border-radius: 8px;
            flex-shrink: 0;
        }
        .back-link:hover { background: #f1f3f5; color: #212529; }
        .hdr-titles h1 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #212529;
            letter-spacing: -0.02em;
        }
        .hdr-sub {
            font-size: 13px;
            color: #868e96;
            margin-top: 2px;
        }
        .hdr-center {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .hdr-center label {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
        }
        .hdr-center input[type="month"] {
            background: #fff;
            border: 1px solid #ced4da;
            color: #212529;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            min-width: 200px;
        }
        .mes-ref-clear {
            border: none;
            background: none;
            color: #6c757d;
            font-size: 12px;
            cursor: pointer;
            text-decoration: underline;
            padding: 4px;
        }
        .mes-ref-clear:hover { color: #495057; }
        .planta-resumo-strip {
            font-size: 13px;
            color: #495057;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 8px 14px;
            margin-top: 8px;
        }
        .planta-resumo-strip .sep { margin: 0 8px; color: #adb5bd; }
        .hdr-right {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-self: end;
            flex-wrap: wrap;
        }
        .btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            background: #e7f1ff;
            color: #0d6efd;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-pdf:hover { background: #d0e4ff; }
        .btn-delete-planta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            border: 2px solid #dc3545;
            background: #fff;
            color: #dc3545;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-delete-planta:hover { background: #fff5f5; }

        .page-body {
            flex: 1;
            min-height: 0;
            width: 100%;
            max-width: 1680px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 240px;
            gap: 20px;
            align-items: start;
        }
        @media (max-width: 960px) {
            .planta-header {
                grid-template-columns: 1fr;
                justify-items: stretch;
            }
            .hdr-right { justify-self: stretch; justify-content: flex-start; }
            .page-body { grid-template-columns: 1fr; }
        }

        .map-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
            position: relative;
            overflow: hidden;
            min-height: min(72vh, calc(100vh - 200px));
            display: flex;
            flex-direction: column;
        }
        .canvas-inner {
            flex: 1;
            min-height: 480px;
            overflow: auto;
            cursor: grab;
            text-align: center;
            background: #fff;
        }
        .canvas-inner:active { cursor: grabbing; }
        .canvas-inner.mode-colocar { cursor: none; }
        .canvas-inner.panning { cursor: grabbing; }

        .planta-img-wrap {
            position: relative;
            display: inline-block;
            vertical-align: top;
            cursor: inherit;
            touch-action: manipulation;
            text-align: left;
        }
        .planta-img-wrap img {
            display: block;
            max-width: none;
            user-select: none;
            pointer-events: none;
        }
        .markers-layer {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
        }
        .marker-pin {
            position: absolute;
            transform: translate(-50%, -50%);
            pointer-events: auto;
            cursor: grab;
            z-index: 2;
        }
        .marker-pin.dragging { cursor: grabbing; z-index: 20; }
        .markers-layer .marker {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.35);
            transition: transform 0.15s;
        }
        .markers-layer .marker.pendente {
            background: radial-gradient(circle at 32% 28%, #ff8a8a, #dc3545 55%, #a71d2a);
        }
        .markers-layer .marker.realizado {
            background: radial-gradient(circle at 32% 28%, #8ce99e, #28a745 55%, #1c7430);
        }
        .markers-layer .marker.duplicado {
            background: radial-gradient(circle at 32% 28%, #c9a9f0, #6f42c1 55%, #4a148c);
        }
        .markers-layer .marker.em-execucao {
            background: radial-gradient(circle at 32% 28%, #ffd699, #fd7e14 55%, #c65a00);
        }

        .ghost-place-layer {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
            overflow: hidden;
        }
        .ghost-ball {
            position: absolute;
            width: 34px;
            height: 34px;
            margin-left: -17px;
            margin-top: -17px;
            border-radius: 50%;
            display: none;
            box-sizing: border-box;
            border: 3px solid #ffc107;
            background: radial-gradient(circle at 35% 30%, #fffde7, #ffeb3b 50%, #fbc02d);
            box-shadow: 0 0 0 5px rgba(255, 193, 7, 0.35), 0 6px 20px rgba(0, 0, 0, 0.25);
            animation: ghostPulse 1.1s ease-in-out infinite;
        }
        .ghost-ball.visible { display: block; }
        @keyframes ghostPulse {
            0%, 100% { transform: scale(1); opacity: 0.95; }
            50% { transform: scale(1.06); opacity: 1; }
        }
        .ghost-label {
            position: absolute;
            left: 0;
            top: 0;
            max-width: 220px;
            padding: 6px 10px;
            background: rgba(33, 37, 41, 0.92);
            border: 1px solid #ffc107;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            color: #fff8e1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: none;
            pointer-events: none;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }
        .ghost-label.visible { display: block; }
        .marker-pin:hover .marker { transform: scale(1.15); }
        .marker-label {
            position: absolute;
            left: 50%;
            top: 100%;
            transform: translateX(-50%);
            margin-top: 4px;
            max-width: 160px;
            padding: 2px 8px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            color: #495057;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            pointer-events: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .zoom-controls {
            position: absolute;
            left: 16px;
            top: 16px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .map-tool-btn {
            width: 44px;
            height: 44px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 600;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #fff;
            color: #495057;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }
        .map-tool-btn:hover { background: #f8f9fa; color: #212529; }

        .legend-box {
            position: absolute;
            right: 16px;
            bottom: 16px;
            z-index: 10;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 12px 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            max-width: 260px;
        }
        .legend-box .legend-title {
            font-size: 13px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 10px;
        }
        .legend-hint {
            font-size: 11px;
            color: #868e96;
            line-height: 1.4;
            margin-bottom: 10px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            margin-bottom: 6px;
            color: #495057;
        }
        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }
        .legend-dot.realizado { background: #28a745; }
        .legend-dot.pendente { background: #dc3545; }
        .legend-dot.duplicado { background: #6f42c1; }
        .legend-dot.em-execucao { background: #fd7e14; }

        .equip-sidebar {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
            padding: 20px;
            position: sticky;
            top: 20px;
        }
        .equip-sidebar h2 {
            font-size: 13px;
            font-weight: 700;
            color: #868e96;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 12px;
        }
        .equip-search-wrap {
            position: relative;
            margin-bottom: 16px;
        }
        .equip-search-wrap label.sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .equip-search-wrap input[type="text"] {
            width: 100%;
            background: #fff;
            border: 1px solid #ced4da;
            color: #212529;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
        }
        .equip-search-wrap input[type="text"]:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }
        .equip-dropdown {
            display: none;
            position: absolute;
            left: 0;
            right: 0;
            top: calc(100% + 4px);
            max-height: 260px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            z-index: 60;
        }
        .equip-dropdown.open { display: block; }
        .equip-dropdown button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 10px 12px;
            border: none;
            background: none;
            font-size: 14px;
            color: #212529;
            cursor: pointer;
            line-height: 1.35;
        }
        .equip-dropdown button:hover,
        .equip-dropdown button.active {
            background: #e7f1ff;
        }
        .equip-dropdown .equip-dd-empty {
            padding: 12px;
            color: #868e96;
            font-size: 13px;
        }
        .equip-sidebar .hint {
            font-size: 12px;
            color: #868e96;
            line-height: 1.45;
            margin-bottom: 20px;
        }
        .equip-preview-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 16px 0;
        }
        .equip-preview-ball {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            background: radial-gradient(circle at 32% 28%, #ff8a8a, #dc3545 55%, #a71d2a);
            transition: opacity 0.2s, transform 0.2s;
        }
        .equip-preview-wrap.empty .equip-preview-ball {
            opacity: 0.35;
            transform: scale(0.92);
            background: radial-gradient(circle at 32% 28%, #dee2e6, #adb5bd 55%, #868e96);
        }
        .equip-preview-label {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: #495057;
            line-height: 1.35;
            max-width: 100%;
            padding: 8px 12px;
            background: #212529;
            color: #fff;
            border-radius: 999px;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .equip-preview-wrap.empty .equip-preview-label {
            background: #adb5bd;
            color: #fff;
        }

        .no-image {
            padding: 48px 24px;
            text-align: center;
            color: #868e96;
            font-size: 15px;
        }

        .marker-menu {
            position: fixed;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 6px 0;
            min-width: 180px;
            z-index: 1000;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        .marker-menu button {
            display: block;
            width: 100%;
            padding: 10px 16px;
            text-align: left;
            background: none;
            border: none;
            color: #212529;
            cursor: pointer;
            font-size: 14px;
        }
        .marker-menu button:hover { background: #f8f9fa; }
        .marker-menu .danger { color: #dc3545; }
        .marker-menu a.marker-menu-link {
            display: block;
            padding: 10px 16px;
            text-align: left;
            color: #0d6efd;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-top: 1px solid #e9ecef;
        }
        .marker-menu a.marker-menu-link:hover { background: #e7f1ff; }
    </style>
</head>
<body class="planta-page">
    <div class="print-only" style="padding:16px 0;font-size:12px;color:#666;">
        {{ $cliente->nome ?? 'Cliente' }} — {{ $planta->nome ?? 'Planta' }} — <span id="printMesRef"></span>
    </div>

    <header class="planta-header no-print">
        <div class="hdr-left">
            <a href="{{ route('crm.clientes.show', $cliente) }}" class="back-link" title="Voltar ao cliente">←</a>
            <div class="hdr-titles">
                <h1>{{ $planta->nome ?: 'Planta' }}</h1>
                <p class="hdr-sub">Scroll = zoom · Alt+arrastar ou botão do meio = mover a vista · clique = posicionar equipamento</p>
            </div>
        </div>
        <div class="hdr-center">
            <label for="mesRef">Mês Ref:</label>
            <input type="month" id="mesRef" value="{{ now()->format('Y-m') }}">
            <button type="button" class="mes-ref-clear" id="mesRefClear" title="Mostrar marcadores cadastrados sem mês (mapa fixo antigo)">sem mês</button>
        </div>
        <div class="hdr-right">
            <button type="button" class="btn-pdf" id="btnPdf" title="Abre a impressão — escolha &quot;Salvar como PDF&quot;">
                <span>⬇</span> Baixar PDF
            </button>
            <form action="{{ route('crm.plantas.destroy', $planta) }}" method="post" style="display:inline;" onsubmit="return confirm('Excluir esta planta e todos os marcadores? Não dá para desfazer.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-planta"><span>🗑</span> Excluir Planta</button>
            </form>
        </div>
        @php
            $plantaResumo = $plantaResumo ?? ['servicos_em_aberto' => 0, 'minutos_mes' => 0];
            $horasMes = round(max(0, (int) ($plantaResumo['minutos_mes'] ?? 0)) / 60, 1);
        @endphp
        <div class="planta-resumo-strip no-print" style="grid-column: 1 / -1;" title="Resumo do cliente (mês atual)">
            <strong>Resumo:</strong>
            {{ (int) ($plantaResumo['servicos_em_aberto'] ?? 0) }} tarefa(s) em aberto (CRM)
            <span class="sep">·</span>
            {{ $horasMes }} h registradas no mês
        </div>
    </header>

    <div class="page-body">
        <div class="map-card print-area" id="mapCard">
            <div class="zoom-controls no-print">
                <button type="button" class="map-tool-btn" id="zoomIn" title="Aumentar">+</button>
                <button type="button" class="map-tool-btn" id="zoomOut" title="Diminuir">−</button>
                <button type="button" class="map-tool-btn" id="zoomReset" title="Zoom 100%">⟲</button>
                <button type="button" class="map-tool-btn" id="btnFullscreen" title="Tela cheia">⛶</button>
            </div>
            <div class="canvas-inner" id="canvasInner">
                @if($planta->imagem_path)
                    @php $imgUrl = $planta->imagem_url ?? ($planta->imagem_path ? asset('storage/' . ltrim(str_replace('\\', '/', $planta->imagem_path), '/')) : ''); @endphp
                    <div class="planta-img-wrap" id="imgWrap">
                        <img id="plantaImg" src="{{ $imgUrl }}" alt="{{ $planta->nome }}" draggable="false">
                        <div class="markers-layer" id="markersLayer"></div>
                        <div class="ghost-place-layer" id="ghostPlaceLayer">
                            <div class="ghost-ball" id="ghostBall"></div>
                            <div class="ghost-label" id="ghostLabel"></div>
                        </div>
                    </div>
                @else
                    <div class="no-image">Esta planta ainda não tem imagem. Adicione uma imagem no cadastro do cliente (aba Planta baixa).</div>
                @endif
            </div>
            <div class="legend-box">
                <div class="legend-title">Legenda (<span id="legendMesLabel">—</span>)</div>
                <div class="legend-hint">Cor do <strong>serviço CRM mais recente</strong> por equipamento: <strong>laranja</strong> = em andamento ou pausado; <strong>verde</strong> = último serviço concluído. Se não houver serviço aplicável: cores de <strong>manutenção</strong> na planta (verde temporário após OK, etc.).</div>
                <div class="legend-item"><span class="legend-dot em-execucao"></span> Serviço em execução</div>
                <div class="legend-item"><span class="legend-dot realizado"></span> Realizado / serviço concluído</div>
                <div class="legend-item"><span class="legend-dot pendente"></span> Pendente (manutenção)</div>
                <div class="legend-item"><span class="legend-dot duplicado"></span> Duplicado</div>
            </div>
        </div>

        <aside class="equip-sidebar no-print">
            <h2>Equipamento</h2>
            @php
                $equipamentosBusca = $equipamentos->map(function ($eq) {
                    $label = $eq->nome ?? '';
                    if (! empty($eq->tag)) {
                        $label .= ' (' . $eq->tag . ')';
                    }
                    return ['id' => (int) $eq->id, 'label' => $label];
                })->values();
            @endphp
            <div class="equip-search-wrap">
                <label class="sr-only" for="equipSearch">Buscar equipamento por nome</label>
                <input type="text" id="equipSearch" autocomplete="off" placeholder="Digite para filtrar o equipamento…" spellcheck="false">
                <div class="equip-dropdown" id="equipDropdown" role="listbox" aria-label="Lista de equipamentos"></div>
            </div>
            <p class="hint"><strong>Digite</strong> para filtrar por nome ou tag; escolha na lista (ou ↑ ↓ e <strong>Enter</strong>). A <strong>bolinha amarela</strong> segue o mouse na planta — <strong>clique</strong> para fixar. Arraste a bolinha colorida para ajustar.</p>
            <div class="equip-preview-wrap empty" id="equipPreviewWrap">
                <div class="equip-preview-ball" id="equipPreviewBall"></div>
                <div class="equip-preview-label" id="equipPreviewLabel">Nenhum selecionado</div>
            </div>
        </aside>
    </div>

    <script>
(function() {
    const plantaId = {{ $planta->id }};
    const EQUIPAMENTOS = @json($equipamentosBusca);
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const markersLayer = document.getElementById('markersLayer');
    const imgWrap = document.getElementById('imgWrap');
    const plantaImg = document.getElementById('plantaImg');
    const canvasInner = document.getElementById('canvasInner');
    const equipSearch = document.getElementById('equipSearch');
    const equipDropdown = document.getElementById('equipDropdown');
    const mesRefInput = document.getElementById('mesRef');

    let selectedEquipId = null;
    let selectedEquipLabel = '';
    let equipHighlightIdx = -1;
    let equipDropdownCloseTimer = null;
    const ghostBall = document.getElementById('ghostBall');
    const ghostLabel = document.getElementById('ghostLabel');
    const legendMesLabel = document.getElementById('legendMesLabel');
    const printMesRef = document.getElementById('printMesRef');
    const equipPreviewWrap = document.getElementById('equipPreviewWrap');
    const equipPreviewLabel = document.getElementById('equipPreviewLabel');
    const mapCard = document.getElementById('mapCard');

    let marcadores = [];
    let scale = 1;
    let dragState = null;
    let plantaPan = null;

    function normalizeStr(s) {
        return String(s || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function filterEquipamentos(query) {
        const n = normalizeStr(query);
        if (!n) {
            return EQUIPAMENTOS.slice();
        }
        return EQUIPAMENTOS.filter(function (e) {
            return normalizeStr(e.label).indexOf(n) !== -1;
        });
    }

    function closeEquipDropdown() {
        if (equipDropdownCloseTimer) {
            clearTimeout(equipDropdownCloseTimer);
            equipDropdownCloseTimer = null;
        }
        if (equipDropdown) {
            equipDropdown.classList.remove('open');
        }
        equipHighlightIdx = -1;
    }

    function openEquipDropdown() {
        if (equipDropdownCloseTimer) {
            clearTimeout(equipDropdownCloseTimer);
            equipDropdownCloseTimer = null;
        }
        if (equipDropdown) {
            equipDropdown.classList.add('open');
        }
    }

    function scheduleCloseEquipDropdown() {
        if (equipDropdownCloseTimer) {
            clearTimeout(equipDropdownCloseTimer);
        }
        equipDropdownCloseTimer = setTimeout(closeEquipDropdown, 200);
    }

    function renderEquipDropdown(list) {
        if (!equipDropdown) {
            return;
        }
        equipDropdown.innerHTML = '';
        if (!list.length) {
            const empty = document.createElement('div');
            empty.className = 'equip-dd-empty';
            empty.textContent = 'Nenhum equipamento encontrado.';
            equipDropdown.appendChild(empty);
            equipHighlightIdx = -1;
            return;
        }
        if (equipHighlightIdx < 0) {
            equipHighlightIdx = 0;
        }
        if (equipHighlightIdx >= list.length) {
            equipHighlightIdx = list.length - 1;
        }
        list.forEach(function (e, idx) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = e.label;
            btn.dataset.id = String(e.id);
            if (idx === equipHighlightIdx) {
                btn.classList.add('active');
            }
            btn.addEventListener('mousedown', function (ev) {
                ev.preventDefault();
            });
            btn.addEventListener('click', function () {
                onSelectEquip(e.id, e.label);
            });
            equipDropdown.appendChild(btn);
        });
    }

    function onSelectEquip(id, label) {
        selectedEquipId = id;
        selectedEquipLabel = label;
        if (equipSearch) {
            equipSearch.value = label;
        }
        closeEquipDropdown();
        syncColocarMode();
    }

    function formatLegendMes(ym) {
        if (!ym || ym.length < 7) return '';
        const parts = ym.split('-');
        const y = parseInt(parts[0], 10);
        const m = parseInt(parts[1], 10);
        if (!y || !m) return ym;
        const d = new Date(y, m - 1, 1);
        return d.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
    }

    function syncLegendMesLabels() {
        let label = '—';
        if (mesRefInput && mesRefInput.value) {
            label = formatLegendMes(mesRefInput.value);
        } else if (mesRefInput && !mesRefInput.value) {
            label = 'sem período';
        }
        if (legendMesLabel) legendMesLabel.textContent = label;
        if (printMesRef) printMesRef.textContent = mesRefInput && mesRefInput.value ? ('Mês ref: ' + formatLegendMes(mesRefInput.value)) : 'Mapa geral (sem mês)';
    }

    function syncEquipPreview() {
        if (!equipPreviewWrap || !equipPreviewLabel) {
            return;
        }
        if (!selectedEquipId) {
            equipPreviewWrap.classList.add('empty');
            equipPreviewLabel.textContent = 'Nenhum selecionado';
            return;
        }
        equipPreviewWrap.classList.remove('empty');
        equipPreviewLabel.textContent = selectedEquipLabel || '';
    }

    function hideGhost() {
        if (ghostBall) ghostBall.classList.remove('visible');
        if (ghostLabel) ghostLabel.classList.remove('visible');
    }

    function syncColocarMode() {
        if (canvasInner) {
            canvasInner.classList.toggle('mode-colocar', !!selectedEquipId);
        }
        if (!selectedEquipId) {
            hideGhost();
        }
        syncEquipPreview();
    }

    function updateGhost(clientX, clientY) {
        if (!ghostBall || !ghostLabel || !imgWrap) return;
        if (dragState) {
            hideGhost();
            return;
        }
        if (!selectedEquipId) {
            hideGhost();
            return;
        }
        const pos = percentFromPlantaClick(clientX, clientY);
        if (!pos) {
            hideGhost();
            return;
        }
        ghostBall.style.left = pos.x + '%';
        ghostBall.style.top = pos.y + '%';
        ghostLabel.style.left = pos.x + '%';
        ghostLabel.style.top = pos.y + '%';
        ghostLabel.style.transform = 'translate(-50%, 22px)';
        ghostLabel.textContent = selectedEquipLabel || '';
        ghostBall.classList.add('visible');
        ghostLabel.classList.add('visible');
    }

    function getMesRef() {
        if (!mesRefInput || !mesRefInput.value) return null;
        return mesRefInput.value + '-01';
    }

    function loadMarcadores() {
        const mes = getMesRef();
        let url = `/crm/plantas/${plantaId}/marcadores`;
        if (mes) url += '?mes_ref=' + encodeURIComponent(mes);
        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
            .then(async r => {
                const text = await r.text();
                let data = {};
                try {
                    data = text ? JSON.parse(text) : {};
                } catch (err) {
                    console.warn('Marcadores: resposta não é JSON.', String(text).slice(0, 120));
                    showPlantaToast('Resposta inválida ao carregar marcadores.');
                    return;
                }
                if (data.message && data.success === false) {
                    showPlantaToast(data.message);
                }
                marcadores = data.marcadores || [];
                renderMarkers();
            })
            .catch(() => {
                marcadores = [];
                renderMarkers();
                showPlantaToast('Falha de rede ao carregar marcadores.');
            });
    }

    function equipLabel(m) {
        if (!m.equipamento) return '';
        let t = m.equipamento.nome || '';
        if (m.equipamento.tag) t += ' · ' + m.equipamento.tag;
        return t || 'Equipamento';
    }

    /** Cor na bolinha: serviço CRM (laranja/verde) tem prioridade sobre manutenção (pendente/realizado/duplicado). */
    function corVisualPlanta(m) {
        const svc = m.planta_cor_servico;
        if (svc === 'servico_execucao') return 'em-execucao';
        if (svc === 'servico_concluido') return 'realizado';
        return m.status || 'pendente';
    }

    function renderMarkers() {
        if (!markersLayer) return;
        markersLayer.innerHTML = '';
        marcadores.forEach(m => {
            const pin = document.createElement('div');
            pin.className = 'marker-pin';
            pin.style.left = (m.pos_x || 0) + '%';
            pin.style.top = (m.pos_y || 0) + '%';
            pin.dataset.id = m.id;

            const dot = document.createElement('div');
            dot.className = 'marker ' + corVisualPlanta(m);
            pin.appendChild(dot);

            const lbl = document.createElement('div');
            lbl.className = 'marker-label';
            lbl.textContent = equipLabel(m);
            pin.appendChild(lbl);

            let tip = equipLabel(m) + ' — clique: menu · arraste: mover';
            if (m.planta_cor_servico === 'servico_execucao') {
                tip += ' • Serviço CRM: em andamento ou pausado';
            } else if (m.planta_cor_servico === 'servico_concluido') {
                tip += ' • Serviço CRM: concluído';
            }
            if (m.servico_relatorio_url) {
                tip += ' • Clique na bolinha: menu com atalho ao relatório';
            }
            if (m.status === 'realizado' && m.realizado_em) {
                try {
                    const d = new Date(m.realizado_em);
                    const lim = new Date(d);
                    lim.setMonth(lim.getMonth() + 1);
                    tip += ' • Manutenção OK até: ' + lim.toLocaleDateString('pt-BR');
                } catch (e) { /* ignore */ }
            }
            pin.title = tip;

            pin.addEventListener('mousedown', (e) => {
                e.preventDefault();
                e.stopPropagation();
                hideGhost();
                dragState = {
                    id: m.id,
                    startX: e.clientX,
                    startY: e.clientY,
                    moved: false,
                    origX: m.pos_x,
                    origY: m.pos_y
                };
                pin.classList.add('dragging');
            });

            pin.addEventListener('click', (e) => {
                e.stopPropagation();
                if (dragState && dragState.moved) return;
                showMarkerMenu(m, e);
            });

            markersLayer.appendChild(pin);
        });
    }

    function showMarkerMenu(m, e) {
        const existing = document.getElementById('markerMenu');
        if (existing) existing.remove();
        const menu = document.createElement('div');
        menu.id = 'markerMenu';
        menu.className = 'marker-menu';
        menu.style.left = e.clientX + 'px';
        menu.style.top = e.clientY + 'px';
        const statuses = [
            { k: 'realizado', l: 'Realizado' },
            { k: 'pendente', l: 'Pendente' },
            { k: 'duplicado', l: 'Duplicado' }
        ];
        statuses.forEach(s => {
            const btn = document.createElement('button');
            btn.textContent = s.l;
            if (m.status === s.k) btn.textContent += ' ✓';
            btn.onclick = () => { updateMarcadorStatus(m.id, s.k); menu.remove(); };
            menu.appendChild(btn);
        });
        if (m.servico_relatorio_url) {
            const rel = document.createElement('a');
            rel.className = 'marker-menu-link';
            rel.href = m.servico_relatorio_url;
            rel.target = '_blank';
            rel.rel = 'noopener noreferrer';
            rel.textContent = '📄 Relatório do serviço';
            rel.onclick = function (ev) {
                ev.stopPropagation();
                menu.remove();
            };
            menu.appendChild(rel);
        }
        if (m.servico_atendimento_url) {
            const att = document.createElement('a');
            att.className = 'marker-menu-link';
            att.href = m.servico_atendimento_url;
            att.target = '_blank';
            att.rel = 'noopener noreferrer';
            att.textContent = '🗂 Atendimento (ficha)';
            att.onclick = function (ev) {
                ev.stopPropagation();
                menu.remove();
            };
            menu.appendChild(att);
        }
        const del = document.createElement('button');
        del.textContent = 'Remover da planta';
        del.className = 'danger';
        del.onclick = () => { deleteMarcador(m.id); menu.remove(); };
        menu.appendChild(del);
        document.body.appendChild(menu);
        const close = () => { menu.remove(); document.removeEventListener('click', close); };
        setTimeout(() => document.addEventListener('click', close), 0);
    }

    function updateMarcadorStatus(id, status) {
        fetch(`/crm/plantas/marcadores/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ status })
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const i = marcadores.findIndex(x => x.id == id);
                    if (i >= 0) marcadores[i] = data.marcador;
                    renderMarkers();
                }
            });
    }

    function patchMarcadorPos(id, px, py) {
        fetch(`/crm/plantas/marcadores/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ pos_x: px, pos_y: py })
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const i = marcadores.findIndex(x => x.id == id);
                    if (i >= 0) marcadores[i] = data.marcador;
                }
            });
    }

    function deleteMarcador(id) {
        fetch(`/crm/plantas/marcadores/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    marcadores = marcadores.filter(x => x.id != id);
                    renderMarkers();
                }
            });
    }

    function showPlantaToast(msg) {
        let el = document.getElementById('plantaToast');
        if (!el) {
            el = document.createElement('div');
            el.id = 'plantaToast';
            el.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:2000;background:#212529;color:#fff;padding:12px 20px;border-radius:10px;font-size:14px;max-width:90%;box-shadow:0 8px 24px rgba(0,0,0,.2);';
            document.body.appendChild(el);
        }
        el.textContent = msg;
        el.style.display = 'block';
        clearTimeout(showPlantaToast._t);
        showPlantaToast._t = setTimeout(() => { el.style.display = 'none'; }, 4500);
    }

    function addMarkerAt(percentX, percentY) {
        if (!selectedEquipId) {
            showPlantaToast('Busque e clique em um equipamento na lista à direita; depois clique na planta.');
            if (equipSearch) {
                equipSearch.focus();
                equipSearch.style.outline = '2px solid #ffc107';
                openEquipDropdown();
                renderEquipDropdown(filterEquipamentos(equipSearch.value));
                setTimeout(function () {
                    equipSearch.style.outline = '';
                }, 2000);
            }
            return;
        }
        const status = 'pendente';
        const mes = getMesRef();
        fetch(`/crm/plantas/${plantaId}/marcadores`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({
                equipamento_id: parseInt(String(selectedEquipId), 10),
                pos_x: Math.round(percentX * 100) / 100,
                pos_y: Math.round(percentY * 100) / 100,
                status,
                mes_ref: mes
            })
        })
            .then(async r => {
                const text = await r.text();
                let data = {};
                try { data = text ? JSON.parse(text) : {}; } catch (err) { data = { message: text || r.status }; }
                if (r.status === 419) {
                    showPlantaToast('Sessão expirada. Atualize a página.');
                    return;
                }
                if (!r.ok || !data.success) {
                    const msg = data.message || (data.errors && Object.values(data.errors).flat().join(' ')) || ('Erro ' + r.status);
                    showPlantaToast(msg);
                    return;
                }
                const idx = marcadores.findIndex(x => x.id == data.marcador.id);
                if (idx >= 0) marcadores[idx] = data.marcador;
                else marcadores.push(data.marcador);
                renderMarkers();
            })
            .catch(() => showPlantaToast('Erro de rede ao salvar o marcador.'));
    }

    function percentFromPlantaClick(clientX, clientY) {
        if (!imgWrap) return null;
        const rect = imgWrap.getBoundingClientRect();
        if (rect.width < 4 || rect.height < 4) return null;
        if (clientX < rect.left || clientX > rect.right || clientY < rect.top || clientY > rect.bottom) return null;
        return {
            x: ((clientX - rect.left) / rect.width) * 100,
            y: ((clientY - rect.top) / rect.height) * 100
        };
    }

    function applyZoom() {
        if (!imgWrap) return;
        imgWrap.style.transform = 'scale(' + scale + ')';
        imgWrap.style.transformOrigin = '0 0';
    }

    document.addEventListener('mousemove', (e) => {
        if (plantaPan && canvasInner) {
            canvasInner.scrollLeft = plantaPan.sl - (e.clientX - plantaPan.x);
            canvasInner.scrollTop = plantaPan.st - (e.clientY - plantaPan.y);
            return;
        }
        if (!dragState || !imgWrap) return;
        const dx = e.clientX - dragState.startX;
        const dy = e.clientY - dragState.startY;
        if (Math.abs(dx) + Math.abs(dy) > 4) dragState.moved = true;
        const rect = imgWrap.getBoundingClientRect();
        const w = rect.width || 1;
        const h = rect.height || 1;
        let px = dragState.origX + (dx / w) * 100;
        let py = dragState.origY + (dy / h) * 100;
        px = Math.max(0, Math.min(100, px));
        py = Math.max(0, Math.min(100, py));
        const pin = markersLayer.querySelector('.marker-pin[data-id="' + dragState.id + '"]');
        if (pin) {
            pin.style.left = px + '%';
            pin.style.top = py + '%';
        }
    });

    document.addEventListener('mouseup', () => {
        if (plantaPan) {
            plantaPan = null;
            if (canvasInner) canvasInner.classList.remove('panning');
            return;
        }
        if (!dragState || !imgWrap) return;
        const pin = markersLayer.querySelector('.marker-pin[data-id="' + dragState.id + '"]');
        if (pin && dragState.moved) {
            const left = parseFloat(pin.style.left);
            const top = parseFloat(pin.style.top);
            patchMarcadorPos(dragState.id, Math.round(left * 100) / 100, Math.round(top * 100) / 100);
        }
        if (pin) pin.classList.remove('dragging');
        dragState = null;
    });

    if (canvasInner && imgWrap) {
        canvasInner.addEventListener('mousedown', (e) => {
            if (e.target.closest && e.target.closest('.marker-pin')) return;
            if (e.target.closest && e.target.closest('.zoom-controls')) return;
            if (e.button === 1 || (e.button === 0 && e.altKey)) {
                e.preventDefault();
                plantaPan = {
                    x: e.clientX,
                    y: e.clientY,
                    sl: canvasInner.scrollLeft,
                    st: canvasInner.scrollTop,
                };
                canvasInner.classList.add('panning');
            }
        });
        canvasInner.addEventListener('auxclick', (e) => {
            if (e.button === 1) e.preventDefault();
        });
        canvasInner.addEventListener('mousemove', (e) => updateGhost(e.clientX, e.clientY));
        canvasInner.addEventListener('mouseleave', () => hideGhost());
        canvasInner.addEventListener('click', (e) => {
            if (e.altKey) return;
            if (e.target.closest && e.target.closest('.marker-pin')) return;
            if (e.target.closest && e.target.closest('.zoom-controls')) return;
            const pos = percentFromPlantaClick(e.clientX, e.clientY);
            if (!pos) return;
            addMarkerAt(pos.x, pos.y);
        });
        canvasInner.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY > 0 ? -0.1 : 0.1;
            scale = Math.max(0.3, Math.min(3, scale + delta));
            applyZoom();
        }, { passive: false });
    }

    if (equipSearch && equipDropdown) {
        equipSearch.addEventListener('input', function () {
            if (selectedEquipLabel && equipSearch.value !== selectedEquipLabel) {
                selectedEquipId = null;
                selectedEquipLabel = '';
            }
            equipHighlightIdx = 0;
            const list = filterEquipamentos(equipSearch.value);
            renderEquipDropdown(list);
            openEquipDropdown();
            syncColocarMode();
        });
        equipSearch.addEventListener('focus', function () {
            const list = filterEquipamentos(equipSearch.value);
            equipHighlightIdx = 0;
            renderEquipDropdown(list);
            openEquipDropdown();
        });
        equipSearch.addEventListener('blur', function () {
            scheduleCloseEquipDropdown();
        });
        equipSearch.addEventListener('keydown', function (e) {
            const isOpen = equipDropdown.classList.contains('open');
            let list = filterEquipamentos(equipSearch.value);
            if (e.key === 'Escape') {
                e.preventDefault();
                closeEquipDropdown();
                return;
            }
            if (!isOpen && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
                e.preventDefault();
                equipHighlightIdx = 0;
                renderEquipDropdown(list);
                openEquipDropdown();
                return;
            }
            if (!isOpen) {
                return;
            }
            if (!list.length) {
                return;
            }
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                equipHighlightIdx = Math.min(list.length - 1, equipHighlightIdx + 1);
                renderEquipDropdown(list);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                equipHighlightIdx = Math.max(0, equipHighlightIdx - 1);
                renderEquipDropdown(list);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (list[equipHighlightIdx]) {
                    const item = list[equipHighlightIdx];
                    onSelectEquip(item.id, item.label);
                }
            }
        });
    }
    syncColocarMode();

    if (mesRefInput) {
        mesRefInput.addEventListener('change', () => {
            syncLegendMesLabels();
            loadMarcadores();
        });
    }
    const mesRefClear = document.getElementById('mesRefClear');
    if (mesRefClear && mesRefInput) {
        mesRefClear.addEventListener('click', () => {
            mesRefInput.value = '';
            syncLegendMesLabels();
            loadMarcadores();
        });
    }

    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');
    const zoomReset = document.getElementById('zoomReset');
    if (zoomIn) zoomIn.onclick = () => { scale = Math.min(3, scale + 0.2); applyZoom(); };
    if (zoomOut) zoomOut.onclick = () => { scale = Math.max(0.3, scale - 0.2); applyZoom(); };
    if (zoomReset) zoomReset.onclick = () => { scale = 1; applyZoom(); };

    const btnFs = document.getElementById('btnFullscreen');
    if (btnFs && mapCard) {
        btnFs.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                mapCard.requestFullscreen().catch(() => {});
            } else {
                document.exitFullscreen();
            }
        });
    }

    const btnPdf = document.getElementById('btnPdf');
    if (btnPdf) {
        btnPdf.addEventListener('click', () => {
            syncLegendMesLabels();
            window.print();
        });
    }

    syncLegendMesLabels();
    loadMarcadores();

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') {
            loadMarcadores();
        }
    });
})();
    </script>
</body>
</html>
