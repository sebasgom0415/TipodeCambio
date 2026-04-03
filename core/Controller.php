<?php

abstract class Controller
{
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    protected function render(string $view, array $vars = []): void
    {
        extract($vars);
        require __DIR__ . '/../app/views/' . $view . '.php';
    }
}
