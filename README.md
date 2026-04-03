  ---                                                                                                                                                                               
  Tipo de Cambio — Dólar                                                                                                                                                            
                                                                                                                                                                                    
  Aplicación web en PHP que consulta el historial del tipo de cambio del dólar (compra/venta) desde la API oficial del Ministerio de Hacienda de Costa Rica y lo persiste en MySQL. 
  Incluye una interfaz con gráfico de líneas, tarjetas resumen y tabla con historial del año en curso.                                                                            

  Requisitos

  - XAMPP (Apache + MySQL + PHP 8.x)
  - mod_rewrite habilitado en Apache

  Instalación

  1. Clonar o copiar la carpeta del proyecto en htdocs/tipoCambio.
  2. Crear la base de datos y la tabla en phpMyAdmin (o MySQL CLI):

  CREATE DATABASE tipocambio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  USE tipocambio;

  CREATE TABLE tipo_cambio (
      fecha   DATE          NOT NULL PRIMARY KEY,
      compra  DECIMAL(10,2) NOT NULL,
      venta   DECIMAL(10,2) NOT NULL
  );

  3. Ajustar credenciales en config/config.php si es necesario (por defecto usa root sin contraseña o la tuya).
  4. Acceder desde el navegador: http://localhost/tipoCambio/public/

  Uso

  - Al cargar la página se muestran los datos ya guardados en la BD (si los hay).
  - Presionar Sincronizar para consultar la API de Hacienda y guardar/actualizar los registros del año en curso (1 enero hasta hoy).

  Estructura

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

  API utilizada

  https://api.hacienda.go.cr/indicadores/tc/dolar/historico
  Parámetros: d (fecha inicio YYYY-MM-DD) y h (fecha fin YYYY-MM-DD).

  Stack

  - PHP 8 (sin frameworks)
  - MySQL / PDO
  - Bootstrap 5.3
  - Chart.js 4
  - jQuery 3.7
