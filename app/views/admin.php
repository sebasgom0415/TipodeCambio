<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · Tipo de Cambio</title>
    <link rel="stylesheet" href="assets/css/inter.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-icons.min.css">
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
        body { background-color: var(--bg); color: var(--text); min-height: 100vh; }

        /* ── Hero ──────────────────────────────────────────── */
        .hero {
            background: linear-gradient(135deg, #312e81 0%, #4f46e5 50%, #7c3aed 100%);
            padding: 1.8rem 0;
            box-shadow: 0 6px 32px rgba(79,70,229,.25);
        }
        .hero-title  { font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0; }
        .hero-sub    { color: rgba(255,255,255,.65); font-size: .8rem; margin-top: 4px; }

        .btn-hero-outline {
            background: rgba(255,255,255,.15);
            border: 1.5px solid rgba(255,255,255,.35);
            border-radius: 10px;
            color: #fff;
            font-size: .85rem;
            font-weight: 600;
            padding: .42rem 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background .2s;
            text-decoration: none;
            cursor: pointer;
            backdrop-filter: blur(4px);
        }
        .btn-hero-outline:hover { background: rgba(255,255,255,.26); color: #fff; }

        /* ── Login card ─────────────────────────────────────── */
        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: var(--shadow-lg);
            max-width: 380px;
            margin: 5rem auto 0;
        }
        .login-icon {
            width: 60px; height: 60px;
            background: var(--surface-2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: var(--accent);
            margin: 0 auto 1.2rem;
        }
        .login-title { font-size: 1.15rem; font-weight: 700; text-align: center; margin-bottom: .3rem; }
        .login-sub   { font-size: .82rem; color: var(--muted); text-align: center; margin-bottom: 1.5rem; }

        .form-input {
            background: var(--surface-2);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            padding: .65rem 1rem;
            font-size: .9rem;
            width: 100%;
            transition: border-color .2s;
        }
        .form-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }

        .btn-primary-full {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: .9rem;
            padding: .65rem;
            width: 100%;
            cursor: pointer;
            transition: opacity .2s, transform .15s;
        }
        .btn-primary-full:hover { opacity: .9; transform: translateY(-1px); }

        /* ── Summary cards ──────────────────────────────────── */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            box-shadow: var(--shadow);
        }
        .stat-icon {
            width: 40px; height: 40px; border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; margin-bottom: .8rem;
        }
        .stat-label { font-size: .7rem; color: var(--muted); text-transform: uppercase; letter-spacing: .6px; font-weight: 600; }
        .stat-value { font-size: 1.45rem; font-weight: 700; letter-spacing: -.3px; }
        .icon-blue   { background: #eef2ff; color: var(--accent); }
        .icon-green  { background: #d1fae5; color: var(--green); }
        .icon-yellow { background: #fef3c7; color: var(--yellow); }

        /* ── Action bar ─────────────────────────────────────── */
        .action-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: .9rem 1.2rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
        }
        .action-bar-title { font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); flex: 1; }

        .btn-accent {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: .85rem;
            font-weight: 600;
            padding: .5rem 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: opacity .2s, transform .15s;
        }
        .btn-accent:hover { opacity: .88; transform: translateY(-1px); }
        .btn-accent:disabled { opacity: .5; cursor: not-allowed; transform: none; }

        .btn-outline-sm {
            background: transparent;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            color: var(--muted);
            font-size: .85rem;
            font-weight: 600;
            padding: .5rem 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: border-color .2s, color .2s;
            text-decoration: none;
        }
        .btn-outline-sm:hover { border-color: var(--accent); color: var(--accent); }

        /* ── Year grid ──────────────────────────────────────── */
        .section-title {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--muted);
            margin-bottom: 1rem;
        }

        .year-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 1rem .9rem;
            box-shadow: var(--shadow);
            text-align: center;
            transition: transform .15s, box-shadow .15s;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .45rem;
        }
        .year-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .year-card.synced   { border-color: #a7f3d0; background: linear-gradient(180deg, #fff 60%, #f0fdf4 100%); }
        .year-card.pending  { border-color: #fde68a; background: linear-gradient(180deg, #fff 60%, #fffbeb 100%); }

        .year-num { font-size: 1.35rem; font-weight: 700; color: var(--text); line-height: 1; }

        .badge-synced {
            background: #d1fae5; color: #065f46;
            border-radius: 99px; padding: 3px 10px;
            font-size: .7rem; font-weight: 700; display: inline-block;
        }
        .badge-pending {
            background: #fef3c7; color: #92400e;
            border-radius: 99px; padding: 3px 10px;
            font-size: .7rem; font-weight: 700; display: inline-block;
        }

        .year-records { font-size: .72rem; color: var(--muted); }
        .year-last    { font-size: .68rem; color: var(--muted); }

        .btn-year-sync {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--accent);
            font-size: .75rem;
            font-weight: 600;
            padding: .32rem .7rem;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: auto;
        }
        .btn-year-sync:hover { background: #eef2ff; border-color: var(--accent); }
        .btn-year-sync:disabled { opacity: .4; cursor: not-allowed; }

        .spinner-xs {
            width: 12px; height: 12px;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            display: inline-block;
            animation: spin .6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Table panel ────────────────────────────────────────── */
        .table-panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: var(--shadow); }
        .table-panel .panel-header { padding: .75rem 1.2rem; border-bottom: 1px solid var(--border); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); display:flex;align-items:center;justify-content:space-between }
        .badge-activo    { background:#d1fae5;color:#065f46;border-radius:99px;padding:2px 10px;font-size:.7rem;font-weight:700 }
        .badge-pendiente { background:#fef3c7;color:#92400e;border-radius:99px;padding:2px 10px;font-size:.7rem;font-weight:700 }

        /* ── Toast ──────────────────────────────────────────── */
        .toast-container { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; }
        .my-toast {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow-lg);
            padding: .9rem 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            min-width: 280px;
            max-width: 360px;
            animation: slideIn .3s ease;
        }
        @keyframes slideIn { from { opacity:0; transform: translateY(20px); } to { opacity:1; transform: translateY(0); } }
        .toast-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
        .toast-msg  { font-size: .83rem; color: var(--text); line-height: 1.4; }
        .toast-ok   { border-left: 3px solid var(--green); }
        .toast-ok   .toast-icon { color: var(--green); }
        .toast-err  { border-left: 3px solid var(--red); }
        .toast-err  .toast-icon { color: var(--red); }
        .toast-info { border-left: 3px solid var(--accent); }
        .toast-info .toast-icon { color: var(--accent); }
    </style>
</head>
<body>

<!-- Hero -->
<div class="hero mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <h1 class="hero-title"><i class="bi bi-shield-lock me-2"></i>Panel de Administración</h1>
                <p class="hero-sub mb-0">Tipo de Cambio · BCCR</p>
            </div>
            <?php if ($authenticated): ?>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn-hero-outline"><i class="bi bi-globe2"></i> Ver sitio</a>
                <a href="index.php?page=admin&logout=1" class="btn-hero-outline"><i class="bi bi-box-arrow-right"></i> Salir</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container pb-5">

<?php if (!$authenticated): ?>

    <!-- ── Login ────────────────────────────────────────────── -->
    <div class="login-card">
        <div class="login-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <div class="login-title">Acceso restringido</div>
        <div class="login-sub">Ingresa la contraseña para acceder al panel.</div>
        <?php if ($error): ?>
        <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:.83rem;border-radius:10px">
            <i class="bi bi-exclamation-triangle me-1"></i><?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        <form method="POST" action="index.php?page=admin">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <input type="password" name="key" class="form-input mb-3" placeholder="Contraseña" autofocus required autocomplete="current-password">
            <button type="submit" class="btn-primary-full">
                <i class="bi bi-unlock me-1"></i>Ingresar
            </button>
        </form>
    </div>

<?php else: ?>

    <!-- ── Summary cards ────────────────────────────────────── -->
    <div class="row g-3 mb-3" id="row-summary">
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="bi bi-calendar3"></i></div>
                <div class="stat-label">Años con datos</div>
                <div class="stat-value" id="sum-years" style="color:var(--accent)">—</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="bi bi-database-fill"></i></div>
                <div class="stat-label">Total registros</div>
                <div class="stat-value" id="sum-total" style="color:var(--green)">—</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon icon-yellow"><i class="bi bi-clock-history"></i></div>
                <div class="stat-label">Última fecha</div>
                <div class="stat-value" id="sum-last" style="color:var(--yellow);font-size:1.1rem">—</div>
            </div>
        </div>
    </div>

    <!-- ── Action bar ───────────────────────────────────────── -->
    <div class="action-bar mb-4">
        <span class="action-bar-title"><i class="bi bi-gear me-1"></i>Acciones</span>
        <button id="btn-sync-pendientes" class="btn-accent" onclick="sincronizarPendientes()">
            <span id="sp-pendientes" class="d-none spinner-xs"></span>
            <i class="bi bi-cloud-arrow-down" id="ic-pendientes"></i>
            Sincronizar años faltantes
        </button>
        <button id="btn-reload" class="btn-outline-sm" onclick="cargarAnios()">
            <i class="bi bi-arrow-clockwise"></i> Actualizar
        </button>
        <a href="index.php" class="btn-outline-sm ms-auto"><i class="bi bi-globe2"></i> Ver sitio público</a>
    </div>

    <!-- ── Suscriptores ─────────────────────────────────────── -->
    <div class="section-title mt-2"><i class="bi bi-envelope-heart me-1"></i>Suscriptores de correo</div>
    <div class="action-bar mb-3" id="sub-bar">
        <span class="action-bar-title" id="sub-resumen">Cargando...</span>
        <button class="btn-accent" id="btn-enviar-hoy" onclick="enviarCorreoHoy()">
            <span id="sp-enviar" class="d-none spinner-xs"></span>
            <i class="bi bi-send-fill" id="ic-enviar"></i> Enviar correo ahora
        </button>
    </div>
    <div class="table-panel mb-4" id="tabla-suscriptores-wrap" style="display:none">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-people me-1"></i>Lista de suscriptores</span>
            <button class="btn-outline-sm btn-sm" onclick="$('#tabla-suscriptores-wrap').hide()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="p-3">
            <table class="table table-sm table-borderless mb-0" id="tabla-subs" style="font-size:.82rem">
                <thead>
                    <tr style="color:var(--muted);font-size:.68rem;text-transform:uppercase;letter-spacing:.5px">
                        <th>Correo</th><th>Estado</th><th>Último envío</th><th>Registrado</th>
                    </tr>
                </thead>
                <tbody id="tbody-subs"></tbody>
            </table>
        </div>
    </div>

    <!-- ── Year grid ────────────────────────────────────────── -->
    <div class="section-title"><i class="bi bi-calendar-range me-1"></i>Estado por año</div>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3" id="grid-anios">
        <!-- Cargando... -->
        <div class="col text-center text-muted py-4" id="grid-loading">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <div class="mt-2" style="font-size:.82rem">Cargando años...</div>
        </div>
    </div>

<?php endif; ?>

</div>

<!-- Toast container -->
<div class="toast-container" id="toast-container"></div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.min.js"></script>

<?php if ($authenticated): ?>
<script>
$(function () {
    cargarAnios();
    cargarSuscriptores();
});

let yearsData = [];

function cargarAnios() {
    $('#grid-loading').show();

    $.ajax({
        url: 'index.php?action=years',
        dataType: 'json',
        success: function (res) {
            if (!res.success) { toast('Error cargando años.', 'err'); return; }
            yearsData = res.data;
            renderAnios(yearsData);
            actualizarResumen(yearsData);
        },
        error: function () { toast('No se pudo cargar el estado de los años.', 'err'); },
        complete: function () { $('#grid-loading').hide(); }
    });
}

function renderAnios(years) {
    const $grid = $('#grid-anios').empty();

    years.forEach(function (y) {
        const cls   = y.sincronizado ? 'synced' : 'pending';
        const badge = y.sincronizado
            ? '<span class="badge-synced"><i class="bi bi-check-circle-fill me-1"></i>Sincronizado</span>'
            : '<span class="badge-pending"><i class="bi bi-clock me-1"></i>Pendiente</span>';
        const info = y.sincronizado
            ? '<div class="year-records">' + y.total + ' registros</div>' +
              '<div class="year-last">Hasta: ' + (y.ultima_fecha ? y.ultima_fecha.substring(0,10) : '—') + '</div>'
            : '<div class="year-records" style="color:#9ca3af">Sin datos</div>';
        const btnLabel = y.sincronizado ? 'Re-sincronizar' : 'Sincronizar';

        $grid.append(
            '<div class="col" id="wrap-' + y.year + '">' +
                '<div class="year-card ' + cls + '" id="card-' + y.year + '">' +
                    '<div class="year-num">' + y.year + '</div>' +
                    badge + info +
                    '<button class="btn-year-sync" id="btn-' + y.year + '" onclick="sincronizarAnio(' + y.year + ')">' +
                        '<i class="bi bi-arrow-repeat"></i>' + btnLabel +
                    '</button>' +
                '</div>' +
            '</div>'
        );
    });
}

function actualizarResumen(years) {
    const synced     = years.filter(y => y.sincronizado);
    const totalRecs  = years.reduce((s, y) => s + y.total, 0);
    const allDates   = years.filter(y => y.ultima_fecha).map(y => y.ultima_fecha).sort();
    const lastDate   = allDates.length ? allDates[allDates.length - 1].substring(0,10) : '—';

    $('#sum-years').text(synced.length + ' / ' + years.length);
    $('#sum-total').text(totalRecs.toLocaleString('es-CR'));
    $('#sum-last').text(lastDate);
}

function sincronizarAnio(year) {
    const $btn = $('#btn-' + year);
    $btn.prop('disabled', true).html('<span class="spinner-xs"></span> Sincronizando...');

    $.ajax({
        url: 'index.php?action=sync&year=' + year,
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                toast('✓ ' + year + ' sincronizado — ' + res.data.total + ' registros.', 'ok');
                cargarAnios();
            } else {
                toast('Error en ' + year + ': ' + (res.error || res.message), 'err');
                $btn.prop('disabled', false).html('<i class="bi bi-arrow-repeat"></i> Reintentar');
            }
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.error || 'Error de conexión.';
            toast('Error en ' + year + ': ' + msg, 'err');
            $btn.prop('disabled', false).html('<i class="bi bi-arrow-repeat"></i> Reintentar');
        }
    });
}

function sincronizarPendientes() {
    const pendientes = yearsData.filter(y => !y.sincronizado).map(y => y.year);

    if (!pendientes.length) {
        toast('Todos los años ya están sincronizados.', 'info');
        return;
    }

    $('#btn-sync-pendientes').prop('disabled', true);
    $('#sp-pendientes').removeClass('d-none');
    $('#ic-pendientes').addClass('d-none');

    toast('Sincronizando ' + pendientes.length + ' año(s)...', 'info');

    let idx = 0;
    function siguiente() {
        if (idx >= pendientes.length) {
            $('#btn-sync-pendientes').prop('disabled', false);
            $('#sp-pendientes').addClass('d-none');
            $('#ic-pendientes').removeClass('d-none');
            toast('Sincronización completa.', 'ok');
            cargarAnios();
            return;
        }
        const year = pendientes[idx++];
        $.ajax({
            url: 'index.php?action=sync&year=' + year,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    toast('✓ ' + year + ' — ' + res.data.total + ' registros.', 'ok');
                } else {
                    toast('✗ ' + year + ': ' + (res.error || res.message), 'err');
                }
                siguiente();
            },
            error: function () {
                toast('✗ ' + year + ': Error de conexión.', 'err');
                siguiente();
            }
        });
    }
    siguiente();
}

function cargarSuscriptores() {
    $.ajax({
        url: 'index.php?action=admin_suscriptores',
        dataType: 'json',
        success: function (res) {
            if (!res.success) return;
            const r = res.resumen;
            $('#sub-resumen').html(
                '<strong>' + r.activos + '</strong> activos &nbsp;·&nbsp; ' +
                '<strong>' + r.pendientes + '</strong> pendientes confirmación &nbsp;·&nbsp; ' +
                '<strong>' + r.enviados_hoy + '</strong> recibieron correo hoy'
            );
            const $tbody = $('#tbody-subs').empty();
            if (res.data.length === 0) {
                $tbody.append('<tr><td colspan="4" class="text-center text-muted py-3">Sin suscriptores aún.</td></tr>');
            } else {
                res.data.forEach(function (s) {
                    const badge = s.activo
                        ? '<span class="badge-activo">Activo</span>'
                        : '<span class="badge-pendiente">Pendiente</span>';
                    $tbody.append('<tr>' +
                        '<td>' + s.email + '</td>' +
                        '<td>' + badge + '</td>' +
                        '<td>' + (s.ultimo_envio || '—') + '</td>' +
                        '<td>' + (s.created_at ? s.created_at.substring(0,10) : '—') + '</td>' +
                    '</tr>');
                });
                $('#tabla-suscriptores-wrap').show();
            }
        }
    });
}

function enviarCorreoHoy() {
    if (!confirm('¿Enviar el tipo de cambio de hoy a todos los suscriptores activos?')) return;

    $('#btn-enviar-hoy').prop('disabled', true);
    $('#sp-enviar').removeClass('d-none');
    $('#ic-enviar').addClass('d-none');

    $.ajax({
        url: 'index.php?action=admin_enviar',
        method: 'POST',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                toast(res.message, 'ok');
                cargarSuscriptores();
            } else {
                toast(res.error || 'Error al enviar.', 'err');
            }
        },
        error: function () { toast('Error de conexión.', 'err'); },
        complete: function () {
            $('#btn-enviar-hoy').prop('disabled', false);
            $('#sp-enviar').addClass('d-none');
            $('#ic-enviar').removeClass('d-none');
        }
    });
}

function toast(msg, type) {
    const icons = { ok: 'bi-check-circle-fill', err: 'bi-x-circle-fill', info: 'bi-info-circle-fill' };
    const $t = $('<div class="my-toast toast-' + type + '">' +
        '<i class="bi ' + icons[type] + ' toast-icon"></i>' +
        '<div class="toast-msg">' + msg + '</div>' +
    '</div>');
    $('#toast-container').append($t);
    setTimeout(() => $t.fadeOut(400, () => $t.remove()), 4000);
}
</script>
<?php endif; ?>

</body>
</html>
