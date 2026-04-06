<?php

require_once __DIR__ . '/../app/controllers/TipoCambioController.php';

$controller = new TipoCambioController();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'sync':
        $controller->sync();
        break;

    case 'data':
        $controller->data();
        break;

    case 'today':
        $controller->today();
        break;

    default:
        $controller->index();
        break;
}
