<?php
namespace App\Services\Avaliacao;

use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\{ TokenDecoded, TokenEncoded };

use App\Utils\TokenJwt;
use App\DTOs\Avaliacao\AvaliacaoCadastroDTO;
use App\Repositories\Avaliacao\AvaliacaoRepository;

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

  public function cadastrar(int $idDoUsuario, AvaliacaoCadastroDTO $dto): bool {
    return false;
  }
}