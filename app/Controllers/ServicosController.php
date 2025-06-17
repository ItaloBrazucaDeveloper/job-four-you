<?php
namespace App\Controllers;

use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Get, Post };
use KissPhp\Attributes\Http\Request\{ Body, RouteParam };

use App\Utils\SessionKeys;
use App\DTOs\Servicos\ServicoCadastroDTO;
use App\Services\Servicos\ServicosService;

#[Controller('/servicos')]
class ServicosController extends WebController {
  public function __construct(private ServicosService $service) { }
  
  #[Get]
  public function exibirPaginaDeServicos(Request $request) {
    $categoriaSelecionada = $request->getQueryString('categoria');

    $categorias = $this->service->buscarCategorias();

    $servicos = $categoriaSelecionada
      ? $this->service->buscarServicos((int) $categoriaSelecionada)
      : [];

    $this->render('Pages/servicos/listar-servicos.twig', [
      'categorias' => $categorias,
      'servicos' => $servicos,
      'categoriaSelecionada' => $categoriaSelecionada
    ]);
  }

  #[Get('/cadastro')]
  public function exibirPaginaCadastrarServico() {    
    $categorias = $this->service->buscarCategorias();

    $this->render('Pages/servicos/cadastro.twig', [
      'Categorias' => $categorias,
    ]);
  }

  #[Post('/cadastro')]
  public function cadastrarServico(
    Request $request,
    #[Body] ServicoCadastroDTO $servico
  ) {
    $foiCadastrado = $this->service->cadastrarServico($servico, []);

    if ($foiCadastrado) return $this->redirectTo('/servicos');

    $request->session->set('UltimoServicoInserido', $servico);
    return $this->redirectTo('/servicos/postar-servico');
  }

  #[Post('/desativar/:id:{numeric}')]
  public function desativarServico(#[RouteParam] int $id, Request $request) {
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);

    if (!$usuario->id) return $this->redirectTo('/login');

    $this->service->desativarServico($id, $usuario->id);
    return $this->redirectTo('/prestadores?sucesso=1');
  }
}