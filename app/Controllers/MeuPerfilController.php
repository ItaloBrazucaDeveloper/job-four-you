<?php
namespace App\Controllers;

use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\Get;

use App\Utils\SessionKeys;
use App\Services\Usuarios\UsuariosService;
use App\Middlewares\VerificaSeUsuarioLogado;

#[Controller('/meu-perfil', [VerificaSeUsuarioLogado::class])]
class MeuPerfilController extends WebController {
  public function __construct(private UsuariosService $service) { }

  #[Get]
  public function exibirPaginaDeMeuPerfil(Request $request) {
    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    $dadosCompletos = $this->service->obterUsuarioPeloId($usuarioLogado->id);

    $this->render('Pages/usuarios/meu-perfil.twig', [
      'usuario' => $dadosCompletos
    ]);
  }

  #[Get('/favoritos')]
  public function exibirListaDeServicosFavoritos() {

  }

  #[Get('/servicos')]
  public function exibirListaDeServicosPostados() {

  }
}