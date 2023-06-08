<?php

include_once("../config/index.php");
include_once("../controllers/ReservacionController.php");
include_once("../middlewares/ApiMiddleware.php");

//Extraemos el uri solicitado por el cliente y lo particionamos desde el subdirectorio "reservaciones"
// uri[0] = "", uri[1] = "api", uri[2] = "lugares"
$uri = explode("/", explode("reservaciones/api/reservaciones", $_SERVER["REQUEST_URI"])[1]);

$middleware_api = new ApiMiddleware($DATABASE);
$controller_reservacion = new ReservacionController($DATABASE);

if (count($uri) === 1 && $uri[0] === "") { // api/reservaciones/
  $result_api = $middleware_api->validarApiKey();
  if (!isset($result_api["error"])) {
    // obtenemos los datos enviados por el cliente
    $request = json_decode(file_get_contents("php://input"), true);
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "POST":
        $controller_reservacion->setClienteId($result_api["data"]["clienteId"]);
        $result = $controller_reservacion->crearConDetalles($request);
        if (!isset($result["error"])) {
          echo json_encode($result);
        } else {
          http_response_code(400);
          echo json_encode($result);
        }
        break;
      default:
        http_response_code(405);
        header("Allow: POST");
        break;
    };
  } else {
    http_response_code(404);
    echo json_encode($result_api);
  }
} else if (count($uri) === 1 && $uri[0] !== "") { // api/reservaciones/[id]
  $id = $uri[0];
  $result_api = $middleware_api->validarApiKey();
  if (!isset($result_api["error"])) {
    // obtenemos los datos enviados por el cliente
    $request = json_decode(file_get_contents("php://input"), true);
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "GET":
        $result = $controller_reservacion->obtenerPorId($id);
        if (!isset($result["error"])) {
          unset($result["data"]["claveAcceso"]);
          unset($result["data"]["cliente"]);
          if ($result["data"]["clienteId"] === $result_api["data"]["clienteId"]) {
            echo json_encode($result);
          } else {
            http_response_code(401);
          }
        } else {
          http_response_code(404);
          echo json_encode($result);
        }
        break;
      case "PUT":
        $controller_reservacion->setClienteId($result_api["data"]["clienteId"]);
        $request["reservacionId"] = $id;
        $result = $controller_reservacion->actualizarConDetalles($request);

        if (isset($result["error"])) http_response_code(400);
        echo json_encode($result);
        break;
      default:
        http_response_code(405);
        header("Allow: GET,PUT");
        break;
    };
  } else {
    http_response_code(404);
    echo json_encode($result_api);
  }
} else {
  http_response_code(404);
  exit;
}
