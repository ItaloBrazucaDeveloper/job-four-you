<?php
namespace App\DTOs\Login;

use DateTime;

class UsuarioAutenticado {
  public readonly ?int $id;
  public readonly string $nome;
  public readonly string $email;
  public readonly ?string $foto;
  public readonly string $grupo;
}