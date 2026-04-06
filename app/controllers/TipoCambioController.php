<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/TipoCambioModel.php';

class TipoCambioController extends Controller
{
    private TipoCambioModel $model;

    public function __construct()
    {
        $this->model = new TipoCambioModel();
    }

    /**
     * GET /
     * Sirve la página principal (HTML).
     */
    public function index(): void
    {
        $this->render('index');
    }

    /**
     * GET /data?year=XXXX
     * Devuelve los datos del año solicitado desde la BD.
     */
    public function data(): void
    {
        $year = $this->getValidYear();

        if (!$this->model->hasData($year)) {
            $this->json([
                'success' => false,
                'message' => "No hay datos para $year. Presiona \"Sincronizar\" primero.",
            ], 404);
            return;
        }

        $data = $this->model->getFromDB($year);
        $this->json(['success' => true, 'data' => $data]);
    }

    /**
     * GET /today
     * Consulta el tipo de cambio de hoy en la API del BCCR, lo guarda y lo devuelve.
     * Se llama automáticamente al cargar la página.
     */
    public function today(): void
    {
        try {
            $registro = $this->model->fetchToday();

            if ($registro) {
                $this->json(['success' => true, 'data' => $registro]);
            } else {
                // El BCCR no publica dato los fines de semana y feriados; devolver el último disponible
                $ultimo = $this->model->getLatest();
                $this->json(['success' => true, 'data' => $ultimo, 'aviso' => 'Sin dato para hoy. Mostrando el último disponible.']);
            }
        } catch (RuntimeException $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * GET /sync?year=XXXX
     * Consulta la API, guarda en la BD y devuelve los datos.
     */
    public function sync(): void
    {
        $year = $this->getValidYear();

        try {
            $data = $this->model->fetchAndSave($year);
            $this->json([
                'success' => true,
                'message' => "Se guardaron {$data['total']} registros del {$data['desde']} al {$data['hasta']}.",
                'data'    => $data,
            ]);
        } catch (RuntimeException $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function getValidYear(): int
    {
        $year = (int) ($_GET['year'] ?? date('Y'));
        if ($year < 2000 || $year > (int) date('Y')) {
            $year = (int) date('Y');
        }
        return $year;
    }
}
