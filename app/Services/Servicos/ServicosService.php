<?php
namespace App\Services\Servicos;

use App\Entities\Categorias\CategoriaServico;
use App\Repositories\Servicos\ServicosRepository;
use App\DTOs\Servicos\{ ServicoDTO, ServicoCadastroDTO };
use App\Utils\Paginacao;
use App\Utils\Paths;
use KissPhp\Core\Types\UploadedFile;

class ServicosService {
  public function __construct(private ServicosRepository $repository) { }

  public function favoritarServico(int $idServico, int $idUsuario): bool {
    try {
      return $this->repository->favoritarServico($idServico, $idUsuario);
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return false;
    }
  }

  public function desfavoritarServico(int $idServico, int $idUsuario): bool {
    try {
      return $this->repository->desfavoritarServico($idServico, $idUsuario);
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return false;
    }
  }

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

  public function cadastrar(ServicoCadastroDTO $servico, UploadedFile $foto): bool {
    try {
      if ($foto && $foto->getError() === UPLOAD_ERR_OK) {
        $nomeEncriptografado = hash('sha256', $foto->getName()) . '.' . $foto->getExtension();
        $caminhoDestino = Paths::PATH_TO_UPLOAD_FILE . $nomeEncriptografado;
        $foiMovido = $foto->move($caminhoDestino);
      }

      if (!$foiMovido) {
        throw new \Exception("Erro ao mover o arquivo para o destino.");
      }
      $servico->foto = $foiMovido ? $nomeEncriptografado : null;
      return $this->repository->cadastrar($servico);
    } catch (\Exception $e) {
      error_log($e->getMessage());
      return false;
    }
  }

  public function desativarServico(int $idServico, int $idUsuario): bool {
    try {
      return $this->repository->desativarServico($idServico, $idUsuario);
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return false;
    }
  }
}
