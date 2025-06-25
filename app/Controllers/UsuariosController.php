<?php
namespace App\Controllers;

use App\DTOs\Usuario\UsuarioAtualizarDTO;
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
  public function atualizarUsuario(Request $request) {
    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    // Obter dados do formulário manualmente
    $dadosForm = $request->getAllBody();

    // Montar EnderecoDTO se houver dados de endereço
    $endereco = null;
    if (!empty($dadosForm['cep']) && !empty($dadosForm['rua']) && !empty($dadosForm['bairro']) && !empty($dadosForm['cidade']) && !empty($dadosForm['estado'])) {
      $endereco = new \App\DTOs\EnderecoDTO(
        $dadosForm['cep'],
        $dadosForm['rua'],
        $dadosForm['bairro'],
        $dadosForm['cidade'],
        $dadosForm['estado']
      );
    }

    // Montar ContatosDTO se houver algum contato preenchido
    $contatos = null;
    if (
      !empty($dadosForm['contato_email']) ||
      !empty($dadosForm['contato_celular']) ||
      !empty($dadosForm['contato_facebook']) ||
      !empty($dadosForm['contato_instagram']) ||
      !empty($dadosForm['contato_whatsapp']) ||
      !empty($dadosForm['contato_outro'])
    ) {
      $contatos = new \App\DTOs\ContatosDTO(
        $dadosForm['contato_email'] ?? null,
        $dadosForm['contato_celular'] ?? null,
        $dadosForm['contato_facebook'] ?? null,
        $dadosForm['contato_instagram'] ?? null,
        $dadosForm['contato_whatsapp'] ?? null,
        $dadosForm['contato_outro'] ?? null
      );
    }

    // Upload da foto
    $fotoFile = $request->getFile('foto');
    $nomeFoto = null;
    if ($fotoFile && $fotoFile->getError() === UPLOAD_ERR_OK) {
      $ext = pathinfo($fotoFile->getName(), PATHINFO_EXTENSION);
      $nomeFoto = uniqid('foto_', true) . '.' . $ext;
      $caminhoDestino = __DIR__ . '/../../public/uploads/fotos/' . $nomeFoto;
      $fotoFile->move($caminhoDestino);
      $caminhoFotoRelativo = '/uploads/fotos/' . $nomeFoto;
    } else {
      $caminhoFotoRelativo = $dadosForm['foto'] ?? null;
    }

    // Juntar nome e sobrenome
    $nomeCompleto = trim(($dadosForm['nome'] ?? '') . ' ' . ($dadosForm['sobrenome'] ?? ''));

    // Montar DTO principal
    $dto = new UsuarioAtualizarDTO(
      $nomeCompleto !== '' ? $nomeCompleto : null,
      $dadosForm['email'] ?? null,
      $dadosForm['telefone'] ?? null,
      $caminhoFotoRelativo,
      $dadosForm['data_nascimento'] ?? null,
      $endereco,
      $contatos
    );

    if (!$this->service->atualizarUsuario($usuarioLogado->id, $dto)) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível atualizar seus dados. Tente novamente.');
      return $this->redirectTo('/usuarios/meu-perfil');
    }
    
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
