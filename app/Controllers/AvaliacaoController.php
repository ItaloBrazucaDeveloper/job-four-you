<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Get, Post };
use KissPhp\Attributes\Http\Request\{ Body, RouteParam };

use App\Utils\SessionKeys;
use App\DTOs\Avaliacao\AvaliacaoCadastroDTO;
use App\Services\Avaliacao\AvaliacaoService;

use App\Middlewares\{
  VerificaSePertenceGrupoPrestador,
  VerificaSeUsuarioLogado,
  VerificaSeTokenAvaliacaoValido
};

#[Controller('/avaliacao', [VerificaSeUsuarioLogado::class])]
class AvaliacaoController extends WebController {
  public function __construct(private AvaliacaoService $service) { }

  #[Get('/:token:{alphanumeric}?', [VerificaSeTokenAvaliacaoValido::class])]
  public function exibirPaginaDeAvaliacao(#[RouteParam] string $token, Request $request) {
    $this->render('Pages/servicos/avaliacao.twig', [

    ]);
  }

  #[Post('/:token:{alphanumeric}?', [VerificaSeTokenAvaliacaoValido::class])]
  public function avaliarUsuario(#[Body] AvaliacaoCadastroDTO $avaliacao, Request $request) {
    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    $foiCadastrado = $this->service->cadastrar($usuarioLogado->id, $avaliacao);

    if (!$foiCadastrado) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível registrar sua avaliação, por favor, tente novamente.');
      return $this->redirectTo('');
    }

    $request->session->setFlashMessage(FlashMessageType::Success, 'Avaliação registrada com sucesso!');
    return $this->redirectTo('/mais-detalhes/2');
  }

  #[Get('/gerar-url/:id:{numeric}', [VerificaSePertenceGrupoPrestador::class])]
  public function gerarUrlParaAvaliacao(#[RouteParam] int $id) {
    $token = $this->service->criarToken($id);
    return json_encode(['url' => "/avaliacao/{$token}"]);
  }
}