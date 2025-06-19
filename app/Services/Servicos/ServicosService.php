<?php
namespace App\Services\Servicos;

use App\Entities\Categorias\CategoriaServico;
use App\Repositories\Servicos\ServicosRepository;
use App\DTOs\Servicos\{ ServicoDTO, ServicoCadastroDTO };
use App\Utils\Paginacao;

use function App\Utils\bp;

class ServicosService {
  public function __construct(private ServicosRepository $repository) { }

  /** @return CategoriaServico[] */
  public function buscarCategorias(): array {
    try {
      return $this->repository->buscarCategorias();
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return [];
    }
  }

  /** @return ServicoDTO[] */
  public function buscarServicos(int $paginaAtual): array {
    try {
      $offset = Paginacao::getOffset($paginaAtual);
      $itemsPorPagina = Paginacao::getItemsPorPagina();
      return $this->repository->buscar($offset, $itemsPorPagina);
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return [];
    }
  }

  /**
   * Retorna dados dos serviços com informações de paginação
   * @return array{servicos: ServicoDTO[], totalRegistros: int, totalPaginas: int, paginaAtual: int}
   */
  public function buscarServicosComPaginacao(int $paginaAtual): array {
    try {
      $servicos = $this->buscarServicos($paginaAtual);
      $totalRegistros = $this->repository->contarTotal();
      $totalPaginas = ceil($totalRegistros / Paginacao::getItemsPorPagina());
      
      return [
        'servicos' => $servicos,
        'totalRegistros' => $totalRegistros,
        'totalPaginas' => $totalPaginas,
        'paginaAtual' => $paginaAtual
      ];
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return [
        'servicos' => [],
        'totalRegistros' => 0,
        'totalPaginas' => 0,
        'paginaAtual' => $paginaAtual
      ];
    }
  }

  // public function cadastrarServico(ServicoCadastroDTO $dto, ?array $foto): bool {
  //   try {
  //     if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
  //       $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
  //       $fotoFile = "img/servicos/" . uniqid() . "." . $ext;
  //       $caminhoAbsoluto = $_SERVER["DOCUMENT_ROOT"] . "/src/" . $fotoFile;
        
  //       if (!move_uploaded_file($foto['tmp_name'], $caminhoAbsoluto)) {
  //         throw new \Exception('Erro ao enviar imagem!');
  //       }
        
  //       $dto->fotoNome = $fotoFile;
  //     }

  //     return $this->repository->cadastrarServico($dto);
  //   } catch (\Exception $e) {
  //     // Se houver erro no upload da foto, tenta cadastrar sem a foto
  //     if ($dto->fotoNome) {
  //       $dto->fotoNome = null;
  //       return $this->repository->cadastrarServico($dto);
  //     }
  //     throw $e;
  //   }
  // }

  public function desativarServico(int $idServico, int $idUsuario): bool {
    try {
      return $this->repository->desativarServico($idServico, $idUsuario);
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return false;
    }
  }
}