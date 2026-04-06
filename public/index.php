<?php
// ── Seguridad de sesión ──────────────────────────────────────────────────────
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? '1' : '0');
ini_set('session.gc_maxlifetime', '3600');

session_start();

// Regenerar ID al inicio de sesión nueva (previene session fixation)
if (!isset($_SESSION['_init'])) {
    session_regenerate_id(true);
    $_SESSION['_init'] = true;
}

require_once __DIR__ . '/../app/controllers/TipoCambioController.php';

$controller = new TipoCambioController();

$page   = $_GET['page']   ?? '';
$action = $_GET['action'] ?? '';

if ($page === 'admin') {
    $controller->admin();
} else {
    switch ($action) {
        case 'sync':              $controller->sync();              break;
        case 'data':              $controller->data();              break;
        case 'today':             $controller->today();             break;
        case 'years':             $controller->years();             break;
        case 'suscribir':         $controller->suscribir();         break;
        case 'confirmar':         $controller->confirmar();         break;
        case 'desuscribir':       $controller->desuscribir();       break;
        case 'cron_enviar':       $controller->cronEnviar();        break;
        case 'admin_enviar':      $controller->adminEnviar();       break;
        case 'admin_suscriptores':$controller->adminSuscriptores(); break;
        default:                  $controller->index();             break;
    }
}
