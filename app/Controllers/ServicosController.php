<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Delete, Get, Post };
use KissPhp\Attributes\Http\Request\{ Body, QueryString, RouteParam };

use App\Utils\SessionKeys;
use App\DTOs\Servicos\ServicoCadastroDTO;
use App\Services\Servicos\ServicosService;
use App\Middlewares\{ VerificaSePertenceGrupoPrestador, VerificaSeUsuarioLogado };

#[Controller('index')]
class ServicosController extends WebController {
  public function __construct(private ServicosService $service) { }
  
  #[Get]
  public function exibirPaginaDeServicos(Request $request) {
    $categoria = $request->getQueryString('categoria');
    $pagina = $request->getQueryString('pagina') ?? 1;
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);

    $categorias = $this->service->buscarCategorias();
    $dadosPaginacao = $this->service->buscarServicosComPaginacao($pagina, $usuario?->id);

    $this->render('Pages/servicos/listar-servicos.twig', [
      'categorias' => $categorias,
      'publicacoes' => $dadosPaginacao['servicos'],
      'categoriaSelecionada' => $categoria,
      'paginaAtual' => $pagina,
      'totalPaginas' => $dadosPaginacao['totalPaginas']
    ]);
  }

  #[Get('/mais-detalhes', [VerificaSeUsuarioLogado::class])]
  public function exibirMaisDetalhesDeServico(#[QueryString] int $id) {
    $detalhes = $this->service->buscarMaisDetalhesDoServico($id);
    if (!$detalhes) {
      // Redireciona ou exibe erro se não encontrado
      return $this->redirectTo('/servicos?erro=nao-encontrado');
    }
    $this->render('Pages/servicos/mais-detalhes.twig', [
      'detalhes' => $detalhes,
      'avaliacoes' => $detalhes->avaliacoes,
    ]);
  }

  #[Get('/postar-servico', [VerificaSeUsuarioLogado::class, VerificaSePertenceGrupoPrestador::class])]
  public function exibirPaginaCadastrarServico() {    
    $categorias = $this->service->buscarCategorias();
    $this->render('Pages/servicos/cadastro.twig', [
      'categorias' => $categorias,
    ]);
  }

  #[Post('/postar-servico', [VerificaSeUsuarioLogado::class, VerificaSePertenceGrupoPrestador::class])]
  public function cadastrarServico(
    Request $request,
    #[Body] ServicoCadastroDTO $servico,
  ) {
    $foto = $request->getFile('foto');
    $foiCadastrado = $this->service->cadastrar($servico, $foto);

    if ($foiCadastrado) {
      $request->session->setFlashMessage(FlashMessageType::Success, "Serviço cadastrado com sucesso!");
      return $this->redirectTo('/servicos');
    }
    $request->session->setFlashMessage(FlashMessageType::Error, "Erro ao cadastrar serviço. Verifique os dados e tente novamente.");
    return $this->redirectTo('/servicos/cadastro');
  }

  #[Post('/desativar-servico', [VerificaSeUsuarioLogado::class, VerificaSePertenceGrupoPrestador::class])]
  public function desativarServico(#[RouteParam] int $id, Request $request) {
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    if (!$usuario->id) return $this->redirectTo('/login');

    $this->service->desativarServico($id, $usuario->id);
    return $this->redirectTo('/prestadores?sucesso=1');
  }

  #[Post('/favoritar-servico')]
  public function favoritarServico(Request $request, #[QueryString] int $id) {
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    return json_encode($this->service->favoritarServico($id, $usuario->id));
  }

  #[Delete('/desfavoritar-servico', [VerificaSeUsuarioLogado::class])]
  public function desfavoritarServico(#[RouteParam] int $id, Request $request) {
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);

    $sucesso = $this->service->desfavoritarServico($id, $usuario->id);
    return json_encode(["sucesso" => $sucesso]);
  }
}
