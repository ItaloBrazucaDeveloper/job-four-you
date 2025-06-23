<?php
namespace App\Middlewares;

use App\Utils\SessionKeys;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebMiddleware;

class VerificaSeUsuarioNaoLogado extends WebMiddleware {
  public function handle(Request $request, \Closure $next): ?Request {
    if (!$request->session->has(SessionKeys::USUARIO_AUTENTICADO)) {
      return $next($request);
    }
    return $request->redirectTo('/');
  }
}