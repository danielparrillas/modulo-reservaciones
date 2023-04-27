<?php

include_once(dirname(__DIR__) . '/models/Lugar.php');
include_once(dirname(__DIR__) . '/utils/Database.php');
class LugarService
{
  public function __construct(private Database $db)
  {
  }

  public function obtenerTodosSimple(): array
  {
    $result = [
      "error" => [
        "status" => false,
        "message" => "No hay error",
        "details" => []
      ],
      "data" => []
    ];

    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id, nombre, permite_acampar FROM lugares_turisticos WHERE activo = 1 AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['permite_acampar'] = (bool) $row['permite_acampar'];
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
    $result = [
      "error" => [
        "status" => false,
        "message" => "No hay error",
        "details" => []
      ],
      "data" => []
    ];
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
                SELECT
                  id,
                  nombre,
                  permite_acampar
                FROM lugares_turisticos
                WHERE id = :lugar_id AND eliminado = 0 AND activo = 1
              ) L
              INNER JOIN (
                SELECT
                  id,
                  lugar_id,
                  grupo_id,
                  cantidad_maxima
                FROM disponibilidades_lugares_gruposservicios
                WHERE lugar_id = :lugar_id AND eliminado = 0
              ) D ON L.id = D.lugar_id
              INNER JOIN (
                SELECT
                  id,
                  nombre
                FROM grupos_disponibilidades
                WHERE eliminado = 0
              ) G ON G.id = D.grupo_id
              INNER JOIN (
                SELECT
                  id,
                  nombre,
                  precio,
                  grupo_disponibilidad_id,
                  descripcion
                FROM servicios
                WHERE eliminado = 0
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
