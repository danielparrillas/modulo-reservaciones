<?php

class Database
{
  public function __construct(
    private string $host,
    private string $name,
    private string $user,
    private string $password,
    private string $driver,
    private int $port,
    private string $charset,
  ) {
  }

  public function conectar(): PDO
  {
    $dsn = $this->driver . ':host=' . $this->host . ';dbname=' . $this->name . ';charset=' . $this->charset . ';port=' . $this->port;
    return new PDO($dsn, $this->user, $this->password);
  }
}
