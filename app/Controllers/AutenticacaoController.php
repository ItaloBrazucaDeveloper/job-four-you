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
use App\Middlewares\VerificaSeUsuarioNaoLogado;
use App\Services\Autenticacao\AutenticacaoService;

#[Controller('/autenticacao', [VerificaSeUsuarioNaoLogado::class])]
class AutenticacaoController extends WebController {
  public function __construct(private AutenticacaoService $service) { }

  #[Get]
  public function exibirPaginaDeLogin(Request $request) {
    $this->render('Pages/autenticacao/login.twig', [
      'flash_message' => $request->session->getFlashMessage()
    ]);
  }

  #[Post]
  public function autenticar(#[Body] Credenciais $usuario, Request $request) {
    $usuarioAutenticado = $this->service->obterUsuarioAutenticado($usuario);

    if ($usuarioAutenticado) {
      $request->session->set(SessionKeys::USUARIO_AUTENTICADO, $usuarioAutenticado);
      return $this->redirectTo('/servicos');
    }
    $request->session->setFlashMessage(FlashMessageType::Error, 'Usuário inválido!');
    return $this->redirectTo('/autenticacao');
  }

  #[Get('/sair')]
  public function finalizarSessao(Request $request) {
    $request->session->clearAll();
    return $this->redirectTo('/autenticacao');
  }
}