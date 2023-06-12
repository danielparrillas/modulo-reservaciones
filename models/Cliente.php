<?php

include_once('../../utils/Database.php');

class Cliente
{
  public function __construct(private Database $db)
  {
  }

  /**
   * Valida si existe y si esta activa el api key
   * 
   * @param string $api_key api que pasa del cliente api
   */
  public function obtenerClientePorApiKey(string $api_key): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id AS clienteId FROM clientes_api WHERE api_key = :api_key AND activo = 1 AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':api_key', $api_key, PDO::PARAM_STR);
      $stmt->execute();
      $result["data"] = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($result["data"])) {
        $result["error"]["status"] = true;
        $result["error"]["message"] = "No se encontro ningun cliente api con esa key";
      }
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["status"] = true;
      $result["error"]["message"] = $e->getMessage();
      //throw new Exception("No se pudo verificar que existe la api key");
    }
    $conn = null;
    return $result;
  }
}
