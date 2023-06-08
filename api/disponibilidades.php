<?php

include_once("../config/index.php");
include_once("../controllers/DisponibilidadController.php");
include_once("../middlewares/AuthMiddleware.php");

//⏺️ se instancia un objeto middleware
$auth = new AuthMiddleware();
//🟨 solo se permite peticiones del mismo dominio
if (isset(getallheaders()["Sec-Fetch-Site"])) {
  //❌ si la peticion no viene del mismo origen
  if (getallheaders()["Sec-Fetch-Site"] !== "same-origin") {
    http_response_code(401);
    exit;
  }
} else {
  http_response_code(401);
  exit;
}

//🔒 verificando autorizacion
$datos_auth = $auth->obtenerDatosSesion();
if (isset($datos_auth["idtipousuario"])) {
  $tipo_user = $datos_auth["idtipousuario"];
  //❌ si el usuario no es del tipo permitido
  if ($tipo_user !== TIPO_USER_ADMIN && $tipo_user !== TIPO_USER_INT_ADMIN_RESERVACIONES) {
    http_response_code(401);
    exit;
  }
}

$uri = explode("/", explode("api/disponibilidades", $_SERVER["REQUEST_URI"])[1]);
// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new DisponbilidadController($DATABASE);

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
