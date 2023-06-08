<?php

include_once('../utils/Database.php');
class Disponibilidad
{
  public function __construct(private Database $db)
  {
  }
  public function actualizarDisponibilidadDeUnLugar(array $data): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "UPDATE disponibilidades_lugares_gruposservicios
              SET cantidad_maxima = :cm
              WHERE lugar_id = :li AND grupo_id = :gi";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':li', $data['id'], PDO::PARAM_INT);
      $stmt->bindParam(':gi', $data['grupoId'], PDO::PARAM_INT);
      $stmt->bindParam(':cm', $data['cantidadMaxima'], PDO::PARAM_STR);
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
  public function crearDisponibilidadParaUnLugar(array $data): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "INSERT INTO disponibilidades_lugares_gruposservicios
                (lugar_id, grupo_id, cantidad_maxima)
              VALUES (:li, :gi, :cm)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':li', $data['id'], PDO::PARAM_INT);
      $stmt->bindParam(':gi', $data['grupoId'], PDO::PARAM_INT);
      $stmt->bindParam(':cm', $data['cantidadMaxima'], PDO::PARAM_STR);
      $stmt->execute();

      $result["data"] = [
        "id" => (int) $conn->lastInsertId(),
      ];
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "crearDisponibilidad"];
    }
    $conn = null;
    return $result;
  }
  public function obtenerDisponibilidadDeUnLugar(array $data): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT lugar_id AS lugarId, grupo_id AS grupo_id, cantidad_maxima AS cantidadMaxima
              FROM disponibilidades_lugares_gruposservicios
              WHERE lugar_id = :li AND grupo_id = :gi";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':li', $data['id'], PDO::PARAM_INT);
      $stmt->bindParam(':gi', $data['grupoId'], PDO::PARAM_INT);
      $stmt->execute();
      $result["data"] = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "obtenerDisponibilidad"];
    }
    $conn = null;
    return $result;
  }
  public function obtenerDisponibilidadesDeUnLugar($lugar_id): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id, lugar_id AS lugarId, grupo_id AS grupoId, cantidad_maxima AS cantidadMaxima
              FROM disponibilidades_lugares_gruposservicios WHERE lugar_id = :lugar_id AND eliminado = 0";
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
      $result["error"]["details"][] = ["model" => "obtenerDisponibilidades"];
    }
    $conn = null;
    return $result;
  }
}
