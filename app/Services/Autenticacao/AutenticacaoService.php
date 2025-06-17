<?php
namespace App\Services\Autenticacao;

use App\DTOs\Login\{ Credenciais, UsuarioAutenticado };
use App\Repositories\Autenticacao\AutenticacaoRepository;

class AutenticacaoService {
  public function __construct(private AutenticacaoRepository $repository) { }

  public function obterUsuarioAutenticado(Credenciais $credenciais): ?UsuarioAutenticado {
    $usuarioLogin = $this->repository->obterUsuarioPelasCredenciais($credenciais->email);
    if (!$usuarioLogin) return null;

    $senhaValida = password_verify($credenciais->senha, $usuarioLogin->senha);
    if (!$senhaValida) return null;
    
    return $usuarioLogin->toObject(UsuarioAutenticado::class);
  }
}