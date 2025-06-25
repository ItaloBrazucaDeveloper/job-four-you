<?php
namespace App\DTOs\Servicos;

class FiltroAtivoDTO {
  public readonly string $tipo;
  public readonly string $valor;
  public readonly string $param;
  public readonly string $param_valor;

  public function __construct(string $tipo, string $valor, string $param, string $param_valor) {
    $this->tipo = $tipo;
    $this->valor = $valor;
    $this->param = $param;
    $this->param_valor = $param_valor;
  }
} 