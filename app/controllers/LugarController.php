<?php

include_once(dirname(__DIR__) . '/models/Lugar.php');

class LugarController
{
  private $model_lugar;
  public function __construct(Database $db)
  {
    $this->model_lugar = new Lugar($db);
  }

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
    $disponibilidad = $this->model_lugar->obtenerDisponibilidad($data);
    //❌ En caso de que exista error retornamos
    if (isset($disponibilidad["error"])) {
      return $disponibilidad;
    }
    //3️⃣ upsert
    //Si disponbilidad da falso es porque no existe una fila con ese lugarId y ese grupo
    if ($disponibilidad["data"] === false) {
      $result = $this->model_lugar->crearDisponibilidad($data);
    } else {
      $result = $this->model_lugar->actualizarDisponibilidad($data);
    }
    //4️⃣ mandamos el resultado (puede contener errores)
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

  public function obtenerDisponibilidadesPorLugar($lugar_id)
  {
    return $this->model_lugar->obtenerDisponibilidades($lugar_id);
  }
}
