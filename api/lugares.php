<?php

include_once(dirname(__DIR__) . '/src/config/index.php');
include_once($PATH_CONTROLADORES . 'LugarController.php');
include_once($PATH_MIDDLEWARES . 'ApiMiddleware.php');

// se instancia un middleware, este utilizara el servico del cliente
// instanciado direcatemente en el middleware
// el objeto ClienteServicio recibe como parametro la instancia tipo Database
$middleware_api = new ApiMiddleware(new ClienteService($DB_RESERVACIONES));
// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new LugarController(new LugarService($DB_RESERVACIONES));

if ($middleware_api->validarApiKey()) {

  if (isset($_GET["id"])) {
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "GET":
        $controller->obtenerServicios(1);
        break;
      default:
        http_response_code(405);
        header("Allow: GET, POST");
        break;
    }
  } else {
    switch ($_SERVER["REQUEST_METHOD"]) {
      case "GET":

        $controller->obtenerTodos();
        break;
      default:
        http_response_code(405);
        header("Allow: GET");
        break;
    }
  }
}
