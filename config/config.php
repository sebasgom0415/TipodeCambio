<?php

define('API_BASE_URL', 'https://apim.bccr.fi.cr/SDDE/api/Bccr.GE.SDDE.Publico.Indicadores.API/cuadro/1/series');
define('API_TOKEN',   'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJCQ0NSLVNEREUiLCJzdWIiOiJzZWJhc3RpYW5nb21lejE2cml2ZXJhQG91dGxvb2suY29tIiwiYXVkIjoiU0RERS1TaXRpb0V4dGVybm8iLCJleHAiOjI1MzQwMjMwMDgwMCwibmJmIjoxNzc1NDg2MjAwLCJpYXQiOjE3NzU0ODYyMDAsImp0aSI6ImE1MmE2MDY2LTQ5MjYtNDE5Mi05NGY4LTFhZjMxMGNmOTI2NSIsImVtYWlsIjoic2ViYXN0aWFuZ29tZXoxNnJpdmVyYUBvdXRsb29rLmNvbSJ9.Fsuae_qUIKjbMujgKOkTqCF-6qYLAZToQw2pI57cfG0');
define('STORAGE_PATH', __DIR__ . '/../storage/');
define('JSON_FILE', STORAGE_PATH . 'tipo_cambio_' . date('Y') . '.json');

// Base de datos

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'tipocambio');
define('DB_USER', 'root');
define('DB_PASS', '');
