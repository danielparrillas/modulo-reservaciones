<?php

include_once(dirname(__DIR__) . '/models/Lugar.php');

class LugarController
{
  private $model_lugar;
  public function __construct(Database $db)
  {
    $this->model_lugar = new Lugar($db);
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
}
