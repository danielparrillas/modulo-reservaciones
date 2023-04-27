<?php

include_once(dirname(__DIR__) . '/models/Lugar.php');
include_once(dirname(__DIR__) . '/services/LugarService.php');

class LugarController
{
  public function __construct(private LugarService $service)
  {
  }

  public function obtenerTodos(): void
  {
    echo json_encode($this->service->obtenerTodosSimple());
  }

  public function obtenerServicios($lugar_id): void
  {
    echo json_encode($this->service->obtenerServicios($lugar_id));
  }
}
