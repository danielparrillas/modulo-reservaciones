<?php
include_once(dirname(__DIR__) . '/models/GrupoDisponibilidad.php');

class DisponbilidadController
{
  private $model_disponibilidad;
  public function __construct(Database $db)
  {
    $this->model_disponibilidad = new GrupoDisponibilidad($db);
  }

  public function obtenerTodos(): array
  {
    $result = $this->model_disponibilidad->obtenerTodos();

    return $result;
  }
}
