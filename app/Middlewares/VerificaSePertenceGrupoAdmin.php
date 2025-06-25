<?php
namespace App\Middlewares;

use App\Utils\Grupos;
use App\Utils\SessionKeys;
use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebMiddleware;

class VerificaSePertenceGrupoAdmin extends WebMiddleware {
  public function handle($request, \Closure $next): ?Request {
    $usuario = $request->session->get(SessionKeys::USUARIO_AUTENTICADO);
    
    if ($usuario->grupo === Grupos::ADMINISTRADOR) return $next($request);
    return $request->redirectTo('/');
  }
}