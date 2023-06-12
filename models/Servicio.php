<?php
include_once('../../utils/Database.php');
class Servicio
{
  public function __construct(private Database $db)
  {
  }
  public function crear(array $data)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "INSERT INTO servicios
                (grupo_disponibilidad_id,nombre,precio,descripcion,eliminado)
              VALUES (:gdi, :n, :p, :d, :e)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':gdi', $data['disponibilidadId'], PDO::PARAM_INT);
      $stmt->bindParam(':n', $data['nombre'], PDO::PARAM_STR);
      $stmt->bindParam(':d', $data['descripcion'], PDO::PARAM_STR);
      $stmt->bindParam(':p', $data['precio'], PDO::PARAM_STR);
      $stmt->bindParam(':e', $data['eliminado'], PDO::PARAM_BOOL);
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
      $sql = "UPDATE servicios SET
                grupo_disponibilidad_id = :gdi,
                nombre = :n,
                precio = :p,
                descripcion = :d,
                eliminado = :e              
              WHERE id = :id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
      $stmt->bindParam(':gdi', $data['disponibilidadId'], PDO::PARAM_INT);
      $stmt->bindParam(':n', $data['nombre'], PDO::PARAM_STR);
      $stmt->bindParam(':d', $data['descripcion'], PDO::PARAM_STR);
      $stmt->bindParam(':p', $data['precio'], PDO::PARAM_STR);
      $stmt->bindParam(':e', $data['eliminado'], PDO::PARAM_BOOL);
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

  public function obtenerPorId($servicio_id)
  {
    $result = [];
    try {
      $conn = $this->db->conectar();
      $sql = "SELECT id,nombre, grupo_disponibilidad_id AS disponibilidadId,
                      precio, eliminado, descripcion
              FROM servicios WHERE id = :id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id', $servicio_id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result["eliminado"] = (bool)$result["eliminado"];
        $result["precio"] = (float) $result["precio"]; // convertimos a float
      } else {
        throw new Exception("No se encuentra ningun servicio con el id " . $servicio_id);
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
      $sql = "SELECT id,nombre, grupo_disponibilidad_id AS disponibilidadId,
                      precio, eliminado, descripcion
              FROM servicios";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['precio'] = (float) $row['precio'];
        $row["eliminado"] = (bool)$row["eliminado"];
        $result[] = $row;
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
}
