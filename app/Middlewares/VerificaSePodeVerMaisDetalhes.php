<?php
namespace App\Middlewares;

use App\Utils\SessionKeys;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebMiddleware;
use KissPhp\Enums\FlashMessageType;

class VerificaSePodeVerMaisDetalhes extends WebMiddleware {
  public function handle(Request $request, \Closure $next): ?Request {
    if ($request->session->has(SessionKeys::USUARIO_AUTENTICADO)) {
      return $next($request);
    }
    $request->session->setFlashMessage(FlashMessageType::Error, 'Você precisa estar logado para ver mais detalhes');
    return $request->redirectTo('/autenticacao');
  }
}