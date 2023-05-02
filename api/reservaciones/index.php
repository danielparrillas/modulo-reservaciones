<?php

include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'index.php');
include_once($PATH_CONTROLADORES . 'ReservacionController.php');
include_once($PATH_MIDDLEWARES . 'ApiMiddleware.php');

//Extraemos el uri solicitado por el cliente y lo particionamos desde el subdirectorio "reservaciones"
// uri[0] = "", uri[1] = "api", uri[2] = "lugares"
$uri = explode("/", explode("reservaciones/api/reservaciones/", $_SERVER["REQUEST_URI"])[1]);

//echo json_encode($uri);
// se instancia un middleware, este utilizara el servico del cliente
// instanciado direcatemente en el middleware
// el objeto ClienteServicio recibe como parametro la instancia tipo Database
$middleware_api = new ApiMiddleware(new Cliente($DB_RESERVACIONES));
// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new ReservacionController(new Reservacion($DB_RESERVACIONES));

if (count($uri) === 1 && $uri[0] === "") { // api/reservaciones
  if ($middleware_api->validarApiKey()) {
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "POST":
        echo json_encode($uri);
        break;
      default:
        http_response_code(405);
        header("Allow: POST");
        break;
    };
  }
} else if (count($uri) === 1 && $uri[0] !== "") { // api/reservaciones/[id]
  if ($middleware_api->validarApiKey()) {
    $id = $uri[0];
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "PUT":
        echo json_encode($uri);
        break;
      default:
        echo json_encode($uri);
        http_response_code(405);
        header("Allow: PUT");
        break;
    }
  }
} else {
  http_response_code(404);
  exit;
}
