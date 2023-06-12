<?php
include_once("../../config/index.php");
require_once "../controllers/ServicioController.php";
include_once("../../middlewares/AuthMiddleware.php");

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

//⏺️
$uri = explode("api/servicios", $_SERVER["REQUEST_URI"]);
$url = count($uri) > 1 ? explode("/", $uri[1]) : [""];
// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new ServicioController($DATABASE);
// obtenemos los datos enviados por el cliente
$request = json_decode(file_get_contents("php://input"), true);
$result = [];

//1️⃣ /reservaciones/servicios
if (count($url) === 1 && $url[0] === "") {
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "PUT":
      $result = $controller->crear($request);
      break;
    case "GET":
      $result = $controller->obtenerTodos();
      break;
    default:
      http_response_code(405);
      header("Allow: GET, PUT");
      break;
  };
}
//2️⃣ /reservaciones/servicio/[id]
else if (
  count($url) === 2 && $url[1] !== ""
) {
  $id = $url[1];
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      $result = $controller->obtenerPorId($id);
      break;
    case "PATCH":
      $request["id"] = $id;
      $result = $controller->actualizar($request);
      break;
    default:
      http_response_code(405);
      header("Allow: GET, PUT");
      break;
  }
}
//❌ si la uri no se encuentra
else {
  http_response_code(404);
  exit;
}

//❌ si hay error en el resultado establecemos el codigo para indicarle al cliente
if (isset($result["error"])) http_response_code(404);
// mandamos la respuesta
echo json_encode($result);
