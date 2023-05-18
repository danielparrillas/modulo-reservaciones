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
  private $model_lugar;
  private $cliente_id = null;
  public function __construct(private Database $db)
  {
    $this->model_reservacion = new Reservacion($db);
    $this->model_detalle = new ReservacionDetalle($db);
    $this->model_servicio = new Servicio($db);
    $this->model_lugar = new Lugar($db);
  }

  public function setClienteId($cliente_id)
  {
    $this->cliente_id = $cliente_id;
  }

  public function obtenerPorId($id)
  {
    $result = [];
    $reservacionId = intval($id);
    $result = $this->model_reservacion->obtenerPorId($reservacionId);

    if (isset($result["error"])) {
      return $result;
    }

    if (!$result["data"]) {
      $result["data"] = [];
      $result["error"]["status"] = true;
      $result["error"]["message"] = "No se encontro la reservacion";
      $result["error"]["details"] = $result["error"]["message"];
      return $result;
    }

    $result_detalles = $this->model_detalle->obtenerDetallesDeReservacion($reservacionId);

    if (isset($result_detalles["error"])) {
      $result["data"] = [];
      $result["error"]["status"] = true;
      $result["error"]["message"] = "Hubo error al obtener los detalles";
      $result["error"]["details"][] = ["detalles" => $result_detalles["error"]];
      return $result;
    }

    $result["data"]["detalles"] = $result_detalles;
    return $result;
  }

  public function crearConDetalles($data): array
  {
    $result_validado = $this->validarDatosParaCrear($data);

    if (isset($result_validado["error"])) {
      $result_validado['data'] = array_merge(["reservacionId" => null], $result_validado['data']);
      return $result_validado;
    }
    // $result_validado["data"]["claveAcceso"] = $this->generarClaveDeAcceso();

    // la clavedeacceso permitira actualizar la reservacion. Si la apikey no coincide con la clave de acceso no permitira modificaciones
    $result_validado['data'] = array_merge(["claveAcceso" => $this->generarClaveDeAcceso()], $result_validado['data']);

    $reservacion_guardada = $this->model_reservacion->crear([
      'clienteId' => $result_validado['data']['clienteId'],
      'lugarId' => $result_validado['data']['lugarId'],
      'claveDeAcceso' => $result_validado['data']['claveAcceso'],
      'nombres' => $result_validado['data']['nombres'],
      'apellidos' => $result_validado['data']['apellidos'],
      'dui' => $result_validado['data']['dui'],
      'pagada' => $result_validado['data']['pagada'],
      'inicio' => $result_validado['data']['inicio'],
      'fin' => $result_validado['data']['fin']
    ]);
    if (!isset($reservacion_guardada['error'])) {
      $result_validado['data'] = array_merge(["reservacionId" => $reservacion_guardada['data']["reservacionId"]], $result_validado['data']);

      foreach ($result_validado['data']["detalles"] as $key => $detalle) {
        $detalle_guardado = $this->model_detalle->crear([
          "reservacionId" => $result_validado["data"]["reservacionId"],
          "servicioId" => $detalle["data"]["servicioId"],
          "cantidad" => $detalle["data"]["cantidad"],
          "precio" => $detalle["data"]["precio"]
        ]);
        if (!isset($detalle_guardado["error"])) {
          $result_validado["data"]["detalles"][$key]["data"]["detalleId"] = $detalle_guardado["data"]["detalleId"];
        } else {
          $result_validado["error"]["status"] = true;
          $result_validado["data"]["detalles"][$key]["error"] = $detalle_guardado["error"];
        }
      }
    } else {
      $result_validado['data'] = array_merge(["reservacionId" => null], $result_validado['data']);
      $result_validado["error"] = $reservacion_guardada["error"];
    }
    return $result_validado;
  }

  public function actualizarConDetalles($data): array
  {
    $data_validada = $this->validarDatosParaActualizar($data);

    if (isset($data_validada["error"])) return $data_validada;

    $reservacionId = intval($data["reservacionId"]);
    $data_current = $this->obtenerPorId($reservacionId);

    if (isset($data_current["error"])) {
      $data_validada["error"] = $data_current["error"];
      return $data_validada;
    }

    if ($data_current["data"]["claveAcceso"] !== $data_validada["data"]["claveAcceso"]) {
      $data_validada["error"]["status"] = true;
      $data_validada["error"]["message"] = "La clave de acceso es incorrecta";
      return $data_validada;
    }

    $data_updated = [
      "id" => $data_current["data"]["reservacionId"],
      "clienteId" => $data_current["data"]["clienteId"],
      "claveAcceso" => $data_current["data"]["claveAcceso"],
      "lugarId" => $data_validada["data"]["lugarId"] ?? $data_current["data"]["lugarId"],
      "nombres" => $data_validada["data"]["nombres"] ?? $data_current["data"]["nombres"],
      "apellidos" => $data_validada["data"]["apellidos"] ?? $data_current["data"]["apellidos"],
      "dui" => $data_validada["data"]["dui"] ?? $data_current["data"]["dui"],
      "pagada" => $data_validada["data"]["pagada"] ?? $data_current["data"]["pagada"],
      "inicio" => $data_validada["data"]["inicio"] ?? $data_current["data"]["inicio"],
      "fin" => $data_validada["data"]["fin"]  ?? $data_current["data"]["fin"]
    ];

    $result_detalle_update = $this->model_reservacion->actualizar($data_updated);

    if (isset($result_detalle_update["error"])) {
      $data_validada["error"] = $result_detalle_update["error"];
      return $data_validada;
    }

    if (!isset($data_validada["data"]["detalles"])) {
      return ["data" => []];
    }

    // resetaremos todos los detalles
    $this->model_detalle->resetearDetallesDeReservacion($data_current["data"]["reservacionId"]);

    if (empty($data_validada["data"]["detalles"])) {
      return ["data" => []];
    }

    foreach ($data_validada["data"]["detalles"] as $key => $detalle) {
      $detalle_actualizado = $this->model_detalle->actualizar([
        "reservacionId" => $data_current["data"]["reservacionId"],
        "servicioId" => $detalle["data"]["servicioId"],
        "cantidad" => $detalle["data"]["cantidad"],
        "precio" => $detalle["data"]["precio"]
      ]);
      if (!isset($detalle_actualizado["error"])) {
        // si no afecto ninguna fila es por que no existe,
        // por lo tanto creamos el detalle
        if ($detalle_actualizado["data"]["filas"] === 0) {
          $detalle_guardado = $this->model_detalle->crear([
            "reservacionId" => $data_current["data"]["reservacionId"],
            "servicioId" => $detalle["data"]["servicioId"],
            "cantidad" => $detalle["data"]["cantidad"],
            "precio" => $detalle["data"]["precio"]
          ]);
          if (isset($detalle_guardado["error"])) {
            $data_validada["error"]["status"] = "true";
            $data_validada["error"]["message"] = "Error al crear detalle";
            $data_validada["error"]["details"]["detalles"][] = $detalle_guardado["error"];
          }
        }
      } else {
        $data_validada["error"]["status"] = true;
        $data_validada["data"]["detalles"][$key]["error"] = $detalle_actualizado["error"];
      }
    }

    return ["data" => []];
  }

  private function validarDatosParaActualizar($data): array
  {
    $result = [];
    $result["data"]["clienteId"] = $this->cliente_id;
    //📝 validacion del cliente id
    if ($result["data"]["clienteId"] === null) {
      $result["error"]["status"] = true;
      $result["error"]["details"]["cliente"][] = "No hay cliente relacionado al api key. Error interno.";
    }

    //📝 validacion de reservacionId
    if (!isset($data["reservacionId"])) {
      $result["data"]["reservacionId"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["reservacionId"][] = "Parametro obligatorio";
    }
    if (isset($data["reservacionId"])) {
      $result["data"]["reservacionId"] = $data["reservacionId"];
      $reservacionId = intval($data["reservacionId"]);
      if ($reservacionId > 0) {
        $result["data"]["reservacionId"] = $reservacionId;
      } else {
        $result["error"]["status"] = true;
        $result["error"]["details"]["reservacionId"][] = "Debe ser un numero";
      }
    }

    //📝 validacion de clave de acceso
    if (!isset($data["claveAcceso"])) {
      $result["data"]["claveAcceso"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["claveAcceso"][] = "Parametro obligatorio";
      $result["error"]["details"]["claveAcceso"][] = "Es necesario para poder actualizar esta reservacion";
    } else {
      $result["data"]["claveAcceso"] = $data["claveAcceso"];
      if (!is_string($data["claveAcceso"])) {
        $result["error"]["status"] = true;
        $result["error"]["details"]["claveAcceso"][] = "Debe ser una cadena de texto";
      }
    }

    //📝 validacion del lugar id
    if (isset($data["lugarId"])) {
      $result["data"]["lugarId"] = $data["lugarId"];
      if (is_int($data["lugarId"])) {
        $lugar_data = $this->model_lugar->obtenerPorIdSimple($data["lugarId"]);
        if (!isset($lugar_data["error"])) {
          if ($lugar_data["data"]["activo"]) {
            $result["data"]["lugarId"] = $lugar_data["data"]["lugarId"];
            $result["data"]["lugar"] = $lugar_data["data"]["lugar"];
            $result["data"]["lugarActivo"] = $lugar_data["data"]["activo"];
          } else {
            $result["error"]["status"] = true;
            $result["error"]["details"]["lugarId"][] = "El lugar actualmente no esta activo";
          }
        } else {
          $result["error"]["status"] = true;
          $result["error"]["details"]["lugarId"][] = $lugar_data["error"]["message"];
        }
      } else {
        $result["error"]["status"] = true;
        $result["error"]["details"]["lugarId"][] = "Debe ser un número";
      }
    }

    //📝 validacion nombres
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
    //📝 validacion apellidos
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
    //📝 validacion de dui
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

    //📝 validacion pagada
    if (isset($data["pagada"])) {
      $result["data"]["pagada"] = $data["pagada"];
      if (!is_bool($data["pagada"])) {
        $result["error"]["status"] = true;
        $result["error"]["details"]["pagada"][] = "Debe ser boleano (true o false)";
      }
    }

    $fecha_inicio = false;
    //📝 validacion de inicio de fecha
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
    //📝 validacion de fin de fecha
    $fecha_fin = false;
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
    //es necesario que si actualizan las fechas, se envien las 2
    if (isset($data["fin"]) xor isset($data["inicio"])) {
      $result["error"]["status"] = true;
      if (!isset($data["fin"])) {
        $result["data"]["fin"] = null;
        $result["error"]["details"]["fin"][] = "Debe enviar tambien este parametro para actualizar la fecha de inicio";
      }
      if (!isset($data["inicio"])) {
        $result["data"]["inicio"] = null;
        $result["error"]["details"]["inicio"][] = "Debe enviar tambien este parametro para actualizar la fecha de fin";
      }
    }
    //📝 validacion de diferencia de fechas
    if ($fecha_inicio !== false && $fecha_fin !== false) {
      $fecha_diferencia = $fecha_inicio->diff($fecha_fin);
      $fecha_actual = new DateTime();
      $fecha_actual->setTime(0, 0, 0, 0); // para hacer la diferencia a nivel de fecha sin tomar en cuenta la hora
      $fecha_diff_hoy = $fecha_inicio->diff($fecha_actual);
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

    //📝 validacion del detalle
    //si no esta nulo
    if (isset($data["detalles"])) {
      //que sea de tipo arreglo
      if (is_array($data["detalles"])) {
        //que no sea un arreglo vacio
        if (count($data["detalles"]) >  0) {
          foreach ($data["detalles"] as $key => $detalle) {
            $detalle_validado = $this->validarDetalle($detalle);
            // si hubo es valido el detalle
            $detalle_validado["data"]["posicion"] = $key;
            if (!isset($detalle_validado["error"])) {
              $result["data"]["detalles"][] = $detalle_validado;
            } else {
              $result["data"]["detalles"][] = $detalle_validado;
              $result["error"]["status"] = true;
              $result["error"]["details"]["detalles"][] = "Error en alguno de los servicios. Revisar detalles en data";
            }
          }
          // todo: validar detalles repetidos
          //se revisa que no haya detalles repetidos si no hay error en alguno de los detalles
          if (!isset($result["error"]["details"]["detalles"])) {
            $servicio_id_sin_repetir = [];
            foreach ($result["data"]["detalles"] as $key => $detalle) {
              if (!in_array($detalle["data"]["servicioId"], $servicio_id_sin_repetir)) {
                $servicio_id_sin_repetir[] = $detalle["data"]["servicioId"];
              } else {
                $result["error"]["status"] = true;
                $result["error"]["details"]["detalles"][] = "No debe enviar servicios duplicados";
                $result["data"]["detalles"][$key]["error"]["status"] = true;
                $result["data"]["detalles"][$key]["error"]["message"] = "Servicio duplicado";
              }
            }
          }
        } else { // no hay error si el arreglo esta vacio
          $result["data"]["detalles"] = [];
        }
      } else {
        $result["error"]["status"] = true;
        $result["error"]["details"]["detalles"][] = "Debe ser un arreglo";
      }
    }

    if (isset($result["error"])) $result["error"]["message"] = "Error en los datos. Revisar los detalles para mayor informacion.";
    return $result;
  }
  private function validarDatosParaCrear($data): array
  {
    $result = [];
    $result["data"]["clienteId"] = $this->cliente_id;
    //📝 validacion del cliente id
    if ($result["data"]["clienteId"] === null) {
      $result["error"]["status"] = true;
      $result["error"]["details"]["cliente"][] = "No hay cliente relacionado al api key. Error interno.";
    }

    //📝  validacion del lugar id
    if (!isset($data["lugarId"])) {
      $result["data"]["lugarId"] = null;
      $result["error"]["status"] = true;
      $result["error"]["details"]["lugarId"][] = "Parametro obligatorio";
    }
    if (isset($data["lugarId"])) {
      $result["data"]["lugarId"] = $data["lugarId"];
      if (is_int($data["lugarId"])) {
        $lugar_data = $this->model_lugar->obtenerPorIdSimple($data["lugarId"]);
        if (!isset($lugar_data["error"])) {
          if ($lugar_data["data"]["activo"]) {
            $result["data"]["lugarId"] = $lugar_data["data"]["lugarId"];
            $result["data"]["lugar"] = $lugar_data["data"]["lugar"];
            $result["data"]["lugarActivo"] = $lugar_data["data"]["activo"];
          } else {
            $result["error"]["status"] = true;
            $result["error"]["details"]["lugarId"][] = "El lugar actualmente no esta activo";
          }
        } else {
          $result["error"]["status"] = true;
          $result["error"]["details"]["lugarId"][] = $lugar_data["error"]["message"];
        }
      } else {
        $result["error"]["status"] = true;
        $result["error"]["details"]["lugarId"][] = "Debe ser un número";
      }
    }

    //📝 validacion nombres
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
    //📝 validacion apellidos
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
    //📝 validacion de dui
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

    //📝 validacion pagada
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
    //📝 validacion de inicio de fecha
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
    //📝 validacion de fin de fecha
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
    //📝 validacion de diferencia de fechas
    if ($fecha_inicio !== false && $fecha_fin !== false) {
      $fecha_diferencia = $fecha_inicio->diff($fecha_fin);
      $fecha_actual = new DateTime();
      $fecha_actual->setTime(0, 0, 0, 0); // para hacer la diferencia a nivel de fecha sin tomar en cuenta la hora
      $fecha_diff_hoy = $fecha_inicio->diff($fecha_actual);
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

    //📝 validacion del detalle
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
              $result["error"]["details"]["detalles"][] = "Error en alguno de los servicios. Revisar detalles en data";
            }
          }
          // todo: validar detalles repetidos
          //se revisa que no haya detalles repetidos si no hay error en alguno de los detalles
          if (!isset($result["error"]["details"]["detalles"])) {
            $servicio_id_sin_repetir = [];
            foreach ($result["data"]["detalles"] as $key => $detalle) {
              if (!in_array($detalle["data"]["servicioId"], $servicio_id_sin_repetir)) {
                $servicio_id_sin_repetir[] = $detalle["data"]["servicioId"];
              } else {
                $result["error"]["status"] = true;
                $result["error"]["details"]["detalles"][] = "No debe enviar servicios duplicados";
                $result["data"]["detalles"][$key]["error"]["status"] = true;
                $result["data"]["detalles"][$key]["error"]["message"] = "Servicio duplicado";
              }
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
    $result = [];
    // se mantendra null hasta que sea guardado, asi indcamos que todavia no se ha guarda
    $result["data"]["detalleId"] = null;
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
            if (!isset($result_model["error"])) {
              $result["data"] = $result_model["data"];
              // se mantendra null hasta que sea guardado, asi indcamos que todavia no se ha guarda
              $result["data"]["detalleId"] = null;
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
