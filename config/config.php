<?php

define('API_BASE_URL', 'https://api.hacienda.go.cr/indicadores/tc/dolar/historico');
define('STORAGE_PATH', __DIR__ . '/../storage/');
define('JSON_FILE', STORAGE_PATH . 'tipo_cambio_' . date('Y') . '.json');

// Base de datos
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'tipocambio');
define('DB_USER', 'root');
define('DB_PASS', '4cRCLnKBPrmNFGN8');
