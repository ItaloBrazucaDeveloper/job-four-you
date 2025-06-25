<?php
namespace App\Services\Usuarios;

use App\Entities\Servico\PublicacaoServico;
use App\Repositories\Usuarios\UsuariosRepository;
use App\Factories\Usuarios\UsuarioMeuPerfilDTOFactory;
use App\Repositories\Credenciais\CredencialRepository;
use App\DTOs\Usuario\{ UsuarioCadastroDTO, UsuarioMeuPerfilDTO, UsuarioAtualizarDTO };

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

  public function obterServicosFavoritos(int $id): ?array {
    return $this->usuarioRepository->obterServicosFavoritos($id) ?? [];
  }

  public function obterServicosPostados(int $id): ?PublicacaoServico {
    return $this->usuarioRepository->obterServicosPostados($id);
  }

  public function verificarSePodeSeTornarPrestador(int $id): array {
    try {
      $usuario = $this->usuarioRepository->buscarPorId($id);
      
      if (!$usuario) {
        return [
          'pode_se_tornar' => false,
          'erros' => ['Usuário não encontrado']
        ];
      }

      $erros = [];
      
      // Verificar se tem foto de perfil
      if (empty($usuario->foto)) {
        $erros[] = 'foto';
      }
      
      // Verificar se tem endereço cadastrado
      if (!$usuario->endereco) {
        $erros[] = 'endereco';
      }

      return [
        'pode_se_tornar' => empty($erros),
        'erros' => $erros
      ];
    } catch (\Throwable $th) {
      error_log("[Error] UsuariosService::verificarSePodeSeTornarPrestador: {$th->getMessage()}");
      return [
        'pode_se_tornar' => false,
        'erros' => ['Erro interno do sistema']
      ];
    }
  }

  public function tornarClienteEmPrestador(int $id): bool {
    try {
      $verificacao = $this->verificarSePodeSeTornarPrestador($id);
      
      if (!$verificacao['pode_se_tornar']) {
        return false;
      }

      return $this->usuarioRepository->tornarClienteEmPrestador($id);
    } catch (\Throwable $th) {
      error_log("[Error] UsuariosService::tornarClienteEmPrestador: {$th->getMessage()}");
      return false;
    }
  }

  public function atualizarUsuario(int $id, UsuarioAtualizarDTO $dados): bool {
    try {
      return $this->usuarioRepository->atualizar($id, $dados);
    } catch (\Throwable $th) {
      error_log("[Error] UsuariosService::atualizarUsuario: {$th->getMessage()}");
      return false;
    }
  }
}
