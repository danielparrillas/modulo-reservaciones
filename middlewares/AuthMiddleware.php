<?php

class AuthMiddleware
{
  public function obtenerDatosSesion()
  {
    $result = [];
    if (isset($_COOKIE['PHPSESSID'])) {
      //obtenemos la session
      $session_id = $_COOKIE['PHPSESSID'];
      // Establece el ID de sesión
      session_id($session_id);
      // Inicia la sesión
      session_start();
      // Ahora puedes acceder a los datos de la sesión
      $result = $_SESSION;
    }
    return $result;
  }
}
