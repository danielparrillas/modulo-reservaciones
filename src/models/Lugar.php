<?php

class Lugar
{
  public function __construct(
    public int $id,
    public string $nombre,
    public bool $permite_acampar,
    public bool $activo,
  ) {
  }
}
