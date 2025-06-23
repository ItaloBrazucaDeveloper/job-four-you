<?php
namespace App\Services\Usuarios;

use App\Repositories\Usuarios\UsuariosRepository;
use App\Factories\Usuarios\UsuarioMeuPerfilDTOFactory;
use App\Repositories\Credenciais\CredencialRepository;
use App\DTOs\Usuario\{ UsuarioCadastroDTO, UsuarioMeuPerfilDTO };

class UsuariosService {
  public function __construct(
    private UsuariosRepository $usuarioRepository,
    private CredencialRepository $credencialRepository
  ) { }

  public function cadastrarUsuario(UsuarioCadastroDTO $usuarioDTO): bool {
    if ($this->credencialRepository->verificarEmailExistente($usuarioDTO->email)) {
      return false;
    }
    $senhaHash = password_hash($usuarioDTO->senha, PASSWORD_BCRYPT);
    $usuarioId = $this->usuarioRepository->cadastrar($usuarioDTO, $senhaHash);
    return !!$usuarioId;
  }

  public function obterUsuarioPeloId(int $id): ?UsuarioMeuPerfilDTO {
    try {
      $usuario = $this->usuarioRepository->buscarPorId($id);
      
      if (!$usuario) {
        error_log("[Error] UsuariosService::obterUsuarioPeloId: Usuário não encontrado");
        return null;
      }
      return UsuarioMeuPerfilDTOFactory::fromEntity($usuario);
    } catch (\Throwable $th) {
      error_log("[Error] UsuariosService::obterUsuarioPeloId: {$th->getMessage()}");
      return null;
    }
  }
}
