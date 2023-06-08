<?php
include_once("../config/index.php");
include_once("../controllers/LugarController.php");
include_once("../middlewares/AuthMiddleware.php");

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
//❌ si la session no tiene el id de tipo usuario
else {
  http_response_code(401);
  exit;
}
//⏺️
$uri = explode("api/lugares", $_SERVER["REQUEST_URI"]);
$url = count($uri) > 0 ? explode("/", $uri[1]) : [""];
// echo ($_SERVER["REQUEST_URI"]);
// se instancia un objeto que pueda manejar la solicitudes del cliente
$controller = new LugarController($DATABASE);
//obtenemos los datos enviados por el cliente
$request = json_decode(file_get_contents("php://input"), true);
$result = [];

//1️⃣ /reservaciones/api/lugares
if (count($url) === 1 && $url[0] === "") {
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
  count($url) === 2 && $url[1] !== ""
) {
  $id = $url[1];
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
else if (count($url) === 3 && $url[2] === "disponibilidades") {
  $id = $url[1];
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
else if (count($url) ===  4 && $url[2] === "disponibilidades") {
  $id = $url[1];
  $grupoDisponibilidad = $url[3];
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
else if (count($url) === 3 && $url[2] === "periodosDeshabilitados") {
  $id = $url[1];
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
else if (count($url) === 4 && $url[2] === "periodosDeshabilitados") {
  $id = $url[1];
  $periodoDeshabilitadoId = $url[3];
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
  echo json_encode($url);
  http_response_code(404);
  exit;
}

//❌ si hay error en el resultado establecemos el codigo para indicarle al cliente
if (isset($result["error"])) http_response_code(404);
// mandamos la respuesta 
echo json_encode($result);
