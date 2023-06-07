<?php
include_once(dirname(__DIR__) .  '/../config/index.php');
include_once($PATH_CONTROLADORES . 'LugarController.php');
include_once($PATH_MIDDLEWARES . 'AuthMiddleware.php');
$uri = explode("/", explode("api/lugares/", $_SERVER["REQUEST_URI"])[1]);

//⏺️ se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new LugarController($DB_RESERVACIONES);
//⏺️ se instancia un objeto middleware
$auth = new AuthMiddleware();
//⏺️obtenemos los datos enviados por el cliente
$request = json_decode(file_get_contents("php://input"), true);
$result = [];

// echo json_encode($request); //👀
// exit; //👀

//❌ si la peticion no viene del mismo origen
// echo json_encode(getallheaders()["Sec-Fetch-Site"]); //👀
if (isset(getallheaders()["Sec-Fetch-Site"])) {
  if (getallheaders()["Sec-Fetch-Site"] !== "same-origin") {
    http_response_code(401);
    exit;
  }
} else {
  http_response_code(401);
  exit;
}

//⏺️ verificando autorizacion
echo json_encode($auth->obtenerDatosSesion());
// exit;

//1️⃣ /reservaciones/api/lugares
if (count($uri) === 1 && $uri[0] === "") {
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
      //⚠️ Por falta de integracion con la base de munipios y anp se agregaran valores por default
      $result = $controller->crear(array_merge($request, ["municipioId" => 0]));
      break;
    case "GET":
      $result = $controller->obtenerTodos();
      break;
    default:
      http_response_code(405);
      header("Allow: GET, POST");
      break;
  };
}
//2️⃣ /reservaciones/api/lugares/[id]
else if (
  count($uri) === 1 && $uri[0] !== ""
) {
  $id = $uri[0];
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      $result = $controller->obtenerPorId($id);
      break;
    case "PUT":
      $request["id"] = $id;
      $result = $controller->actualizar($request);
      break;
    default:
      http_response_code(405);
      header("Allow: GET, PUT");
      break;
  }
}
//3️⃣ /reservaciones/api/lugares/[id]/disponibilidades
else if (count($uri) === 2 && $uri[1] === "disponibilidades") {
  $id = $uri[0];
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      $result = $controller->obtenerDisponibilidadesPorLugar($id);
      break;
    default:
      http_response_code(405);
      header("Allow: GET");
      break;
  }
}
//4️⃣ /reservaciones/api/lugares/[id]/disponibilidades/[grupoDisponbilidadId]
else if (count($uri) ===  3 && $uri[1] === "disponibilidades") {
  $id = $uri[0];
  $grupoDisponibilidad = $uri[2];
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "PUT":
      if ($request === null) {
        http_response_code(404);
        $result = ["error" => ["message" => "Debe enviar parametros", "details" => []]];
        break;
      }
      $result = $controller->upsertDisponibilidad(array_merge($request, ["id" => $id, "grupoId" => $grupoDisponibilidad]));
      break;
    default:
      http_response_code(405);
      header("Allow: PUT");
      break;
  }
}
//5️⃣ /reservaciones/api/lugares/[id]/periodosDeshabilitados
else if (count($uri) === 2 && $uri[1] === "periodosDeshabilitados") {
  $id = $uri[0];
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
      $result = $controller->crearPeriodoDeshabilitado(array_merge($request, ["id" => $id]));
      break;
    case "GET":
      $result = $controller->obtenerPeriodosDeshabilitados($id);
      break;
    default:
      http_response_code(405);
      header("Allow: GET, POST");
      break;
  }
}
//6️⃣ /reservaciones/api/lugares/[id]/periodosDeshabilitados/[periodoDeshabilitadoId]
else if (count($uri) === 3 && $uri[1] === "periodosDeshabilitados") {
  $id = $uri[0];
  $periodoDeshabilitadoId = $uri[2];
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "DELETE":
      $result = $controller->eliminarPeriodoDeshabilitado(array_merge(["id" => $id, "periodoId" => $periodoDeshabilitadoId]));
      break;
    default:
      http_response_code(405);
      header("Allow: DELETE");
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
