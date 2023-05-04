<?php

include_once(dirname(__DIR__) . '/models/Cliente.php');
class ApiMiddleware
{
  private $model_cliente;
  public function __construct(Database $db)
  {
    $this->model_cliente = new Cliente($db);
  }

  /**
   * - Obtiene la api_key pasada en el header por el cliente y la validara
   * - Lanzara un error si la api no es valida o si no se ha mandado
   */
  public function validarApiKey(): array
  {
    $api_key = $this->obtenerApiKeyDelHeaderCliente();
    if ($api_key !== null) {
      $result = $this->model_cliente->obtenerClientePorApiKey($api_key);
    } else {
      $result["error"]["status"] = true;
      $result["error"]["message"] = "No esta autorizado";
    }
    return $result;
  }

  /**
   * Utiliza getallheaders() para obtener el header Authorization y asi obtener el api key mandado desde el cliente
   */
  private function obtenerApiKeyDelHeaderCliente(): null | string
  {
    if (isset(getallheaders()['Authorization'])) {

      $authorization = (string) getallheaders()['Authorization'] ?? ''; // obtener el valor del encabezado Authorization
      $api_key  = "";
      // $se retorna la key si el esquema no coincide con "Bearer"
      if (preg_match('/Bearer\s(\S+)/', $authorization, $matches)) {
        // Si el valor del encabezado Authorization coincide con el esquema "Bearer", extraer el token
        $api_key = (string) $matches[1];
        return $api_key;
      }
    }
    return null;
  }
}
