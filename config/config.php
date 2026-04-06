<?php

define('API_BASE_URL', 'https://apim.bccr.fi.cr/SDDE/api/Bccr.GE.SDDE.Publico.Indicadores.API/cuadro/1/series');
define('API_TOKEN',   '');
define('STORAGE_PATH', __DIR__ . '/../storage/');
define('JSON_FILE', STORAGE_PATH . 'tipo_cambio_' . date('Y') . '.json');

// Base de datos

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'tipocambio');
define('DB_USER', 'root');
define('DB_PASS', '');
