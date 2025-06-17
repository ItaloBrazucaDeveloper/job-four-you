<?php
namespace App\DTOs\Login;

use KissPhp\Attributes\Data\Validate;
use App\Validators\{ Password, Email };

class Credenciais {
  // #[Validate(Email::class)]
  public readonly string $email;

  // #[Validate(Password::class)]
  public readonly string $senha;
}