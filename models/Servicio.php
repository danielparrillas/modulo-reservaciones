<?php
include_once(dirname(__DIR__) . '/utils/Database.php');
class Servicio
{
  public function __construct(private Database $db)
  {
  }

  public function obtenerPorId($servicio_id)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id,nombre, grupo_disponibilidad_id AS disponibilidadId,
                      precio, eliminado, descripcion
              FROM servicios WHERE id = :id AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id', $servicio_id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result["eliminado"] = (bool)$result["eliminado"];
        $result["precio"] = (float) $result["precio"]; // convertimos a float
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

  public function obtenerTodos(): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id,nombre, grupo_disponibilidad_id AS disponibilidadId,
                      precio, eliminado, descripcion
              FROM servicios";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['precio'] = (float) $row['precio'];
        $row["eliminado"] = (bool)$row["eliminado"];
        $result[] = $row;
      }
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "obtenerTodos"];
    }
    $conn = null;
    return $result;
  }
}
