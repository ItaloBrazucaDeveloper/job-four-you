<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Request\{ Body };
use KissPhp\Attributes\Http\Methods\{ Get, Post };

use App\Utils\SessionKeys;
use App\DTOs\Usuario\UsuarioCadastroDTO;
use App\Services\Usuarios\UsuariosService;
use App\Middlewares\VerificaSeUsuarioNaoLogado;

#[Controller('/usuarios', [VerificaSeUsuarioNaoLogado::class])]
class UsuariosController extends WebController {
  public function __construct(private UsuariosService $service) { }

  #[Get('/cadastro')]
  public function exibirPaginaDeCadastro() {
    $this->render('Pages/usuarios/cadastro.twig', []);
  }

  #[Post('/cadastro')]
  public function cadastrarUsuario(Request $request, #[Body] UsuarioCadastroDTO $usuario) {
    if (!$this->service->cadastrarUsuario($usuario)) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível terminar o cadastro :/');
      return $this->redirectTo('/usuarios/cadastro');
    }
    $request->session->setFlashMessage(FlashMessageType::Success, 'Cadastro realizado com sucesso!');
    return $this->redirectTo('/autenticacao');
  }

  #[Get('/meu-perfil')]
  public function exibirPaginaDeMeuPerfil(Request $request) {
    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    $dadosCompletos = $this->service->obterUsuarioPeloId($usuarioLogado->id);

    $this->render('Pages/usuarios/meu-perfil.twig', [
      'usuario' => $dadosCompletos
    ]);
  }

  #[Get('/meus-favoritos')]
  public function exibirListaDeServicosFavoritos() {

  }

  #[Get('/meus-servicos')]
  public function exibirListaDeServicosPostados() {

  }
}
