<?php
namespace App\Utils;

use KissPhp\Support\Env;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenDecoded;
use Nowakowskir\JWT\TokenEncoded;

class TokenJwt {
  private static string $algoritimoDeCodificacao = 'HS256';
  private static ?string $secretKey = null;

  private static function getSecretKey(): string {
    if (self::$secretKey === null) {
      self::$secretKey = (string) Env::get('APP_SECRET_KEY');
    }
    return self::$secretKey;
  }

  public static function criarToken(array $payload) {
    $tokenEmJwt = new TokenDecoded($payload);

    $token = JWT::encode(
      $tokenEmJwt,
      self::getSecretKey(),
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
      self::getSecretKey(),
      self::$algoritimoDeCodificacao
    );
    return $eLegitmo;
  }
}