<?php
include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'index.php');
include_once($PATH_CONTROLADORES . 'LugarController.php');

$uri = explode("/", explode("reservaciones/app/api/lugares/", $_SERVER["REQUEST_URI"])[1]);

// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new LugarController($DB_RESERVACIONES);

if (count($uri) === 1 && $uri[0] === "") { // api/lugares

  // obtenemos los datos enviados por el cliente
  $request = json_decode(file_get_contents("php://input"), true);

  switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
      //⚠️ Por falta de integracion con la base de munipios y anp se agregaran valores por default
      $result = $controller->crear(array_merge($request, ["anpId" => 0, "municipioId" => 0]));
      //❌ si hay error establecemos el codigo
      if (isset($result["error"])) http_response_code(404);
      echo json_encode($result);
      break;
    case "GET":
      $get_lugares = $controller->obtenerTodos();
      if (isset($get_lugares["error"])) http_response_code(404);
      echo json_encode($get_lugares);
      break;
    default:
      http_response_code(405);
      header("Allow: GET, POST");
      break;
  };
} else if ( // api/lugares/[id]
  count($uri) === 1 && $uri[0] !== ""
) {
  $id = $uri[0];
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      $result = $controller->obtenerPorId($id);
      if (isset($result["error"])) http_response_code(404);
      echo json_encode($result);
      break;
    default:
      http_response_code(405);
      header("Allow: GET");
      break;
  }
} else if (count($uri) === 2 && $uri[1] === "disponibilidades") {
  $id = $uri[0];
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      $result = $controller->obtenerDisponibilidadesPorLugar($id);
      if (isset($result["error"])) http_response_code(404);
      echo json_encode($result);
      break;
    default:
      http_response_code(405);
      header("Allow: GET");
      break;
  }
} else {
  http_response_code(404);
  exit;
}
