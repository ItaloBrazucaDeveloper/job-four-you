<?php
namespace App\Middlewares;

use KissPhp\Enums\FlashMessageType;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebMiddleware;

use App\Utils\TokenJwt;

class VerificaSeTokenAvaliacaoValido extends WebMiddleware {
  public function handle(Request $request, \Closure $next): ?Request {
    $token = $request->getQueryString('token');
    
    try {
      if (!TokenJwt::verificarSeELegitimo($token)) {
        $request->session->setFlashMessage(
          FlashMessageType::Error,
          'Link para avaliação inválido ou expirado! Peça outro link ao prestador.'
        );
        return $request->redirectTo('/');
      }
      return $next($request);
    } catch (\Throwable $th) {
      error_log("[Error] VerificaSeTokenAvaliacaoValido::handle: {$th->getMessage()}");
      return $request->redirectTo('/');
    }
  }
}