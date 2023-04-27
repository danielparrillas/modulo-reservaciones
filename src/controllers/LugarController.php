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
    $response = $this->service->obtenerDetalle($lugar_id);
    $newData = [
      "lugarId" => null,
      "lugar" => null,
      "permiteAcampar" => null,
      "gruposDeServicios" => []
    ];
    $count = 0;
    foreach ($response["data"] as $row) {
      $newData["lugarId"] ?? $count++;
      $newData["lugarId"] ?? $newData["lugarId"] = $row["lugarId"];
      $newData["lugar"] ?? $newData["lugar"] = $row["lugar"];
      $newData["permiteAcampar"] ?? $newData["permiteAcampar"]  = $row["permiteAcampar"];
      $grupo_encontrado =  false;
      foreach ($newData["gruposDeServicios"] as $index => $grupo) {
        if ($grupo["grupoId"] === $row["grupoId"]) {
          $grupo_encontrado = true;
          $newData["gruposDeServicios"][$index]["servicios"][] = [
            "servicioId" => $row["servicioId"],
            "servicio" => $row["servicio"],
            "precio" => $row["precio"],
            "descripcion" => $row["descripcion"]
          ];
        }
      }
      if ($grupo_encontrado === false && $row["cantidadMaximaDiariaPorGrupo"] > 0) {
        $newData["gruposDeServicios"][] = [
          "disponibilidadId" => $row["disponibilidadId"],
          "grupoId" => $row["grupoId"],
          "nombre" => $row["grupo"],
          "cantidadMaximaDiaria" => $row["cantidadMaximaDiariaPorGrupo"],
          "servicios" => [
            [
              "servicioId" => $row["servicioId"],
              "servicio" => $row["servicio"],
              "precio" => $row["precio"],
              "descripcion" => $row["descripcion"]
            ]
          ]
        ];
      }
    }
    if ($response["data"]) $response["data"] = $newData;
    else {
      $response["error"]["status"] = true;
      $response["error"]["message"] = "No se encontro el lugar ni servicios";
    }
    echo json_encode($response);
  }
}
