<?php
include_once(dirname(__DIR__) . '/utils/Database.php');
class Servicio
{
  public function __construct(private Database $db)
  {
  }

  public function obtenerPorId($servicio_id)
  {
    $result = [
      "data" => [],
      "error" => [
        "status" => false,
        "message" => "No hay error",
        "details" => []
      ]
    ];

    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id AS servicioId ,precio, nombre AS servicio FROM servicios WHERE id = :id AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id', $servicio_id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $result["data"] = $stmt->fetch(PDO::FETCH_ASSOC);
        $result["data"]["precio"] = (float) $result["data"]["precio"]; // convertimos a float
      } else {
        throw new Exception("No se encuentra ningun servicio con el id " . $servicio_id);
      }
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["status"] = true;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
    }
    $conn = null;
    return $result;
  }
}
