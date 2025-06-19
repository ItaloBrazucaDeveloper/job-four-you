<?php
namespace App\Controllers;

use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Get, Post };

use App\Middlewares\VerificaSeUsuarioNaoLogado;

#[Controller('/recuperar-senha', [VerificaSeUsuarioNaoLogado::class])]
class RecuperarSenha extends WebController {
  #[Get]
  public function exibirPaginaRecuperarSenha() {
    $this->render('Pages/autenticacao/recuperar-senha.twig', [
      
    ]);
  }

  #[Post]
  public function enviarEmailParaRecuperacao() {
    // lógica para enviar código
  }

  #[Get('/codigo')]
  public function exibirPaginaDeInserirCodigo() {
    
  }

  #[Post('/codigo')]
  public function verfificarCodigo() {
    // lógica para enviar código
  }

  #[Get('/nova-senha')]
  public function exibirPaginaDeMudarSenha() {
    
  }

  #[Post('/nova-senha')]
  public function mudarSenha() {
    // lógica para enviar código
  }
}
