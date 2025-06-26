<?php
namespace App\Controllers;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Delete, Get, Post };
use KissPhp\Attributes\Http\Request\{ Body, QueryString, RouteParam };

use App\Utils\SessionKeys;
use App\Services\Servicos\ServicosService;
use App\Services\Usuarios\UsuariosService;
use App\DTOs\Servicos\{ ServicoCadastroDTO, FiltrosServicoDTO };
use App\Middlewares\{ VerificaSePertenceGrupoPrestador, VerificaSeUsuarioLogado };

#[Controller('index')]
class ServicosController extends WebController {
  public function __construct(private ServicosService $service, private UsuariosService $usuariosService) { }
  
  #[Get]
  public function exibirPaginaDeServicos(Request $request) {
    $pagina = $request->getQueryString('pagina') ?? 1;
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);

    $queryStrings = $request->getAllQueryStrings();
    
    // Criar DTO de filtros
    $filtros = new FiltrosServicoDTO(
      categoria: $queryStrings['categoria'] ?? null,
      estado: is_array($queryStrings['estado'] ?? null) ? $queryStrings['estado'] : [],
      valor: is_array($queryStrings['valor'] ?? null) ? $queryStrings['valor'] : [],
      prestador: $queryStrings['prestador'] ?? null
    );

    $categorias = $this->service->buscarCategorias();
    $dadosPaginacao = $this->service->buscarServicosComPaginacao($pagina, $usuario?->id, $filtros);
    $filtrosAtivos = $this->service->processarFiltrosAtivos($filtros);

    $this->render('Pages/servicos/listar-servicos.twig', array_merge($queryStrings, [
      'categorias' => $categorias,
      'publicacoes' => $dadosPaginacao['servicos'],
      'categoria_selecionada' => $filtros->categoria,
      'estado' => $filtros->estado,
      'valor' => $filtros->valor,
      'prestador' => $filtros->prestador,
      'paginaAtual' => $pagina,
      'totalPaginas' => $dadosPaginacao['totalPaginas'],
      'filtros_ativos' => $filtrosAtivos
    ]));
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
  public function exibirPaginaCadastrarServico(Request $request) {
    $categorias = $this->service->buscarCategorias();
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    $usuarioCompleto = $this->usuariosService->obterUsuarioPeloId($usuario->id);
    $contatos = [
      'contato_email' => $usuarioCompleto->contato_email,
      'contato_celular' => $usuarioCompleto->contato_celular,
      'contato_facebook' => $usuarioCompleto->contato_facebook,
      'contato_instagram' => $usuarioCompleto->contato_instagram,
      'contato_whatsapp' => $usuarioCompleto->contato_whatsapp,
      'contato_outro' => $usuarioCompleto->contato_outro,
    ];
    $this->render('Pages/servicos/cadastro.twig', [
      'categorias' => $categorias,
      'contatos' => $contatos,
    ]);
  }

  #[Post('/postar-servico', [VerificaSeUsuarioLogado::class, VerificaSePertenceGrupoPrestador::class])]
  public function cadastrarServico(
    Request $request,
    #[Body] ServicoCadastroDTO $servico,
  ) {
    $foiCadastrado = $this->service->cadastrar($servico);

    if ($foiCadastrado) {
      $request->session->setFlashMessage(FlashMessageType::Success, "Serviço cadastrado com sucesso!");
      return $this->redirectTo('/usuarios/meus-servicos');
    }
    $request->session->setFlashMessage(FlashMessageType::Error, "Erro ao cadastrar serviço. Verifique os dados e tente novamente.");
    return $this->redirectTo('/postar-servico');
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
