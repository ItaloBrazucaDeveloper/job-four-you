<?php
namespace App\Repositories\Servicos;

use KissPhp\Abstractions\Repository;

use App\DTOs\CategoriaServicoDTO;
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
  public function buscar(int $offset, int $itemsPorPagina): ?array {
    try {
      $query = $this->database()
        ->createQueryBuilder()
        ->select('v')
        ->from(ViewPublicacao::class, 'v')
        ->setFirstResult($offset)
        ->setMaxResults($itemsPorPagina);

      $publicacoes = $query->getQuery()->getResult();

      return array_map(function($publicacao) {
        return $publicacao->toObject(ServicoDTO::class);
      }, $publicacoes);
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::buscarServicos: {$th->getMessage()}");
      throw new \Exception("Erro ao buscar serviços");
    }
  }

  public function contarTotal(): int {
    try {
      $query = $this->database()
        ->createQueryBuilder()
        ->select('COUNT(v.idPublicacao)')
        ->from(ViewPublicacao::class, 'v');

      return (int) $query->getQuery()->getSingleScalarResult();
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::contarTotal: {$th->getMessage()}");
      throw new \Exception("Erro ao contar total de serviços");
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
}
