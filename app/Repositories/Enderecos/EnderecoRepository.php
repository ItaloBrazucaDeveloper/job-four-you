<?php
namespace App\Repositories\Enderecos;

use App\Entities\Endereco;
use KissPhp\Abstractions\Repository;

class EnderecoRepository extends Repository {
  public function cadastrar(Endereco $endereco): Endereco {
    try {
      $this->database()->persist($endereco);
      $this->database()->flush();
      return $endereco;
    } catch (\Throwable $th) {
      error_log("[Error] EnderecoRepository::cadastrar: {$th->getMessage()}");
      throw new \Exception("Erro ao cadastrar endereço");
    }
  }

  public function buscarPorId(int $id): ?Endereco {
    try {
      return $this->database()
        ->getRepository(Endereco::class)
        ->findOneBy(['id' => $id]);
    } catch (\Throwable $th) {
      error_log("[Error] EnderecoRepository: {$th->getMessage()}");
      return null;
    }
  }
} 