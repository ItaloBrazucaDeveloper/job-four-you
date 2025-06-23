<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Request\{ Body, QueryString};
use KissPhp\Attributes\Http\Methods\{ Get, Post };

use App\Utils\SessionKeys;
use App\DTOs\Usuario\UsuarioCadastroDTO;
use App\Services\Usuarios\UsuariosService;
use App\Middlewares\{ VerificaSeUsuarioLogado, VerificaSeUsuarioNaoLogado };

#[Controller('/usuarios')]
class UsuariosController extends WebController {
  public function __construct(private UsuariosService $service) { }

  #[Get('/cadastro', [VerificaSeUsuarioNaoLogado::class])]
  public function exibirPaginaDeCadastro() {
    $this->render('Pages/usuarios/cadastro.twig', []);
  }

  #[Post('/cadastro', [VerificaSeUsuarioNaoLogado::class])]
  public function cadastrarUsuario(Request $request, #[Body] UsuarioCadastroDTO $usuario) {
    if (!$this->service->cadastrarUsuario($usuario)) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível terminar o cadastro :/');
      return $this->redirectTo('/usuarios/cadastro');
    }
    $request->session->setFlashMessage(FlashMessageType::Success, 'Cadastro realizado com sucesso!');
    return $this->redirectTo('/autenticacao');
  }

  #[Get('/meu-perfil', [VerificaSeUsuarioLogado::class])]
  public function exibirPaginaDeMeuPerfil(Request $request) {
    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    $dadosCompletos = $this->service->obterUsuarioPeloId($usuarioLogado->id);

    $this->render('Pages/usuarios/meu-perfil.twig', [
      'usuario' => $dadosCompletos
    ]);
  }

  #[Get('/meus-favoritos', [VerificaSeUsuarioLogado::class])]
  public function exibirListaDeServicosFavoritos(Request $request) {
    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    $favoritos = $this->service->obterServicosFavoritos($usuarioLogado->id);

    $this->render('Pages/usuarios/meu-perfil.twig', [
      'favoritos' => $favoritos
    ]);
  }

  #[Get('/meus-servicos', [VerificaSeUsuarioLogado::class])]
  public function exibirListaDeServicosPostados(Request $request) {
    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    $servicos = $this->service->obterServicosPostados($usuarioLogado->id);

    $this->render('Pages/usuarios/meu-perfil.twig', [
      'servicos' => $servicos
    ]);
  }

  #[Get('/tornar-prestador')]
  public function tornarClienteEmPrestador(#[QueryString] int $id) {
    return "Usuário: {$id}";
  }
}
