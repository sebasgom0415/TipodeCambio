<?php
// Copia este archivo como config.php y rellena los valores

define('API_BASE_URL', 'https://apim.bccr.fi.cr/SDDE/api/Bccr.GE.SDDE.Publico.Indicadores.API/cuadro/1/series');
define('API_TOKEN',   'TU_TOKEN_JWT_DEL_BCCR');
define('STORAGE_PATH', __DIR__ . '/../storage/');
define('JSON_FILE', STORAGE_PATH . 'tipo_cambio_' . date('Y') . '.json');

// Base de datos
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'tipocambio');
define('DB_USER', 'root');
define('DB_PASS', '');

// Panel de administración
define('ADMIN_KEY', 'cambia_esta_contrasena');

// Correo (Gmail SMTP)
define('MAIL_HOST',      'smtp.gmail.com');
define('MAIL_PORT',      587);
define('MAIL_USER',      'tu.correo@gmail.com');
define('MAIL_PASS',      'xxxx xxxx xxxx xxxx'); // App Password de Google
define('MAIL_FROM_NAME', 'Tipo de Cambio BCCR');

// URL pública del sitio (sin barra al final)
define('APP_URL', 'http://localhost/tipoCambio/public');

// Clave secreta para el cron job
define('CRON_KEY', 'cambia_esta_clave_cron');
