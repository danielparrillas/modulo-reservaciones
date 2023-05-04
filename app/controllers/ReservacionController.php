<?php
include_once(dirname(__DIR__) . '/models/Reservacion.php');
include_once(dirname(__DIR__) . '/models/ReservacionDetalle.php');
include_once(dirname(__DIR__) . '/models/Lugar.php');
include_once(dirname(__DIR__) . '/models/Servicio.php');
include_once(dirname(__DIR__) . '/utils/validarDui.php');

class ReservacionController
{
  private $model_reservacion;
  private $model_detalle;
  private $model_servicio;
  private $cliente_id = null;
  public function __construct(private Database $db)
  {
    $this->model_reservacion = new Reservacion($db);
    $this->model_detalle = new ReservacionDetalle($db);
    $this->model_servicio = new Servicio($db);
  }

  public function setClienteId($cliente_id)
  {
    $this->cliente_id = $cliente_id;
  }

  public function obtenerPorId(int $id): void
  {
    $result = $this->model_reservacion->obtenerPorId($id);

    if (!$result["data"]) {
      http_response_code(404);
      $result["data"] = [];
      $result["error"]["status"] = true;
      $result["error"]["message"] = "No se encontro la reservacion";
      $result["error"]["details"] = $result["error"]["message"];
      echo json_encode($result);
      exit;
    }

    $result_detalles = $this->model_detalle->obtenerDetallesDeReservacion($id);

    if ($result_detalles["error"]["status"]) {
      http_response_code(404);
      $result["data"] = [];
      $result["error"]["status"] = true;
      $result["error"]["message"] = "Hubo error al obtener los detalles";
      $result["error"]["details"][] = ["detalles" => $result_detalles["error"]];
      echo json_encode($result);
      exit;
    }

    $result["data"]["detalles"] = $result_detalles;
    echo json_encode($result);
  }

  public function crear($data): array
  {

    $result_validado = $this->validarEntradas($data);

    if (isset($result_validado["error"])) {
      return $result_validado;
    }
    $result_validado["data"]["claveAcceso"] = $this->generarClaveDeAcceso();
    // $new_reservacion = $this->model_reservacion->crear([
    //   'clienteId' => $result_validado['data']['clienteId'],
    //   'lugarId' => $result_validado['data']['lugarId'], // !ALERTA
    //   'claveDeAcceso' => $result_validado['data']['claveDeAcceso'],
    //   'nombres' => $result_validado['data']['nombres'],
    //   'apellidos' => $result_validado['data']['apellidos'],
    //   'dui' => $result_validado['data']['dui'],
    //   'pagada' => $result_validado['data']['pagada'],
    //   'inicio' => $result_validado['data']['inicio'],
    //   'fin' => $result_validado['data']['fin']
    // ]);

    if (isset($result_valid['error'])) {

      foreach ($result_validado['data']['detalles'] as $key => $detalle) {
        $result_validado['data']['detalles'][$key]["data"]["asdf"] = "asdf";
      }
    }
    return $result_validado;
  }

  private function validarEntradas($data): array
  {
    $result["data"]["clienteId"] = $this->cliente_id;
    // validacion del cliente id
    if ($result["data"]["clienteId"] === null) {
      $result["error"]["status"] = true;
      $result["error"]["details"]["cliente"][] = "No hay cliente relacionado al api key. Error interno.";
    }

    //validacion nombres
    if (!isset($data["nombres"])) {
      $result["data"]["nombres"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["nombres"][] = "Parametro obligatorio";
    }

    if (isset($data["nombres"])) {
      $result["data"]["nombres"] = $data["nombres"];
      if (!is_string($data["nombres"])) {
        $result["error"]["status"] = true;
        $result["error"]["details"]["nombres"][] = "Debe ser texto";
      } else {
        if (trim($data["nombres"]) === "") {
          $result["error"]["status"] = true;
          $result["error"]["details"]["nombres"][] = "No debe ser un texto vacío";
        }
      }
    }
    //validacion apellidos
    if (!isset($data["apellidos"])) {
      $result["data"]["apellidos"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["apellidos"][] = "Parametro obligatorio";
    }

    if (isset($data["apellidos"])) {
      $result["data"]["apellidos"] = $data["apellidos"];
      if (!is_string($data["apellidos"])) {
        $result["error"]["status"] = true;
        $result["error"]["details"]["apellidos"][] = "Debe ser texto";
      } else {
        if (trim($data["apellidos"]) === "") {
          $result["error"]["status"] = true;
          $result["error"]["details"]["apellidos"][] = "No debe ser un texto vacío";
        }
      }
    }
    //validacion de dui
    if (!isset($data["dui"])) {
      $result["data"]["dui"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["dui"][] = "Parametro obligatorio";
    }

    if (isset($data["dui"])) {
      $result["data"]["dui"] = $data["dui"];
      if (!is_string($data["dui"])) {
        $result["error"]["status"] = true;
        $result["error"]["details"]["dui"][] = "Debe ser texto";
      } else {
        if (trim($data["dui"]) === "") {
          $result["error"]["status"] = true;
          $result["error"]["details"]["dui"][] = "No debe ser un texto vacío";
        }
        if (!isDUI($data["dui"])) {
          $result["error"]["status"] = true;
          $result["error"]["details"]["dui"][] = "Formato no válido (#######-#)";
        }
      }
    }

    //validacion pagada
    if (!isset($data["pagada"])) {
      $result["data"]["pagada"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["pagada"][] = "Parametro obligatorio";
    }
    if (isset($data["pagada"])) {
      $result["data"]["pagada"] = $data["pagada"];
      if (!is_bool($data["pagada"])) {
        $result["error"]["status"] = true;
        $result["error"]["details"]["pagada"][] = "Debe ser boleano (true o false)";
      }
    }

    $fecha_inicio = false;
    //validacion de inicio de fecha
    if (!isset($data["inicio"])) {
      $result["data"]["inicio"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["inicio"][] = "Parametro obligatorio";
    }
    if (isset($data["inicio"])) {
      $result["data"]["inicio"] = $data["inicio"];
      if (is_string($data["inicio"])) {
        $fecha_inicio = DateTime::createFromFormat('Y-m-d', $data["inicio"]);

        if ($fecha_inicio === false) {
          $result["error"]["status"] = true;
          $result["error"]["details"]["inicio"][] = "Formato erróneo (YYYY-MM-DD)";
        } else {
          $result["data"]["inicio"] = $fecha_inicio->format('Y-m-d');
        }
      } else {
        $result["error"]["status"] = true;
        $result["error"]["details"]["inicio"][] = "Debe ser una fecha en texto";
      }
    }
    //validacion de fin de fecha
    $fecha_fin = false;
    if (!isset($data["fin"])) {
      $result["data"]["fin"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["fin"][] = "Parametro obligatorio";
    }
    if (isset($data["fin"])) {
      $result["data"]["fin"] = $data["fin"];
      if (is_string($data["fin"])) {
        $fecha_fin = DateTime::createFromFormat('Y-m-d', $data["fin"]);

        if ($fecha_fin === false) {
          $result["error"]["status"] = true;
          $result["error"]["details"]["fin"][] = "Formato erróneo (YYYY-MM-DD)";
        } else {
          $result["data"]["fin"] = $fecha_fin->format('Y-m-d');
        }
      } else {
        $result["error"]["status"] = true;
        $result["error"]["details"]["fin"][] = "Debe ser una fecha en texto";
      }
    }
    //validacion de diferencia de fechas
    if ($fecha_inicio !== false && $fecha_fin !== false) {
      $fecha_diferencia = $fecha_inicio->diff($fecha_fin);
      $fecha_actual = new DateTime();
      $fecha_actual->setTime(0, 0, 0, 0);
      $fecha_diff_hoy = $fecha_inicio->diff($fecha_actual);
      // $result["data"]["diff"][] = $fecha_diff_hoy;
      // $result["data"]["diff"][] = new DateTime();
      if (
        $fecha_diff_hoy->invert === 0 ||
        $fecha_diff_hoy->invert === 0 && $fecha_diff_hoy->days === 0
      ) {
        $result["error"]["status"] = true;
        $result["error"]["details"]["inicio"][] = "La fecha debe ser despues de la fecha actual";
      }
      if ($fecha_diferencia->invert === 1 && $fecha_diferencia->days > 0) {
        $result["error"]["status"] = true;
        $result["error"]["details"]["inicio"][] = "No debe ser despues de la fecha de fin";
        $result["error"]["details"]["fin"][] = "No debe ser antes de la fecha de inicio";
      }
    }
    //validacion del detalle
    //que no este nulo
    if (!isset($data["detalles"])) {
      $result["data"]["detalles"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["detalles"][] = "Parametro obligatorio";
    }
    //si no esta nulo
    if (isset($data["detalles"])) {
      //que sea de tipo arreglo
      if (is_array($data["detalles"])) {
        //que no sea un arreglo vacio
        if (count($data["detalles"]) > 0) {
          foreach ($data["detalles"] as $key => $detalle) {
            $detalle_validado = $this->validarDetalle($detalle);
            // si hubo es valido el detalle
            $detalle_validado["data"]["posicion"] = $key;
            if (!isset($detalle_validado["error"])) {
              $result["data"]["detalles"][] = $detalle_validado;
            } else {
              $result["data"]["detalles"][] = $detalle_validado;
              $result["error"]["status"] = true;
              $result["error"]["details"]["detalles"] = "Error en alguno de los servicios. Revisar detalles en data";
            }
          }
        } else {
          $result["error"]["status"] = true;
          $result["error"]["details"]["detalles"][] = "El arreglo no debe estar vacío";
        }
      } else {
        $result["error"]["status"] = true;
        $result["error"]["details"]["detalles"][] = "Debe ser un arreglo";
      }
    }

    if (isset($result["error"])) $result["error"]["message"] = "Error en los datos. Revisar los detalles para mayor informacion.";
    return $result;
  }

  private function validarDetalle($data)
  {
    // que sea un objeto
    if (is_array($data)) {

      // que exista el campo servicioId
      if (isset($data["servicioId"])) {
        $result["data"]["servicioId"] = $data["servicioId"];
        // que sea entero
        if (is_int($data["servicioId"])) {
          // que sea mayor a cero
          if ($data["servicioId"] > 0) {
            $result_model = $this->model_servicio->obtenerPorId($data["servicioId"]);
            if ($result_model["error"]["status"] === false) {
              $result["data"] = $result_model["data"];
            } else {
              $result["error"]["status"] = true;
              $result["error"]["details"]["servicioId"] = $result_model["error"]["message"];
            }
          } else {
            $result["error"]["status"] = true;
            $result["error"]["details"]["servicioId"] = "Debe ser un numero entero mayor a cero";
          }
        } else {
          $result["error"]["status"] = true;
          $result["error"]["details"]["servicioId"] = "Debe ser un numero entero";
        }
      } else {
        $result["data"]["servicioId "] = null;
        $result["error"]["status"] = true;
        $result["error"]["details"]["servicioId"] = "Parametro obligatorio";
      }

      // que exita el campo cantidad
      if (isset($data["cantidad"])) {
        $result["data"]["cantidad"] = $data["cantidad"];
        if (is_int($data["cantidad"])) {
          if ($data["cantidad"] < 0) {
            $result["error"]["status"] = true;
            $result["error"]["details"]["cantidad"] = "Debe ser un numero entero mayor o igual a cero";
          }
        } else {
          $result["error"]["status"] = true;
          $result["error"]["details"]["cantidad"] = "Debe ser un numero entero";
        }
      } else {
        $result["data"]["cantidad"] = null;
        $result["error"]["status"] = true;
        $result["error"]["details"]["cantidad"] = "Parametro obligatorio";
      }
    } else {
      $result["error"]["status"] = true;
      $result["error"]["details"]["detalle"] = "Debe ser un objeto";
    }

    if (isset($result["error"])) $result["error"]["message"] = "Hay un error en este detalle. Revisar los detalles";
    return $result;
  }
  private function generarClaveDeAcceso()
  {
    $val = true;
    $key = bin2hex(openssl_random_pseudo_bytes(6, $val));
    return $key;
  }
}
