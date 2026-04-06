# Tipo de Cambio — Dólar BCCR

Aplicación web en PHP que consulta y persiste el historial del tipo de cambio del dólar (compra/venta) desde la API oficial del Banco Central de Costa Rica (BCCR). Incluye una interfaz pública con gráfico, estadísticas, comparación entre años y convertidor de colones a dólares; un panel de administración protegido para gestionar la sincronización de datos; y un sistema de suscripción por correo para recibir la tasa diaria.

---

## Características

- **Vista pública** — tasas del día, gráfico de líneas, estadísticas, tabla con historial y filtro por fechas
- **Comparar años** — gráfico superpuesto con promedios mensuales y estadísticas comparativas
- **Convertidor ₡ / $** — modal flotante que usa las tasas del día para convertir en ambas direcciones
- **Suscripción por correo** — doble opt-in, envío diario vía Gmail SMTP, link de cancelación en cada correo
- **Panel de administración** — sincronización por año, gestión de suscriptores, envío manual de correos
- **Sin dependencias externas en runtime** — todas las librerías JS/CSS se sirven localmente
- **Seguridad** — headers HTTP, CSRF, rate limiting, protección de directorios, sesiones seguras

---

## Requisitos

- XAMPP (Apache + MySQL + PHP 8.x)
- Cuenta registrada en el sitio de indicadores del BCCR (ver sección API)
- Cuenta de Gmail con **App Password** habilitada para el envío de correos

---

## Instalación local (XAMPP)

### 1. Clonar el proyecto

```
htdocs/tipoCambio/
```

### 2. Crear la base de datos

Ejecutar en phpMyAdmin o MySQL CLI:

```sql
create database tipocambio

USE tipocambio

CREATE TABLE tipo_cambio (                                                                                                                                                        
      id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,                                                                                                                          
      fecha       DATE            NOT NULL,                                                                                                                                         
      venta       DECIMAL(10, 2)  NOT NULL,                                                                                                                                         
      compra      DECIMAL(10, 2)  NOT NULL,
      created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      UNIQUE KEY uq_fecha (fecha)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

> La tabla `suscriptores` se crea automáticamente la primera vez que alguien intenta suscribirse.

### 3. Configurar `config/config.php`

```php
// Base de datos
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'tipocambio');
define('DB_USER', 'root');
define('DB_PASS', '');

// API del BCCR
define('API_TOKEN', 'tu_token_jwt_aqui');

// Panel de administración
define('ADMIN_KEY', 'contraseña_segura');

// Correo (Gmail)
define('MAIL_USER', 'tu.correo@gmail.com');
define('MAIL_PASS', 'xxxx xxxx xxxx xxxx'); // App Password de Google

// URL pública del sitio (sin barra al final)
define('APP_URL', 'http://localhost/tipoCambio/public');

// Clave para el cron job
define('CRON_KEY', 'clave_secreta_cron');
```

### 4. Acceder al sitio

```
http://localhost/tipoCambio/public/
```

Panel de administración:
```
http://localhost/tipoCambio/public/?page=admin
```

---

## Instalación en Hostinger

1. Subir todos los archivos al hosting.
2. En el panel de Hostinger, establecer `public/` como **Document Root**.
3. Crear la base de datos MySQL desde el panel y actualizar `config/config.php` con las credenciales del hosting.
4. Cambiar `APP_URL` al dominio real:
   ```php
   define('APP_URL', 'https://tudominio.com');
   ```
5. Configurar el cron job diario (ver sección Cron Job).

> **Importante:** No subas `config/config.php` a un repositorio público. Agrégalo al `.gitignore`.

---

## Cron job — envío diario de correos

Para que el sistema envíe automáticamente el tipo de cambio cada mañana, crea un cron job en Hostinger que ejecute esta URL:

```
https://tudominio.com/index.php?action=cron_enviar&key=TU_CRON_KEY
```

Horario sugerido: `0 8 * * *` (todos los días a las 8:00 a.m.)

La `CRON_KEY` debe coincidir exactamente con la definida en `config/config.php`.

---

## Configuración de la API del BCCR

### Registrar usuario

1. Ir a `https://sdd.bccr.fi.cr/es/IndicadoresEconomicos/Inicio/`
2. Crear un usuario nuevo y acceder a **Mi Perfil**
3. Copiar el token JWT y pegarlo en `config/config.php` como `API_TOKEN`

**Endpoint utilizado:**
```
GET https://apim.bccr.fi.cr/SDDE/api/Bccr.GE.SDDE.Publico.Indicadores.API/cuadro/1/series
    ?idioma=ES&fechaInicio=yyyy/mm/dd&fechaFin=yyyy/mm/dd
Authorization: Bearer <token>
```

Indicadores: **317** (Compra) · **318** (Venta) · Datos desde: **1983-01-01**

---

## Configuración de Gmail (App Password)

1. Activar la verificación en dos pasos en la cuenta de Google
2. Ir a `Cuenta de Google → Seguridad → Contraseñas de aplicaciones`
3. Crear una nueva contraseña para "Correo"
4. Copiar los 16 caracteres y pegarlos en `config/config.php` como `MAIL_PASS`

---

## Estructura del proyecto

```
tipoCambio/
├── app/
│   ├── controllers/
│   │   └── TipoCambioController.php   # Lógica de rutas y acciones
│   ├── models/
│   │   ├── TipoCambioModel.php        # Datos de tipo de cambio
│   │   └── SuscriptorModel.php        # Suscriptores de correo
│   ├── services/
│   │   └── EmailService.php           # Envío de correos via PHPMailer
│   ├── lib/
│   │   └── PHPMailer/                 # PHPMailer v6.9.1
│   └── views/
│       ├── index.php                  # Vista pública
│       ├── admin.php                  # Panel de administración
│       └── message.php               # Página de confirmación / baja
├── config/
│   └── config.php                     # Credenciales y constantes
├── core/
│   ├── Controller.php                 # Clase base (headers, CSRF, render)
│   └── Model.php                      # Clase base (PDO, API fetch)
├── public/
│   ├── assets/
│   │   ├── css/                       # Bootstrap, Bootstrap Icons, DataTables, Inter
│   │   ├── js/                        # Bootstrap, jQuery, Chart.js, DataTables
│   │   ├── fonts/                     # Inter (woff2), Bootstrap Icons
│   │   └── i18n/                      # DataTables es-ES.json
│   ├── .htaccess                      # Headers de seguridad, bloqueo de bots
│   └── index.php                      # Router principal
├── storage/                           # JSONs de respaldo (ignorar en producción)
└── README.md
```

---

## Seguridad implementada

| Medida | Descripción |
|---|---|
| Headers HTTP | `X-Frame-Options`, `X-Content-Type-Options`, `X-XSS-Protection`, `Referrer-Policy` |
| CSRF | Token en el formulario de login del admin |
| Rate limiting | 15 s entre sincronizaciones, 60 s entre consultas a la API, 30 s entre suscripciones |
| Sesiones | `httponly`, `samesite=Strict`, `use_strict_mode`, regeneración de ID |
| Directorios | `.htaccess` en `app/`, `core/`, `config/`, `storage/` deniega acceso directo |
| Brute force | `sleep(1)` en contraseña incorrecta del admin |
| Errores PHP | `display_errors Off` en producción |

---

## Stack

| Categoría | Tecnología |
|---|---|
| Backend | PHP 8 sin frameworks, PDO |
| Base de datos | MySQL |
| Frontend | Bootstrap 5.3, Bootstrap Icons 1.11 |
| Gráficos | Chart.js 4.4 |
| Tablas | DataTables 2.0 |
| JavaScript | jQuery 3.7 |
| Tipografía | Inter (self-hosted) |
| Email | PHPMailer 6.9 + Gmail SMTP |
