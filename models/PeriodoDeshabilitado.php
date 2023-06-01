<?php

include_once(dirname(__DIR__) . '/utils/Database.php');
class PeriodoDeshabilitado
{
  public function __construct(private Database $db)
  {
  }
  public function obtenerPeriodosDeshabilitadosDeLugar($lugar_id): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id, inicio, fin FROM periodos_deshabilitados
              WHERE lugar_id = :lugar_id AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(":lugar_id", $lugar_id, PDO::PARAM_INT);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = $row;
      }
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "obtenerPeriodosDeshabilitados"];
    }
    $conn = null;
    return $result;
  }
  public function crearPeriodoDeshabilitadoParaUnLugar($data): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "INSERT INTO periodos_deshabilitados (lugar_id, inicio, fin)
              VALUES (:li, :i, :f)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(":li", $data["id"], PDO::PARAM_INT);
      $stmt->bindParam(":i", $data["inicio"], PDO::PARAM_STR);
      $stmt->bindParam(":f", $data["fin"], PDO::PARAM_STR);
      $stmt->execute();
      $result["data"] = [
        "id" => (int) $conn->lastInsertId(),
      ];
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "actualizarDisponibilidad"];
    }
    $conn = null;
    return $result;
  }
  // eliminado logico
  public function eliminarPeriodoDeshabilitadoDeUnLugar($data): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "UPDATE periodos_deshabilitados
              SET eliminado = 1
              WHERE lugar_id = :li AND id = :pId";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(":li", $data["id"], PDO::PARAM_INT);
      $stmt->bindParam(":pId", $data["periodoId"], PDO::PARAM_INT);
      $stmt->execute();
      $result["filasAfectadas"] = $stmt->rowCount();
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "actualizarDisponibilidad"];
    }
    $conn = null;
    return $result;
  }
}
