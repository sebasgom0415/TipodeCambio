<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipo de Cambio · Dólar BCCR</title>
    <meta name="description" content="Tipo de cambio del dólar publicado por el BCCR de Costa Rica.">
    <link rel="stylesheet" href="assets/css/inter.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-icons.min.css">
    <style>
        :root {
            --bg:        #f4f6ff;
            --surface:   #ffffff;
            --surface-2: #eef2ff;
            --border:    #e2e8ff;
            --text:      #1e1b4b;
            --muted:     #6b7280;
            --accent:    #4f46e5;
            --green:     #059669;
            --red:       #e11d48;
            --yellow:    #d97706;
            --blue:      #2563eb;
            --orange:    #ea580c;
            --shadow:    0 1px 3px rgba(79,70,229,.07), 0 4px 18px rgba(79,70,229,.09);
            --shadow-lg: 0 4px 10px rgba(79,70,229,.10), 0 14px 36px rgba(79,70,229,.13);
        }
        * { box-sizing: border-box; font-family: 'Inter', system-ui, sans-serif; }
        body { background: var(--bg); color: var(--text); min-height: 100vh; }

        /* ── Hero ───────────────────────────────────────────────── */
        .hero {
            background: linear-gradient(135deg, #312e81 0%, #4f46e5 55%, #7c3aed 100%);
            padding: 1.8rem 0 1.5rem;
            position: relative; overflow: hidden;
            box-shadow: 0 6px 32px rgba(79,70,229,.28);
        }
        .hero::before {
            content: ''; position: absolute; top: -80px; right: -60px;
            width: 340px; height: 340px;
            background: radial-gradient(circle, rgba(255,255,255,.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero::after {
            content: ''; position: absolute; bottom: -50px; left: 8%;
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-title   { font-size: 1.75rem; font-weight: 800; letter-spacing: -.5px; margin: 0; color: #fff; }
        .hero-subtitle { color: rgba(255,255,255,.65); font-size: .8rem; margin-top: 4px; }

        /* ── Mode tabs ───────────────────────────────────────────── */
        .mode-tabs {
            display: inline-flex; background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.25); border-radius: 12px;
            padding: 3px; gap: 2px; backdrop-filter: blur(6px);
        }
        .mode-tab {
            border: none; background: transparent; color: rgba(255,255,255,.7);
            font-size: .82rem; font-weight: 600; padding: .38rem .95rem;
            border-radius: 9px; cursor: pointer; transition: all .18s;
            display: flex; align-items: center; gap: 5px;
        }
        .mode-tab.active { background: rgba(255,255,255,.95); color: var(--accent); }
        .mode-tab:not(.active):hover { background: rgba(255,255,255,.18); color: #fff; }

        /* ── Hero selectors ─────────────────────────────────────── */
        .ctrl-select {
            background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.3);
            color: #fff; border-radius: 10px; padding: .42rem 1.8rem .42rem .85rem;
            font-size: .88rem; cursor: pointer; transition: background .2s;
            backdrop-filter: blur(4px);
        }
        .ctrl-select:focus { outline: none; background: rgba(255,255,255,.22); border-color: rgba(255,255,255,.55); }
        .ctrl-select option { background: #4f46e5; color: #fff; }

        .vs-badge {
            background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.3);
            border-radius: 8px; color: rgba(255,255,255,.8); font-size: .78rem;
            font-weight: 700; padding: .3rem .65rem; letter-spacing: .5px;
        }

        /* ── Today box ──────────────────────────────────────────── */
        .today-pill {
            background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
            border-radius: 12px; padding: .65rem 1rem; backdrop-filter: blur(6px);
        }
        .today-label { font-size: .65rem; color: rgba(255,255,255,.55); text-transform: uppercase; letter-spacing: .6px; font-weight: 700; }
        .today-vals  { display: flex; gap: .6rem; margin-top: .4rem; flex-wrap: wrap; }
        .pill-compra { background: rgba(5,150,105,.25); border: 1px solid rgba(5,150,105,.5); color: #6ee7b7; border-radius: 8px; padding: 4px 12px; font-size: .92rem; font-weight: 700; }
        .pill-venta  { background: rgba(225,29,72,.2);  border: 1px solid rgba(225,29,72,.45); color: #fca5a5; border-radius: 8px; padding: 4px 12px; font-size: .92rem; font-weight: 700; }

        /* ── Stat cards ─────────────────────────────────────────── */
        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 1.2rem 1.4rem; box-shadow: var(--shadow);
            transition: transform .2s, box-shadow .2s; height: 100%;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
        .stat-icon { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; margin-bottom: .85rem; }
        .stat-label { font-size: .7rem; color: var(--muted); text-transform: uppercase; letter-spacing: .65px; font-weight: 600; margin-bottom: 5px; }
        .stat-value { font-size: 1.5rem; font-weight: 700; letter-spacing: -.4px; }
        .stat-date  { font-size: .78rem; color: var(--muted); margin-top: 3px; }
        .icon-blue   { background: #eef2ff; color: var(--accent); }
        .icon-green  { background: #d1fae5; color: var(--green); }
        .icon-red    { background: #ffe4e6; color: var(--red); }
        .icon-yellow { background: #fef3c7; color: var(--yellow); }

        /* ── Panel genérico ─────────────────────────────────────── */
        .panel {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 1.4rem; box-shadow: var(--shadow);
        }
        .panel-title { font-size: .74rem; text-transform: uppercase; letter-spacing: .7px; font-weight: 700; color: var(--muted); }

        /* ── Filter bar ─────────────────────────────────────────── */
        .filter-bar {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; padding: .9rem 1.2rem; box-shadow: var(--shadow);
        }
        .filter-bar .form-control,
        .filter-bar .form-control:focus {
            background: var(--surface-2); border: 1px solid var(--border);
            color: var(--text); border-radius: 8px; font-size: .85rem;
        }
        .filter-bar .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        .filter-bar .form-label { color: var(--muted); font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
        .btn-outline-dim {
            background: transparent; border: 1px solid var(--border); color: var(--muted);
            border-radius: 8px; font-size: .85rem; transition: border-color .2s, color .2s, background .2s;
        }
        .btn-outline-dim:hover { border-color: var(--accent); color: var(--accent); background: #eef2ff; }

        /* ── Table panel ────────────────────────────────────────── */
        .table-panel { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow); }
        .table-panel .panel-header { padding: .9rem 1.4rem; border-bottom: 1px solid var(--border); font-size: .74rem; text-transform: uppercase; letter-spacing: .7px; font-weight: 700; color: var(--muted); }
        #tabla, #tabla tbody, #tabla tbody td, #tabla tbody tr { color: var(--text); font-size: .87rem; }
        #tabla thead th { background: var(--surface-2) !important; color: var(--accent); font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; border-color: #c7d2fe !important; padding: .8rem 1rem; border-bottom: 2px solid #c7d2fe !important; }
        #tabla tbody td { border-color: #f1f5f9; padding: .7rem 1rem; vertical-align: middle; }
        #tabla tbody tr:hover td { background: #f8faff !important; }
        #tabla { --bs-table-bg: transparent; --bs-table-hover-bg: #f8faff; --bs-table-color: var(--text); --bs-table-border-color: #f1f5f9; }
        table.dataTable tbody tr, table.dataTable tbody tr.odd, table.dataTable tbody tr.even,
        table.dataTable.stripe > tbody > tr.odd > *, table.dataTable.display > tbody > tr.odd > * { background-color: transparent !important; box-shadow: none !important; }
        table.dataTable tbody tr:hover > * { background-color: #f8faff !important; box-shadow: none !important; }
        .badge-compra { background: #d1fae5; color: var(--green); border-radius: 99px; padding: 4px 12px; font-weight: 600; font-size: .82rem; display: inline-block; }
        .badge-venta  { background: #ffe4e6; color: var(--red);   border-radius: 99px; padding: 4px 12px; font-weight: 600; font-size: .82rem; display: inline-block; }
        .diff-val     { color: #6366f1; font-weight: 500; font-size: .84rem; }
        .dataTables_wrapper { padding: 1.2rem 1.4rem; background: transparent !important; }
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_info { color: var(--muted) !important; font-size: .82rem; }
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select { background: var(--surface-2) !important; border: 1px solid var(--border) !important; color: var(--text) !important; border-radius: 8px; padding: 4px 10px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { color: var(--muted) !important; font-size: .82rem; border-radius: 7px !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: var(--accent) !important; border-color: var(--accent) !important; color: #fff !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) { background: var(--surface-2) !important; border-color: var(--border) !important; color: var(--accent) !important; }

        /* ── Compare section ────────────────────────────────────── */
        .year-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: .35rem .9rem; border-radius: 99px; font-size: .82rem; font-weight: 700;
        }
        .year-badge-a { background: #dbeafe; color: #1d4ed8; }
        .year-badge-b { background: #ffedd5; color: #c2410c; }

        .compare-panel {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; box-shadow: var(--shadow); overflow: hidden;
        }
        .compare-panel-header {
            background: var(--surface-2); border-bottom: 1px solid var(--border);
            padding: .9rem 1.4rem; display: grid;
            grid-template-columns: 1fr auto 1fr auto;
            align-items: center; gap: .5rem;
        }
        .cp-col-a  { font-size: 1rem; font-weight: 800; color: #1d4ed8; text-align: left; }
        .cp-col-vs { font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; text-align: center; padding: 0 .3rem; }
        .cp-col-b  { font-size: 1rem; font-weight: 800; color: #c2410c; text-align: right; }
        .cp-col-delta-h { font-size: .65rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; text-align: right; }

        .compare-row {
            display: grid; grid-template-columns: 1fr auto 1fr auto;
            align-items: center; padding: .7rem 1.4rem; gap: .5rem;
            border-bottom: 1px solid #f1f5f9; transition: background .12s;
        }
        .compare-row:last-child { border-bottom: none; }
        .compare-row:hover { background: #f8faff; }
        .cr-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); text-align: center; padding: 0 .3rem; }
        .cr-val-a { font-size: .95rem; font-weight: 700; color: #1d4ed8; text-align: left; }
        .cr-val-b { font-size: .95rem; font-weight: 700; color: #c2410c; text-align: right; }
        .cr-delta  { font-size: .78rem; font-weight: 700; text-align: right; min-width: 90px; }
        .delta-up   { color: var(--red);   }
        .delta-down { color: var(--green); }
        .delta-flat { color: var(--muted); }

        .compare-empty {
            text-align: center; padding: 3rem 1rem; color: var(--muted); font-size: .9rem;
        }

        /* ── Modal ──────────────────────────────────────────────── */
        .modal-content { background: var(--surface); border: 1px solid var(--border); border-radius: 18px; color: var(--text); box-shadow: 0 20px 60px rgba(79,70,229,.18); }
        .modal-header  { border-bottom: 1px solid var(--border); }
        .modal-title   { font-weight: 700; font-size: 1rem; }

        /* ── Converter modal ─────────────────────────────────────── */
        .conv-rate-row { display: flex; gap: .6rem; }
        .conv-rate-pill { flex: 1; border-radius: 12px; padding: .7rem .85rem; text-align: center; }
        .conv-rate-pill .pill-label { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; opacity: .75; }
        .conv-rate-pill .pill-val   { font-size: 1.15rem; font-weight: 700; }
        .cpill-compra { background: #d1fae5; color: #065f46; }
        .cpill-venta  { background: #ffe4e6; color: #9f1239; }
        .conv-toggle { display: flex; background: var(--surface-2); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
        .conv-toggle button { flex: 1; padding: .5rem .8rem; border: none; background: transparent; font-size: .82rem; font-weight: 600; color: var(--muted); cursor: pointer; transition: all .15s; }
        .conv-toggle button.active { background: var(--accent); color: #fff; border-radius: 8px; }
        .conv-input-wrap { position: relative; }
        .conv-prefix { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-size: 1.1rem; font-weight: 700; color: var(--muted); pointer-events: none; }
        .conv-amount { background: var(--surface-2); border: 1.5px solid var(--border); border-radius: 12px; color: var(--text); font-size: 1.4rem; font-weight: 700; padding: .65rem 1rem .65rem 2.4rem; width: 100%; transition: border-color .2s; }
        .conv-amount:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        .conv-result-box { background: linear-gradient(135deg, #eef2ff, #f5f3ff); border: 1.5px solid #c7d2fe; border-radius: 14px; padding: 1.1rem 1.2rem; text-align: center; }
        .conv-result-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--accent); margin-bottom: .3rem; }
        .conv-result-value { font-size: 2rem; font-weight: 800; color: var(--text); letter-spacing: -1px; }
        .conv-result-note  { font-size: .7rem; color: var(--muted); margin-top: .35rem; }

        /* ── FAB ─────────────────────────────────────────────────── */
        .fab-btn {
            position: fixed; bottom: 1.8rem; right: 1.8rem;
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none; color: #fff; font-size: 1.35rem;
            box-shadow: 0 4px 20px rgba(79,70,229,.45);
            cursor: pointer; z-index: 1050;
            display: flex; align-items: center; justify-content: center;
            transition: transform .2s, box-shadow .2s;
        }
        .fab-btn:hover { transform: scale(1.1) translateY(-2px); box-shadow: 0 8px 30px rgba(79,70,229,.55); }

        /* ── Suscripción ────────────────────────────────────────────────────── */
        .subscribe-card {
            background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
            border: 1.5px solid #c7d2fe; border-radius: 20px;
            padding: 2rem 2.2rem; box-shadow: 0 4px 20px rgba(79,70,229,.1);
        }
        .sub-icon-wrap {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg,#4f46e5,#7c3aed);
            color: #fff; font-size: 1.5rem;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 14px rgba(79,70,229,.35);
        }
        .sub-title  { font-size: 1.05rem; font-weight: 800; color: var(--text); margin-bottom: .3rem; }
        .sub-desc   { font-size: .85rem; color: var(--muted); line-height: 1.55; }
        .sub-input  {
            background: #fff; border: 1.5px solid #c7d2fe; border-radius: 10px;
            color: var(--text); font-size: .88rem; padding: .6rem 1rem;
            flex: 1; min-width: 220px; transition: border-color .2s;
        }
        .sub-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        .sub-btn {
            background: linear-gradient(135deg,#4f46e5,#7c3aed); border: none;
            border-radius: 10px; color: #fff; font-weight: 700; font-size: .88rem;
            padding: .6rem 1.3rem; cursor: pointer; white-space: nowrap;
            transition: opacity .2s, transform .15s; display: flex; align-items: center; gap: 6px;
        }
        .sub-btn:hover { opacity: .88; transform: translateY(-1px); }
        .sub-btn:disabled { opacity: .55; cursor: not-allowed; transform: none; }
        .sub-msg { font-size: .82rem; font-weight: 500; }
        .sub-msg.ok   { color: var(--green); }
        .sub-msg.info { color: var(--accent); }
        .sub-msg.err  { color: var(--red); }

        /* ── Footer ─────────────────────────────────────────────── */
        .site-footer {
            background: var(--surface); border-top: 1px solid var(--border);
            padding: 1.2rem 0; margin-top: 3rem;
        }
        .site-footer p { font-size: .75rem; color: var(--muted); margin: 0; }

        /* ── Responsive ─────────────────────────────────────────── */
        @media (max-width: 576px) {
            .hero-title { font-size: 1.35rem; }
            .compare-panel-header { grid-template-columns: 1fr auto 1fr; }
            .cp-col-delta-h { display: none; }
            .compare-row { grid-template-columns: 1fr auto 1fr; }
            .cr-delta { display: none; }
        }
    </style>
</head>
<body>

<!-- ── Hero ──────────────────────────────────────────────────────────────── -->
<div class="hero mb-4">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">

            <!-- Título -->
            <div>
                <h1 class="hero-title"><i class="bi bi-currency-dollar me-1"></i>Tipo de Cambio &nbsp;·&nbsp; Dólar</h1>
                <p class="hero-subtitle mb-0" id="subtitulo">Cargando datos...</p>
            </div>

            <!-- Tasas de hoy -->
            <div id="hoy-box" style="opacity:0;transition:opacity .4s">
                <div class="today-pill">
                    <div class="today-label"><i class="bi bi-calendar-check me-1"></i>Hoy · <span id="hoy-fecha">—</span></div>
                    <div class="today-vals">
                        <span class="pill-compra"><i class="bi bi-arrow-down me-1"></i>Compra: <span id="hoy-compra">—</span></span>
                        <span class="pill-venta"><i class="bi bi-arrow-up me-1"></i>Venta: <span id="hoy-venta">—</span></span>
                    </div>
                    <div id="hoy-aviso" style="font-size:.65rem;color:rgba(255,255,255,.45);margin-top:3px"></div>
                </div>
            </div>

            <!-- Controles (modo + selectores) -->
            <div class="d-flex flex-column gap-2 align-items-end">
                <!-- Tabs de modo -->
                <div class="mode-tabs">
                    <button class="mode-tab active" id="tab-simple" onclick="setModo('simple')">
                        <i class="bi bi-calendar3"></i> Vista anual
                    </button>
                    <button class="mode-tab" id="tab-comparar" onclick="setModo('comparar')">
                        <i class="bi bi-bar-chart-steps"></i> Comparar años
                    </button>
                </div>
                <!-- Selectores -->
                <div id="ctrl-simple" class="d-flex align-items-center gap-2">
                    <select id="select-ver-anio" class="ctrl-select"></select>
                </div>
                <div id="ctrl-comparar" class="d-flex align-items-center gap-2" style="display:none!important">
                    <select id="select-anio-a" class="ctrl-select"></select>
                    <span class="vs-badge">VS</span>
                    <select id="select-anio-b" class="ctrl-select"></select>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="container pb-4">

    <!-- ══════════════════════════════════════════════════════════════════════
         MODO SIMPLE
    ════════════════════════════════════════════════════════════════════════ -->
    <div id="seccion-simple">

        <!-- Stat cards fila 1 -->
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-lg-4">
                <div class="stat-card">
                    <div class="stat-icon icon-blue"><i class="bi bi-calendar3"></i></div>
                    <div class="stat-label">Total registros</div>
                    <div class="stat-value" id="card-total" style="color:var(--accent)">—</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="stat-card">
                    <div class="stat-icon icon-green"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="stat-label">Compra promedio</div>
                    <div class="stat-value" id="card-compra" style="color:var(--green)">—</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="stat-card">
                    <div class="stat-icon icon-red"><i class="bi bi-graph-down-arrow"></i></div>
                    <div class="stat-label">Venta promedio</div>
                    <div class="stat-value" id="card-venta" style="color:var(--red)">—</div>
                </div>
            </div>
        </div>

        <!-- Stat cards fila 2 -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <div class="stat-card" style="border-left:3px solid var(--red)">
                    <div class="stat-icon icon-red"><i class="bi bi-arrow-up-circle-fill"></i></div>
                    <div class="stat-label">Día con venta más alta</div>
                    <div class="stat-value" id="card-max-valor" style="color:var(--red)">—</div>
                    <div class="stat-date" id="card-max-fecha">—</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="stat-card" style="border-left:3px solid var(--green)">
                    <div class="stat-icon icon-green"><i class="bi bi-arrow-down-circle-fill"></i></div>
                    <div class="stat-label">Día con venta más baja</div>
                    <div class="stat-value" id="card-min-valor" style="color:var(--green)">—</div>
                    <div class="stat-date" id="card-min-fecha">—</div>
                </div>
            </div>
        </div>

        <!-- Filtro de fechas -->
        <div class="filter-bar mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label mb-1">Desde</label>
                    <input type="date" id="filtro-desde" class="form-control form-control-sm">
                </div>
                <div class="col-auto">
                    <label class="form-label mb-1">Hasta</label>
                    <input type="date" id="filtro-hasta" class="form-control form-control-sm">
                </div>
                <div class="col-auto">
                    <button id="btn-limpiar" class="btn btn-outline-dim btn-sm">
                        <i class="bi bi-x-lg me-1"></i>Limpiar
                    </button>
                </div>
                <div class="col-auto ms-auto">
                    <small id="label-filtrado" style="color:var(--muted)"></small>
                </div>
            </div>
        </div>

        <!-- Gráfico simple -->
        <div class="panel mb-4">
            <div class="panel-title mb-3"><i class="bi bi-bar-chart-line me-1"></i>Evolución del tipo de cambio</div>
            <div style="position:relative;height:340px">
                <canvas id="grafico-simple"></canvas>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-panel">
            <div class="panel-header"><i class="bi bi-table me-1"></i>Detalle de registros</div>
            <table id="tabla" class="table table-borderless text-center w-100 mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Compra (₡)</th>
                        <th>Venta (₡)</th>
                        <th>Diferencia (₡)</th>
                    </tr>
                </thead>
                <tbody id="tabla-body"></tbody>
            </table>
        </div>

    </div><!-- /seccion-simple -->


    <!-- ══════════════════════════════════════════════════════════════════════
         MODO COMPARAR
    ════════════════════════════════════════════════════════════════════════ -->
    <div id="seccion-comparar" style="display:none">

        <!-- Panel comparativo de estadísticas -->
        <div class="compare-panel mb-4" id="compare-stats-panel">
            <div class="compare-empty" id="compare-empty-msg">
                <i class="bi bi-bar-chart-steps" style="font-size:2rem;opacity:.3;display:block;margin-bottom:.5rem"></i>
                Selecciona dos años distintos para ver la comparación.
            </div>
            <div id="compare-table-content" style="display:none">
                <div class="compare-panel-header">
                    <div class="cp-col-a" id="cp-year-a">—</div>
                    <div class="cp-col-vs">vs</div>
                    <div class="cp-col-b" id="cp-year-b">—</div>
                    <div class="cp-col-delta-h">Diferencia</div>
                </div>
                <!-- Filas de comparación -->
                <div class="compare-row">
                    <div class="cr-val-a" id="cr-total-a">—</div>
                    <div class="cr-label">Registros</div>
                    <div class="cr-val-b" id="cr-total-b">—</div>
                    <div class="cr-delta" id="cr-delta-total">—</div>
                </div>
                <div class="compare-row">
                    <div class="cr-val-a" id="cr-compra-a">—</div>
                    <div class="cr-label">Compra prom.</div>
                    <div class="cr-val-b" id="cr-compra-b">—</div>
                    <div class="cr-delta" id="cr-delta-compra">—</div>
                </div>
                <div class="compare-row">
                    <div class="cr-val-a" id="cr-venta-a">—</div>
                    <div class="cr-label">Venta prom.</div>
                    <div class="cr-val-b" id="cr-venta-b">—</div>
                    <div class="cr-delta" id="cr-delta-venta">—</div>
                </div>
                <div class="compare-row">
                    <div class="cr-val-a" id="cr-max-a">—</div>
                    <div class="cr-label">Venta máx.</div>
                    <div class="cr-val-b" id="cr-max-b">—</div>
                    <div class="cr-delta" id="cr-delta-max">—</div>
                </div>
                <div class="compare-row">
                    <div class="cr-val-a" id="cr-min-a">—</div>
                    <div class="cr-label">Venta mín.</div>
                    <div class="cr-val-b" id="cr-min-b">—</div>
                    <div class="cr-delta" id="cr-delta-min">—</div>
                </div>
            </div>
        </div>

        <!-- Gráfico comparativo -->
        <div class="panel mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div class="panel-title"><i class="bi bi-bar-chart-steps me-1"></i>Evolución comparada · promedio mensual</div>
                <div class="d-flex align-items-center gap-2">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="toggle-compra" onchange="toggleCompraLines()">
                        <label class="form-check-label" style="font-size:.75rem;color:var(--muted)" for="toggle-compra">Mostrar compra</label>
                    </div>
                </div>
            </div>
            <div style="position:relative;height:360px">
                <canvas id="grafico-compare"></canvas>
            </div>
        </div>

    </div><!-- /seccion-comparar -->

</div><!-- /container -->

<!-- ── Suscripción ───────────────────────────────────────────────────────── -->
<div class="container mb-5">
    <div class="subscribe-card">
        <div class="d-flex align-items-start gap-4 flex-wrap">
            <div class="sub-icon-wrap flex-shrink-0">
                <i class="bi bi-envelope-heart-fill"></i>
            </div>
            <div class="flex-grow-1">
                <div class="sub-title">Recibe el tipo de cambio cada día</div>
                <div class="sub-desc">Te enviamos la tasa de compra y venta del dólar (BCCR) directo a tu correo, cada mañana, sin spam.</div>
                <form id="form-subscribe" class="d-flex gap-2 mt-3 flex-wrap" onsubmit="suscribirse(event)">
                    <input type="email" id="sub-email" class="sub-input" placeholder="tu@correo.com" required autocomplete="email">
                    <button type="submit" id="sub-btn" class="sub-btn">
                        <span id="sub-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <i class="bi bi-send-fill me-1" id="sub-icon"></i>Suscribirme
                    </button>
                </form>
                <div id="sub-msg" class="sub-msg mt-2" style="display:none"></div>
            </div>
        </div>
    </div>
</div>

<!-- ── Footer ─────────────────────────────────────────────────────────────── -->
<footer class="site-footer">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <p>Datos oficiales del <strong>Banco Central de Costa Rica (BCCR)</strong>. Indicadores 317 y 318.</p>
        <p>© <?= date('Y') ?> · <a href="index.php?page=admin" style="color:var(--muted);text-decoration:none" tabindex="-1">Administración</a></p>
    </div>
</footer>

<!-- ── FAB Convertidor ────────────────────────────────────────────────────── -->
<button class="fab-btn" data-bs-toggle="modal" data-bs-target="#modalConverter" title="Convertir ₡ / $">
    <i class="bi bi-currency-exchange"></i>
</button>

<!-- ── Modal Convertidor ─────────────────────────────────────────────────── -->
<div class="modal fade" id="modalConverter" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
        <div class="modal-content">
            <div class="modal-header px-4 pt-4 pb-3">
                <h5 class="modal-title"><i class="bi bi-currency-exchange me-2" style="color:var(--accent)"></i>Convertidor ₡ / $</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <!-- Tasas -->
                <div class="conv-rate-row mb-1">
                    <div class="conv-rate-pill cpill-compra">
                        <div class="pill-label"><i class="bi bi-arrow-down me-1"></i>Compra</div>
                        <div class="pill-val" id="cv-compra">—</div>
                    </div>
                    <div class="conv-rate-pill cpill-venta">
                        <div class="pill-label"><i class="bi bi-arrow-up me-1"></i>Venta</div>
                        <div class="pill-val" id="cv-venta">—</div>
                    </div>
                </div>
                <div id="cv-fecha-label" class="mb-4" style="font-size:.68rem;color:var(--muted);text-align:center"></div>
                <!-- Toggle -->
                <div class="conv-toggle mb-3">
                    <button class="active" id="btn-dir-crc" onclick="setDir('CRC')">₡ Colones → $ Dólares</button>
                    <button id="btn-dir-usd" onclick="setDir('USD')">$ Dólares → ₡ Colones</button>
                </div>
                <!-- Input -->
                <div class="conv-input-wrap mb-4">
                    <span class="conv-prefix" id="cv-prefix">₡</span>
                    <input type="number" id="cv-amount" class="conv-amount" placeholder="0.00" min="0" step="any" oninput="calcular()">
                </div>
                <!-- Resultado -->
                <div class="conv-result-box">
                    <div class="conv-result-label">Resultado</div>
                    <div class="conv-result-value" id="cv-result">—</div>
                    <div class="conv-result-note" id="cv-note">Ingresa un monto para convertir</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Scripts ────────────────────────────────────────────────────────────── -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/chart.umd.min.js"></script>
<script src="assets/js/dataTables.min.js"></script>
<script src="assets/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {

    /* ── Constantes ──────────────────────────────────────────────────────── */
    const ANIO_MIN = 2000;
    const ANIO_MAX = new Date().getFullYear();
    const MESES    = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

    /* ── Estado ──────────────────────────────────────────────────────────── */
    let modo          = 'simple';
    let anioActivo    = ANIO_MAX - 1;
    let anioA         = ANIO_MAX - 1;
    let anioB         = ANIO_MAX - 2;
    let registros     = [];   // modo simple
    let registrosA    = [];   // modo comparar año A
    let registrosB    = [];   // modo comparar año B
    let graficoSimple = null;
    let graficoComp   = null;
    let tabla         = null;
    let tasaHoy       = { compra: null, venta: null, fecha: null };

    /* ── Init ────────────────────────────────────────────────────────────── */
    llenarSelect($('#select-ver-anio'), anioActivo);
    llenarSelect($('#select-anio-a'),   anioA);
    llenarSelect($('#select-anio-b'),   anioB);
    sincronizarHoy();
    cargarDatos(anioActivo);

    /* ── Cambios en selectores ───────────────────────────────────────────── */
    $('#select-ver-anio').on('change', function () {
        anioActivo = parseInt($(this).val());
        cargarDatos(anioActivo);
    });

    $('#select-anio-a, #select-anio-b').on('change', function () {
        anioA = parseInt($('#select-anio-a').val());
        anioB = parseInt($('#select-anio-b').val());
        if (anioA !== anioB) cargarComparacion();
        else mostrarMensajeVacio();
    });

    /* ── Filtro de fechas ────────────────────────────────────────────────── */
    $('#filtro-desde, #filtro-hasta').on('change', aplicarFiltro);
    $('#btn-limpiar').on('click', function () {
        $('#filtro-desde, #filtro-hasta').val('');
        aplicarFiltro();
    });

    /* ════════════════════════════════════════════════════════════════════════
       MODO
    ═══════════════════════════════════════════════════════════════════════ */
    window.setModo = function (m) {
        modo = m;
        if (m === 'simple') {
            $('#tab-simple').addClass('active');
            $('#tab-comparar').removeClass('active');
            $('#ctrl-simple').css('display', '');
            $('#ctrl-comparar').css('display', 'none !important');
            $('#seccion-simple').show();
            $('#seccion-comparar').hide();
        } else {
            $('#tab-comparar').addClass('active');
            $('#tab-simple').removeClass('active');
            $('#ctrl-simple').css('display', 'none');
            $('#ctrl-comparar').css('display', '').show();
            $('#seccion-simple').hide();
            $('#seccion-comparar').show();
            anioA = parseInt($('#select-anio-a').val());
            anioB = parseInt($('#select-anio-b').val());
            if (anioA !== anioB) cargarComparacion();
            else mostrarMensajeVacio();
        }
    };

    /* ════════════════════════════════════════════════════════════════════════
       MODO SIMPLE
    ═══════════════════════════════════════════════════════════════════════ */
    function cargarDatos(anio) {
        $('#subtitulo').text('Cargando...');
        $.ajax({
            url: 'index.php?action=data&year=' + anio,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    renderizarDatos(res.data);
                } else {
                    $('#subtitulo').text(res.message);
                    limpiarVista();
                }
            },
            error: function () {
                $('#subtitulo').text('Sin datos guardados para este año.');
                limpiarVista();
            }
        });
    }

    function renderizarDatos(data) {
        registros = (data.registros || []).map(r => ({
            fecha:  r.fecha.substring(0, 10),
            compra: parseFloat(r.compra),
            venta:  parseFloat(r.venta),
        })).sort((a, b) => a.fecha.localeCompare(b.fecha));

        $('#subtitulo').text(
            'Del ' + data.desde.substring(0,10) + ' al ' + data.hasta.substring(0,10) +
            ' · Actualizado: ' + data.generado_en
        );
        $('#filtro-desde, #filtro-hasta').val('');
        aplicarFiltro();
    }

    function aplicarFiltro() {
        const desde = $('#filtro-desde').val();
        const hasta = $('#filtro-hasta').val();
        const filtrados = registros.filter(r => {
            if (desde && r.fecha < desde) return false;
            if (hasta && r.fecha > hasta) return false;
            return true;
        });
        poblarTabla(filtrados);
        actualizarCards(filtrados);
        actualizarGraficoSimple(filtrados);
        $('#label-filtrado').text(
            (desde || hasta) && filtrados.length < registros.length
                ? filtrados.length + ' de ' + registros.length + ' registros' : ''
        );
    }

    function poblarTabla(rows) {
        if (tabla) { tabla.destroy(); tabla = null; }
        const $tbody = $('#tabla-body').empty();
        rows.forEach(r => {
            const diff = (r.venta - r.compra).toFixed(2);
            $tbody.append(
                '<tr>' +
                    '<td>' + r.fecha + '</td>' +
                    '<td><span class="badge-compra">₡ ' + r.compra.toFixed(2) + '</span></td>' +
                    '<td><span class="badge-venta">₡ '  + r.venta.toFixed(2)  + '</span></td>' +
                    '<td class="diff-val">₡ ' + diff + '</td>' +
                '</tr>'
            );
        });
        tabla = $('#tabla').DataTable({
            language: { url: 'assets/i18n/es-ES.json' },
            order: [[0, 'asc']], pageLength: 15, lengthMenu: [10, 15, 25, 50, 100],
            columnDefs: [{ orderable: false, targets: [1, 2, 3] }]
        });
    }

    function actualizarCards(rows) {
        if (!rows.length) {
            $('#card-total,#card-compra,#card-venta,#card-max-valor,#card-min-valor').text('—');
            $('#card-max-fecha,#card-min-fecha').text('—');
            return;
        }
        const sumC = rows.reduce((s, r) => s + r.compra, 0);
        const sumV = rows.reduce((s, r) => s + r.venta,  0);
        const maxR = rows.reduce((a, b) => b.venta > a.venta ? b : a);
        const minR = rows.reduce((a, b) => b.venta < a.venta ? b : a);
        $('#card-total').text(rows.length + ' días');
        $('#card-compra').text('₡ ' + (sumC / rows.length).toFixed(2));
        $('#card-venta').text('₡ '  + (sumV / rows.length).toFixed(2));
        $('#card-max-valor').text('₡ ' + maxR.venta.toFixed(2)); $('#card-max-fecha').text(maxR.fecha);
        $('#card-min-valor').text('₡ ' + minR.venta.toFixed(2)); $('#card-min-fecha').text(minR.fecha);
    }

    function actualizarGraficoSimple(rows) {
        const labels  = rows.map(r => r.fecha);
        const compras = rows.map(r => r.compra);
        const ventas  = rows.map(r => r.venta);
        const ctx = document.getElementById('grafico-simple').getContext('2d');
        if (graficoSimple) {
            graficoSimple.data.labels           = labels;
            graficoSimple.data.datasets[0].data = compras;
            graficoSimple.data.datasets[1].data = ventas;
            graficoSimple.update(); return;
        }
        graficoSimple = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label:'Compra (₡)', data:compras, borderColor:'#059669', backgroundColor:'rgba(5,150,105,.08)', borderWidth:2.5, pointRadius:0, pointHoverRadius:5, fill:true, tension:.4 },
                    { label:'Venta (₡)',  data:ventas,  borderColor:'#e11d48', backgroundColor:'rgba(225,29,72,.07)', borderWidth:2.5, pointRadius:0, pointHoverRadius:5, fill:true, tension:.4 }
                ]
            },
            options: chartOptions('₡')
        });
    }

    function limpiarVista() {
        registros = [];
        poblarTabla([]); actualizarCards([]); actualizarGraficoSimple([]);
    }

    /* ════════════════════════════════════════════════════════════════════════
       MODO COMPARAR
    ═══════════════════════════════════════════════════════════════════════ */
    function cargarComparacion() {
        $('#compare-empty-msg').hide();
        $('#compare-table-content').hide();
        $('#compare-empty-msg').text('Cargando datos...').show();

        const reqA = $.ajax({ url:'index.php?action=data&year='+anioA, dataType:'json' });
        const reqB = $.ajax({ url:'index.php?action=data&year='+anioB, dataType:'json' });

        $.when(reqA, reqB).then(function (resA, resB) {
            const dA = resA[0]; const dB = resB[0];

            if (!dA.success && !dB.success) {
                $('#compare-empty-msg').text('No hay datos para ninguno de los dos años seleccionados. Sincroniza desde el panel de administración.').show();
                return;
            }

            registrosA = dA.success ? dA.data.registros.map(r => ({ fecha: r.fecha.substring(0,10), compra: parseFloat(r.compra), venta: parseFloat(r.venta) })) : [];
            registrosB = dB.success ? dB.data.registros.map(r => ({ fecha: r.fecha.substring(0,10), compra: parseFloat(r.compra), venta: parseFloat(r.venta) })) : [];

            if (!dA.success) {
                $('#compare-empty-msg').text('No hay datos para ' + anioA + '. Sincroniza ese año desde el panel de administración.').show();
            } else if (!dB.success) {
                $('#compare-empty-msg').text('No hay datos para ' + anioB + '. Sincroniza ese año desde el panel de administración.').show();
            } else {
                $('#compare-empty-msg').hide();
            }

            renderizarComparacion();
        }).fail(function () {
            $('#compare-empty-msg').text('Error de conexión. Intenta de nuevo.').show();
        });
    }

    function renderizarComparacion() {
        const sA = calcularStats(registrosA);
        const sB = calcularStats(registrosB);

        // Encabezados
        $('#cp-year-a').text(anioA); $('#cp-year-b').text(anioB);

        if (sA && sB) {
            // Filas de datos
            setText('cr-total-a', sA.total + ' días'); setText('cr-total-b', sB.total + ' días');
            setDelta('cr-delta-total', sA.total, sB.total, 0, true);

            setText('cr-compra-a', '₡ ' + sA.compraProm.toFixed(2)); setText('cr-compra-b', '₡ ' + sB.compraProm.toFixed(2));
            setDelta('cr-delta-compra', sA.compraProm, sB.compraProm, 2);

            setText('cr-venta-a', '₡ ' + sA.ventaProm.toFixed(2)); setText('cr-venta-b', '₡ ' + sB.ventaProm.toFixed(2));
            setDelta('cr-delta-venta', sA.ventaProm, sB.ventaProm, 2);

            setText('cr-max-a', '₡ ' + sA.ventaMax.toFixed(2)); setText('cr-max-b', '₡ ' + sB.ventaMax.toFixed(2));
            setDelta('cr-delta-max', sA.ventaMax, sB.ventaMax, 2);

            setText('cr-min-a', '₡ ' + sA.ventaMin.toFixed(2)); setText('cr-min-b', '₡ ' + sB.ventaMin.toFixed(2));
            setDelta('cr-delta-min', sA.ventaMin, sB.ventaMin, 2);

            $('#compare-table-content').show();
        }

        actualizarGraficoCompare();
    }

    function calcularStats(rows) {
        if (!rows.length) return null;
        const sumC = rows.reduce((s, r) => s + r.compra, 0);
        const sumV = rows.reduce((s, r) => s + r.venta,  0);
        return {
            total:      rows.length,
            compraProm: sumC / rows.length,
            ventaProm:  sumV / rows.length,
            ventaMax:   Math.max(...rows.map(r => r.venta)),
            ventaMin:   Math.min(...rows.map(r => r.venta)),
        };
    }

    function promediosMensuales(rows) {
        const m = Array.from({length:12}, () => ({ compra:[], venta:[] }));
        rows.forEach(r => {
            const idx = parseInt(r.fecha.substring(5,7)) - 1;
            m[idx].compra.push(r.compra);
            m[idx].venta.push(r.venta);
        });
        return m.map(x => ({
            compra: x.compra.length ? x.compra.reduce((a,b) => a+b,0) / x.compra.length : null,
            venta:  x.venta.length  ? x.venta.reduce((a,b)  => a+b,0) / x.venta.length  : null,
        }));
    }

    function actualizarGraficoCompare() {
        const pmA = promediosMensuales(registrosA);
        const pmB = promediosMensuales(registrosB);

        const ventasA  = pmA.map(m => m.venta);
        const ventasB  = pmB.map(m => m.venta);
        const comprasA = pmA.map(m => m.compra);
        const comprasB = pmB.map(m => m.compra);
        const showCompra = $('#toggle-compra').is(':checked');
        const ctx = document.getElementById('grafico-compare').getContext('2d');

        const datasets = [
            { label: anioA + ' · Venta',  data:ventasA,  borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,.07)',  borderWidth:2.5, pointRadius:3, pointHoverRadius:6, fill:false, tension:.4 },
            { label: anioB + ' · Venta',  data:ventasB,  borderColor:'#ea580c', backgroundColor:'rgba(234,88,12,.07)',  borderWidth:2.5, pointRadius:3, pointHoverRadius:6, fill:false, tension:.4 },
            { label: anioA + ' · Compra', data:comprasA, borderColor:'#60a5fa', backgroundColor:'transparent', borderWidth:1.8, borderDash:[5,4], pointRadius:2, pointHoverRadius:5, fill:false, tension:.4, hidden:!showCompra },
            { label: anioB + ' · Compra', data:comprasB, borderColor:'#fb923c', backgroundColor:'transparent', borderWidth:1.8, borderDash:[5,4], pointRadius:2, pointHoverRadius:5, fill:false, tension:.4, hidden:!showCompra },
        ];

        if (graficoComp) {
            graficoComp.data.labels   = MESES;
            graficoComp.data.datasets = datasets;
            graficoComp.update(); return;
        }
        graficoComp = new Chart(ctx, {
            type: 'line',
            data: { labels: MESES, datasets },
            options: chartOptions('₡')
        });
    }

    window.toggleCompraLines = function () {
        if (!graficoComp) return;
        const show = $('#toggle-compra').is(':checked');
        [2, 3].forEach(i => {
            graficoComp.data.datasets[i].hidden = !show;
        });
        graficoComp.update();
    };

    function mostrarMensajeVacio() {
        $('#compare-empty-msg').text('Selecciona dos años distintos para ver la comparación.').show();
        $('#compare-table-content').hide();
    }

    /* ════════════════════════════════════════════════════════════════════════
       HOY + CONVERTIDOR
    ═══════════════════════════════════════════════════════════════════════ */
    function sincronizarHoy() {
        $.ajax({
            url: 'index.php?action=today', dataType: 'json',
            success: function (res) {
                if (!res.success || !res.data) return;
                const d = res.data;
                $('#hoy-fecha').text(d.fecha.substring(0,10));
                $('#hoy-compra').text('₡ ' + parseFloat(d.compra).toFixed(2));
                $('#hoy-venta').text('₡ '  + parseFloat(d.venta).toFixed(2));
                if (res.aviso) $('#hoy-aviso').text('⚠ ' + res.aviso);
                $('#hoy-box').css('opacity', '1');

                tasaHoy.compra = parseFloat(d.compra);
                tasaHoy.venta  = parseFloat(d.venta);
                tasaHoy.fecha  = d.fecha.substring(0,10);

                $('#cv-compra').text('₡ ' + tasaHoy.compra.toFixed(2));
                $('#cv-venta').text('₡ '  + tasaHoy.venta.toFixed(2));
                $('#cv-fecha-label').text('Tasas del ' + tasaHoy.fecha + (res.aviso ? ' · dato anterior' : ''));

                if (modo === 'simple' && anioActivo === ANIO_MAX) cargarDatos(ANIO_MAX);
            }
        });
    }

    /* ════════════════════════════════════════════════════════════════════════
       HELPERS
    ═══════════════════════════════════════════════════════════════════════ */
    function llenarSelect($sel, seleccionado) {
        $sel.empty();
        for (let y = ANIO_MAX; y >= ANIO_MIN; y--) {
            $sel.append('<option value="' + y + '"' + (y === seleccionado ? ' selected' : '') + '>' + y + '</option>');
        }
    }

    function setText(id, val) { $('#'+id).text(val); }

    function setDelta(id, vA, vB, decimals, noPrefix) {
        const diff = vA - vB;
        const sign = diff > 0 ? '+' : '';
        const prefix = noPrefix ? '' : '₡ ';
        const pct = vB !== 0 ? ' (' + (diff/vB*100).toFixed(1) + '%)' : '';
        const cls = diff > 0 ? 'delta-up' : diff < 0 ? 'delta-down' : 'delta-flat';
        const arrow = diff > 0 ? ' ↑' : diff < 0 ? ' ↓' : '';
        $('#'+id).html('<span class="'+cls+'">' + sign + prefix + Math.abs(diff).toFixed(decimals) + pct + arrow + '</span>');
    }

    function chartOptions(unit) {
        return {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode:'index', intersect:false },
            plugins: {
                legend: { position:'top', labels: { usePointStyle:true, pointStyleWidth:10, color:'#374151', font:{ family:'Inter', size:12, weight:'600' }, padding:20 } },
                tooltip: { background:'#fff', titleColor:'#1e1b4b', bodyColor:'#6b7280', borderColor:'#e2e8ff', borderWidth:1, padding:12, cornerRadius:10, boxPadding:4, callbacks:{ label: ctx => '  ' + unit + ' ' + ctx.parsed.y.toFixed(2) } }
            },
            scales: {
                x: { grid:{ color:'#f1f5f9' }, ticks:{ color:'#9ca3af', maxTicksLimit:12, maxRotation:45, font:{ family:'Inter', size:11 } } },
                y: { grid:{ color:'#f1f5f9' }, ticks:{ color:'#9ca3af', font:{ family:'Inter', size:11 }, callback: v => unit + ' ' + v.toFixed(0) } }
            }
        };
    }

});

/* ── Suscripción (global) ─────────────────────────────────────────────────── */
function suscribirse(e) {
    e.preventDefault();
    const email = $('#sub-email').val().trim();
    if (!email) return;

    $('#sub-btn').prop('disabled', true);
    $('#sub-spinner').removeClass('d-none');
    $('#sub-icon').addClass('d-none');
    $('#sub-msg').hide().removeClass('ok info err');

    $.ajax({
        url: 'index.php?action=suscribir',
        method: 'POST',
        data: { email },
        dataType: 'json',
        success: function (res) {
            const $msg = $('#sub-msg');
            if (res.success) {
                $msg.addClass('ok').text('✓ ' + res.message).show();
                $('#sub-email').val('');
            } else if (res.info) {
                $msg.addClass('info').text('ℹ ' + res.info).show();
            } else {
                $msg.addClass('err').text('✗ ' + (res.error || 'Error. Intenta de nuevo.')).show();
            }
        },
        error: function () {
            $('#sub-msg').addClass('err').text('✗ Error de conexión. Intenta de nuevo.').show();
        },
        complete: function () {
            $('#sub-btn').prop('disabled', false);
            $('#sub-spinner').addClass('d-none');
            $('#sub-icon').removeClass('d-none');
        }
    });
}

/* ── Convertidor (global) ─────────────────────────────────────────────────── */
let convDir = 'CRC';

function setDir(dir) {
    convDir = dir;
    $('#btn-dir-crc').toggleClass('active', dir === 'CRC');
    $('#btn-dir-usd').toggleClass('active', dir === 'USD');
    $('#cv-prefix').text(dir === 'CRC' ? '₡' : '$');
    $('#cv-amount').val('').trigger('focus');
    $('#cv-result').text('—');
    $('#cv-note').text('Ingresa un monto para convertir');
}

function calcular() {
    const amount = parseFloat($('#cv-amount').val());
    const compra = parseFloat($('#cv-compra').text().replace('₡ ', ''));
    const venta  = parseFloat($('#cv-venta').text().replace('₡ ', ''));

    if (!amount || amount <= 0 || !compra || !venta) {
        $('#cv-result').text('—');
        $('#cv-note').text('Ingresa un monto para convertir');
        return;
    }

    if (convDir === 'CRC') {
        const dolares = amount / venta;
        $('#cv-result').text('$ ' + dolares.toLocaleString('es-CR', { minimumFractionDigits:2, maximumFractionDigits:4 }));
        $('#cv-note').text('Usando tasa de venta ₡ ' + venta.toFixed(2) + ' (precio al que el banco vende $)');
    } else {
        const colones = amount * compra;
        $('#cv-result').text('₡ ' + colones.toLocaleString('es-CR', { minimumFractionDigits:2, maximumFractionDigits:2 }));
        $('#cv-note').text('Usando tasa de compra ₡ ' + compra.toFixed(2) + ' (precio al que el banco compra $)');
    }
}
</script>

</body>
</html>
