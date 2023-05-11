<?php

include_once(dirname(__DIR__) . '/utils/Database.php');
class Lugar
{
  public function __construct(private Database $db)
  {
  }
  public function obtenerPorIdSimple(int $lugarId): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id AS lugarId, nombre AS lugar, permite_acampar AS permiteAcampar, activo FROM lugares_turisticos
              WHERE eliminado = 0 AND id = :lugarId";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':lugarId', $lugarId);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $result["data"] = $stmt->fetch(PDO::FETCH_ASSOC);
        $result["data"]["activo"] = $result["data"]["activo"] === 1 ? true : false;
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
      $result["error"]["status"] = true;
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
    return $result;
  }
}
