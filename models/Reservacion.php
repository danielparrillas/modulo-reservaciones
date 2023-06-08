<?php

include_once('../utils/Database.php');

class Reservacion
{
  public function __construct(private Database $db)
  {
  }
  public function crear(array $data)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "INSERT INTO reservaciones
                (cliente_id, lugar_id, clave_acceso, nombres, apellidos, dui, pagada, inicio, fin)
              VALUES (:c_id, :l_id, :clave, :nom, :ape, :dui, :pagada, :inicio, :fin)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':c_id', $data['clienteId'], PDO::PARAM_INT);
      $stmt->bindParam(':l_id', $data['lugarId'], PDO::PARAM_INT);
      $stmt->bindParam(':clave', $data['claveDeAcceso'], PDO::PARAM_STR);
      $stmt->bindParam(':nom', $data['nombres'], PDO::PARAM_STR);
      $stmt->bindParam(':ape', $data['apellidos'], PDO::PARAM_STR);
      $stmt->bindParam(':dui', $data['dui'], PDO::PARAM_STR);
      $stmt->bindParam(':pagada', $data['pagada'], PDO::PARAM_BOOL);
      $stmt->bindParam(':inicio', $data['inicio'], PDO::PARAM_STR);
      $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
      $stmt->execute();

      $result["data"] = [
        "reservacionId" => (int) $conn->lastInsertId(),
      ];
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["status"] = true;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
    }
    $conn = null;
    return $result;
  }

  public function actualizar(array $data)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "UPDATE reservaciones
              SET cliente_id = :c_id, lugar_id = :l_id, clave_acceso = :clave,
                  nombres = :nom, apellidos = :ape, dui = :dui, pagada = :pagada,
                  inicio = :inicio, fin = :fin
              WHERE id = :id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
      $stmt->bindParam(':c_id', $data['clienteId'], PDO::PARAM_INT);
      $stmt->bindParam(':l_id', $data['lugarId'], PDO::PARAM_INT);
      $stmt->bindParam(':clave', $data["claveAcceso"], PDO::PARAM_STR);
      $stmt->bindParam(':nom', $data['nombres'], PDO::PARAM_STR);
      $stmt->bindParam(':ape', $data['apellidos'], PDO::PARAM_STR);
      $stmt->bindParam(':dui', $data['dui'], PDO::PARAM_STR);
      $stmt->bindParam(':pagada', $data['pagada'], PDO::PARAM_BOOL);
      $stmt->bindParam(':inicio', $data['inicio'], PDO::PARAM_STR);
      $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
      $stmt->execute();
      $result["data"]["filas"] = $stmt->rowCount();
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["status"] = true;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
    }
    $conn = null;
    return $result;
  }

  public function obtenerPorId(int $reservacion_id)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT 
                R.id AS reservacionId, C.id AS clienteId, C.nombre AS cliente, R.clave_acceso AS claveAcceso,
                L.id AS lugarId, L.nombre AS lugar, R.nombres, R.apellidos,
                R.dui, R.pagada, R.inicio, R.fin
              FROM reservaciones R
              JOIN clientes_api C ON R.cliente_id = C.id AND C.eliminado = 0
              JOIN lugares_turisticos L ON R.lugar_id = L.id AND L.eliminado = 0
              WHERE R.id = :reservacion_id AND R.eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':reservacion_id', $reservacion_id, PDO::PARAM_INT);
      $stmt->execute();
      $result["data"] = $stmt->fetch(PDO::FETCH_ASSOC);
      if (isset($result["data"]["pagada"])) $result["data"]["pagada"] = (bool) $result["data"]["pagada"];
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
