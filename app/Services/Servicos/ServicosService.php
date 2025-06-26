<?php
namespace App\Services\Servicos;

use KissPhp\Core\Types\UploadedFile;

use App\Utils\{ Paginacao, Paths };
use App\Services\Usuarios\UsuariosService;
use App\Entities\Categorias\CategoriaServico;
use App\Repositories\Servicos\ServicosRepository;
use App\DTOs\Servicos\{MaisDetalhesDTO, ServicoDTO, ServicoCadastroDTO, FiltrosServicoDTO, FiltroAtivoDTO };

class ServicosService {
  public function __construct(
    private ServicosRepository $repository,
    private UsuariosService $usuariosService
  ) { }

  public function favoritarServico(int $idServico, int $idUsuario): array {
    try {
      $foiFavoritado = $this->repository->favoritarServico($idServico, $idUsuario);
      if (!$foiFavoritado) throw new \Exception();
      
      return ['sucesso' => $foiFavoritado];
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return ['mensagem' => $th->getMessage()];
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

  /**
   * Retorna dados dos serviços com informações de paginação
   * @return array{servicos: ServicoDTO[], totalRegistros: int, totalPaginas: int, paginaAtual: int}
   */
  public function buscarServicosComPaginacao(int $paginaAtual, ?int $idUsuario = null, ?FiltrosServicoDTO $filtros = null): array {
    try {
      $offset = Paginacao::getOffset($paginaAtual);
      $itemsPorPagina = Paginacao::getItemsPorPagina();
      $servicos = $this->repository->buscar($offset, $itemsPorPagina, $filtros);

      // Obter IDs dos serviços favoritos do usuário
      $favoritosIds = [];
      if ($idUsuario && $this->usuariosService) {
        $favoritos = $this->usuariosService->obterServicosFavoritos($idUsuario);
        if ($favoritos) {
          $favoritosIds = array_map(function($servico) {
            return is_object($servico) && property_exists($servico, 'id') ? $servico->id : (is_array($servico) && isset($servico['id']) ? $servico['id'] : null);
          }, $favoritos);
        }
      }

      // Adicionar campo favoritado em cada serviço (sem modificar readonly)
      $servicos = array_map(function($servico) use ($favoritosIds) {
        // Converter para array se for objeto
        $dados = is_object($servico) ? get_object_vars($servico) : $servico;
        $dados['favoritado'] = in_array($dados['idPublicacao'], $favoritosIds);
        return $dados;
      }, $servicos);

      $totalRegistros = $this->repository->contarTotal($filtros);
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

  /**
   * Processa os filtros ativos baseado nos parâmetros recebidos
   * @return FiltroAtivoDTO[]
   */
  public function processarFiltrosAtivos(FiltrosServicoDTO $filtros): array {
    $filtrosAtivos = [];

    // Filtro de categoria
    if (!empty($filtros->categoria) && $filtros->categoria !== '0') {
      $categoria = $this->buscarCategoriaPorId($filtros->categoria);
      if ($categoria) {
        $filtrosAtivos[] = new FiltroAtivoDTO(
          'Categoria',
          $categoria->nome,
          'categoria',
          $filtros->categoria
        );
      }
    }

    // Filtros de estado
    foreach ($filtros->estado as $estado) {
      $nomeEstado = $this->obterNomeEstado($estado);
      $filtrosAtivos[] = new FiltroAtivoDTO(
        'Estado',
        $nomeEstado,
        'estado',
        $estado
      );
    }

    // Filtros de valor
    foreach ($filtros->valor as $valor) {
      $nomeValor = $this->obterNomeFaixaValor($valor);
      $filtrosAtivos[] = new FiltroAtivoDTO(
        'Preço',
        $nomeValor,
        'valor',
        $valor
      );
    }

    // Filtro de prestador
    if (!empty($filtros->prestador)) {
      $filtrosAtivos[] = new FiltroAtivoDTO(
        'Prestador',
        $filtros->prestador,
        'prestador',
        $filtros->prestador
      );
    }

    return $filtrosAtivos;
  }

  /**
   * Busca categoria por ID
   */
  private function buscarCategoriaPorId(string $id): ?\App\DTOs\CategoriaServicoDTO {
    try {
      $categorias = $this->repository->buscarCategorias();
      foreach ($categorias as $categoria) {
        if ($categoria->id == $id) {
          return $categoria;
        }
      }
      return null;
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return null;
    }
  }

  /**
   * Obtém o nome completo do estado pela sigla
   */
  private function obterNomeEstado(string $sigla): string {
    $estados = [
      'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
      'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal',
      'ES' => 'Espírito Santo', 'GO' => 'Goiás', 'MA' => 'Maranhão',
      'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul', 'MG' => 'Minas Gerais',
      'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná', 'PE' => 'Pernambuco',
      'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
      'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima',
      'SC' => 'Santa Catarina', 'SP' => 'São Paulo', 'SE' => 'Sergipe',
      'TO' => 'Tocantins'
    ];

    return $estados[$sigla] ?? $sigla;
  }

  /**
   * Obtém o nome da faixa de valor pelo código
   */
  private function obterNomeFaixaValor(string $codigo): string {
    $faixas = [
      '0-50' => 'Até R$ 50,00',
      '50-100' => 'R$ 50,00 - R$ 100,00',
      '100-200' => 'R$ 100,00 - R$ 200,00',
      '200-500' => 'R$ 200,00 - R$ 500,00',
      '500-1000' => 'R$ 500,00 - R$ 1.000,00',
      '1000-plus' => 'Acima de R$ 1.000,00'
    ];

    return $faixas[$codigo] ?? $codigo;
  }

  public function cadastrar(ServicoCadastroDTO $servico): bool {
    try {
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

  public function buscarMaisDetalhesDoServico(int $id): ?MaisDetalhesDTO {
    $dados = $this->repository->obterMaisDetalhes($id);
    if (!$dados) return null;
    return new MaisDetalhesDTO(
      $dados['foto'],
      $dados['prestador'],
      $dados['titulo'],
      $dados['descricacao'],
      $dados['contatos'],
      $dados['quantiadadeEstrelas'],
      $dados['mediaAvaliacoes'],
      $dados['avaliacoes']
    );
    return $dto;
  }
}
