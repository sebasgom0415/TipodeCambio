<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipo de Cambio - Dólar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg:        #f5f7ff;
            --surface:   #ffffff;
            --surface-2: #eef2ff;
            --border:    #e2e8ff;
            --text:      #1e1b4b;
            --muted:     #6b7280;
            --accent:    #4f46e5;
            --green:     #059669;
            --red:       #e11d48;
            --yellow:    #d97706;
            --shadow:    0 1px 3px rgba(79,70,229,.07), 0 4px 18px rgba(79,70,229,.09);
            --shadow-lg: 0 4px 10px rgba(79,70,229,.10), 0 14px 36px rgba(79,70,229,.13);
        }

        * { box-sizing: border-box; font-family: 'Inter', system-ui, sans-serif; }

        body {
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Hero header ─────────────────────────────────────── */
        .hero {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 2rem 0 1.8rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 32px rgba(79,70,229,.25);
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -40px; left: 10%;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-title {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
            color: #fff;
        }
        .hero-subtitle {
            color: rgba(255,255,255,.7);
            font-size: 0.82rem;
            margin-top: 5px;
        }

        /* ── Controls ────────────────────────────────────────── */
        .ctrl-select {
            background-color: rgba(255,255,255,.15);
            border: 1.5px solid rgba(255,255,255,.3);
            color: #fff;
            border-radius: 10px;
            padding: 0.42rem 2rem 0.42rem 0.85rem;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background .2s;
            backdrop-filter: blur(4px);
        }
        .ctrl-select:focus { outline: none; background: rgba(255,255,255,.22); box-shadow: 0 0 0 3px rgba(255,255,255,.2); border-color: rgba(255,255,255,.5); }
        .ctrl-select option { background: #4f46e5; color: #fff; }

        .btn-sync-hero {
            background: rgba(255,255,255,.18);
            border: 1.5px solid rgba(255,255,255,.35);
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.48rem 1.2rem;
            display: flex; align-items: center; gap: 6px;
            transition: background .2s, transform .15s;
            backdrop-filter: blur(4px);
        }
        .btn-sync-hero:hover { background: rgba(255,255,255,.28); color: #fff; transform: translateY(-1px); }

        /* ── Stat cards ──────────────────────────────────────── */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            box-shadow: var(--shadow);
            transition: transform .2s, box-shadow .2s;
            height: 100%;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
        .stat-icon {
            width: 42px; height: 42px;
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
            margin-bottom: 0.85rem;
        }
        .stat-label { font-size: 0.72rem; color: var(--muted); text-transform: uppercase; letter-spacing: .65px; font-weight: 600; margin-bottom: 5px; }
        .stat-value { font-size: 1.5rem; font-weight: 700; letter-spacing: -.4px; }
        .stat-date  { font-size: 0.78rem; color: var(--muted); margin-top: 3px; }

        .icon-blue   { background: #eef2ff; color: var(--accent); }
        .icon-green  { background: #d1fae5; color: var(--green); }
        .icon-red    { background: #ffe4e6; color: var(--red); }
        .icon-yellow { background: #fef3c7; color: var(--yellow); }

        /* ── Chart card ──────────────────────────────────────── */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.4rem;
            box-shadow: var(--shadow);
        }
        .panel-title {
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: .7px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 1rem;
        }
        #chart-container { position: relative; height: 340px; }

        /* ── Filter bar ──────────────────────────────────────── */
        .filter-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem 1.4rem;
            box-shadow: var(--shadow);
        }
        .filter-bar .form-control,
        .filter-bar .form-control:focus {
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 8px;
            font-size: 0.85rem;
        }
        .filter-bar .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        .filter-bar .form-label { color: var(--muted); font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
        .btn-outline-dim {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
            border-radius: 8px;
            font-size: 0.85rem;
            transition: border-color .2s, color .2s, background .2s;
        }
        .btn-outline-dim:hover { border-color: var(--accent); color: var(--accent); background: #eef2ff; }

        /* ── Table panel ─────────────────────────────────────── */
        .table-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        .table-panel .panel-header {
            padding: 1rem 1.4rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: .7px;
            font-weight: 600;
            color: var(--muted);
        }

        /* ── DataTable styles ────────────────────────────────── */
        #tabla, #tabla tbody, #tabla tbody td, #tabla tbody tr { color: var(--text); font-size: 0.88rem; }
        #tabla thead th {
            background: var(--surface-2) !important;
            color: var(--accent);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            border-color: #c7d2fe !important;
            padding: 0.85rem 1rem;
            border-bottom: 2px solid #c7d2fe !important;
        }
        #tabla tbody td {
            border-color: #f1f5f9;
            padding: 0.72rem 1rem;
            vertical-align: middle;
        }
        #tabla tbody tr { transition: background .12s; }
        #tabla tbody tr:hover td { background: #f8faff !important; }

        /* Bootstrap table vars override */
        #tabla {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: transparent;
            --bs-table-active-bg: transparent;
            --bs-table-hover-bg: #f8faff;
            --bs-table-color: var(--text);
            --bs-table-striped-color: var(--text);
            --bs-table-hover-color: var(--text);
            --bs-table-border-color: #f1f5f9;
        }
        table.dataTable tbody tr,
        table.dataTable tbody tr.odd,
        table.dataTable tbody tr.even,
        table.dataTable.stripe > tbody > tr.odd > *,
        table.dataTable.display > tbody > tr.odd > * {
            background-color: transparent !important;
            box-shadow: none !important;
        }
        table.dataTable tbody tr:hover > * {
            background-color: #f8faff !important;
            box-shadow: none !important;
        }

        /* Badges */
        .badge-compra { background: #d1fae5; color: var(--green); border-radius: 99px; padding: 5px 14px; font-weight: 600; font-size: 0.83rem; display: inline-block; }
        .badge-venta  { background: #ffe4e6; color: var(--red);   border-radius: 99px; padding: 5px 14px; font-weight: 600; font-size: 0.83rem; display: inline-block; }
        .diff-val     { color: #6366f1; font-weight: 500; font-size: 0.85rem; }

        /* ── DataTables controls ─────────────────────────────── */
        .dataTables_wrapper { padding: 1.2rem 1.4rem; background: transparent !important; }
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_info { color: var(--muted) !important; font-size: 0.83rem; }
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            background: var(--surface-2) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
            border-radius: 8px;
            padding: 4px 10px;
        }
        .dataTables_wrapper .dataTables_filter input:focus,
        .dataTables_wrapper .dataTables_length select:focus {
            outline: none;
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button { color: var(--muted) !important; font-size: 0.83rem; border-radius: 7px !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
            color: #fff !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
            background: var(--surface-2) !important;
            border-color: var(--border) !important;
            color: var(--accent) !important;
        }

        /* ── Modal ───────────────────────────────────────────── */
        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            color: var(--text);
            box-shadow: 0 20px 60px rgba(79,70,229,.18);
        }
        .modal-header { border-bottom: 1px solid var(--border); }
        .modal-footer { border-top: 1px solid var(--border); }
        .modal-title  { font-weight: 700; font-size: 1rem; color: var(--text); }
        .modal-ctrl-select {
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 10px;
            padding: .55rem .85rem;
            font-size: 0.9rem;
            width: 100%;
        }
        .modal-ctrl-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        .modal-ctrl-select option { background: var(--surface); color: var(--text); }

    </style>
</head>
<body>

<!-- Hero header -->
<div class="hero mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="hero-title">
                    <i class="bi bi-currency-dollar me-1"></i>
                    Tipo de Cambio &nbsp;·&nbsp; Dólar
                </h1>
                <p class="hero-subtitle mb-0" id="subtitulo">Cargando datos...</p>
            </div>
            <!-- Tipo de cambio de hoy -->
            <div id="hoy-box" class="d-flex align-items-center gap-3 flex-wrap" style="opacity:0;transition:opacity .4s">
                <div style="text-align:right">
                    <div style="font-size:.68rem;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.6px;font-weight:600">Hoy · <span id="hoy-fecha">—</span></div>
                    <div class="d-flex gap-2 mt-1">
                        <span style="background:rgba(5,150,105,.25);border:1px solid rgba(5,150,105,.5);color:#6ee7b7;border-radius:8px;padding:4px 14px;font-size:.95rem;font-weight:700">
                            <i class="bi bi-arrow-down me-1"></i>Compra: <span id="hoy-compra">—</span>
                        </span>
                        <span style="background:rgba(225,29,72,.22);border:1px solid rgba(225,29,72,.45);color:#fca5a5;border-radius:8px;padding:4px 14px;font-size:.95rem;font-weight:700">
                            <i class="bi bi-arrow-up me-1"></i>Venta: <span id="hoy-venta">—</span>
                        </span>
                    </div>
                    <div id="hoy-aviso" style="font-size:.68rem;color:rgba(255,255,255,.5);margin-top:3px"></div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <select id="select-ver-anio" class="ctrl-select"></select>
                <button id="btn-sync" class="btn-sync-hero" data-bs-toggle="modal" data-bs-target="#modalSync">
                    <i class="bi bi-arrow-repeat"></i> Sincronizar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">

    <!-- Fila 1: totales y promedios -->
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

    <!-- Fila 2: máximo y mínimo -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <div class="stat-card" style="border-left: 3px solid var(--red);">
                <div class="stat-icon icon-red"><i class="bi bi-arrow-up-circle-fill"></i></div>
                <div class="stat-label">Día con venta más alta</div>
                <div class="stat-value" id="card-max-valor" style="color:var(--red)">—</div>
                <div class="stat-date" id="card-max-fecha">—</div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="stat-card" style="border-left: 3px solid var(--green);">
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

    <!-- Gráfico -->
    <div class="panel mb-4">
        <div class="panel-title"><i class="bi bi-bar-chart-line me-1"></i>Evolución del tipo de cambio</div>
        <div id="chart-container">
            <canvas id="grafico"></canvas>
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

</div>

<!-- Modal Sincronizar -->
<div class="modal fade" id="modalSync" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header px-4 pt-4 pb-3">
                <h5 class="modal-title"><i class="bi bi-arrow-repeat me-2" style="color:var(--accent)"></i>Sincronizar datos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <label for="select-sync-anio" class="form-label" style="color:var(--muted);font-size:.76rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Seleccionar año</label>
                <select id="select-sync-anio" class="modal-ctrl-select"></select>
                <p class="mt-3 mb-0" style="font-size:.8rem;color:var(--muted)">
                    <i class="bi bi-info-circle me-1"></i>
                    Se consultará la API del BCCR y se guardarán los registros en la base de datos.
                </p>
            </div>
            <div class="modal-footer px-4 pb-4 pt-3 gap-2">
                <button type="button" class="btn btn-outline-dim px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-sync-hero px-4" id="btn-sync-confirmar" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);border:none">
                    <span id="spinner-sync" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    <i class="bi bi-cloud-download" id="icon-sync"></i>
                    Sincronizar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {

    const ANIO_MIN     = 2000;
    const ANIO_MAX     = new Date().getFullYear();
    let anioActivo     = ANIO_MAX - 1;
    let grafico        = null;
    let tabla          = null;
    let todosRegistros = [];

    // ── Inicializar selectores de año ────────────────────────────────────────
    function llenarSelectAnio($select, seleccionado) {
        $select.empty();
        for (let y = ANIO_MAX; y >= ANIO_MIN; y--) {
            $select.append(
                '<option value="' + y + '"' + (y === seleccionado ? ' selected' : '') + '>' + y + '</option>'
            );
        }
    }

    llenarSelectAnio($('#select-ver-anio'), anioActivo);
    llenarSelectAnio($('#select-sync-anio'), anioActivo);

    // ── Consulta automática del tipo de cambio de HOY ───────────────────────
    sincronizarHoy();

    cargarDatos(anioActivo);

    // ── Cambio de año en el selector de vista ───────────────────────────────
    $('#select-ver-anio').on('change', function () {
        anioActivo = parseInt($(this).val());
        cargarDatos(anioActivo);
    });

    // ── SweetAlert2 con tema claro ───────────────────────────────────────────
    const swalLight = Swal.mixin({
        background: '#ffffff',
        color: '#1e1b4b',
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#e8eaf6',
        customClass: { cancelButton: 'swal-cancel-btn' }
    });

    // ── Botón confirmar sincronización ───────────────────────────────────────
    $('#btn-sync-confirmar').on('click', function () {
        const anio = parseInt($('#select-sync-anio').val());

        bootstrap.Modal.getInstance(document.getElementById('modalSync')).hide();

        $.ajax({
            url: 'index.php?action=data&year=' + anio,
            method: 'GET',
            dataType: 'json',
            success: function (checkRes) {
                if (checkRes.success) {
                    swalLight.fire({
                        icon: 'warning',
                        title: anio + ' ya está sincronizado',
                        text: '¿Desea volver a sincronizar los datos de este año?',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, sincronizar',
                        cancelButtonText: 'Cancelar',
                    }).then(result => {
                        if (result.isConfirmed) ejecutarSync(anio);
                    });
                } else {
                    ejecutarSync(anio);
                }
            },
            error: function () {
                ejecutarSync(anio);
            }
        });
    });

    function ejecutarSync(anio) {
        swalLight.fire({
            title: 'Sincronizando ' + anio + '...',
            text: 'Consultando la API de Hacienda',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: 'index.php?action=sync&year=' + anio,
            method: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    anioActivo = anio;
                    $('#select-ver-anio').val(anio);
                    renderizarDatos(res.data);
                    swalLight.fire({
                        icon: 'success',
                        title: '¡Sincronizado!',
                        text: res.message,
                        timer: 3500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                } else {
                    swalLight.fire({
                        icon: 'error',
                        title: 'Error al sincronizar',
                        text: res.error || res.message,
                    });
                }
            },
            error: function (xhr) {
                const res = xhr.responseJSON;
                swalLight.fire({
                    icon: 'error',
                    title: 'Error al sincronizar',
                    text: res?.error || 'No se pudo conectar con el servidor.',
                });
            }
        });
    }

    // ── Filtro de fechas ─────────────────────────────────────────────────────
    $('#btn-limpiar').on('click', function () {
        $('#filtro-desde, #filtro-hasta').val('');
        aplicarFiltro();
    });

    $('#filtro-desde, #filtro-hasta').on('change', function () {
        aplicarFiltro();
    });

    // ── Funciones ────────────────────────────────────────────────────────────

    function cargarDatos(anio) {
        $('#subtitulo').text('Cargando...');
        $.ajax({
            url: 'index.php?action=data&year=' + anio,
            method: 'GET',
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
                $('#subtitulo').text('Sin datos guardados. Presiona "Sincronizar".');
                limpiarVista();
            }
        });
    }

    function renderizarDatos(data) {
        todosRegistros = (data.registros || []).map(function (r) {
            return {
                fecha:  formatFecha(r.fecha),
                compra: parseFloat(r.compra),
                venta:  parseFloat(r.venta),
            };
        }).sort((a, b) => a.fecha.localeCompare(b.fecha));

        $('#subtitulo').text(
            'Del ' + formatFecha(data.desde) + ' al ' + formatFecha(data.hasta) +
            ' · Actualizado: ' + data.generado_en
        );

        $('#filtro-desde, #filtro-hasta').val('');
        aplicarFiltro();
    }

    function aplicarFiltro() {
        const desde = $('#filtro-desde').val();
        const hasta = $('#filtro-hasta').val();

        const filtrados = todosRegistros.filter(function (r) {
            if (desde && r.fecha < desde) return false;
            if (hasta && r.fecha > hasta) return false;
            return true;
        });

        poblarTabla(filtrados);
        actualizarCards(filtrados);
        actualizarGrafico(filtrados);

        const total = filtrados.length;
        $('#label-filtrado').text(
            (desde || hasta) && total < todosRegistros.length
                ? total + ' de ' + todosRegistros.length + ' registros'
                : ''
        );
    }

    function poblarTabla(registros) {
        if (tabla) { tabla.destroy(); tabla = null; }

        const $tbody = $('#tabla-body').empty();

        registros.forEach(function (r) {
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
            language: { url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json' },
            order: [[0, 'asc']],
            pageLength: 15,
            lengthMenu: [10, 15, 25, 50, 100],
            columnDefs: [{ orderable: false, targets: [1, 2, 3] }]
        });
    }

    function actualizarCards(registros) {
        if (!registros.length) {
            $('#card-total, #card-compra, #card-venta, #card-max-valor, #card-min-valor').text('—');
            $('#card-max-fecha, #card-min-fecha').text('—');
            return;
        }

        const totalCompra = registros.reduce((s, r) => s + r.compra, 0);
        const totalVenta  = registros.reduce((s, r) => s + r.venta,  0);
        const maxR        = registros.reduce((a, b) => b.venta > a.venta ? b : a);
        const minR        = registros.reduce((a, b) => b.venta < a.venta ? b : a);

        $('#card-total').text(registros.length + ' días');
        $('#card-compra').text('₡ ' + (totalCompra / registros.length).toFixed(2));
        $('#card-venta').text('₡ '  + (totalVenta  / registros.length).toFixed(2));

        $('#card-max-valor').text('₡ ' + maxR.venta.toFixed(2));
        $('#card-max-fecha').text(maxR.fecha);
        $('#card-min-valor').text('₡ ' + minR.venta.toFixed(2));
        $('#card-min-fecha').text(minR.fecha);
    }

    function actualizarGrafico(registros) {
        const labels  = registros.map(r => r.fecha);
        const compras = registros.map(r => r.compra);
        const ventas  = registros.map(r => r.venta);

        if (grafico) {
            grafico.data.labels           = labels;
            grafico.data.datasets[0].data = compras;
            grafico.data.datasets[1].data = ventas;
            grafico.update();
            return;
        }

        const ctx = document.getElementById('grafico').getContext('2d');

        grafico = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Compra (₡)',
                        data: compras,
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5,150,105,0.08)',
                        borderWidth: 2.5,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Venta (₡)',
                        data: ventas,
                        borderColor: '#e11d48',
                        backgroundColor: 'rgba(225,29,72,0.07)',
                        borderWidth: 2.5,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        fill: true,
                        tension: 0.4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyleWidth: 10,
                            color: '#374151',
                            font: { family: 'Inter', size: 12, weight: '600' },
                            padding: 20,
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#1e1b4b',
                        bodyColor: '#6b7280',
                        borderColor: '#e2e8ff',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 10,
                        boxPadding: 4,
                        callbacks: { label: ctx => '  ₡ ' + ctx.parsed.y.toFixed(2) }
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#9ca3af',
                            maxTicksLimit: 12,
                            maxRotation: 45,
                            font: { family: 'Inter', size: 11 },
                        }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#9ca3af',
                            font: { family: 'Inter', size: 11 },
                            callback: val => '₡ ' + val.toFixed(0)
                        }
                    }
                }
            }
        });
    }

    function limpiarVista() {
        todosRegistros = [];
        poblarTabla([]);
        actualizarCards([]);
        actualizarGrafico([]);
    }

    function formatFecha(fechaStr) {
        return fechaStr.substring(0, 10);
    }

    // ── Sincronización automática diaria ────────────────────────────────────
    function sincronizarHoy() {
        $.ajax({
            url: 'index.php?action=today',
            method: 'GET',
            dataType: 'json',
            success: function (res) {
                if (!res.success || !res.data) return;
                const d = res.data;
                $('#hoy-fecha').text(formatFecha(d.fecha));
                $('#hoy-compra').text('₡ ' + parseFloat(d.compra).toFixed(2));
                $('#hoy-venta').text('₡ '  + parseFloat(d.venta).toFixed(2));
                if (res.aviso) $('#hoy-aviso').text('⚠ ' + res.aviso);
                $('#hoy-box').css('opacity', '1');

                // Si el año activo es el año actual, refrescar la tabla con el nuevo dato
                if (anioActivo === ANIO_MAX) {
                    cargarDatos(ANIO_MAX);
                }
            }
        });
    }

});

</script>

</body>
</html>
