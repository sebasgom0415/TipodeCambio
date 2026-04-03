<?php

require_once __DIR__ . '/../../core/Model.php';
require_once __DIR__ . '/../../config/config.php';

class TipoCambioModel extends Model
{
    /**
     * Consulta la API de Hacienda y guarda/actualiza los registros en la BD para el año dado.
     */
    public function fetchAndSave(int $year): array
    {
        $desde = $year . '-01-01';
        $hasta = ($year === (int) date('Y')) ? date('Y-m-d') : $year . '-12-31';

        $url  = API_BASE_URL . '?d=' . $desde . '&h=' . $hasta;
        $data = $this->fetchFromApi($url);

        $db   = $this->getDB();
        $stmt = $db->prepare('
            INSERT INTO tipo_cambio (fecha, venta, compra)
            VALUES (:fecha, :venta, :compra)
            ON DUPLICATE KEY UPDATE venta = VALUES(venta), compra = VALUES(compra)
        ');

        foreach ($data as $registro) {
            $stmt->execute([
                ':fecha'  => $registro['fecha'],
                ':venta'  => $registro['venta'],
                ':compra' => $registro['compra'],
            ]);
        }

        return [
            'generado_en' => date('Y-m-d H:i:s'),
            'desde'       => $desde,
            'hasta'       => $hasta,
            'total'       => count($data),
            'registros'   => $data,
        ];
    }

    /**
     * Lee los registros del año dado desde la BD.
     */
    public function getFromDB(int $year): array
    {
        $desde = $year . '-01-01';
        $hasta = ($year === (int) date('Y')) ? date('Y-m-d') : $year . '-12-31';

        $stmt = $this->getDB()->prepare('
            SELECT fecha, compra, venta
            FROM tipo_cambio
            WHERE fecha BETWEEN :desde AND :hasta
            ORDER BY fecha ASC
        ');
        $stmt->execute([':desde' => $desde, ':hasta' => $hasta]);
        $registros = $stmt->fetchAll();

        foreach ($registros as &$r) {
            $r['compra'] = (float) $r['compra'];
            $r['venta']  = (float) $r['venta'];
        }

        return [
            'generado_en' => date('Y-m-d H:i:s'),
            'desde'       => $desde,
            'hasta'       => $hasta,
            'total'       => count($registros),
            'registros'   => $registros,
        ];
    }

    /**
     * Indica si ya hay datos en la BD para el año dado.
     */
    public function hasData(int $year): bool
    {
        $desde = $year . '-01-01';
        $hasta = ($year === (int) date('Y')) ? date('Y-m-d') : $year . '-12-31';

        $stmt = $this->getDB()->prepare(
            'SELECT COUNT(*) FROM tipo_cambio WHERE fecha BETWEEN :desde AND :hasta'
        );
        $stmt->execute([':desde' => $desde, ':hasta' => $hasta]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
