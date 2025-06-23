<?php
namespace App\DTOs\Servicos;

class ServicoCadastroDTO {
  public readonly string $titulo;
  public readonly string $descricao;
  public readonly float $preco;
  public readonly int $categoria;
  public readonly ?string $usuario;
  public ?string $foto;
}