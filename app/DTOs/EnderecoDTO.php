<?php
namespace App\DTOs;

class EnderecoDTO {
  public function __construct(
    public readonly string $cep,
    public readonly string $rua,
    public readonly string $bairro,
    public readonly string $cidade,
    public readonly string $estado
  ) { } 
}