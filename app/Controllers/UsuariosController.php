<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Request\{ Body };
use KissPhp\Attributes\Http\Methods\{ Get, Post };

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
  public function cadastrarUsuario(
    Request $request,
    #[Body] UsuarioCadastroDTO $usuario
  ) {
    $foiCadastrado = $this->service->cadastrarUsuario($usuario);

    if (!$foiCadastrado) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível terminar o cadastro :/');
      return $this->redirectTo('/usuarios/cadastro');
    }

    $request->session->setFlashMessage(FlashMessageType::Success, 'Cadastro realizado com sucesso!');
    return $this->redirectTo('/autenticacao');
  }

  #[Post('/atualizar')]
  public function atualizarUsuario(#[Body] UsuarioAtualizarDTO $usuario) {
    
  }
}
