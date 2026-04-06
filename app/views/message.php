<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> · Tipo de Cambio</title>
    <link rel="stylesheet" href="assets/css/inter.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-icons.min.css">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
        body { background: #f4f6ff; min-height: 100vh; display: flex; flex-direction: column; }
        .hero { background: linear-gradient(135deg,#312e81,#4f46e5,#7c3aed); padding: 1.4rem 0; box-shadow: 0 4px 20px rgba(79,70,229,.2); }
        .hero a { color: rgba(255,255,255,.75); text-decoration: none; font-size: .85rem; font-weight: 600; }
        .hero a:hover { color: #fff; }
        .msg-card { background: #fff; border: 1px solid #e2e8ff; border-radius: 20px; padding: 2.5rem 2rem; box-shadow: 0 4px 24px rgba(79,70,229,.1); max-width: 480px; width: 100%; margin: auto; text-align: center; }
        .msg-icon { width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1.2rem; }
        .msg-title { font-size: 1.25rem; font-weight: 800; color: #1e1b4b; margin-bottom: .5rem; }
        .msg-body  { font-size: .9rem; color: #6b7280; line-height: 1.6; margin-bottom: 1.5rem; }
        .btn-home  { background: linear-gradient(135deg,#4f46e5,#7c3aed); color: #fff; border: none; border-radius: 10px; font-weight: 600; font-size: .88rem; padding: .6rem 1.4rem; text-decoration: none; display: inline-block; }
        .icon-success { background: #d1fae5; color: #059669; }
        .icon-error   { background: #ffe4e6; color: #e11d48; }
        .icon-info    { background: #eef2ff; color: #4f46e5; }
    </style>
</head>
<body>
<div class="hero mb-0">
    <div class="container">
        <a href="index.php"><i class="bi bi-arrow-left me-1"></i>Tipo de Cambio · BCCR</a>
    </div>
</div>
<div class="container d-flex flex-column align-items-center justify-content-center flex-grow-1 py-5">
    <div class="msg-card">
        <div class="msg-icon icon-<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>">
            <?php
                $icons = ['success' => 'bi-check-circle-fill', 'error' => 'bi-x-circle-fill', 'info' => 'bi-info-circle-fill'];
                echo '<i class="bi ' . ($icons[$type] ?? 'bi-info-circle-fill') . '"></i>';
            ?>
        </div>
        <div class="msg-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></div>
        <div class="msg-body"><?= htmlspecialchars($body, ENT_QUOTES, 'UTF-8') ?></div>
        <a href="index.php" class="btn-home"><i class="bi bi-house me-1"></i>Volver al inicio</a>
    </div>
</div>
</body>
</html>
