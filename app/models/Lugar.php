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
                (anp_id, municipio_id, nombre, permite_acampar, activo)
              VALUES (:anp, :mun, :nom, :pa, :activo)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':anp', $data['anpId'], PDO::PARAM_INT);
      $stmt->bindParam(':mun', $data['municipioId'], PDO::PARAM_INT);
      $stmt->bindParam(':nom', $data['nombre'], PDO::PARAM_STR);
      $stmt->bindParam(':pa', $data['permiteAcampar'], PDO::PARAM_BOOL);
      $stmt->bindParam(':activo', $data['activo'], PDO::PARAM_BOOL);
      $stmt->execute();

      $result["data"] = [
        "id" => (int) $conn->lastInsertId(),
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
  public function obtenerPorIdSimple(int $lugarId): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id, nombre, permite_acampar AS permiteAcampar, activo FROM lugares_turisticos
              WHERE eliminado = 0 AND id = :lugarId";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':lugarId', $lugarId);
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
      $sql = "SELECT id AS lugarId, nombre, permite_acampar AS permiteAcampar, activo
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
    }
    $conn = null;
    return $result;
  }

  public function obtenerDisponibilidades($lugar_id): array
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
    }
    $conn = null;
    return $result;
  }
  public function obtenerTodosSimple(): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id, nombre, permite_acampar AS permiteAcampar
              FROM lugares_turisticos WHERE activo = 1 AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['permiteAcampar'] = (bool) $row['permiteAcampar'];
        $result["data"][] = $row;
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

  public function obtenerDetalle($lugar_id): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT 
                  L.id AS lugarId,
                  L.nombre AS lugar,
                  L.permite_acampar AS permiteAcampar,
                  D.id AS disponibilidadId,
                  D.cantidad_maxima AS cantidadMaximaDiariaPorGrupo,
                  G.id AS grupoId,
                  G.nombre AS grupo,
                  S.id AS servicioId,
                  S.nombre AS servicio,
                  S.precio AS precio,
                  S.descripcion AS descripcion
              FROM (
                SELECT id, nombre, permite_acampar FROM lugares_turisticos
                WHERE id = :lugar_id AND eliminado = 0 AND activo = 1
              ) L
              INNER JOIN (
                SELECT id, lugar_id, grupo_id, cantidad_maxima
                FROM disponibilidades_lugares_gruposservicios
                WHERE lugar_id = :lugar_id AND eliminado = 0
              ) D ON L.id = D.lugar_id
              INNER JOIN (
                SELECT id, nombre FROM grupos_disponibilidades WHERE eliminado = 0
              ) G ON G.id = D.grupo_id
              INNER JOIN (
                SELECT id, nombre, precio, grupo_disponibilidad_id, descripcion
                FROM servicios WHERE eliminado = 0
              ) S ON G.id = S.grupo_disponibilidad_id
              ";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(":lugar_id", $lugar_id);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['permiteAcampar'] = (bool) $row['permiteAcampar'];
        $row['precio'] = (float) $row['precio'];
        $result["data"][] = $row;
      }
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["status"] = true;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
    }
    $conn = null;
    echo json_encode($result);
    exit;
    return $result;
  }
}
