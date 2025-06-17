<?php
namespace App\Middlewares;

use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebMiddleware;

class VerificaSePertenceGrupoPrestador extends WebMiddleware {
  public function handle($request, \Closure $next): ?Request {
    return null;
  }
}