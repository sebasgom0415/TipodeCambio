<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/TipoCambioModel.php';
require_once __DIR__ . '/../models/SuscriptorModel.php';
require_once __DIR__ . '/../services/EmailService.php';

class TipoCambioController extends Controller
{
    private TipoCambioModel $model;

    public function __construct()
    {
        $this->model = new TipoCambioModel();
    }

    /** GET /  — Sirve la página pública. */
    public function index(): void
    {
        $this->render('index');
    }

    /** GET ?action=data&year=XXXX  — Datos del año desde la BD. */
    public function data(): void
    {
        $year = $this->getValidYear();

        if (!$this->model->hasData($year)) {
            $this->json([
                'success' => false,
                'message' => "No hay datos para $year. Sincroniza desde el panel de administración.",
            ], 404);
            return;
        }

        $data = $this->model->getFromDB($year);
        $this->json(['success' => true, 'data' => $data]);
    }

    /** GET ?action=today  — Tipo de cambio de hoy. */
    public function today(): void
    {
        // Rate limit: máximo 1 consulta cada 60 s por sesión
        $now  = time();
        $last = $_SESSION['rl_today'] ?? 0;
        if (($now - $last) < 60) {
            // Devolver último dato guardado sin llamar a la API
            $ultimo = $this->model->getLatest();
            $this->json([
                'success' => true,
                'data'    => $ultimo,
                'aviso'   => 'Dato en caché.',
            ]);
            return;
        }
        $_SESSION['rl_today'] = $now;

        try {
            $registro = $this->model->fetchToday();
            if ($registro) {
                $this->json(['success' => true, 'data' => $registro]);
            } else {
                $ultimo = $this->model->getLatest();
                $this->json([
                    'success' => true,
                    'data'    => $ultimo,
                    'aviso'   => 'Sin dato para hoy. Mostrando el último disponible.',
                ]);
            }
        } catch (RuntimeException $e) {
            $this->json(['success' => false, 'error' => 'Error al consultar la API.'], 500);
        }
    }

    /** GET ?action=sync&year=XXXX  — Sincroniza un año con la API del BCCR. */
    public function sync(): void
    {
        $year = $this->getValidYear();

        // Rate limit: mínimo 15 s entre sincronizaciones del mismo año
        $key  = 'rl_sync_' . $year;
        $now  = time();
        $last = $_SESSION[$key] ?? 0;
        if (($now - $last) < 15) {
            $this->json([
                'success' => false,
                'error'   => 'Espere unos segundos antes de volver a sincronizar.',
            ], 429);
            return;
        }
        $_SESSION[$key] = $now;

        try {
            $data = $this->model->fetchAndSave($year);
            $this->json([
                'success' => true,
                'message' => "Se guardaron {$data['total']} registros del {$data['desde']} al {$data['hasta']}.",
                'data'    => $data,
            ]);
        } catch (RuntimeException $e) {
            $this->json(['success' => false, 'error' => 'Error al sincronizar con la API.'], 500);
        }
    }

    /** GET/POST ?page=admin  — Panel de administración. */
    public function admin(): void
    {
        $error = null;

        if (isset($_GET['logout'])) {
            session_destroy();
            header('Location: index.php?page=admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->verifyCsrfToken()) {
                $error = 'Solicitud inválida. Recarga la página e intenta de nuevo.';
            } elseif (($_POST['key'] ?? '') === ADMIN_KEY) {
                session_regenerate_id(true);
                $_SESSION['admin_auth'] = true;
                header('Location: index.php?page=admin');
                exit;
            } else {
                // Pequeña pausa para dificultar fuerza bruta
                sleep(1);
                $error = 'Contraseña incorrecta.';
            }
        }

        $authenticated = !empty($_SESSION['admin_auth']);
        $csrfToken     = $this->getCsrfToken();
        $this->render('admin', compact('authenticated', 'error', 'csrfToken'));
    }

    /** GET ?action=years  — Estado de sincronización por año (requiere sesión admin). */
    public function years(): void
    {
        if (empty($_SESSION['admin_auth'])) {
            $this->json(['success' => false, 'error' => 'No autorizado.'], 401);
            return;
        }
        $this->json(['success' => true, 'data' => $this->model->getYearsStatus()]);
    }

    /* ══════════════════════════════════════════════════════════════════════
       SUSCRIPCIONES
    ═══════════════════════════════════════════════════════════════════════ */

    /** POST ?action=suscribir  — Registra un nuevo suscriptor. */
    public function suscribir(): void
    {
        // Rate limit: 1 intento por 30 s por sesión
        $now  = time();
        $last = $_SESSION['rl_sub'] ?? 0;
        if (($now - $last) < 30) {
            $this->json(['success' => false, 'error' => 'Espera unos segundos antes de intentarlo de nuevo.'], 429);
            return;
        }
        $_SESSION['rl_sub'] = $now;

        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $this->json(['success' => false, 'error' => 'Correo electrónico inválido.'], 422);
            return;
        }

        $model     = new SuscriptorModel();
        $resultado = $model->suscribir($email);

        switch ($resultado['estado']) {
            case 'activo':
                $this->json(['success' => false, 'info' => 'Este correo ya está suscrito y confirmado.']);
                return;
            case 'pendiente':
                $this->json(['success' => false, 'info' => 'Ya enviamos un correo de confirmación a esa dirección. Revisa tu bandeja.']);
                return;
        }

        try {
            (new EmailService())->enviarConfirmacion($email, $resultado['token']);
            $this->json(['success' => true, 'message' => '¡Listo! Revisa tu correo y confirma tu suscripción.']);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => 'No se pudo enviar el correo. Intenta más tarde.'], 500);
        }
    }

    /** GET ?action=confirmar&token=XXX  — Confirma la suscripción. */
    public function confirmar(): void
    {
        $token = trim($_GET['token'] ?? '');
        if (empty($token)) {
            $this->render('message', ['type' => 'error', 'title' => 'Enlace inválido', 'body' => 'El enlace de confirmación no es válido.']);
            return;
        }

        $model = new SuscriptorModel();
        $email = $model->confirmar($token);

        if ($email) {
            $this->render('message', ['type' => 'success', 'title' => '¡Suscripción confirmada!', 'body' => "Genial, $email empezará a recibir el tipo de cambio diario del dólar (BCCR)."]);
        } else {
            $this->render('message', ['type' => 'error', 'title' => 'Enlace no encontrado', 'body' => 'Este enlace ya fue usado o no existe. Si ya confirmaste antes, todo está en orden.']);
        }
    }

    /** GET ?action=desuscribir&token=XXX  — Elimina la suscripción. */
    public function desuscribir(): void
    {
        $token = trim($_GET['token'] ?? '');
        if (empty($token)) {
            $this->render('message', ['type' => 'error', 'title' => 'Enlace inválido', 'body' => 'El enlace para cancelar no es válido.']);
            return;
        }

        $model = new SuscriptorModel();
        $email = $model->desuscribir($token);

        if ($email) {
            $this->render('message', ['type' => 'info', 'title' => 'Suscripción cancelada', 'body' => "$email fue eliminado de la lista. Ya no recibirás más correos."]);
        } else {
            $this->render('message', ['type' => 'error', 'title' => 'Enlace no encontrado', 'body' => 'Este enlace no es válido o ya fue usado anteriormente.']);
        }
    }

    /** GET ?action=cron_enviar&key=XXX  — Endpoint para el cron job de Hostinger. */
    public function cronEnviar(): void
    {
        if (($_GET['key'] ?? '') !== CRON_KEY) {
            $this->json(['success' => false, 'error' => 'No autorizado.'], 401);
            return;
        }

        $this->enviarCorreoDiario();
    }

    /** POST ?action=admin_enviar  — Envío manual desde el admin (requiere sesión). */
    public function adminEnviar(): void
    {
        if (empty($_SESSION['admin_auth'])) {
            $this->json(['success' => false, 'error' => 'No autorizado.'], 401);
            return;
        }
        $this->enviarCorreoDiario();
    }

    /** GET ?action=admin_suscriptores  — Lista para el admin. */
    public function adminSuscriptores(): void
    {
        if (empty($_SESSION['admin_auth'])) {
            $this->json(['success' => false, 'error' => 'No autorizado.'], 401);
            return;
        }
        $model = new SuscriptorModel();
        $this->json(['success' => true, 'data' => $model->listar(), 'resumen' => $model->resumen()]);
    }

    /** Lógica de envío masivo compartida por cron y admin. */
    private function enviarCorreoDiario(): void
    {
        $tasa = $this->model->getLatest();
        if (!$tasa) {
            $this->json(['success' => false, 'error' => 'Sin datos de tipo de cambio disponibles.'], 500);
            return;
        }

        $model    = new SuscriptorModel();
        $pendientes = $model->activosPendientesHoy();

        if (empty($pendientes)) {
            $this->json(['success' => true, 'message' => 'No hay suscriptores pendientes de recibir el correo hoy.', 'enviados' => 0]);
            return;
        }

        $emailSvc = new EmailService();
        $enviados = 0;
        $errores  = [];

        foreach ($pendientes as $sub) {
            try {
                $emailSvc->enviarTipoDia($sub['email'], $sub['token'], $tasa);
                $model->marcarEnviadoHoy((int) $sub['id']);
                $enviados++;
            } catch (\Exception $e) {
                $errores[] = $sub['email'];
            }
        }

        $this->json([
            'success'  => true,
            'message'  => "Correos enviados: $enviados de " . count($pendientes) . '.',
            'enviados' => $enviados,
            'errores'  => $errores,
        ]);
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
