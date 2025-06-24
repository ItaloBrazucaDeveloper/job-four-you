<?php
namespace App\Utils;

use KissPhp\Support\Env;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenDecoded;
use Nowakowskir\JWT\TokenEncoded;

class TokenJwt {
  private static string $algoritimoDeCodificacao = 'HS256';
  private static string $secretKey = (string) Env::get('APP_SECRET_KEY');

  public static function criarToken(array $payload) {
    $tokenEmJwt = new TokenDecoded($payload);

    $token = JWT::encode(
      $tokenEmJwt,
      self::$secretKey,
      self::$algoritimoDeCodificacao
    );
    return $token->toString();
  }

  public static function obterPayload(string $tokenEmString): array {
    $tokenEmJwt = new TokenEncoded($tokenEmString);
    $tokenDecodificado = JWT::decode($tokenEmJwt);
    return $tokenDecodificado->getPayload();
  }

  public static function verificarSeELegitimo(string $tokenEmString): bool {
    $tokenEmJwt = new TokenEncoded($tokenEmString);
    $eLegitmo = JWT::validate(
      $tokenEmJwt,
      self::$secretKey,
      self::$algoritimoDeCodificacao
    );
    return $eLegitmo;
  }
}