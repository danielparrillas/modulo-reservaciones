<?php
include_once(dirname(__DIR__) .  '/../config/index.php');
include_once($PATH_CONTROLADORES . 'DisponibilidadController.php');

$uri = explode("/", explode("reservaciones/app/services/disponibilidades/", $_SERVER["REQUEST_URI"])[1]);

// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new DisponbilidadController($DB_RESERVACIONES);

if (count($uri) === 1 && $uri[0] === "") { // services/disponibilidades
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      $result = $controller->obtenerTodos();
      if (isset($result["error"])) http_response_code(404);
      echo json_encode($result);
      break;
    default:
      http_response_code(405);
      header("Allow: GET, POST");
      break;
  };
} else {
  http_response_code(404);
  exit;
}
