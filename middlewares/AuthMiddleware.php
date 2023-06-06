<?php

class AuthMiddleware
{
  public function obtenerDatosSesion()
  {
    //obtenemos la session
    $session_id = $_COOKIE['PHPSESSID'];
    // Establece el ID de sesión
    session_id($session_id);
    // Inicia la sesión
    session_start();
    // Ahora puedes acceder a los datos de la sesión
    $datos_sesion = $_SESSION;
    // Hacer algo con los datos de la sesión
    return $datos_sesion;
  }
}
