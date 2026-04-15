<style>
    /* Sidebar Styles - Padronizado */
    .sidebar {
        width: 280px;
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        color: white;
        display: flex;
        flex-direction: column;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.3);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
    }

    .sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 200px;
        background: linear-gradient(180deg, rgba(74, 144, 226, 0.1) 0%, transparent 100%);
        pointer-events: none;
    }

    .sidebar-header {
        padding: 28px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        z-index: 1;
        background: linear-gradient(135deg, rgba(74, 144, 226, 0.05) 0%, transparent 100%);
    }

    .logo-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 50%, #5ba3f5 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        font-weight: 800;
        color: white;
        box-shadow: 0 8px 24px rgba(74, 144, 226, 0.4),
                    0 0 0 1px rgba(255, 255, 255, 0.1) inset,
                    0 2px 8px rgba(0, 0, 0, 0.2) inset;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .logo-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(45deg);
        animation: shine 3s infinite;
    }

    @keyframes shine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .logo-icon:hover {
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 12px 32px rgba(74, 144, 226, 0.5),
                    0 0 0 1px rgba(255, 255, 255, 0.2) inset;
    }

    .logo-text {
        font-size: 22px;
        font-weight: 800;
        background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 8px rgba(74, 144, 226, 0.3);
    }

    .logo-subtitle {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.5);
        margin-top: 2px;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .sidebar-menu {
        flex: 1;
        padding: 24px 0;
        overflow-y: auto;
        overflow-x: hidden;
        position: relative;
        z-index: 1;
    }

    .sidebar-menu::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-menu::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
    }

    .sidebar-menu::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }

    .sidebar-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .menu-section {
        margin-bottom: 32px;
        animation: fadeInUp 0.6s ease-out backwards;
    }

    .menu-section:nth-child(1) { animation-delay: 0.1s; }
    .menu-section:nth-child(2) { animation-delay: 0.2s; }
    .menu-section:nth-child(3) { animation-delay: 0.3s; }
    .menu-section:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .menu-section-title {
        font-size: 10px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.35);
        padding: 0 24px;
        margin-bottom: 16px;
        font-weight: 700;
        letter-spacing: 1.5px;
        position: relative;
    }

    .menu-section-title::after {
        content: '';
        position: absolute;
        left: 24px;
        bottom: -8px;
        width: 30px;
        height: 2px;
        background: linear-gradient(90deg, rgba(74, 144, 226, 0.5), transparent);
        border-radius: 2px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 24px;
        color: rgba(255, 255, 255, 0.65);
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
        font-weight: 500;
        position: relative;
        margin: 0 12px;
        border-radius: 12px;
        overflow: hidden;
    }

    .menu-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #4a90e2, #357abd);
        transform: scaleY(0);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 0 4px 4px 0;
    }

    .menu-item::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(74, 144, 226, 0.1), rgba(53, 122, 189, 0.05));
        opacity: 0;
        transition: opacity 0.3s;
    }

    .menu-item:hover {
        color: white;
        transform: translateX(4px);
        background: rgba(255, 255, 255, 0.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .menu-item:hover::before {
        transform: scaleY(1);
    }

    .menu-item:hover::after {
        opacity: 1;
    }

    .menu-item.active {
        background: linear-gradient(135deg, rgba(74, 144, 226, 0.15), rgba(53, 122, 189, 0.1));
        color: #87ceeb;
        box-shadow: 0 4px 16px rgba(74, 144, 226, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
        transform: translateX(4px);
    }

    .menu-item.active::before {
        transform: scaleY(1);
    }

    .menu-item.active::after {
        opacity: 1;
    }

    .menu-item.active .menu-icon {
        transform: scale(1.1);
        filter: drop-shadow(0 0 8px rgba(135, 206, 235, 0.5));
    }

    .menu-icon {
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 1;
    }

    .menu-item:hover .menu-icon {
        transform: scale(1.15) rotate(5deg);
    }

    /* ========== Videira — layout responsivo (mobile / tablet) ========== */
    .mobile-menu-btn {
        display: none;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1003;
        width: 44px;
        height: 44px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
        color: #fff;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        padding: 0;
    }

    .mobile-menu-btn svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
    }

    .sidebar-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.52);
        z-index: 1001;
        backdrop-filter: blur(3px);
    }

    .sidebar-backdrop.is-visible {
        display: block;
    }

    @media (max-width: 1024px) {
        .mobile-menu-btn {
            display: flex;
        }

        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.28s ease;
            z-index: 1002;
        }

        .sidebar.sidebar-open {
            transform: translateX(0);
            box-shadow: 8px 0 40px rgba(0, 0, 0, 0.38);
        }

        /* Conteúdo principal: largura total (todas as telas com sidebar) */
        body > .main-content,
        body > .main-wrapper,
        body > .main {
            margin-left: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .top-header {
            flex-wrap: wrap;
            gap: 10px !important;
            padding-left: 58px !important;
            padding-right: 12px !important;
        }

        .top-header-left {
            font-size: 12px !important;
        }

        .user-profile {
            flex-wrap: wrap;
        }

        /* Áreas de conteúdo: menos padding lateral */
        .content-area {
            padding-left: 14px !important;
            padding-right: 14px !important;
        }

        .main-wrapper {
            padding-left: 14px !important;
            padding-right: 14px !important;
        }

        /* Grades e filtros comuns */
        .form-grid,
        .stats-grid,
        .filters-section,
        .search-action-bar,
        .page-header .search-action-bar {
            flex-wrap: wrap !important;
        }

        .form-grid {
            grid-template-columns: 1fr !important;
        }

        .stats-grid {
            grid-template-columns: 1fr !important;
        }

        /* Modais genéricos */
        .modal {
            width: min(100vw - 24px, 600px) !important;
            max-width: calc(100vw - 24px) !important;
            margin: 12px !important;
        }

        .modal-overlay .modal {
            max-height: calc(100vh - 24px);
            overflow-y: auto;
        }

        /* Tabelas largas: rolagem horizontal no bloco (mantém display:table) */
        .content-area,
        .main-wrapper,
        .main-content {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .content-area table,
        .main-wrapper table,
        .main-content table,
        .card table {
            min-width: max-content;
        }
    }

    @media (max-width: 1024px) {
        .dash-kpi-row,
        .dash-grid-3,
        .dash-grid-2,
        .charts-grid {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 600px) {
        .page-title {
            font-size: 22px !important;
        }

        .stat-value,
        .kpi-card .kpi-val {
            font-size: 24px !important;
        }
    }

    @media (min-width: 1025px) {
        .mobile-menu-btn {
            display: none !important;
        }

        .sidebar-backdrop {
            display: none !important;
            pointer-events: none !important;
        }
    }
</style>
