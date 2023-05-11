<?php
include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'index.php');
include_once($PATH_CONTROLADORES . 'LugarController.php');
include_once($PATH_MIDDLEWARES . 'ApiMiddleware.php');

//Extraemos el uri solicitado por el cliente y lo particionamos desde el subdirectorio "reservaciones"
// uri[0] = "", uri[1] = "api", uri[2] = "lugares"
$uri = explode("/", explode("reservaciones/app/api/lugares/", $_SERVER["REQUEST_URI"])[1]);

// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new LugarController($DB_RESERVACIONES);

if (count($uri) === 1 && $uri[0] === "") { // api/lugares
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      $get_lugares = $controller->obtenerTodos();
      if (isset($get_lugares["error"])) http_response_code(404);
      echo json_encode($get_lugares);
      break;
    default:
      http_response_code(405);
      header("Allow: GET");
      break;
  };
} else if ( // api/lugares/[id]
  count($uri) === 1 && $uri[0] !== ""
) {
  if (true) {
    $id = $uri[0];
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "GET":
        echo "id";
        break;
      default:
        http_response_code(405);
        header("Allow: GET, POST");
        break;
    }
  }
} else {
  http_response_code(404);
  exit;
}
