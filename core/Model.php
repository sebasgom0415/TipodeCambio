<?php

abstract class Model
{
    protected function getDB(): PDO
    {
        static $pdo = null;

        if ($pdo === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return $pdo;
    }

    protected function fetchFromApi(string $url): array
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 30,
                'header'  => implode("\r\n", [
                    'Accept: application/json',
                    'Authorization: Bearer ' . API_TOKEN,
                    'User-Agent: Mozilla/5.0',
                ]),
            ],
        ]);

        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new RuntimeException("No se pudo conectar a la API: $url");
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Respuesta inválida de la API: ' . json_last_error_msg());
        }

        if (isset($data['statusCode']) && $data['statusCode'] >= 400) {
            throw new RuntimeException("Error de la API ({$data['statusCode']}): " . ($data['message'] ?? 'desconocido'));
        }

        if (isset($data['estado']) && $data['estado'] === false) {
            throw new RuntimeException('Error de la API: ' . ($data['mensaje'] ?? 'desconocido'));
        }

        return $data;
    }
}
