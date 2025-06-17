<?php
namespace App\DTOs\Usuario;

class EnderecoDTO {
  public function __construct(
    public readonly string $cep,
    public readonly string $estado,
    public readonly string $rua,
    public readonly string $bairro,
    public readonly string $cidade
  ) { }
}