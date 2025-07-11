<?php
namespace App\Repositories\Servicos;

use KissPhp\Abstractions\Repository;

use App\DTOs\CategoriaServicoDTO;
use App\DTOs\Servicos\FiltrosServicoDTO;
use App\Entities\Views\ViewPublicacao;
use App\Entities\Categorias\CategoriaServico;
use App\DTOs\Servicos\{ ServicoCadastroDTO, ServicoDTO };
use App\Entities\Servico\PublicacaoServico;
use App\Entities\Usuario;

class ServicosRepository extends Repository {
  public function favoritarServico(int $idServico, int $idUsuario): bool {
    try {
      $qb = $this->database()->getConnection()->createQueryBuilder();
      
      $qb->insert('ServicoFavorito')
        ->values([
          'IDUsuario' => ':idUsuario',
          'IDServico' => ':idServico'
        ])
        ->setParameters([
          'idUsuario' => $idUsuario,
          'idServico' => $idServico
        ])
        ->executeStatement();
      
      return true;
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::favoritarServico: {$th->getMessage()}");
      throw new \Exception("Erro ao favoritar serviço");
    }
  }

  public function desfavoritarServico(int $idServico, int $idUsuario): bool {
    try {
      $qb = $this->database()->getConnection()->createQueryBuilder();
      
      $qb->delete('ServicoFavorito')
        ->where('IDUsuario = :idUsuario')
        ->andWhere('IDServico = :idServico')
        ->setParameters([
          'idUsuario' => $idUsuario,
          'idServico' => $idServico
        ])
        ->executeStatement();
      
      return true;
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::desfavoritarServico: {$th->getMessage()}");
      throw new \Exception("Erro ao desfavoritar serviço");
    }
  }

  /** @return CategoriaServicoDTO[] */
  public function buscarCategorias(): array {
    try {
      /** @var CategoriaServico[] $categorias */
      $categorias = $this->database()
        ->getRepository(CategoriaServico::class)
        ->findAll();
      
      return array_map(
        fn($categoria) => $categoria->toObject(CategoriaServicoDTO::class),
        $categorias
      );
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::buscarCategorias: {$th->getMessage()}");
      throw new \Exception("Erro ao buscar categorias");
    }
  }

  /** @return ServicoDTO[] */
  public function buscar(int $offset, int $itemsPorPagina, ?FiltrosServicoDTO $filtros = null): ?array {
    try {
      $query = $this->database()
        ->createQueryBuilder()
        ->select('v')
        ->from(ViewPublicacao::class, 'v');

      // Aplicar filtros se fornecidos
      if ($filtros && $filtros->temFiltros()) {
        $this->aplicarFiltros($query, $filtros);
      }
      $query->setFirstResult($offset)->setMaxResults($itemsPorPagina);
      $publicacoes = $query->getQuery()->getResult();

      return array_map(function($publicacao) {
        return $publicacao->toObject(ServicoDTO::class);
      }, $publicacoes);
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::buscarServicos: {$th->getMessage()}");
      throw new \Exception("Erro ao buscar serviços");
    }
  }

  public function contarTotal(?FiltrosServicoDTO $filtros = null): int {
    try {
      $query = $this->database()
        ->createQueryBuilder()
        ->select('COUNT(v.idPublicacao)')
        ->from(ViewPublicacao::class, 'v');

      // Aplicar filtros se fornecidos
      if ($filtros && $filtros->temFiltros()) {
        $this->aplicarFiltros($query, $filtros);
      }

      return (int) $query->getQuery()->getSingleScalarResult();
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::contarTotal: {$th->getMessage()}");
      throw new \Exception("Erro ao contar total de serviços");
    }
  }

  /**
   * Aplica filtros à query
   */
  private function aplicarFiltros($query, FiltrosServicoDTO $filtros): void {
    $paramIndex = 1;

    // Filtro de categoria
    if (!empty($filtros->categoria) && $filtros->categoria !== '0') {
      // Buscar o nome da categoria pelo ID
      $categoria = $this->buscarCategoriaPorId($filtros->categoria);
      if ($categoria) {
        $query->andWhere('v.categoria = :categoria' . $paramIndex)
          ->setParameter('categoria' . $paramIndex, $categoria->nome);
        $paramIndex++;
      }
    }

    // Filtros de estado
    if (!empty($filtros->estado)) {
      $estados = [];
      foreach ($filtros->estado as $estado) {
        $estados[] = ':estado' . $paramIndex;
        $query->setParameter('estado' . $paramIndex, $estado);
        $paramIndex++;
      }
      $query->andWhere('v.estado IN (' . implode(',', $estados) . ')');
    }

    // Filtros de valor
    if (!empty($filtros->valor)) {
      $valorConditions = [];
      foreach ($filtros->valor as $valor) {
        $valorConditions[] = $this->criarCondicaoValor($query, $valor, $paramIndex);
        $paramIndex++;
      }
      if (!empty($valorConditions)) {
        $query->andWhere('(' . implode(' OR ', $valorConditions) . ')');
      }
    }

    // Filtro de prestador (busca por nome)
    if (!empty($filtros->prestador)) {
      $query->andWhere('v.nomeUsuario LIKE :prestador' . $paramIndex)
        ->setParameter('prestador' . $paramIndex, '%' . $filtros->prestador . '%');
    }
  }

  /**
   * Busca categoria por ID
   */
  private function buscarCategoriaPorId(string $id): ?CategoriaServicoDTO {
    try {
      $categorias = $this->buscarCategorias();
      foreach ($categorias as $categoria) {
        if ($categoria->id == $id) {
          return $categoria;
        }
      }
      return null;
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::buscarCategoriaPorId: {$th->getMessage()}");
      return null;
    }
  }

  /**
   * Cria condição para filtro de valor baseado na faixa
   */
  private function criarCondicaoValor($query, string $valor, int $paramIndex): string {
    switch ($valor) {
      case '0-50':
        return 'v.valor <= 50.00';
      case '50-100':
        return 'v.valor >= 50.00 AND v.valor <= 100.00';
      case '100-200':
        return 'v.valor >= 100.00 AND v.valor <= 200.00';
      case '200-500':
        return 'v.valor >= 200.00 AND v.valor <= 500.00';
      case '500-1000':
        return 'v.valor >= 500.00 AND v.valor <= 1000.00';
      case '1000-plus':
        return 'v.valor > 1000.00';
      default:
        return '1=1'; // Sem filtro
    }
  }

  public function cadastrar(ServicoCadastroDTO $dto): bool {
    try {
      $categoria = $this->database()
        ->getRepository(CategoriaServico::class)
        ->find($dto->categoria);

      if (!$categoria) {
        throw new \Exception("Categoria não encontrada");
      }

      $servico = new PublicacaoServico();
      $servico->titulo = $dto->titulo;
      $servico->sobre = $dto->descricao;
      $servico->valor = $dto->preco;
      $servico->categoria = $categoria;
      $servico->usuario = $this->database()->getReference(Usuario::class, (int) $dto->usuario);

      $this->database()->persist($servico);
      $this->database()->flush();
      return true;
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::cadastrarServico: {$th->getMessage()}");
      throw new \Exception("Erro ao cadastrar serviço");
    }
  }

  public function desativarServico(int $idServico, int $idUsuario): bool {
    try {
      $qb = $this->database()->getConnection()->createQueryBuilder();
      
      $qb->update('PublicacaoServico')
        ->set('StatusPublicacao', ':status')
        ->where('ID = :idServico')
        ->andWhere('FKUsuario = :idUsuario')
        ->setParameters([
          'status' => 'INATIVO',
          'idServico' => $idServico,
          'idUsuario' => $idUsuario
        ])
        ->executeStatement();
      
      return true;
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::desativarServico: {$th->getMessage()}");
      throw new \Exception("Erro ao desativar serviço");
    }
  }

  public function obterMaisDetalhes(int $idServico): ?array {
    try {
      // Buscar dados principais do serviço na ViewPublicacao
      $view = $this->database()
        ->getRepository(\App\Entities\Views\ViewPublicacao::class)
        ->findOneBy(['idPublicacao' => $idServico]);
      if (!$view) return null;

      // Buscar entidade PublicacaoServico para acessar relações
      $publicacao = $this->database()
        ->getRepository(\App\Entities\Servico\PublicacaoServico::class)
        ->find($idServico);
      if (!$publicacao) return null;

      // Buscar contatos do prestador
      $usuario = $publicacao->usuario;
      $contatos = [];
      foreach ($usuario->informacoesContato as $contato) {
        $contatos[] = [
          'tipo' => $contato->categoria->nome,
          'valor' => $contato->contato
        ];
      }

      // Buscar avaliações do serviço
      $avaliacoes = [];
      $quantidadeEstrelas = 0;
      if ($publicacao->avaliacoes && count($publicacao->avaliacoes) > 0) {
        foreach ($publicacao->avaliacoes as $avaliacao) {
          $avaliacoes[] = [
            'nota' => $avaliacao->nota,
            'comentario' => $avaliacao->comentario,
            'nome' => $avaliacao->usuario->nome,
            'foto_perfil' => $avaliacao->usuario->foto,
            'cargo' => $avaliacao->usuario->credencial->tipo ?? 'Usuário',
          ];
          $quantidadeEstrelas++;
        }
      }

      return [
        'foto' => $view->fotoUsuario,
        'prestador' => $view->nomeUsuario,
        'titulo' => $view->titulo,
        'descricacao' => $view->sobre,
        'contatos' => $contatos,
        'quantiadadeEstrelas' => $quantidadeEstrelas,
        'mediaAvaliacoes' => $view->mediaAvaliacoes,
        'avaliacoes' => $avaliacoes,
      ];
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::obterMaisDetalhes: {$th->getMessage()}");
      return null;
    }
  }
}
