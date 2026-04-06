<?php

abstract class Controller
{
    protected function json(array $data, int $status = 200): void
    {
        $this->setSecurityHeaders();
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    protected function render(string $view, array $vars = []): void
    {
        $this->setSecurityHeaders();
        extract($vars);
        require __DIR__ . '/../app/views/' . $view . '.php';
    }

    /**
     * Cabeceras de seguridad HTTP enviadas en todas las respuestas.
     */
    protected function setSecurityHeaders(): void
    {
        if (headers_sent()) return;
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }

    /**
     * Genera (o recupera) el token CSRF de la sesión actual.
     */
    protected function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verifica que el token CSRF del POST sea válido.
     */
    protected function verifyCsrfToken(): bool
    {
        $submitted = $_POST['csrf_token'] ?? '';
        return !empty($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $submitted);
    }
}
