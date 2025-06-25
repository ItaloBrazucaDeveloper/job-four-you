<?php
namespace App\Controllers;

use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Get, Post };
use KissPhp\Attributes\Http\Request\{ Body, RouteParam };

use App\Utils\SessionKeys;
use KissPhp\Enums\FlashMessageType;
use App\Middlewares\VerificaSeUsuarioNaoLogado;
use App\Services\RecuperarSenha\RecuperarSenhaService;

use function App\Utils\bp;

#[Controller('/recuperar-senha', [VerificaSeUsuarioNaoLogado::class])]
class RecuperarSenhaController extends WebController {
  public function __construct(private RecuperarSenhaService $service) { }

  #[Get('/:etapa:{alpha}')]
  public function exibirPaginaRecuperarSenha(
    Request $request,  
    #[RouteParam] string $etapa
  ) {
    $descricao = match ($etapa) {
      'email' => 'Para recuperar sua senha, digite seu e-mail abaixo e enviaremos um código de verificação para você.',
      'codigo' => 'Para recuperar sua senha, digite seu e-mail abaixo e enviaremos um código de verificação para você.',
      'senha' => 'Para recuperar sua senha, digite seu e-mail abaixo e enviaremos um código de verificação para você.',
      default => 'Para recuperar sua senha, digite seu e-mail abaixo e enviaremos um código de verificação para você.',
    };

    $this->render('Pages/autenticacao/recuperar-senha.twig', [
      'etapa' => $etapa,
      'flash_message' => $request->session->getFlashMessage(),
      'descricao' => $descricao
    ]);
  }

  #[Post('/email')]
  public function enviarEmailParaRecuperacao(#[Body] string $email, Request $request) {
    $emailHigienizado = trim($email);
    $foiEnviado = $this->service->enviarCodigoDeVerificacao($emailHigienizado);

    if (!$foiEnviado) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Falha ao enviar o e-mail, tente novamente mais tarde.');
      return $this->redirectTo('/recuperar-senha/email');
    }
    
    $request->session->set(SessionKeys::EMAIL_RECUPERAR_SENHA, $email);
    $request->session->setFlashMessage(FlashMessageType::Success, 'Código enviado para o seu e-mail, por favor, verifique a caixa de mensagem.');
    return $this->redirectTo('/recuperar-senha/codigo');
  }

  #[Post('/codigo')]
  public function verfificarCodigo(Request $request) {
    $codigo = implode($request->getAllBody());
    $emailInserido = $request->session->get(SessionKeys::EMAIL_RECUPERAR_SENHA) ?? '';

    if ($emailInserido === '') {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível verificar o código, tente novamente.');
      return $this->redirectTo('/recuperar-senha/email');
    }
    
    $eValido = $this->service->validarCodigo($emailInserido, $codigo);

    if (!$eValido) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Código inválido, confira o código enviado e tente novamente.');
      return $this->redirectTo('/recuperar-senha/codigo');
    }

    $request->session->setFlashMessage(FlashMessageType::Success, 'Código verificado com sucesso, insira sua nova senha.');
    return $this->redirectTo('/recuperar-senha/senha');
  }

  #[Post('/senha')]
  public function mudarSenha(
    Request $request,
    #[Body('primeira-senha')] string $primeiraSenha,
    #[Body('segunda-senha')] string $segundaSenha
  ) {
    if ($primeiraSenha !== $segundaSenha) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'As senha não são iguais, por favor, tente novamente.');
      return $this->redirectTo('/recuperar-senha/codigo');
    }

    $emailInserido = $request->session->get(SessionKeys::EMAIL_RECUPERAR_SENHA);
    $senhaFoiAtualizada = $this->service->redefinirSenha($emailInserido, $primeiraSenha);

    if (!$senhaFoiAtualizada) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível atualizar a senha, tente novamente.');
      return $this->redirectTo('/recuperar-senha/senha');
    }

    $request->session->remove(SessionKeys::EMAIL_RECUPERAR_SENHA);
    $request->session->setFlashMessage(FlashMessageType::Success, 'Senha redefina com sucesso!');
    return $this->redirectTo('/autenticacao');
  }
}
