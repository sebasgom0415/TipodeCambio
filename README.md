# Tipo de Cambio — Dólar

Aplicación web en PHP que consulta el historial del tipo de cambio del dólar (compra/venta) desde la API oficial del Banco Central de Costa Rica (BCCR) y lo persiste en MySQL. Incluye una interfaz con gráfico de líneas, tarjetas resumen, tabla con historial desde el año 2000 y sincronización automática diaria al cargar la página.

---

## Requisitos

- XAMPP (Apache + MySQL + PHP 8.x)
- Extensión `zip` de PHP habilitada (ver paso 3)
- Cuenta registrada en el sitio de indicadores del BCCR (ver paso 1)

---

## Configuración de la API del BCCR

El BCCR cuenta con una nueva plataforma de indicadores económicos que expone un API REST con autenticación Bearer. Para utilizarla es necesario crear un usuario y obtener un token de acceso.

### Paso 1 — Crear usuario en el sitio del BCCR

1. Ingresar a: https://sdd.bccr.fi.cr/es/IndicadoresEconomicos/Inicio/
2. Hacer clic en **"Iniciar sesión"** y registrar un nuevo usuario.
3. Puede consultar la guía oficial de registro en:
   https://gee.bccr.fi.cr/indicadoreseconomicos/Documentos/DocumentosMetodologiasNotasTecnicas/Guia_de_uso_sitio_externo.pdf

### Paso 2 — Obtener el token de acceso

1. Una vez registrado e iniciada la sesión, ir a la sección **"Mi Perfil"**.
2. Copiar el **token de acceso** (JWT) que aparece en esa sección.

### Paso 3 — Configurar el token en el proyecto

Abrir el archivo `config/config.php` y reemplazar el valor de `API_TOKEN` con el token obtenido:

```php
define('API_TOKEN', 'pegar_aqui_tu_token');
```

El endpoint utilizado es:

```
GET https://apim.bccr.fi.cr/SDDE/api/Bccr.GE.SDDE.Publico.Indicadores.API/cuadro/1/series
    ?idioma=ES&fechaInicio=yyyy/mm/dd&fechaFin=yyyy/mm/dd
```

Indicadores consultados: **317** (Compra) y **318** (Venta).

Guía técnica completa del API:
https://gee.bccr.fi.cr/indicadoreseconomicos/Documentos/DocumentosMetodologiasNotasTecnicas/Estandar_API_SDDE.pdf

---

## Instalación

1. Clonar o copiar la carpeta del proyecto en `htdocs/tipoCambio`.

2. Crear la base de datos y la tabla en phpMyAdmin o MySQL CLI:

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

3. Habilitar la extensión `zip` de PHP en XAMPP:
   - Abrir `C:\xampp\php\php.ini`.
   - Buscar la línea `;extension=zip` y quitarle el `;` para que quede `extension=zip`.
   - Reiniciar Apache desde el panel de XAMPP.

4. Configurar el token del BCCR en `config/config.php` (ver sección anterior).

5. Acceder desde el navegador: http://localhost/tipoCambio/public/

---

## Uso

- Al cargar la página, la app **consulta automáticamente** el tipo de cambio del día actual en el BCCR, lo guarda en la base de datos y lo muestra en el encabezado. Si el BCCR no publica dato para ese día (fin de semana o feriado), se muestra el último valor disponible.
- Usar el selector de año para ver el historial de cualquier año entre 2000 y el año actual.
- Presionar **Sincronizar** para importar todos los registros de un año completo desde la API del BCCR y guardarlos en la base de datos.

---

## Estructura

```
tipoCambio/
├── app/
│   ├── controllers/TipoCambioController.php
│   ├── models/TipoCambioModel.php
│   └── views/index.php
├── config/config.php
├── core/
│   ├── Controller.php
│   └── Model.php
└── public/
    ├── .htaccess
    └── index.php
```

---

## API utilizada

**Banco Central de Costa Rica — SDDE API**
`https://apim.bccr.fi.cr/SDDE/api/Bccr.GE.SDDE.Publico.Indicadores.API/cuadro/1/series`

Parámetros: `idioma` (ES), `fechaInicio` y `fechaFin` en formato `yyyy/mm/dd`.
Autenticación: `Authorization: Bearer <token>`

Datos históricos disponibles desde: **1983-01-01**

---

## Stack

- PHP 8 (sin frameworks)
- MySQL / PDO
- Bootstrap 5.3
- Chart.js 4
- jQuery 3.7
