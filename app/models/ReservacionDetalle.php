<?php
include_once(dirname(__DIR__) . '/utils/Database.php');
class ReservacionDetalle
{
  public function __construct(public Database $db)
  {
  }

  public function obtenerDetallesDeReservacion(int $reservacion_id)
  {
    $result = [
      "data" => [],
      "error" => [
        "status" => false,
        "message" => "No hay error",
        "details" => []
      ]
    ];

    try {
      $conn = $this->db->conectar();
      $sql = "SELECT 
              R.id AS reservacionId,
              S.id AS servicioId,
              S.nombre AS servicio,
              D.cantidad AS cantidad,
              D.precio AS precio
              FROM (
                SELECT reservacion_id,servicio_id, cantidad, precio
                FROM detalles_reservaciones
                WHERE id = :reservacion_id AND eliminado = 0
              ) D
              INNER JOIN (
                SELECT id, nombre FROM servicios
                WHERE eliminado = 0
              ) S ON S.id = D.servicio_id
              INNER JOIN (
                SELECT id FROM reservaciones WHERE eliminado = 0
              ) R ON R.id = D.reservacion_id";
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

  public function agregarDetalleAReservacion(array $data)
  {
    $result = [
      "data" => [],
      "error" => [
        "status" => false,
        "message" => "No hay error",
        "details" => []
      ]
    ];
    try {
      $conn = $this->db->conectar();
      $sql = "INSERT INTO detalles_reservaciones
              (reservacion_id, servicio_id, cantidad, precio)
              VALUES (:r_id, :s_id, :cantidad, :precio])";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':r_id', $data['reservacionId'], PDO::PARAM_INT);
      $stmt->bindParam(':s_id', $data['servicioId'], PDO::PARAM_INT);
      $stmt->bindParam(':cantidad', $data["cantidad"], PDO::PARAM_INT);
      $stmt->bindParam(':precio', $data["precio"], PDO::PARAM_STR);
      $stmt->execute();

      $result["data"]["detalleId"] = $conn->lastInsertId();
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
    $valor_resetaer = 0;
    $result = [
      "data" => [],
      "error" => [
        "status" => false,
        "message" => "No hay error",
        "details" => []
      ]
    ];

    try {
      $conn = $this->db->conectar();
      //$sql = "UPDATE products SET name = :name, size = :size, is_available = :is_available WHERE id = :id";

      $sql = "UPDATE detalles_reservaciones
              SET cantidad = :cantidad
              WHERE reservacion_id = :r_id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':r_id', $id_reservaciones, PDO::PARAM_INT);
      $stmt->bindParam(':cantidad', $valor_resetaer, PDO::PARAM_INT);
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

  public function actualizarDetalle(array $data)
  {
    $result = [
      "data" => [],
      "error" => [
        "status" => false,
        "message" => "No hay error",
        "details" => []
      ]
    ];

    try {
      $conn = $this->db->conectar();
      //$sql = "UPDATE products SET name = :name, size = :size, is_available = :is_available WHERE id = :id";

      $sql = "UPDATE detalles_reservaciones
              SET cantidad = :cantidad, precio = :precio
              WHERE reservacion_id = :r_id AND servicio_id = :s_id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':r_id', $data['reservacionId'], PDO::PARAM_INT);
      $stmt->bindParam(':s_id', $data['servicioId'], PDO::PARAM_INT);
      $stmt->bindParam(':cantidad', $data['cantidad'], PDO::PARAM_INT);
      $stmt->bindParam(':precio', $data['precio'], PDO::PARAM_STR);
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
}
