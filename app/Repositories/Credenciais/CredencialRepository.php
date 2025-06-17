<?php
namespace App\Repositories\Credenciais;

use KissPhp\Abstractions\Repository;

use App\Entities\{ Credencial, NivelAcesso };

class CredencialRepository extends Repository {
  public function verificarEmailExistente(string $email): bool {
    try {
      return $this->buscarPorEmail($email) !== null;
    } catch (\Throwable $th) {
      error_log("[Error] CredencialRepository::verificarEmailExistente: {$th->getMessage()}");
      throw new \Exception("Erro ao verificar email existente");
    }
  }

  public function cadastrar(string $email, string $senha): Credencial {
    try {
      $credencial = new Credencial();
      $credencial->email = $email;
      $credencial->senha = $senha;
      $credencial->nivelAcesso = $this->database()
        ->getRepository(NivelAcesso::class)
        ->find(3); // CLIENTE

      $this->database()->persist($credencial);
      $this->database()->flush();

      return $credencial;
    } catch (\Throwable $th) {
      error_log("[Error] CredencialRepository::cadastrar: {$th->getMessage()}");
      throw new \Exception("Erro ao cadastrar credencial");
    }
  }

  public function atualizarNivelAcesso(int $idUsuario, int $nivelAcesso): void {
    try {
      $query = $this->database()->getConnection()
        ->createQueryBuilder()
        ->update('Credencial')
        ->set('FKNivelAcesso', ':nivelAcesso')
        ->where('ID = (SELECT FKCredencial FROM Usuario WHERE ID = :idUsuario)')
        ->setParameter('nivelAcesso', $nivelAcesso)
        ->setParameter('idUsuario', $idUsuario);

      $query->executeQuery();
    } catch (\Throwable $th) {
      error_log("[Error] CredencialRepository::atualizarNivelAcesso: {$th->getMessage()}");
      throw new \Exception("Erro ao atualizar nível de acesso");
    }
  }

  public function buscarPorEmail(string $email): ?Credencial {
    try {
      $credencial = $this->database()
        ->getRepository(Credencial::class)
        ->find(['email' => $email]);
      return $credencial;
    } catch (\Throwable $th) {
      error_log("[Error] CredencialRepository: {$th->getMessage()}");
      return null;
    }
  }
} 