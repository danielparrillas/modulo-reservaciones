<?php
include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'index.php');
include_once($PATH_CONTROLADORES . 'LugarController.php');
include_once($PATH_MIDDLEWARES . 'ApiMiddleware.php');

//Extraemos el uri solicitado por el cliente y lo particionamos desde el subdirectorio "reservaciones"
// uri[0] = "", uri[1] = "api", uri[2] = "lugares"
$uri = explode("/", explode("reservaciones/app/api/lugares/", $_SERVER["REQUEST_URI"])[1]);

//echo json_encode($uri);
// se instancia un middleware, este utilizara el servico del cliente
// instanciado direcatemente en el middleware
// el objeto ClienteServicio recibe como parametro la instancia tipo Database
$middleware_api = new ApiMiddleware(new Cliente($DB_RESERVACIONES));
// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new LugarController(new Lugar($DB_RESERVACIONES));

if ( // api/lugares
  count($uri) === 0 ||
  (count($uri) === 1 && $uri[0] === "")
) {
  // if ($middleware_api->validarApiKey()) {
  if (true) {
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "GET":
        $controller->obtenerTodos();
        break;
      default:
        http_response_code(405);
        header("Allow: GET");
        break;
    };
  }
} else if ( // api/lugares/[id]
  count($uri) === 1 && $uri[0] !== ""
) {
  // if ($middleware_api->validarApiKey()) {
  if (true) {
    $id = $uri[0];
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "GET":
        $controller->obtenerServicios($id);
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
