<?php
namespace App\Repositories\Avaliacao;

use App\Entities\Servico\AvaliacaoServico;
use KissPhp\Abstractions\Repository;

class AvaliacaoRepository extends Repository {
  public function cadastrar(AvaliacaoServico $avaliacao): bool {
    try {
      $this->database()->persist($avaliacao);
      $this->database()->flush();
      return true;
    } catch (\Throwable $th) {
      error_log("[Error] AvaliacaoRepository::cadastrar: {$th->getMessage()}");
      return false;
    }
  }

  public function buscar(): array {
    return [];
  }

  public function buscarUsuarioEPublicacao(int $idUsuario, int $idPublicacao): array {
    $usuario = $this->database()->getRepository(\App\Entities\Usuario::class)->find($idUsuario);
    $publicacao = $this->database()->getRepository(\App\Entities\Servico\PublicacaoServico::class)->find($idPublicacao);
    return [
      'usuario' => $usuario,
      'publicacao' => $publicacao
    ];
  }
}