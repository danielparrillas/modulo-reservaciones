<?php

include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'index.php');
include_once($PATH_CONTROLADORES . 'ReservacionController.php');
include_once($PATH_MIDDLEWARES . 'ApiMiddleware.php');

//Extraemos el uri solicitado por el cliente y lo particionamos desde el subdirectorio "reservaciones"
// uri[0] = "", uri[1] = "api", uri[2] = "lugares"
$uri = explode("/", explode("reservaciones/api/reservaciones/", $_SERVER["REQUEST_URI"])[1]);

$middleware_api = new ApiMiddleware($DB_RESERVACIONES);
$controller_reservacion = new ReservacionController($DB_RESERVACIONES);

if (count($uri) === 1 && $uri[0] === "") { // api/reservaciones/
  $result_api = $middleware_api->validarApiKey();
  if ($result_api["error"]["status"] === false) {
    // obtenemos los datos enviados por el cliente
    $request = json_decode(file_get_contents("php://input"), true);
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "POST":
        $controller_reservacion->setClienteId($result_api["data"]["clienteId"]);
        $result = $controller_reservacion->crear($request);
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
  if ($middleware_api->validarApiKey()) {
    $id = $uri[0];
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "GET":
        $controller_reservacion->obtenerPorId($id);
        break;
      case "PUT":
        echo json_encode($uri);
        break;
      default:
        http_response_code(405);
        header("Allow: PUT");
        break;
    }
  }
} else {
  http_response_code(404);
  exit;
}
