<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Get, Post };
use KissPhp\Attributes\Http\Request\{ Body, QueryString, RouteParam };

use App\Utils\SessionKeys;
use App\DTOs\Avaliacao\AvaliacaoCadastroDTO;
use App\Services\Avaliacao\AvaliacaoService;

use App\Middlewares\{
  VerificaSePertenceGrupoPrestador,
  VerificaSeUsuarioLogado,
  VerificaSeTokenAvaliacaoValido
};
use App\Utils\TokenJwt;
use App\Services\Servicos\ServicosService;

use function App\Utils\bp;

#[Controller('/avaliacao', [VerificaSeUsuarioLogado::class])]
class AvaliacaoController extends WebController {
  public function __construct(
    private AvaliacaoService $service,
    private ServicosService $servicosService
  ) { }

  #[Get(middlewares: [VerificaSeTokenAvaliacaoValido::class])]
  public function exibirPaginaDeAvaliacao(#[QueryString] string $token, Request $request) {
    $token = $request->getQueryString('token');
    $payload = TokenJwt::obterPayload($token);
    $id = $payload['puid'];

    $detalhes = $this->servicosService->buscarMaisDetalhesDoServico($id);

    $this->render('Pages/servicos/avaliacao.twig', [
      'foto' => $detalhes?->foto ?? '',
      'prestador' => $detalhes?->prestador ?? '',
      'titulo' => $detalhes?->titulo ?? '',
      'categoria' => $detalhes?->descricacao ?? '',
      'mediaAvaliacoes' => $detalhes?->mediaAvaliacoes ?? 0,
      'quantidadeEstrelas' => $detalhes?->quantiadadeEstrelas ?? 0 // Adapte se necessário
    ]);
  }

  #[Post(middlewares: [VerificaSeTokenAvaliacaoValido::class])]
  public function avaliarUsuario(#[Body] AvaliacaoCadastroDTO $avaliacao, Request $request) {
    $token = $request->getQueryString('token');
    $id = TokenJwt::obterPayload($token)['puid'] ?? 0;

    if (!$token) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível registrar sua avaliação, por favor, tente novamente.');
      return $this->redirectTo("/avaliacao?token={$token}");
    }

    $usuarioLogado = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    $foiCadastrado = $this->service->cadastrar($usuarioLogado->id, $id, $avaliacao);

    if (!$foiCadastrado) {
      $request->session->setFlashMessage(FlashMessageType::Error, 'Não foi possível registrar sua avaliação, por favor, tente novamente.');
      return $this->redirectTo("/avaliacao?token={$token}");
    }

    $request->session->setFlashMessage(FlashMessageType::Success, 'Avaliação registrada com sucesso!');
    return $this->redirectTo('/mais-detalhes/2');
  }

  #[Get('/gerar-url/:id:{numeric}', [VerificaSePertenceGrupoPrestador::class])]
  public function gerarUrlParaAvaliacao(#[RouteParam] int $id) {
    $token = $this->service->criarToken($id);
    return json_encode(['url' => "/avaliacao?token={$token}"]);
  }
}