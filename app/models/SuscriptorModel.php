<?php

require_once __DIR__ . '/../../core/Model.php';

class SuscriptorModel extends Model
{
    public function __construct()
    {
        $this->ensureTable();
    }

    /** Crea la tabla si no existe (migración automática). */
    private function ensureTable(): void
    {
        $this->getDB()->exec('
            CREATE TABLE IF NOT EXISTS suscriptores (
                id          INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
                email       VARCHAR(255) NOT NULL UNIQUE,
                token       VARCHAR(64)  NOT NULL,
                activo      TINYINT(1)   NOT NULL DEFAULT 0,
                ultimo_envio DATE                 DEFAULT NULL,
                created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ');
    }

    /**
     * Intenta crear una nueva suscripción.
     * Retorna: 'nuevo' | 'pendiente' | 'activo'
     */
    public function suscribir(string $email): array
    {
        $existing = $this->buscarPorEmail($email);

        if ($existing) {
            return [
                'estado' => $existing['activo'] ? 'activo' : 'pendiente',
                'token'  => $existing['token'],
            ];
        }

        $token = bin2hex(random_bytes(32));
        $stmt  = $this->getDB()->prepare('
            INSERT INTO suscriptores (email, token) VALUES (:email, :token)
        ');
        $stmt->execute([':email' => $email, ':token' => $token]);

        return ['estado' => 'nuevo', 'token' => $token];
    }

    /** Activa la suscripción por token. Retorna el email o null si token inválido. */
    public function confirmar(string $token): ?string
    {
        $stmt = $this->getDB()->prepare('
            SELECT id, email, activo FROM suscriptores WHERE token = :token LIMIT 1
        ');
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch();

        if (!$row) return null;

        if (!$row['activo']) {
            $this->getDB()->prepare('UPDATE suscriptores SET activo = 1 WHERE id = :id')
                          ->execute([':id' => $row['id']]);
        }

        return $row['email'];
    }

    /** Elimina al suscriptor por token. Retorna email o null. */
    public function desuscribir(string $token): ?string
    {
        $stmt = $this->getDB()->prepare('
            SELECT id, email FROM suscriptores WHERE token = :token LIMIT 1
        ');
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $this->getDB()->prepare('DELETE FROM suscriptores WHERE id = :id')
                      ->execute([':id' => $row['id']]);

        return $row['email'];
    }

    /** Retorna todos los suscriptores activos que NO han recibido correo hoy. */
    public function activosPendientesHoy(): array
    {
        $hoy  = date('Y-m-d');
        $stmt = $this->getDB()->prepare('
            SELECT id, email, token
            FROM suscriptores
            WHERE activo = 1
              AND (ultimo_envio IS NULL OR ultimo_envio < :hoy)
        ');
        $stmt->execute([':hoy' => $hoy]);
        return $stmt->fetchAll();
    }

    /** Marca que se envió el correo hoy a este suscriptor. */
    public function marcarEnviadoHoy(int $id): void
    {
        $this->getDB()->prepare('UPDATE suscriptores SET ultimo_envio = :hoy WHERE id = :id')
                      ->execute([':hoy' => date('Y-m-d'), ':id' => $id]);
    }

    /** Resumen para el admin. */
    public function resumen(): array
    {
        $row = $this->getDB()->query('
            SELECT
                COUNT(*)                                  AS total,
                SUM(activo = 1)                           AS activos,
                SUM(activo = 0)                           AS pendientes,
                SUM(activo = 1 AND ultimo_envio = CURDATE()) AS enviados_hoy
            FROM suscriptores
        ')->fetch();

        return [
            'total'        => (int) $row['total'],
            'activos'      => (int) $row['activos'],
            'pendientes'   => (int) $row['pendientes'],
            'enviados_hoy' => (int) $row['enviados_hoy'],
        ];
    }

    /** Lista para el admin. */
    public function listar(): array
    {
        return $this->getDB()->query('
            SELECT email, activo, ultimo_envio, created_at
            FROM suscriptores
            ORDER BY created_at DESC
        ')->fetchAll();
    }

    private function buscarPorEmail(string $email): array|false
    {
        $stmt = $this->getDB()->prepare('
            SELECT id, email, token, activo FROM suscriptores WHERE email = :email LIMIT 1
        ');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
}
