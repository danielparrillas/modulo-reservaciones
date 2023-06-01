<?php

/**
 * *1️⃣CONFIGURACION DE ERRORES
 * Se configura el manejo de mensaje de errores
 * Se formatean de modo que muestren en formato JSON
 * Es muy util ya que esto permitira que los errores se envien al cliente api en formato JSON
 */
include_once(dirname(__DIR__) . '/utils/ManejadorDeErrores.php');

//Definimos el manejador de errores
set_error_handler("ManejadorDeErrores::handleError");
// Definimos que el controlador de error sera la clase ErrorHandler
set_exception_handler("ManejadorDeErrores::handleException");

/**
 * *2️⃣SE IMPORTAN LA CONFIGURACIONES DEL ARCHIVO "config.json"
 * Obtencion de los datos del archivo de configuracion
 * Para guardarla en la constante CONFIGURACION
 */
$config_path = dirname(__DIR__) . '/config/config.json';
$config_file = file_get_contents($config_path);
$config_data = json_decode($config_file, true);
define("CONFIGURACION", $config_data);

/**
 * *3️⃣CONFIGURACION DE LAS BASES DE DATOS
 * Se obtienen los datos de la configuracion de conexion con db de la constante CONFIGURACION
 */
include_once(dirname(__DIR__) . '/utils/Database.php');

// 3️⃣.1️⃣cambia la base de datos a conveniencia
$database = "dev-local"; //dev-marn || dev-local || produccion
$DB_RESERVACIONES = new Database(
  CONFIGURACION["databases"][$database]['host'],
  CONFIGURACION["databases"][$database]['name'],
  CONFIGURACION["databases"][$database]['user'],
  CONFIGURACION["databases"][$database]['password'],
  CONFIGURACION["databases"][$database]['driver'],
  CONFIGURACION["databases"][$database]['port'],
  CONFIGURACION["databases"][$database]['charset']
);

/**
 * *4️⃣CONFIGURACION DE LAS RUTAS
 * Se guardan las rutas generales a las fuentes para facilitar la inclucion de estos ficheros
 */

$PATH_CONTROLADORES = dirname(__DIR__) . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR;
$PATH_MIDDLEWARES = dirname(__DIR__) . DIRECTORY_SEPARATOR . "middlewares" . DIRECTORY_SEPARATOR;
