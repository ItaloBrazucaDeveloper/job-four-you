<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Delete, Get, Post };
use KissPhp\Attributes\Http\Request\{ Body, RouteParam };

use App\Utils\SessionKeys;
use App\DTOs\Servicos\ServicoCadastroDTO;
use App\Middlewares\{ VerificaSePertenceGrupoPrestador, VerificaSeUsuarioLogado };
use App\Services\Servicos\ServicosService;

#[Controller('index')]
class ServicosController extends WebController {
  public function __construct(private ServicosService $service) { }
  
  #[Get]
  public function exibirPaginaDeServicos(Request $request) {
    $categoria = $request->getQueryString('categoria');
    $pagina = $request->getQueryString('pagina') ?? 1;

    $categorias = $this->service->buscarCategorias();
    $dadosPaginacao = $this->service->buscarServicosComPaginacao($pagina);

    $this->render('Pages/servicos/listar-servicos.twig', [
      'categorias' => $categorias,
      'publicacoes' => $dadosPaginacao['servicos'],
      'categoriaSelecionada' => $categoria,
      'paginaAtual' => $pagina,
      'totalPaginas' => $dadosPaginacao['totalPaginas']
    ]);
  }

  #[Get('/mais-detalhes/:id:{numeric}', [VerificaSeUsuarioLogado::class])]
  public function exibirMaisDetalhesDeServico() {
    $this->render('Pages/servicos/mais-detalhes.twig', []);
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

  #[Post('/desativar-servico/:id:{numeric}', [VerificaSeUsuarioLogado::class, VerificaSePertenceGrupoPrestador::class])]
  public function desativarServico(#[RouteParam] int $id, Request $request) {
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    if (!$usuario->id) return $this->redirectTo('/login');

    $this->service->desativarServico($id, $usuario->id);
    return $this->redirectTo('/prestadores?sucesso=1');
  }

  #[Post('/favoritar-servico/:id:{numeric}', [VerificaSeUsuarioLogado::class])]
  public function favoritarServico(Request $request, #[RouteParam] int $id = 0) {
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    if (!$usuario->id) {
      return json_encode(["sucesso" => false, "mensagem" => "Usuário não autenticado"]);
    }

    $sucesso = $this->service->favoritarServico($id, $usuario->id);
    return json_encode(["sucesso" => $sucesso]);
  }

  #[Delete('/desfavoritar-servico/:id:{numeric}', [VerificaSeUsuarioLogado::class])]
  public function desfavoritarServico(#[RouteParam] int $id, Request $request) {
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    if (!$usuario->id) {
      return json_encode(["sucesso" => false, "mensagem" => "Usuário não autenticado"]);
    }

    $sucesso = $this->service->desfavoritarServico($id, $usuario->id);
    return json_encode(["sucesso" => $sucesso]);
  }
}
