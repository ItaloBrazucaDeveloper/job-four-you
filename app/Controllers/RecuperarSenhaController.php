<?php
namespace App\Controllers;

use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Request\Body;
use KissPhp\Attributes\Http\Methods\{ Get, Post };

use App\Middlewares\VerificaSeUsuarioNaoLogado;
use App\Services\RecuperarSenha\RecuperarSenhaService;

#[Controller('/recuperar-senha', [VerificaSeUsuarioNaoLogado::class])]
class RecuperarSenhaController extends WebController {
  public function __construct(private RecuperarSenhaService $service) { }

  #[Get('/inserir-email')]
  public function exibirPaginaRecuperarSenha() {
    $this->render('Pages/autenticacao/recuperar-senha.twig', [
      'etapa' => 'email',
      'descricacao' => 'Insira seu e-email para receber o código de autenticação.'
    ]);
  }

  #[Post]
  public function enviarEmailParaRecuperacao(#[Body] string $email) {
    $emailHigienizado = trim($email);
    $this->service->enviarCodigoDeVerificacao($emailHigienizado);
    return $this->redirectTo('/recuperar-senha/inserir-codigo');
  }

  #[Get('/inserir-codigo/:token:{alphanumeric}')]
  public function exbirPaginaDeInserirCodigo() {
    $this->render('Pages/autenticacao/recuperar-senha.twig', [
      'etapa' => 'codigo',
      'descricacao' => 'Insira o código de verificação enviado para seu email.'
    ]);
  }

  #[Post('/codigo')]
  public function verfificarCodigo() {
    
  }

  #[Post('/nova-senha')]
  public function mudarSenha() {
    // lógica para enviar código
  }
}
