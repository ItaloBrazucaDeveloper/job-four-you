<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Request\Body;
use KissPhp\Attributes\Http\Methods\{ Get, Post };

use App\Utils\SessionKeys;
use App\DTOs\Login\Credenciais;
use App\Services\Autenticacao\AutenticacaoService;
use App\Middlewares\{ VerificaSeUsuarioLogado, VerificaSeUsuarioNaoLogado };

#[Controller('/autenticacao')]
class AutenticacaoController extends WebController {
  public function __construct(private AutenticacaoService $service) { }

  #[Get(middlewares: [VerificaSeUsuarioNaoLogado::class])]
  public function exibirPaginaDeLogin(Request $request) {
    $this->render('Pages/autenticacao/login.twig', [
      'flash_message' => $request->session->getFlashMessage()
    ]);
  }

  #[Post(middlewares: [VerificaSeUsuarioNaoLogado::class])]
  public function autenticar(#[Body] Credenciais $usuario, Request $request) {
    $usuarioAutenticado = $this->service->obterUsuarioAutenticado($usuario);

    if ($usuarioAutenticado) {
      $request->session->set(SessionKeys::USUARIO_AUTENTICADO, $usuarioAutenticado);
      return $this->redirectTo('/');
    }
    $request->session->setFlashMessage(FlashMessageType::Error, 'Usuário inválido!');
    return $this->redirectTo('/autenticacao');
  }

  #[Get('/sair', [VerificaSeUsuarioLogado::class])]
  public function finalizarSessao(Request $request) {
    $request->session->clearAll();
    return $this->redirectTo('/autenticacao');
  }
}