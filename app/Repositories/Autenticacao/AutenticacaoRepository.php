<?php
namespace App\Repositories\Autenticacao;

use KissPhp\Abstractions\Repository;
use App\Entities\Views\ViewUsuarioLogin;

class AutenticacaoRepository extends Repository {
  public function obterUsuarioPelasCredenciais(string $email): ?ViewUsuarioLogin {
    try {
      $viewUsuario = $this->database()
        ->getRepository(ViewUsuarioLogin::class)
        ->findOneBy(['email' => $email]);
      
      return $viewUsuario;
    } catch (\Throwable $th) {
      error_log("[Error] AutenticacaoRepository::obterUsuarioPelasCredenciais: {$th->getMessage()}");
      return null;
    }
  }
}