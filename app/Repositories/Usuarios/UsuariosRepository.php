<?php
namespace App\Repositories\Usuarios;

use KissPhp\Abstractions\Repository;

use App\Entities\{ Usuario, Endereco };
use App\DTOs\Usuario\UsuarioCadastroDTO;
use App\Repositories\Enderecos\EnderecoRepository;
use App\Repositories\Credenciais\CredencialRepository;

class UsuariosRepository extends Repository {
  public function __construct(
    private EnderecoRepository $enderecoRepository,
    private CredencialRepository $credencialRepository
  ) { }

  public function cadastrar(UsuarioCadastroDTO $usuarioDTO, string $senhaHash): int {
    try {
      $this->database()->getConnection()->beginTransaction();

      $endereco = (new Endereco())->fromObject($usuarioDTO->endereco);
      $endereco = $this->enderecoRepository->cadastrar($endereco);
      $credencial = $this->credencialRepository->cadastrar($usuarioDTO->email, $senhaHash);

      $usuario = new Usuario();
      $usuario->nome = $usuarioDTO->nome;
      $usuario->cpf = $usuarioDTO->cpf;
      $usuario->celular = $usuarioDTO->celular;
      $usuario->dataNascimento = new \DateTime($usuarioDTO->dataNascimento);
      $usuario->credencial = $credencial;
      $usuario->endereco = $endereco;

      $this->database()->persist($usuario);
      $this->database()->flush();

      $this->database()->getConnection()->commit();
      return $usuario->id;
    } catch (\Throwable $th) {
      if ($this->database()->getConnection()->isTransactionActive()) {
        $this->database()->getConnection()->rollBack();
      }
      error_log("[Error] UsuariosRepository::cadastrar: {$th->getMessage()}");
      throw new \Exception("Erro ao cadastrar usuário: {$th->getMessage()}");
    }
  }

  private function verificarNivelAcesso(int $idUsuario): void {
    try {
      $qb = $this->database()->getConnection()->createQueryBuilder();
      
      $resultado = $qb->select('COUNT(*) AS total')
        ->from('PublicacaoServico')
        ->where('FKUsuario = :idUsuario')
        ->andWhere('StatusPublicacao = :status')
        ->setParameters([
          'idUsuario' => $idUsuario,
          'status' => 'ATIVO'
        ])
        ->executeQuery()
        ->fetchAssociative();

      // Se não restar nenhum, volta a ser CLIENTE
      if ($resultado['total'] === 0) {
        $qb = $this->database()->getConnection()->createQueryBuilder();
        
        $qb->update('Credencial')
          ->set('FKNivelAcesso', ':nivel')
          ->where('ID = (SELECT FKCredencial FROM Usuario WHERE ID = :idUsuario)')
          ->setParameters([
            'nivel' => 3,
            'idUsuario' => $idUsuario
          ])
          ->executeStatement();
      }
    } catch (\Throwable $th) {
      error_log("[Error] ServicosRepository::verificarNivelAcesso: {$th->getMessage()}");
      throw new \Exception("Erro ao verificar nível de acesso");
    }
  }

  public function verificarTipoUsuario(int $idUsuario): string {
    try {
      $query = $this->database()->getConnection()
        ->createQueryBuilder()
        ->select('COUNT(*) as qtd')
        ->from('PublicacaoServico', 'p')
        ->where('p.FKUsuario = :idUsuario')
        ->setParameter('idUsuario', $idUsuario);

      $result = $query->executeQuery()->fetchAssociative();

      if ($result['qtd'] > 0) return "PRESTADOR";

      return "CLIENTE";
    } catch (\Throwable $th) {
      error_log("[Error] UsuariosRepository::verificarTipoUsuario: {$th->getMessage()}");
      throw new \Exception("Erro ao verificar tipo de usuário");
    }
  }

  public function buscarPorId(int $id): ?Usuario {
    try {
      $usuario = $this->database()->getReference(Usuario::class, $id);
    
      if (!$usuario) {
        error_log("[Error] UsuariosRepository::buscarPorId: Usuário não encontrado para o ID {$id}");
        return null;
      }
      return $usuario;
    } catch (\Throwable $th) {
      error_log("[Error] UsuariosRepository::buscarPorId: {$th->getMessage()}");
      return null;
    }
  }
}
