<?php
namespace App\Middlewares;

use App\Utils\SessionKeys;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebMiddleware;

class VerificaSeUsuarioLogado extends WebMiddleware {
  public function handle(Request $request, \Closure $next): ?Request {
    echo json_encode([
      'autenticado' => $request->session->has(SessionKeys::USUARIO_AUTENTICADO)
    ]);
    die();
    if ($request->session->has(SessionKeys::USUARIO_AUTENTICADO)) {
      return $next($request);
    }
    return $request->redirectTo('/autenticacao');
  }
}