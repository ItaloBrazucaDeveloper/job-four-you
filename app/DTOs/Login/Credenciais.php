<?php
namespace App\DTOs\Login;

use KissPhp\Attributes\Data\Validate;
use App\Validators\{ Password, Email };

class Credenciais {
  public readonly string $email;
  public readonly string $senha;
}