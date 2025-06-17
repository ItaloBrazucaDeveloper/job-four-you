<?php
namespace App\Validators;

use KissPhp\Abstractions\DataValidator;

class Email extends DataValidator {
  public function __construct(private string $email) { }

  public function check(): array {
    return $this->newValidate()
      ->whenNot(filter_var($this->email, FILTER_VALIDATE_EMAIL))
      ->notify('E-mail inválido.')
      ->result();
  }
}