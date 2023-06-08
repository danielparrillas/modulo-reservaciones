<?php

/**
 * *1️⃣SE IMPORTAN LA CONFIGURACIONES DEL ARCHIVO "config.json"
 * Obtencion de los datos del archivo de configuracion
 * Para guardarla en la constante CONFIGURACION
 */
$config_path = dirname(__DIR__) . '/config/config.json';
$config_file = file_get_contents($config_path);
$config_data = json_decode($config_file, true);
define("CONFIGURACION", $config_data);

/**
 * *2️⃣CONFIGURACION DE LAS BASES DE DATOS
 * Se obtienen los datos de la configuracion de conexion con db de la constante CONFIGURACION
 */
include_once('../utils/Database.php');

// 3️⃣cambia la base de datos a conveniencia
$database_name = "produccion"; //local || desarrollo || produccion
$DATABASE = new Database(
  CONFIGURACION["databases"][$database_name]['host'],
  CONFIGURACION["databases"][$database_name]['name'],
  CONFIGURACION["databases"][$database_name]['user'],
  CONFIGURACION["databases"][$database_name]['password'],
  CONFIGURACION["databases"][$database_name]['driver'],
  CONFIGURACION["databases"][$database_name]['port'],
  CONFIGURACION["databases"][$database_name]['charset']
);

/**
 * *4️⃣CONSTANTES
 * Definicicion de constantes
 */
// constantes de tipo de usuario
const TIPO_USER_ADMIN = 1;
const TIPO_USER_EXTERNO = 2;
const TIPO_USER_INT_RESTAURACION_INCENDIOS = 3;
const TIPO_USER_INT_INCENDIOS = 4;
const TIPO_USER_INT_RESTAURACION = 5;
const TIPO_USER_INT_QUEJAS = 6;
const TIPO_USER_INT_EFICIENCIA_MANEJO = 7;
const TIPO_USER_INT_ADMIN_QUEJAS = 8;
const TIPO_USER_INT_ADMIN_RESERVACIONES = 9;
const TIPO_USER_INT_ADMIN_RESERVACIONES_API = 10;
