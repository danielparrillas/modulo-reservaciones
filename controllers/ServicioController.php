<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/reservaciones/models/Servicio.php");

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
}
