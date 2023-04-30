<?php

/**
 * *CONFIGURACION DE ERRORES
 * Se configura el manejo de mensaje de errores
 * Se formatean de modo que muestren en formato JSON
 * Es muy util ya que esto permitira que los errores se envien al cliente api en formato JSON
 */
include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'ManejadorDeErrores.php');

//Definimos el manejador de errores
set_error_handler("ManejadorDeErrores::handleError");
// Definimos que el controlador de error sera la clase ErrorHandler
set_exception_handler("ManejadorDeErrores::handleException");

/**
 * *SE IMPORTAN LA CONFIGURACIONES DEL ARCHIVO "config.json"
 * Obtencion de los datos del archivo de configuracion
 * Para guardarla en la constante CONFIGURACION
 */
$config_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.json';
$config_file = file_get_contents($config_path);
$config_data = json_decode($config_file, true);
define("CONFIGURACION", $config_data);

/**
 * *CONFIGURACION DE LAS BASES DE DATOS
 * Se obtienen los datos de la configuracion de conexion con db de la constante CONFIGURACION
 */
include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'Database.php');

$DB_RESERVACIONES = new Database(
  CONFIGURACION["databases"]["reservaciones"]['host'],
  CONFIGURACION["databases"]["reservaciones"]['name'],
  CONFIGURACION["databases"]["reservaciones"]['user'],
  CONFIGURACION["databases"]["reservaciones"]['password'],
  CONFIGURACION["databases"]["reservaciones"]['driver'],
  CONFIGURACION["databases"]["reservaciones"]['port'],
  CONFIGURACION["databases"]["reservaciones"]['charset']
);

/**
 * *CONFIGURACION DE LAS RUTAS
 * Se guardan las rutas generales a las fuentes
 */

$PATH_MODELOS = dirname(__DIR__) . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR;
$PATH_CONTROLADORES = dirname(__DIR__) . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR;
$PATH_MIDDLEWARES = dirname(__DIR__) . DIRECTORY_SEPARATOR . "middlewares" . DIRECTORY_SEPARATOR;
