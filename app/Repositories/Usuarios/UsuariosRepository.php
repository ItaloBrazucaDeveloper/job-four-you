<?php
namespace App\Repositories\Usuarios;

use KissPhp\Abstractions\Repository;

use App\Entities\{ Usuario, Endereco };
use App\DTOs\Usuario\UsuarioCadastroDTO;
use App\Entities\Servico\PublicacaoServico;
use App\Repositories\Enderecos\EnderecoRepository;
use App\Repositories\Credenciais\CredencialRepository;

class UsuariosRepository extends Repository {
  public function __construct(
    private EnderecoRepository $enderecoRepository,
    private CredencialRepository $credencialRepository
  ) { }

  public function cadastrar(UsuarioCadastroDTO $usuarioDTO, string $senhaHash): int {
    try {
      $this->database()->getConnection()->beginTransaction();

      $endereco = new Endereco();
      $endereco->cep = str_replace('-', '', $usuarioDTO->cep);
      $endereco->rua = $usuarioDTO->rua;
      $endereco->bairro = $usuarioDTO->bairro;
      $endereco->cidade = $usuarioDTO->cidade;
      $endereco->estado = $usuarioDTO->estado;

      $endereco = $this->enderecoRepository->cadastrar($endereco);
      $credencial = $this->credencialRepository->cadastrar($usuarioDTO->email, $senhaHash);

      $usuario = new Usuario();
      $usuario->nome = $usuarioDTO->nome . ' ' . $usuarioDTO->sobrenome;
      $usuario->cpf = $usuarioDTO->cpf;
      $usuario->celular = $usuarioDTO->celular;
      $usuario->credencial = $credencial;
      $usuario->dataNascimento = new \DateTime($usuarioDTO->dataNascimento);
      $usuario->credencial = $credencial;
      $usuario->endereco = $endereco;

      $this->database()->persist($usuario);
      $this->database()->flush();

      $this->database()->getConnection()->commit();
      return $usuario->id;
    } catch (\Throwable $th) {
      if ($this->database()->getConnection()->isTransactionActive()) {
        $this->database()->getConnection()->rollBack();
      }
      error_log("[Error] UsuariosRepository::cadastrar: {$th->getMessage()}");
      throw new \Exception("Erro ao cadastrar usuário: {$th->getMessage()}");
    }
  }

  public function buscarPorId(int $id): ?Usuario {
    try {
      $usuario = $this->database()->find(Usuario::class, $id);
      
      if (!$usuario) {
        error_log("[Error] UsuariosRepository::buscarPorId: Usuário não encontrado para o ID {$id}");
        return null;
      }
      return $usuario;
    } catch (\Throwable $th) {
      error_log("[Error] UsuariosRepository::buscarPorId: Falha ao buscar usuário pelo ID: {$th->getMessage()}");
      return null;
    }
  }

  public function obterServicosFavoritos(int $id): ?array {
    try {
      $usuario = $this->database()->find(Usuario::class, $id);
      if (!$usuario) {
        error_log("[Error] UsuariosRepository::obterServicosFavoritos: Usuário não encontrado para o ID {$id}");
        return null;
      }
      // Retorna os serviços favoritos como array
      return $usuario->servicosFavoritos instanceof \Doctrine\Common\Collections\Collection
        ? $usuario->servicosFavoritos->toArray()
        : [];
    } catch (\Throwable $th) {
      error_log("[Error] UsuariosRepository::obterServicosFavoritos: Falha ao buscar serviços favoritos: {$th->getMessage()}");
      return null;
    }
  }

  public function obterServicosPostados(int $id): ?PublicacaoServico {
    return $this->database()->find(PublicacaoServico::class, [
      'usuario' => $id
    ]);
  }

  public function tornarClienteEmPrestador(int $id): bool {
    try {
      $this->database()->getConnection()->beginTransaction();

      $usuario = $this->database()->find(Usuario::class, $id);
      
      if (!$usuario) {
        error_log("[Error] UsuariosRepository::tornarClienteEmPrestador: Usuário não encontrado para o ID {$id}");
        return false;
      }

      // Buscar o nível de acesso PRESTADOR
      $nivelPrestador = $this->database()->getRepository(\App\Entities\NivelAcesso::class)
        ->findOneBy(['grupo' => 'PRESTADOR']);

      if (!$nivelPrestador) {
        error_log("[Error] UsuariosRepository::tornarClienteEmPrestador: Nível de acesso PRESTADOR não encontrado");
        return false;
      }

      // Atualizar o nível de acesso do usuário
      $usuario->credencial->nivelAcesso = $nivelPrestador;
      $usuario->ultimaAtualizacao = new \DateTime();

      $this->database()->persist($usuario);
      $this->database()->flush();

      $this->database()->getConnection()->commit();
      return true;
    } catch (\Throwable $th) {
      if ($this->database()->getConnection()->isTransactionActive()) {
        $this->database()->getConnection()->rollBack();
      }
      error_log("[Error] UsuariosRepository::tornarClienteEmPrestador: {$th->getMessage()}");
      return false;
    }
  }

  public function atualizar(int $id, \App\DTOs\Usuario\UsuarioAtualizarDTO $dados): bool {
    try {
      $this->database()->getConnection()->beginTransaction();

      $usuario = $this->database()->find(Usuario::class, $id);
      
      if (!$usuario) {
        error_log("[Error] UsuariosRepository::atualizar: Usuário não encontrado para o ID {$id}");
        return false;
      }

      // Atualizar dados do usuário
      $usuario->nome = $dados->nome;
      $usuario->celular = $dados->telefone;
      
      if ($dados->foto) {
        $usuario->foto = $dados->foto;
      }
      
      if ($dados->dataNascimento) {
        $usuario->dataNascimento = new \DateTime($dados->dataNascimento);
      }

      // Atualizar email na credencial
      $usuario->credencial->email = $dados->email;
      
      $usuario->ultimaAtualizacao = new \DateTime();

      $this->database()->persist($usuario);
      $this->database()->flush();

      $this->database()->getConnection()->commit();
      return true;
    } catch (\Throwable $th) {
      if ($this->database()->getConnection()->isTransactionActive()) {
        $this->database()->getConnection()->rollBack();
      }
      error_log("[Error] UsuariosRepository::atualizar: {$th->getMessage()}");
      return false;
    }
  }
}
