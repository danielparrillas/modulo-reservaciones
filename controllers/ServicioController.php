<?php
include_once("../models/Servicio.php");

class ServicioController
{
  private $model_servicio;
  public function __construct(Database $db)
  {
    $this->model_servicio = new Servicio($db);
  }

  public function obtenerTodos(): array
  {
    $result = $this->model_servicio->obtenerTodos();

    return $result;
  }

  public function obtenerPorId(int $id): array
  {
    return $this->model_servicio->obtenerPorId($id);
  }
  public function crear(array $data)
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
    //📝 validacion de descripcion
    if (isset($data["descripcion"])) {
      if (!is_string($data["descripcion"])) {
        $result["error"]["details"][] = "Descripcion debe ser texto";
      } else {
        if (trim($data["descripcion"]) === "") {
          $result["error"]["details"][] = "Descripcion no debe ser un texto vacío";
        }
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse la descripcion";
    }
    //📝 validacion de eliminado
    if (isset($data["eliminado"])) {
      if (!is_bool($data["eliminado"])) {
        $result["error"]["details"][] = "El campo eliminado debe ser un valor booleano";
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse el estado eliminado";
    }
    //📝 validacion de precio
    if (isset($data["precio"])) {
      if (!is_numeric($data["precio"])) {
        $result["error"]["details"][] = "El campo precio debe ser un numero";
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse el precio";
    }
    //❌ En caso de error retornamos los mensajes de error
    if (isset($result["error"])) {
      $result["error"]["message"] = "Error en los parámetros";
      return $result;
    }
    //✅ Realizamos la inserccion en la base de datos
    $result = $this->model_servicio->crear($data);
    $result["modo"] = "creando";
    return $result;
  }
  public function actualizar(array $data)
  {
    $result = [];
    $servicio = [];
    //📝 validacion de id
    if (isset($data["id"])) {
      if (
        intval($data["id"]) > 0
      ) {
        $servicio = $this->model_servicio->obtenerPorId($data["id"]);
        //❌ En caso de que devuelva error
        if (isset($servicio["error"])) {
          return $servicio;
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
      $data["nombre"] = $servicio["nombre"];
    }
    //📝 validacion de descripcion
    if (isset($data["descripcion"])) {
      if (!is_string($data["descripcion"])) {
        $result["error"]["details"][] = "Descripcion debe ser texto";
      } else {
        if (trim($data["descripcion"]) === "") {
          $result["error"]["details"][] = "Descripcion no debe ser un texto vacío";
        }
      }
    } else {
      $result["error"]["details"][] = "Debe enviarse la descripcion";
    }
    //📝 validacion de eliminado
    if (isset($data["eliminado"])) {
      if (!is_bool($data["eliminado"])) {
        $result["error"]["details"][] = "El campo eliminado debe ser un valor booleano";
      }
    } else {
      $data["eliminado"] = $servicio["eliminado"];
    }
    //📝 validacion de precio
    if (isset($data["precio"])) {
      if (!is_numeric($data["precio"])) {
        $result["error"]["details"][] = "El campo precio debe ser un numero";
      }
    } else {
      $data["precio"] = $servicio["precio"];
    }
    //❌ En caso de error retornamos los mensajes de error
    if (isset($result["error"])) {
      $result["error"]["message"] = "Error en los parámetros";
      return $result;
    }
    //✅ Realizamos la inserccion en la base de datos
    $result = $this->model_servicio->actualizar($data);
    return $result;
  }
}
