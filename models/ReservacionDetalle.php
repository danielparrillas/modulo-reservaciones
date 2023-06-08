<?php
include_once('../utils/Database.php');
class ReservacionDetalle
{
  public function __construct(public Database $db)
  {
  }

  public function obtenerDetallesDeReservacion(int $reservacion_id)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT 
                D.id AS detalleId, R.id AS reservacionId, S.id AS servicioId,
                S.nombre AS servicio, D.cantidad AS cantidad, D.precio AS precio
              FROM detalles_reservaciones D
              INNER JOIN servicios S ON S.id = D.servicio_id AND S.eliminado = 0
              INNER JOIN reservaciones R ON R.id = D.reservacion_id AND R.eliminado = 0
              WHERE D.reservacion_id = :reservacion_id AND D.eliminado = 0";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':reservacion_id', $reservacion_id, PDO::PARAM_INT);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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

  public function crear(array $data)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "INSERT INTO detalles_reservaciones (reservacion_id, servicio_id, cantidad, precio)
              VALUES (:r_id, :s_id, :cantidad, :precio)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':r_id', $data['reservacionId'], PDO::PARAM_INT);
      $stmt->bindParam(':s_id', $data['servicioId'], PDO::PARAM_INT);
      $stmt->bindParam(':cantidad', $data["cantidad"], PDO::PARAM_INT);
      $stmt->bindParam(':precio', $data["precio"], PDO::PARAM_STR);
      $stmt->execute();
      $result["data"]["detalleId"] = (int) $conn->lastInsertId();
    } catch (Exception $e) {
      $conn = null;
      $result["error"]["status"] = true;
      $result["error"]["message"] = $e->getMessage();
      $result["error"]["details"][] = ["database" => $e];
    }
    $conn = null;
    return $result;
  }

  public function resetearDetallesDeReservacion(int $id_reservaciones)
  {
    $result = [];
    $CANTIDAD_RESETEADA = 0;
    try {
      $conn = $this->db->conectar();
      //$sql = "UPDATE products SET name = :name, size = :size, is_available = :is_available WHERE id = :id";

      $sql = "UPDATE detalles_reservaciones SET cantidad = :cantidad
              WHERE reservacion_id = :r_id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':r_id', $id_reservaciones, PDO::PARAM_INT);
      $stmt->bindParam(':cantidad', $CANTIDAD_RESETEADA, PDO::PARAM_INT);
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

  public function actualizar(array $data)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      //$sql = "UPDATE products SET name = :name, size = :size, is_available = :is_available WHERE id = :id";

      $sql = "UPDATE detalles_reservaciones SET cantidad = :cantidad, precio = :precio
              WHERE reservacion_id = :r_id AND servicio_id = :s_id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':r_id', $data['reservacionId'], PDO::PARAM_INT);
      $stmt->bindParam(':s_id', $data['servicioId'], PDO::PARAM_INT);
      $stmt->bindParam(':cantidad', $data['cantidad'], PDO::PARAM_INT);
      $stmt->bindParam(':precio', $data['precio'], PDO::PARAM_STR);
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
}
