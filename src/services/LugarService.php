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
    $result = [];

    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id, nombre FROM lugares_turisticos WHERE activo = 1 AND eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $conn = null;
    } catch (Exception $e) {
      $conn = null;
      throw $e;
    }
    return $result;
  }

  public function obtenerServicios($lugar_id): array
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT 
                  G.id,
                  S.nombre,
                  S.descripcion,
                  S.precio,
                  D.cantidad_maxima
              FROM lugares_turisticos L
              JOIN (
                  SELECT DISTINCT lugar_id, grupo_id, cantidad_maxima
                  FROM disponibilidades_lugares_gruposservicios
                  WHERE eliminado = 0
              ) D ON L.id = D.lugar_id
              JOIN grupos_disponibilidades G ON D.grupo_id = G.id AND G.eliminado = 0
              JOIN servicios S ON G.servicio_id = S.id AND S.eliminado = 0
              WHERE L.id = $lugar_id AND L.activo = 1 AND L.eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // $row['is_available'] = (bool) $row['is_available'];
        $result[] = $row;
      }
    } catch (Exception $e) {
      $conn = null;
      throw $e;
    }
    $conn = null;
    return $result;
  }
}
