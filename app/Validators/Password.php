<?php
namespace App\Validators;

use KissPhp\Abstractions\DataValidator;

class Password extends DataValidator {
  public function __construct(private string $passwaord) { }

  public function check(): array {
    return $this->newValidate()
      ->whenNot(preg_match('#[\w\d\s]{8,24}#', $this->passwaord))
      ->notify('Senha inválida.')
      ->result();
  }
}