<?php
namespace App\DTOs\Usuario;

class UsuarioAtualizarDTO {
  public function __construct(
    public readonly string $nome,
    public readonly string $email,
    public readonly string $telefone,
    public readonly ?string $foto = null,
    public readonly ?string $dataNascimento = null
  ) { }
}