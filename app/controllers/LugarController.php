<?php
include_once(dirname(__DIR__) . '/models/Lugar.php');
include_once(dirname(__DIR__) . '/models/PeriodoDeshabilitado.php');
include_once(dirname(__DIR__) . '/models/Disponibilidad.php');
class LugarController
{
  private $model_lugar;
  private $model_disponibilidad;
  private $model_periodo_deshabilitado;
  public function __construct(Database $db)
  {
    $this->model_lugar = new Lugar($db);
    $this->model_periodo_deshabilitado = new PeriodoDeshabilitado($db);
    $this->model_disponibilidad = new Disponibilidad($db);
  }
  //1️⃣ Lugares
  public function crear($data)
  {
    $result = [];
    //📝 validacion de nombre
    if (isset($data["nombre"])) {
      if (!is_string($data["nombre"])) {
        $result["error"]["details"][] = "Nombre debe ser texto";
      } else {
        if (trim($data["nombre"]) === "") {
          $result["error"]["details"][] = "Nombre no debe ser un texto vacío";
        }
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse el nombre";
    }
    //📝 validacion de permite acampar
    if (isset($data["permiteAcampar"])) {
      if (!is_bool($data["permiteAcampar"])) {
        $result["error"]["details"][] = "Permite acampar debe ser un valor booleano";
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse el campo permite acampar";
    }
    //📝 validacion de activo
    if (isset($data["activo"])) {
      if (!is_bool($data["activo"])) {
        $result["error"]["details"][] = "El campo activo debe ser un valor booleano";
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse el campo activo";
    }
    //📝 validacion de id municipio
    if (isset($data["municipioId"])) {
      if (!is_int($data["municipioId"])) {
        $result["error"]["details"][] = "El id del municipio debe ser un número";
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse el municipio";
    }
    //📝 validacion de id anp
    if (isset($data["anpId"])) {
      if (!is_int($data["anpId"])) {
        $result["error"]["details"][] = "El id del anp debe ser un número";
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse la anp";
    }

    //❌ En caso de error retornamos los mensajes de error
    if (isset($result["error"])) {
      $result["error"]["message"] = "Error en los parámetros";
      // echo json_encode($result); //!
      return $result;
    }
    //✅ Realizamos la inserccion en la base de datos
    $result = $this->model_lugar->crear($data);
    // echo json_encode($result); //!

    return $result;
  }
  public function actualizar($data)
  {
    $result = [];
    $lugar = [];
    //📝 validacion de id
    if (isset($data["id"])) {
      if (intval($data["id"]) > 0) {
        $lugar = $this->model_lugar->obtenerPorId($data["id"]);
        //❌ En caso de que devuelva error
        if (isset($lugar["error"])) {
          return $lugar;
        }
      } else $result["error"]["details"] = "El id debe ser un número entero";
    } else {
      $result["error"]["details"][] = "Debe enviarse el id";
    }
    //📝 validacion de nombre
    if (isset($data["nombre"])) {
      if (!is_string($data["nombre"])) {
        $result["error"]["details"][] = "Nombre debe ser texto";
      } else {
        if (trim($data["nombre"]) === "") {
          $result["error"]["details"][] = "Nombre no debe ser un texto vacío";
        }
      }
    } else {
      $data["nombre"] = $lugar["data"]["nombre"];
    }
    //📝 validacion de permite acampar
    if (isset($data["permiteAcampar"])) {
      if (!is_bool($data["permiteAcampar"])) {
        $result["error"]["details"][] = "Permite acampar debe ser un valor booleano";
      }
    } else {
      $data["permiteAcampar"] = $lugar["data"]["permiteAcampar"];
    }
    //📝 validacion de activo
    if (isset($data["activo"])) {
      if (!is_bool($data["activo"])) {
        $result["error"]["details"][] = "El campo activo debe ser un valor booleano";
      }
    } else {
      $data["activo"] = $lugar["data"]["activo"];
    }
    //📝 validacion de id municipio
    if (isset($data["municipioId"])) {
      if (!is_int($data["municipioId"])) {
        $result["error"]["details"][] = "El id del municipio debe ser un número";
      }
    } else {
      $data["municipioId"] = $lugar["data"]["municipioId"];
    }
    //📝 validacion de id anp
    if (isset($data["anpId"])) {
      if (!is_int($data["anpId"])) {
        $result["error"]["details"][] = "El id del anp debe ser un número";
      }
    } else {
      $data["anpId"] = $lugar["data"]["anpId"];
    }
    //❌ En caso de error retornamos los mensajes de error
    if (isset($result["error"])) {
      $result["error"]["message"] = "Error en los parámetros";
      return $result;
    }
    //✅ Realizamos la inserccion en la base de datos
    $result = $this->model_lugar->actualizar($data);
    return $result;
  }
  public function obtenerTodos()
  {
    return $this->model_lugar->obtenerTodos();
  }
  public function obtenerPorId($id)
  {
    $id_valid = intval($id);
    if ($id_valid > 0) return $this->model_lugar->obtenerPorId($id);
    else return ["error" => ["message" => "El id debe ser un numero valido"]];
  }
  //2️⃣ Disponibilidades de lugares
  public function upsertDisponibilidad($data)
  {
    $result = [];
    $disponibilidad = [];
    $lugar = [];
    //📝 validacion de id
    if (isset($data["id"])) {
      if (!intval($data["id"]) > 0) {
        $result["error"]["details"] = "El id debe ser un número entero";
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse el id del lugar";
    }
    //📝 validacion de grupoId
    if (isset($data["grupoId"])) {
      if (!intval($data["grupoId"]) > 0) {
        $result["error"]["details"] = "El id del grupo debe ser un número entero";
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse el id del grupo de disponibilidad";
    }
    //📝 validacion de cantidad maxima
    if (isset($data["cantidadMaxima"])) {
      if (!intval($data["cantidadMaxima"]) > 0) {
        $result["error"]["details"] = "La cantidad debe ser un número entero";
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse la cantidad maxima";
    }
    //❌ En caso de que exista error retornamos
    if (isset($result["error"])) {
      return $result;
    }
    //1️⃣ Buscamos el lugar para saber si existe
    $lugar = $this->model_lugar->obtenerPorId($data["id"]);
    //❌ En caso de que exista error retornamos
    if (isset($lugar["error"])) {
      return $lugar;
    }
    //2️⃣ Buscamos una disponibilidad con ese id (lugarId) y grupoId
    $disponibilidad = $this->model_disponibilidad->obtenerDisponibilidadDeUnLugar($data);
    //❌ En caso de que exista error retornamos
    if (isset($disponibilidad["error"])) {
      return $disponibilidad;
    }
    //3️⃣ upsert
    //Si disponbilidad da falso es porque no existe una fila con ese lugarId y ese grupo
    if ($disponibilidad["data"] === false) {
      $result = $this->model_disponibilidad->crearDisponibilidadParaUnLugar($data);
    } else {
      $result = $this->model_disponibilidad->actualizarDisponibilidadDeUnLugar($data);
    }
    //4️⃣ mandamos el resultado (puede contener errores)
    return $result;
  }
  public function obtenerDisponibilidadesPorLugar($lugar_id)
  {
    return $this->model_disponibilidad->obtenerDisponibilidadesDeUnLugar($lugar_id);
  }
  //3️⃣ Periodos deshabilitados de lugares
  public function crearPeriodoDeshabilitado($data)
  {
    $result = [];
    $lugar = [];
    $fecha_fin = false;
    $fecha_inicio = false;
    //📝 validacion de inicio de fecha
    if (!isset($data["inicio"])) {
      $result["error"]["details"][] = "Debe indicar el inicio";
    }
    if (isset($data["inicio"])) {
      if (is_string($data["inicio"])) {
        $fecha_inicio = DateTime::createFromFormat('Y-m-d', $data["inicio"]);
        if ($fecha_inicio === false) {
          $result["error"]["details"][] = "Fecha de inicio tiene formato erróneo (YYYY-MM-DD)";
        }
      } else {
        $result["error"]["details"][] = "Fecha de inicio debe ser una fecha en texto";
      }
    }
    //📝 validacion de fin de fecha
    if (!isset($data["fin"])) {
      $result["error"]["details"]["fin"][] = "Debe indicar el fin";
    }
    if (isset($data["fin"])) {
      if (is_string($data["fin"])) {
        $fecha_fin = DateTime::createFromFormat('Y-m-d', $data["fin"]);
        if ($fecha_fin === false) {
          $result["error"]["details"][] = "Fecha de fin tiene formato erróneo (YYYY-MM-DD)";
        }
      } else {
        $result["error"]["details"][] = "Fecha de fin debe ser una fecha en texto";
      }
    }
    //📝 validacion de diferencia de fechas
    if ($fecha_inicio !== false && $fecha_fin !== false) {
      $fecha_diferencia = $fecha_inicio->diff($fecha_fin);
      if ($fecha_diferencia->invert === 1 && $fecha_diferencia->days > 0) {
        $result["error"]["details"][] = "La fecha de inicio debe ser despues de la fecha de fin";
      }
    }
    //❌ En caso de que devuelva error
    if (isset($result["error"])) {
      $result["data"] = $data; //👀
      return $result;
    }
    //1️⃣ validacion de lugarId
    if (isset($data["id"])) {
      if (intval($data["id"]) > 0) {
        $lugar = $this->model_lugar->obtenerPorId($data["id"]);
        //❌ En caso de que devuelva error
        if (isset($lugar["error"])) {
          return $lugar;
        }
      } else $result["error"]["details"][] = "El id debe ser un número entero";
    } else {
      $result["error"]["details"][] = "Debe enviarse el id";
    }
    //2️⃣ enviamos el resultado
    $result = $this->model_periodo_deshabilitado->crearPeriodoDeshabilitadoParaUnLugar($data);
    return $result;
  }
  public function obtenerPeriodosDeshabilitados($lugar_id)
  {
    //❌ retornar en caso de id invalido
    if (intval($lugar_id) < 1) {
      return ["error" => ["message" => "El id del lugar no es valido"]];
    }
    return $this->model_periodo_deshabilitado->obtenerPeriodosDeshabilitadosDeLugar($lugar_id);
  }
}
