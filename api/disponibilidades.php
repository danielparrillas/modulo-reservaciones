<?php

declare(strict_types=1);

include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'index.php');
// Hacemos que la cabecera de respuesta indique que eniamos un J|sON
header("Content-type: application/; charset=UTF-8");

var_dump($db_reservaciones);
