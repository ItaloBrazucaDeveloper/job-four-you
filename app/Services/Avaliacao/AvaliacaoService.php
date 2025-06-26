<?php
namespace App\Services\Avaliacao;

use App\Utils\TokenJwt;
use App\DTOs\Avaliacao\AvaliacaoCadastroDTO;
use App\Repositories\Avaliacao\AvaliacaoRepository;
use App\Entities\Servico\AvaliacaoServico;
use App\Entities\Servico\PublicacaoServico;
use App\Entities\Usuario;

class AvaliacaoService {
  private int $tempoDeExpiracaoEmSegundos = 1800;

  public function __construct(private AvaliacaoRepository $repository) {  }

  public function criarToken(int $idDaPublicacao): string {
    $agora = time();
    $expiraEm = $agora + $this->tempoDeExpiracaoEmSegundos;

    return TokenJwt::criarToken([
      'iat' => $agora,
      'exp' => $expiraEm,
      'puid' => $idDaPublicacao
    ]);
  }

  public function obterIdDaPublicacaoPeloToken(string $token): int {
    if ($this->verificarSeExpirou($token)) return 0;
    $payload = TokenJwt::obterPayload($token);
    return (int) $payload['puid'] ?? 0;
  }

  private function verificarSeExpirou(string $token): bool {
    $payload = TokenJwt::obterPayload($token);
    $jaExpirou = isset($payload['exp']) && $payload['exp'] < time();
    return $jaExpirou;
  }

  public function cadastrar(int $idDoUsuario, int $idPublicacao, AvaliacaoCadastroDTO $dto): bool {
    try {
      // Buscar usuário e publicação
      $dados = $this->repository->buscarUsuarioEPublicacao($idDoUsuario, $idPublicacao);
      $usuario = $dados['usuario'];
      $publicacao = $dados['publicacao'];
      if (!$usuario || !$publicacao) return false;

      // Criar avaliação
      $avaliacao = new AvaliacaoServico();
      $avaliacao->nota = $dto->nota;
      $avaliacao->comentario = $dto->descricao;
      $avaliacao->usuario = $usuario;
      $avaliacao->publicacao = $publicacao;
      $avaliacao->dataCriacao = new \DateTime();

      return $this->repository->cadastrar($avaliacao);
    } catch (\Throwable $th) {
      error_log("[Error] AvaliacaoService::cadastrar: {$th->getMessage()}");
      return false;
    }
  }
}