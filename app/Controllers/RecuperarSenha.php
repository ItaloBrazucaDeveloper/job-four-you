<?php
namespace App\Controllers;

use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Get, Post };

#[Controller('/recuperar-senha', [VerificaSeUsuarioNaoLogado::class])]
class RecuperarSenha extends WebController {
  #[Get]
  public function exibirPaginaRecuperarSenha() {
    $this->render('Pages/autenticacao/recuperar-senha.twig', [
      
    ]);
  }

  #[Post]
  public function enviarCodigoDeRecuperacao() {
    // lógica para enviar código
  }
}