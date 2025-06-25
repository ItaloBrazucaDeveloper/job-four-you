<?php
namespace App\DTOs\Servicos;

class FiltrosServicoDTO {
  public readonly ?string $categoria;
  public readonly array $estado;
  public readonly array $valor;
  public readonly ?string $prestador;

  public function __construct(
    ?string $categoria = null,
    array $estado = [],
    array $valor = [],
    ?string $prestador = null
  ) {
    $this->categoria = $categoria;
    $this->estado = $estado;
    $this->valor = $valor;
    $this->prestador = $prestador;
  }

  public function temFiltros(): bool {
    return !empty($this->categoria) || 
           !empty($this->estado) || 
           !empty($this->valor) || 
           !empty($this->prestador);
  }
} 