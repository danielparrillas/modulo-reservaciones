<?php

include_once(dirname(__DIR__) . '/services/ClienteService.php');
class ApiMiddleware
{
  public function __construct(private ClienteService $service)
  {
  }

  /**
   * - Obtiene la api_key pasada en el header por el cliente y la validara
   * - Lanzara un error si la api no es valida o si no se ha mandado
   * 
   * @return bool false si no es valido y true si lo es
   */
  public function validarApiKey(): bool
  {
    $api_key = $this->obtenerApiKeyDelHeaderCliente();
    if ($api_key !== null) {

      if (!$this->service->validarApiKey($api_key)) {
        http_response_code(401);
        echo json_encode([
          "error" => [
            "message" => "La api key no es valida",
          ]
        ]);
        return false;
      }
    } else {
      http_response_code(401);
      echo json_encode([
        "error" => [
          "message" => "No esta autorizado",
        ]
      ]);
      return false;
    }
    return true;
  }

  /**
   * Utiliza getallheaders() para obtener el header Authorization y asi obtener el api key mandado desde el cliente
   * @return string api key enviada en el header Authorization
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
