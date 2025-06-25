<?php
namespace App\Repositories\Admin;

use KissPhp\Abstractions\Repository;
use App\Entities\{ Usuario, Servico\PublicacaoServico };
use App\Entities\Status\{ StatusUsuario, StatusPublicacao };
use App\DTOs\Admin\{ DashboardDTO, UsuarioAdminDTO, SolicitacaoAdminDTO };

class AdminRepository extends Repository {
  
  public function obterDadosDashboard(): DashboardDTO {
    try {
      // Total de usuários
      $totalUsuarios = $this->database()->getRepository(Usuario::class)->count([]);
      
      // Total de prestadores (usuários com nível PRESTADOR)
      $totalPrestadores = $this->database()->createQueryBuilder()
        ->select('COUNT(u.id)')
        ->from(Usuario::class, 'u')
        ->join('u.credencial', 'c')
        ->join('c.nivelAcesso', 'n')
        ->where('n.grupo = :grupo')
        ->setParameter('grupo', 'PRESTADOR')
        ->getQuery()
        ->getSingleScalarResult();
      
      // Total de serviços ativos
      $totalServicos = $this->database()->getRepository(PublicacaoServico::class)
        ->count(['statusPublicacao' => StatusPublicacao::ATIVO]);
      
      // Total de aprovações pendentes
      $totalAprovacoes = $this->database()->getRepository(PublicacaoServico::class)
        ->count(['statusPublicacao' => StatusPublicacao::EM_ANALISE]);
      
      // Notificações recentes (últimos 5 usuários cadastrados)
      $notificacoes = $this->database()->createQueryBuilder()
        ->select('u.nome, u.dataCriacao')
        ->from(Usuario::class, 'u')
        ->orderBy('u.dataCriacao', 'DESC')
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();
      
      $notificacoesFormatadas = array_map(function($notif) {
        return [
          'mensagem' => "Novo usuário cadastrado: {$notif['nome']}",
          'data' => $notif['dataCriacao']->format('d/m/Y H:i')
        ];
      }, $notificacoes);
      
      // Últimos serviços cadastrados
      $ultimosServicos = $this->database()->createQueryBuilder()
        ->select('p.titulo, u.nome as prestador, p.statusPublicacao')
        ->from(PublicacaoServico::class, 'p')
        ->join('p.usuario', 'u')
        ->orderBy('p.dataCriacao', 'DESC')
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();
      
      $ultimosServicosFormatados = array_map(function($servico) {
        return [
          'titulo' => $servico['titulo'],
          'prestador' => $servico['prestador'],
          'status' => $servico['statusPublicacao']->value
        ];
      }, $ultimosServicos);
      
      // Aprovações de prestadores pendentes
      $aprovacoesPrestadores = $this->database()->createQueryBuilder()
        ->select('u.nome, c.nome as categoria')
        ->from(Usuario::class, 'u')
        ->join('u.credencial', 'cr')
        ->join('cr.nivelAcesso', 'n')
        ->leftJoin('u.publicacoesServico', 'p')
        ->leftJoin('p.categoria', 'c')
        ->where('n.grupo = :grupo')
        ->andWhere('u.statusUsuario = :status')
        ->setParameter('grupo', 'PRESTADOR')
        ->setParameter('status', StatusUsuario::ATIVO)
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();
      
      $aprovacoesFormatadas = array_map(function($prestador) {
        return [
          'nome' => $prestador['nome'],
          'categoria' => $prestador['categoria'] ?? 'Não definida'
        ];
      }, $aprovacoesPrestadores);
      
      return new DashboardDTO(
        $totalUsuarios,
        $totalPrestadores,
        $totalServicos,
        $totalAprovacoes,
        $notificacoesFormatadas,
        $ultimosServicosFormatados,
        $aprovacoesFormatadas
      );
    } catch (\Throwable $th) {
      error_log("[Error] AdminRepository::obterDadosDashboard: {$th->getMessage()}");
      throw new \Exception("Erro ao obter dados do dashboard: {$th->getMessage()}");
    }
  }
  
  public function listarUsuarios(int $pagina = 1, int $limite = 10, ?string $filtro = null): array {
    try {
      $offset = ($pagina - 1) * $limite;
      
      $qb = $this->database()->createQueryBuilder()
        ->select('u.id, u.nome, u.cpf, u.foto, u.statusUsuario, COUNT(p.id) as totalServicos')
        ->from(Usuario::class, 'u')
        ->leftJoin('u.publicacoesServico', 'p')
        ->groupBy('u.id');
      
      if ($filtro) {
        $qb->andWhere('u.nome LIKE :filtro OR u.cpf LIKE :filtro')
           ->setParameter('filtro', "%{$filtro}%");
      }
      
      $usuarios = $qb->setFirstResult($offset)
                     ->setMaxResults($limite)
                     ->getQuery()
                     ->getResult();
      
      $dados = [];
      foreach ($usuarios as $usuario) {
        // Calcular avaliação média
        $avaliacao = $this->calcularAvaliacaoMedia($usuario['id']);
        
        $dados[] = new UsuarioAdminDTO(
          $usuario['id'],
          $usuario['nome'],
          $usuario['cpf'],
          null, // especialidade
          $usuario['totalServicos'],
          $avaliacao['media'],
          $avaliacao['total'],
          $usuario['statusUsuario']->value,
          $usuario['foto']
        );
      }
      
      return $dados;
    } catch (\Throwable $th) {
      error_log("[Error] AdminRepository::listarUsuarios: {$th->getMessage()}");
      throw new \Exception("Erro ao listar usuários: {$th->getMessage()}");
    }
  }
  
  public function contarUsuarios(?string $filtro = null): int {
    try {
      $qb = $this->database()->createQueryBuilder()
        ->select('COUNT(u.id)')
        ->from(Usuario::class, 'u');
      
      if ($filtro) {
        $qb->where('u.nome LIKE :filtro OR u.cpf LIKE :filtro')
           ->setParameter('filtro', "%{$filtro}%");
      }
      
      return $qb->getQuery()->getSingleScalarResult();
    } catch (\Throwable $th) {
      error_log("[Error] AdminRepository::contarUsuarios: {$th->getMessage()}");
      return 0;
    }
  }
  
  public function listarSolicitacoesServicos(int $pagina = 1, int $limite = 10): array {
    try {
      $offset = ($pagina - 1) * $limite;
      
      $servicos = $this->database()->createQueryBuilder()
        ->select('p.id, p.titulo as nome, u.nome as prestadorNome, c.nome as categoriaNome, p.valor, p.dataCriacao')
        ->from(PublicacaoServico::class, 'p')
        ->join('p.usuario', 'u')
        ->join('p.categoria', 'c')
        ->where('p.statusPublicacao = :status')
        ->setParameter('status', StatusPublicacao::EM_ANALISE)
        ->orderBy('p.dataCriacao', 'DESC')
        ->setFirstResult($offset)
        ->setMaxResults($limite)
        ->getQuery()
        ->getResult();
      
      $dados = [];
      foreach ($servicos as $servico) {
        $dados[] = new SolicitacaoAdminDTO(
          $servico['id'],
          $servico['nome'],
          $servico['prestadorNome'],
          $servico['categoriaNome'],
          (float) $servico['valor'],
          $servico['dataCriacao']->format('d/m/Y H:i'),
          'servico'
        );
      }
      
      return $dados;
    } catch (\Throwable $th) {
      error_log("[Error] AdminRepository::listarSolicitacoesServicos: {$th->getMessage()}");
      throw new \Exception("Erro ao listar solicitações de serviços: {$th->getMessage()}");
    }
  }
  
  public function listarSolicitacoesPrestadores(int $pagina = 1, int $limite = 10): array {
    try {
      $offset = ($pagina - 1) * $limite;
      
      $prestadores = $this->database()->createQueryBuilder()
        ->select('u.id, u.nome, u.cpf, u.dataCriacao')
        ->from(Usuario::class, 'u')
        ->join('u.credencial', 'c')
        ->join('c.nivelAcesso', 'n')
        ->where('n.grupo = :grupo')
        ->andWhere('u.statusUsuario = :status')
        ->setParameter('grupo', 'PRESTADOR')
        ->setParameter('status', StatusUsuario::ATIVO)
        ->orderBy('u.dataCriacao', 'DESC')
        ->setFirstResult($offset)
        ->setMaxResults($limite)
        ->getQuery()
        ->getResult();
      
      $dados = [];
      foreach ($prestadores as $prestador) {
        $dados[] = new SolicitacaoAdminDTO(
          $prestador['id'],
          $prestador['nome'],
          $prestador['nome'],
          'Prestador',
          0.0,
          $prestador['dataCriacao']->format('d/m/Y H:i'),
          'prestador',
          null // link documentos
        );
      }
      
      return $dados;
    } catch (\Throwable $th) {
      error_log("[Error] AdminRepository::listarSolicitacoesPrestadores: {$th->getMessage()}");
      throw new \Exception("Erro ao listar solicitações de prestadores: {$th->getMessage()}");
    }
  }
  
  public function aprovarServico(int $servicoId): bool {
    try {
      $this->database()->getConnection()->beginTransaction();
      
      $servico = $this->database()->find(PublicacaoServico::class, $servicoId);
      
      if (!$servico) {
        throw new \Exception("Serviço não encontrado");
      }
      
      $servico->statusPublicacao = StatusPublicacao::ATIVO;
      $servico->ultimaAtualizacao = new \DateTime();
      
      $this->database()->persist($servico);
      $this->database()->flush();
      
      $this->database()->getConnection()->commit();
      return true;
    } catch (\Throwable $th) {
      if ($this->database()->getConnection()->isTransactionActive()) {
        $this->database()->getConnection()->rollBack();
      }
      error_log("[Error] AdminRepository::aprovarServico: {$th->getMessage()}");
      return false;
    }
  }
  
  public function rejeitarServico(int $servicoId): bool {
    try {
      $this->database()->getConnection()->beginTransaction();
      
      $servico = $this->database()->find(PublicacaoServico::class, $servicoId);
      
      if (!$servico) {
        throw new \Exception("Serviço não encontrado");
      }
      
      $servico->statusPublicacao = StatusPublicacao::REJEITADO;
      $servico->ultimaAtualizacao = new \DateTime();
      
      $this->database()->persist($servico);
      $this->database()->flush();
      
      $this->database()->getConnection()->commit();
      return true;
    } catch (\Throwable $th) {
      if ($this->database()->getConnection()->isTransactionActive()) {
        $this->database()->getConnection()->rollBack();
      }
      error_log("[Error] AdminRepository::rejeitarServico: {$th->getMessage()}");
      return false;
    }
  }
  
  public function alterarStatusUsuario(int $usuarioId, StatusUsuario $status): bool {
    try {
      $this->database()->getConnection()->beginTransaction();
      
      $usuario = $this->database()->find(Usuario::class, $usuarioId);
      
      if (!$usuario) {
        throw new \Exception("Usuário não encontrado");
      }
      
      $usuario->statusUsuario = $status;
      $usuario->ultimaAtualizacao = new \DateTime();
      
      $this->database()->persist($usuario);
      $this->database()->flush();
      
      $this->database()->getConnection()->commit();
      return true;
    } catch (\Throwable $th) {
      if ($this->database()->getConnection()->isTransactionActive()) {
        $this->database()->getConnection()->rollBack();
      }
      error_log("[Error] AdminRepository::alterarStatusUsuario: {$th->getMessage()}");
      return false;
    }
  }
  
  private function calcularAvaliacaoMedia(int $usuarioId): array {
    try {
      $resultado = $this->database()->createQueryBuilder()
        ->select('AVG(a.nota) as media, COUNT(a.id) as total')
        ->from('App\Entities\Servico\AvaliacaoServico', 'a')
        ->join('a.publicacao', 'p')
        ->where('p.usuario = :usuarioId')
        ->setParameter('usuarioId', $usuarioId)
        ->getQuery()
        ->getSingleResult();
      
      return [
        'media' => $resultado['media'] ? (float) $resultado['media'] : 0.0,
        'total' => (int) $resultado['total']
      ];
    } catch (\Throwable $th) {
      return ['media' => 0.0, 'total' => 0];
    }
  }
} 