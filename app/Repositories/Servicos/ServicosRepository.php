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
