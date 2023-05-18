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

  public function obtenerTodos()
  {
    return $this->model_lugar->obtenerTodos();
  }

  public function obtenerPorId($id)
  {
    $id_valid = intval($id);
    if ($id_valid > 0) return $this->model_lugar->obtenerPorIdSimple($id);
    else return ["error" => ["message" => "El id debe ser un numero valido"]];
  }

  public function obtenerDisponibilidadesPorLugar($lugar_id)
  {
    return $this->model_lugar->obtenerDisponibilidades($lugar_id);
  }
}
