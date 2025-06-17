<?php
namespace App\DTOs\Login;

class UsuarioAutenticado {
  public readonly ?int $id;
  public readonly string $nome;
  public readonly string $email;
  public readonly ?string $foto;
  public readonly string $grupo;
  public readonly string $statusUsuario;
}