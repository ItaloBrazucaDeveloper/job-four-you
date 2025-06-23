<?php
namespace App\Repositories\Usuarios;

use KissPhp\Abstractions\Repository;

use App\Entities\{Credencial, Usuario, Endereco };
use App\DTOs\Usuario\UsuarioCadastroDTO;
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
      $usuario = $this->database()->getReference(Usuario::class, $id);
    
      if (!$usuario) {
        error_log("[Error] UsuariosRepository::buscarPorId: Usuário não encontrado para o ID {$id}");
        return null;
      }
      return $usuario;
    } catch (\Throwable $th) {
      error_log("[Error] UsuariosRepository::buscarPorId: {$th->getMessage()}");
      return null;
    }
  }
}
