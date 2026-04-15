<style>
    :root {
        --bg: #0b1220;
        --surface: rgba(255, 255, 255, 0.92);
        --card: rgba(255, 255, 255, 0.96);
        --stroke: rgba(148, 163, 184, 0.35);
        --text: #0f172a;
        --muted: #64748b;
        --primary: #2563eb;
        --primary-2: #4f46e5;
        --danger: #dc2626;
        --warning: #f59e0b;
        --success: #16a34a;
        --shadow: 0 18px 60px rgba(2, 6, 23, 0.20);
        --shadow-soft: 0 10px 26px rgba(2, 6, 23, 0.12);
        --radius: 16px;
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
        color: var(--text);
        display: flex;
        background:
            radial-gradient(1000px 700px at 20% -10%, rgba(79, 70, 229, 0.25), transparent 60%),
            radial-gradient(900px 650px at 90% 10%, rgba(37, 99, 235, 0.25), transparent 55%),
            linear-gradient(180deg, #eef2ff 0%, #f8fafc 40%, #f1f5f9 100%);
        min-height: 100vh;
    }

    .main-content {
        margin-left: 280px;
        width: calc(100% - 280px);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .top-header {
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        position: sticky;
        top: 0;
        z-index: 30;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.92) 0%, rgba(248, 250, 252, 0.75) 100%);
        backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(148, 163, 184, 0.25);
    }

    .top-header h1 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    .header-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
    }

    .content-area {
        padding: 18px;
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
        flex: 1;
    }

    .card {
        background: var(--card);
        border: 1px solid var(--stroke);
        border-radius: var(--radius);
        padding: 14px;
        margin-bottom: 14px;
        box-shadow: var(--shadow-soft);
    }

    .card-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .card h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: -0.2px;
    }

    .subtle {
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
    }

    .toolbar {
        display: flex;
        gap: 10px;
        align-items: end;
        flex-wrap: wrap;
    }

    .field {
        min-width: 160px;
        flex: 1;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-size: 12px;
        color: var(--muted);
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    input, select, textarea {
        width: 100%;
        border: 1px solid rgba(148, 163, 184, 0.45);
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 13px;
        outline: none;
        background: rgba(255, 255, 255, 0.9);
        transition: box-shadow .15s ease, border-color .15s ease, transform .15s ease;
    }

    textarea { min-height: 88px; resize: vertical; }

    input:focus, select:focus, textarea:focus {
        border-color: rgba(37, 99, 235, 0.65);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
    }

    .btn {
        border: 1px solid rgba(148, 163, 184, 0.55);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--text);
        padding: 10px 12px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        line-height: 1;
        white-space: nowrap;
        gap: 8px;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease;
        user-select: none;
    }

    .btn:hover {
        background: rgba(255, 255, 255, 1);
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(2, 6, 23, 0.12);
    }

    .btn:active { transform: translateY(0); }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-color: rgba(37, 99, 235, 0.65);
        color: #fff;
    }

    .btn-primary:hover {
        box-shadow: 0 16px 30px rgba(37, 99, 235, 0.24);
    }

    .btn-danger {
        border-color: rgba(220, 38, 38, 0.6);
        color: #b91c1c;
        background: rgba(254, 242, 242, 0.8);
    }

    .btn-danger:hover {
        background: rgba(254, 242, 242, 1);
        box-shadow: 0 14px 26px rgba(220, 38, 38, 0.12);
    }

    .btn-ghost {
        background: transparent;
        border-color: transparent;
        color: var(--muted);
        font-weight: 800;
    }

    .btn-ghost:hover {
        background: rgba(148, 163, 184, 0.14);
        box-shadow: none;
        transform: translateY(0);
    }

    .alert-success, .alert-error {
        margin-bottom: 12px;
        padding: 12px 12px;
        border-radius: 14px;
        font-weight: 800;
        border: 1px solid rgba(148, 163, 184, 0.25);
        box-shadow: 0 10px 26px rgba(2, 6, 23, 0.08);
    }

    .alert-success { background: rgba(220, 252, 231, 0.9); color: #166534; border-color: rgba(22, 163, 74, 0.25); }
    .alert-error { background: rgba(254, 226, 226, 0.92); color: #991b1b; border-color: rgba(220, 38, 38, 0.25); }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(148, 163, 184, 0.25);
        border-radius: 18px;
        padding: 14px;
        box-shadow: 0 16px 38px rgba(2, 6, 23, 0.10);
        overflow: hidden;
        position: relative;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        inset: -2px;
        background: radial-gradient(400px 120px at 20% 10%, rgba(37, 99, 235, 0.10), transparent 55%);
        pointer-events: none;
    }

    .kpi-title {
        font-size: 11px;
        color: var(--muted);
        text-transform: uppercase;
        font-weight: 900;
        letter-spacing: 0.9px;
        position: relative;
    }

    .stat-card strong {
        display: block;
        font-size: 20px;
        line-height: 1.15;
        margin-top: 8px;
        position: relative;
        letter-spacing: -0.2px;
    }

    .chart-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 12px;
    }

    .table-wrap {
        overflow: auto;
        border: 1px solid rgba(148, 163, 184, 0.35);
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.78);
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 860px;
    }

    .table th, .table td {
        border-bottom: 1px solid rgba(148, 163, 184, 0.22);
        padding: 10px 10px;
        text-align: left;
        font-size: 13px;
        vertical-align: middle;
    }

    .table th {
        color: rgba(51, 65, 85, 0.9);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        position: sticky;
        top: 0;
        background: rgba(248, 250, 252, 0.92);
        backdrop-filter: blur(10px);
        z-index: 1;
    }

    .table tbody tr:hover {
        background: rgba(37, 99, 235, 0.06);
    }

    .table.compact td { padding: 8px 8px; }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.2px;
        border: 1px solid rgba(148, 163, 184, 0.25);
        background: rgba(255, 255, 255, 0.7);
    }

    .badge.danger { background: rgba(254, 226, 226, 0.9); color: #b91c1c; border-color: rgba(220, 38, 38, 0.25); }
    .badge.warning { background: rgba(254, 243, 199, 0.92); color: #92400e; border-color: rgba(245, 158, 11, 0.25); }
    .badge.success { background: rgba(220, 252, 231, 0.92); color: #166534; border-color: rgba(22, 163, 74, 0.25); }
    .badge.info { background: rgba(224, 242, 254, 0.92); color: #0c4a6e; border-color: rgba(14, 165, 233, 0.25); }

    .text-positive { color: #15803d; }
    .text-negative { color: #b91c1c; }
    .text-warning { color: #a16207; }

    .actions-inline {
        display: inline-flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .grid-3 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 10px;
    }

    .pagination { margin-top: 10px; }

    .nowrap { white-space: nowrap; }

    /* Modal */
    .modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        z-index: 80;
    }
    .modal.is-open { display: flex; }
    .modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(2, 6, 23, 0.62);
        backdrop-filter: blur(6px);
    }
    .modal-panel {
        position: relative;
        width: min(820px, 100%);
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(148, 163, 184, 0.35);
        border-radius: 22px;
        box-shadow: var(--shadow);
        overflow: hidden;
        transform: translateY(8px);
        animation: modalIn .18s ease-out forwards;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(14px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-header {
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.25);
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.95) 0%, rgba(248, 250, 252, 0.80) 100%);
    }
    .modal-title {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .modal-title strong { font-size: 14px; font-weight: 900; }
    .modal-body { padding: 14px 16px; }
    .modal-footer {
        padding: 12px 16px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        border-top: 1px solid rgba(148, 163, 184, 0.25);
        background: rgba(248, 250, 252, 0.7);
    }

    /* Floating Action Button */
    .fab {
        position: fixed;
        right: 18px;
        bottom: 18px;
        z-index: 70;
        border-radius: 999px;
        padding: 12px 14px;
        box-shadow: 0 18px 40px rgba(37, 99, 235, 0.28);
    }

    @media (max-width: 1100px) {
        .chart-grid { grid-template-columns: 1fr; }
        .table { min-width: 760px; }
    }
    @media (max-width: 900px) {
        .main-content { margin-left: 0; width: 100%; }
        .content-area { padding: 14px; }
    }
</style>
