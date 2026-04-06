<?php

require_once __DIR__ . '/../../core/Model.php';
require_once __DIR__ . '/../../config/config.php';

class TipoCambioModel extends Model
{
    /**
     * Consulta la API del BCCR y guarda/actualiza los registros en la BD para el año dado.
     */
    public function fetchAndSave(int $year): array
    {
        $desde = $year . '-01-01';
        $hasta = ($year === (int) date('Y')) ? date('Y-m-d') : $year . '-12-31';

        // La API del BCCR requiere fechas en formato yyyy/mm/dd
        $desdeApi = str_replace('-', '/', $desde);
        $hastaApi = str_replace('-', '/', $hasta);

        $url  = API_BASE_URL . '?idioma=ES&fechaInicio=' . $desdeApi . '&fechaFin=' . $hastaApi;
        $raw  = $this->fetchFromApi($url);
        $data = $this->parseBccrResponse($raw);

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
     * Transforma la respuesta del BCCR al formato interno [{fecha, compra, venta}].
     */
    private function parseBccrResponse(array $raw): array
    {
        if (empty($raw['datos'][0]['indicadores'])) {
            throw new RuntimeException('La respuesta de la API no contiene datos de indicadores.');
        }

        $compras = [];
        $ventas  = [];

        foreach ($raw['datos'][0]['indicadores'] as $indicador) {
            $codigo = (string) $indicador['codigoIndicador'];
            foreach ($indicador['series'] as $punto) {
                $fecha = substr($punto['fecha'], 0, 10);
                $valor = (float) $punto['valorDatoPorPeriodo'];
                if ($codigo === '317') {
                    $compras[$fecha] = $valor;
                } elseif ($codigo === '318') {
                    $ventas[$fecha] = $valor;
                }
            }
        }

        $fechas = array_unique(array_merge(array_keys($compras), array_keys($ventas)));
        sort($fechas);

        $resultado = [];
        foreach ($fechas as $fecha) {
            if (!isset($compras[$fecha]) || !isset($ventas[$fecha])) {
                continue;
            }
            $resultado[] = [
                'fecha'  => $fecha,
                'compra' => $compras[$fecha],
                'venta'  => $ventas[$fecha],
            ];
        }

        return $resultado;
    }

    /**
     * Consulta la API solo para hoy y guarda/actualiza el registro.
     * Devuelve el registro de hoy {fecha, compra, venta} o null si no hay dato.
     */
    public function fetchToday(): ?array
    {
        $hoy     = date('Y-m-d');
        $hoyApi  = date('Y/m/d');

        $url = API_BASE_URL . '?idioma=ES&fechaInicio=' . $hoyApi . '&fechaFin=' . $hoyApi;
        $raw = $this->fetchFromApi($url);

        $data = $this->parseBccrResponse($raw);

        if (empty($data)) {
            return null;
        }

        $db   = $this->getDB();
        $stmt = $db->prepare('
            INSERT INTO tipo_cambio (fecha, venta, compra)
            VALUES (:fecha, :venta, :compra)
            ON DUPLICATE KEY UPDATE venta = VALUES(venta), compra = VALUES(compra)
        ');
        $stmt->execute([
            ':fecha'  => $data[0]['fecha'],
            ':venta'  => $data[0]['venta'],
            ':compra' => $data[0]['compra'],
        ]);

        return $data[0];
    }

    /**
     * Devuelve el último registro disponible en la BD (el día más reciente).
     */
    public function getLatest(): ?array
    {
        $stmt = $this->getDB()->query('
            SELECT fecha, compra, venta
            FROM tipo_cambio
            ORDER BY fecha DESC
            LIMIT 1
        ');
        $row = $stmt->fetch();
        if (!$row) return null;

        return [
            'fecha'  => $row['fecha'],
            'compra' => (float) $row['compra'],
            'venta'  => (float) $row['venta'],
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
