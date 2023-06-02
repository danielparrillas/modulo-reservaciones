<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/reservaciones/config/index.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/reservaciones/controllers/ServicioController.php";

$uri = explode("/", explode("/api/servicios/", $_SERVER["REQUEST_URI"])[1]);

// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new ServicioController($DB_RESERVACIONES);
// obtenemos los datos enviados por el cliente
$request = json_decode(file_get_contents("php://input"), true);
$result = [];
// echo json_encode($request); //👀
// exit; //👀

//❌ si la peticion no viene del mismo origen
// echo json_encode(getallheaders()["Sec-Fetch-Site"]); //👀
// if (isset(getallheaders()["Sec-Fetch-Site"])) {
//   if (getallheaders()["Sec-Fetch-Site"] !== "same-origin") {
//     http_response_code(401);
//     exit;  
//   }
// } else {
//   http_response_code(401);
//   exit;
// }
//1️⃣ /reservaciones/servicios
if (count($uri) === 1 && $uri[0] === "") {
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      $result = $controller->obtenerTodos();
      break;
    default:
      http_response_code(405);
      header("Allow: GET, POST");
      break;
  };
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
