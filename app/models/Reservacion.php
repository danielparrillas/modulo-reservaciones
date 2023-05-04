<?php

include_once(dirname(__DIR__) . '/utils/Database.php');

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
              VALUES (:c_id, :l_id, :clave, :nom, :ape, :dui, :pagado, :inicio, :fin)
              ";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':c_id', $data['clienteId'], PDO::PARAM_INT);
      $stmt->bindParam(':l_id', $data['lugar'], PDO::PARAM_INT);
      $stmt->bindParam(':clave', $data['claveDeAcceso'], PDO::PARAM_STR);
      $stmt->bindParam(':nom', $data['nombres'], PDO::PARAM_STR);
      $stmt->bindParam(':ape', $data['apellidos'], PDO::PARAM_STR);
      $stmt->bindParam(':dui', $data['dui'], PDO::PARAM_STR);
      $stmt->bindParam(':pagada', $data['pagada'], PDO::PARAM_BOOL);
      $stmt->bindParam(':inicio', $data['inicio'], PDO::PARAM_STR);
      $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
      $stmt->execute();

      $result["data"] = [
        "id" => $conn->lastInsertId(),
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
              SET cliente_id = :c_id,
                  lugar_id = :l_id,
                  clave_acceso = :clave,
                  nombres = :nom,
                  apellidos = :ape,
                  dui = :dui,
                  pagada = :pagado,
                  inicio = :inicio,
                  fin = :fin
              WHERE id = :id
              ";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
      $stmt->bindParam(':c_id', $data['clienteId'], PDO::PARAM_INT);
      $stmt->bindParam(':l_id', $data['lugarId'], PDO::PARAM_INT);
      $stmt->bindParam(':clave', $claveDeAcceso, PDO::PARAM_STR);
      $stmt->bindParam(':nom', $data['nombres'], PDO::PARAM_STR);
      $stmt->bindParam(':ape', $data['apellidos'], PDO::PARAM_STR);
      $stmt->bindParam(':dui', $data['dui'], PDO::PARAM_STR);
      $stmt->bindParam(':pagada', $data['pagada'], PDO::PARAM_BOOL);
      $stmt->bindParam(':inicio', $data['inicio'], PDO::PARAM_STR);
      $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
      $stmt->execute();
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
              R.id AS reservacionId,
              C.id AS clienteId,
              C.nombre AS cliente,
              L.id AS lugarId,
              L.nombre AS lugar,
              R.nombres AS nombres,
              R.apellidos AS apellidos,
              R.dui AS dui,
              R.pagada AS pagada,
              R.inicio AS inicio,
              R.fin AS fin
              FROM (
                SELECT id, cliente_id, lugar_id, nombres, apellidos, dui, pagada, inicio, fin
                FROM reservaciones
                WHERE id = :reservacion_id AND eliminado = 0
              ) R
              INNER JOIN (
                SELECT id, nombre FROM clientes_api
                WHERE eliminado = 0
              ) C ON R.cliente_id = C.id
              INNER JOIN (
                SELECT id, nombre FROM lugares_turisticos
                WHERE eliminado = 0
              ) L ON R.lugar_id = L.id
      ";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':reservacion_id', $reservacion_id, PDO::PARAM_INT);
      $stmt->execute();
      $result["data"] = $stmt->fetch(PDO::FETCH_ASSOC);
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
