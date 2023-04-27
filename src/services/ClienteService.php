<?php

include_once(dirname(__DIR__) . '/models/Cliente.php');
include_once(dirname(__DIR__) . '/utils/Database.php');

class ClienteService
{
  public function __construct(private Database $db)
  {
  }

  /**
   * Valida si existe y si esta activa el api key
   * 
   * @param string $api_key api que pasa del cliente api
   * @return bool
   */
  public function validarApiKey(string $api_key): bool
  {
    $result = false;
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id FROM clientes_api WHERE api_key = :api_key AND activo = 1 AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':api_key', $api_key, PDO::PARAM_STR);
      $stmt->execute();

      if ($stmt->rowCount() > 0) $result = true;
      $conn = null;
    } catch (Exception $e) {
      $conn = null;
      throw $e;
      //throw new Exception("No se pudo verificar que existe la api key");
    }
    return $result;
  }
}
