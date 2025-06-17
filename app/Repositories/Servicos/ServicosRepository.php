<?php
namespace App\Repositories\Servicos;

use KissPhp\Abstractions\Repository;

use App\DTOs\CategoriaServicoDTO;
use App\Entities\Views\ViewPublicacao;
use App\Entities\Categorias\CategoriaServico;
use App\DTOs\Servicos\{ ServicoCadastroDTO, ServicoDTO };

class ServicosRepository extends Repository {
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
  public function buscarServicos(?int $categoriaId = null): array {
    try {
      $query = $this->database()
        ->getConnection()
        ->createQueryBuilder()
        ->select('*')
        ->from('ViewPublicacao', 'vp');

      if ($categoriaId) {
        $query->where('vp.Categoria = :categoriaId')->setParameter('categoriaId', $categoriaId);
      }
      $resultados = $query->executeQuery()->fetchAllAssociative();

      return array_map(
        fn($row) => (new ViewPublicacao())->fromObject((object) $row)->toObject(ServicoDTO::class),
        $resultados
      );
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::buscarServicos: {$th->getMessage()}");
      throw new \Exception("Erro ao buscar serviços");
    }
  }

  public function cadastrar(ServicoCadastroDTO $dto): bool {
    try {
      $qb = $this->database()->getConnection()->createQueryBuilder();
      
      $qb->insert('PublicacaoServico')
        ->values([
          'Titulo' => ':titulo',
          'Sobre' => ':descricao',
          'Valor' => ':preco',
          'FKCategoria' => ':categoriaServico',
          'FKUsuario' => ':idUsuario',
          'StatusPublicacao' => ':status',
          'Foto' => ':fotoNome'
        ])
        ->setParameters([
          'titulo' => $dto->titulo,
          'descricao' => $dto->descricao,
          'preco' => $dto->preco,
          'categoriaServico' => $dto->categoriaServico,
          'idUsuario' => $dto->idUsuario,
          'status' => 'EM_ANALISE',
          'fotoNome' => $dto->fotoNome
        ])
        ->executeStatement();
      
      // Verifica se precisa atualizar o nível de acesso
      $this->atualizarNivelAcesso($dto->idUsuario);
      
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
      
      // Verifica se precisa voltar para CLIENTE
      $this->verificarNivelAcesso($idUsuario);
      
      return true;
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::desativarServico: {$th->getMessage()}");
      throw new \Exception("Erro ao desativar serviço");
    }
  }
}