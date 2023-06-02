<?php

include_once(dirname(__DIR__) . '/utils/Database.php');
class Lugar
{
  public function __construct(private Database $db)
  {
  }
  public function crear(array $data)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "INSERT INTO lugares_turisticos
                (anp_id, municipio_id, nombre, permite_acampar, activo, descripcion)
              VALUES (:anp, :mun, :nom, :pa, :activo, :descripcion)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':anp', $data['anpId'], PDO::PARAM_INT);
      $stmt->bindParam(':mun', $data['municipioId'], PDO::PARAM_INT);
      $stmt->bindParam(':nom', $data['nombre'], PDO::PARAM_STR);
      $stmt->bindParam(':descripcion', $data['descripcion'], PDO::PARAM_STR);
      $stmt->bindParam(':pa', $data['permiteAcampar'], PDO::PARAM_BOOL);
      $stmt->bindParam(':activo', $data['activo'], PDO::PARAM_BOOL);
      $stmt->execute();

      $result["id"] = (int) $conn->lastInsertId();
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "crear"];
    }
    $conn = null;
    return $result;
  }
  public function actualizar(array $data)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "UPDATE lugares_turisticos
              SET anp_id = :anp, municipio_id = :mun, nombre = :nom, permite_acampar = :pa, activo = :activo , descripcion = :descripcion
              WHERE id = :id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
      $stmt->bindParam(':anp', $data['anpId'], PDO::PARAM_INT);
      $stmt->bindParam(':mun', $data['municipioId'], PDO::PARAM_INT);
      $stmt->bindParam(':nom', $data['nombre'], PDO::PARAM_STR);
      $stmt->bindParam(':descripcion', $data['descripcion'], PDO::PARAM_STR);
      $stmt->bindParam(':pa', $data['permiteAcampar'], PDO::PARAM_BOOL);
      $stmt->bindParam(':activo', $data['activo'], PDO::PARAM_BOOL);
      $stmt->execute();

      $result["filasAfectadas"] = $stmt->rowCount();
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "actualizar"];
    }
    $conn = null;
    return $result;
  }
  public function obtenerPorId(int $lugarId): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id,municipio_id AS municipioId ,anp_id AS anpId , nombre,permite_acampar AS permiteAcampar, activo, descripcion FROM lugares_turisticos
              WHERE id = :lugarId AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':lugarId', $lugarId, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $result["data"] = $stmt->fetch(PDO::FETCH_ASSOC);
        $result["data"]["activo"] = $result["data"]["activo"] === 1 ? true : false;
        $result["data"]["permiteAcampar"] = $result["data"]["permiteAcampar"] === 1 ? true : false;
      } else {
        $result["error"]["status"] = true;
        $result["error"]["message"] = "No se encontro ningun lugar con el id " . $lugarId;
      }
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "obtenerPorId"];
    }
    $conn = null;
    return $result;
  }
  public function obtenerTodos(): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id AS lugarId, nombre, permite_acampar AS permiteAcampar, activo, descripcion
              FROM lugares_turisticos WHERE eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['permiteAcampar'] = (bool) $row['permiteAcampar'];
        $row["activo"] = (bool)$row["activo"];
        $result["data"][] = $row;
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
  public function obtenerTodosSimple(): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id, nombre, permite_acampar AS permiteAcampar, descripcion
              FROM lugares_turisticos WHERE activo = 1 AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['permiteAcampar'] = (bool) $row['permiteAcampar'];
        $result["data"][] = $row;
      }
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
      $result["error"]["details"][] = ["model" => "obtenerTodosSimple"];
    }
    $conn = null;
    return $result;
  }
}
