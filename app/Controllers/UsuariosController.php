<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Request\Body;
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

  #[Post('/atualizar', [VerificaSeUsuarioLogado::class])]
  public function atualizarUsuario(Request $request, #[Body] \App\DTOs\Usuario\UsuarioAtualizarDTO $dados) {
    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    
    if (!$this->service->atualizarUsuario($usuarioLogado->id, $dados)) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível atualizar seus dados. Tente novamente.');
      return $this->redirectTo('/usuarios/meu-perfil');
    }
    
    // Atualizar a sessão do usuário
    $usuarioAtualizado = $this->service->obterUsuarioPeloId($usuarioLogado->id);
    $request->session->set(SessionKeys::USUARIO_AUTENTICADO, $usuarioAtualizado);
    
    $request->session->setFlashMessage(FlashMessageType::Success, 'Dados atualizados com sucesso!');
    return $this->redirectTo('/usuarios/meu-perfil');
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

  #[Get('/tornar-prestador', [VerificaSeUsuarioLogado::class])]
  public function verificarSePodeSeTornarPrestador(Request $request) {
    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    
    // Verificar se o usuário já é prestador
    if ($usuarioLogado->grupo === 'PRESTADOR') {
      $request->session->setFlashMessage(FlashMessageType::Info, 'Você já é um prestador de serviços!');
      return $this->redirectTo('/usuarios/meu-perfil');
    }

    $verificacao = $this->service->verificarSePodeSeTornarPrestador($usuarioLogado->id);
    
    if ($verificacao['pode_se_tornar']) {
      // Se pode se tornar prestador, realizar a conversão
      if ($this->service->tornarClienteEmPrestador($usuarioLogado->id)) {
        // Atualizar a sessão do usuário
        $usuarioAtualizado = $this->service->obterUsuarioPeloId($usuarioLogado->id);
        $request->session->set(SessionKeys::USUARIO_AUTENTICADO, $usuarioAtualizado);
        
        $request->session->setFlashMessage(FlashMessageType::Success, 'Parabéns! Agora você pode publicar seus serviços na plataforma!');
        return $this->redirectTo('/usuarios/meu-perfil');
      } else {
        $request->session->setFlashMessage(FlashMessageType::Error, 'Erro ao processar sua solicitação. Tente novamente.');
        return $this->redirectTo('/usuarios/meu-perfil');
      }
    } else {
      // Se não pode se tornar prestador, exibir a página com os erros
      $dadosCompletos = $this->service->obterUsuarioPeloId($usuarioLogado->id);
      
      $this->render('Pages/usuarios/meu-perfil.twig', [
        'usuario' => $dadosCompletos,
        'erros_prestador' => $verificacao['erros']
      ]);
    }
  }
}
