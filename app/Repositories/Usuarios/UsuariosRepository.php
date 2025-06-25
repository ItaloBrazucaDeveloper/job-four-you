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
      if ($dados->nome !== null) {
        $usuario->nome = $dados->nome;
      }
      if ($dados->telefone !== null) {
        $usuario->celular = $dados->telefone;
      }
      if ($dados->foto !== null) {
        $usuario->foto = $dados->foto;
      }
      if ($dados->dataNascimento !== null) {
        $usuario->dataNascimento = new \DateTime($dados->dataNascimento);
      }
      // Atualizar email na credencial
      if ($dados->email !== null) {
        $usuario->credencial->email = $dados->email;
      }

      // Atualizar endereço
      if ($dados->endereco !== null) {
        $endereco = $usuario->endereco;
        if (!$endereco) {
          $endereco = new \App\Entities\Endereco();
        }
        $endereco->cep = $dados->endereco->cep;
        $endereco->rua = $dados->endereco->rua;
        $endereco->bairro = $dados->endereco->bairro;
        $endereco->cidade = $dados->endereco->cidade;
        $endereco->estado = $dados->endereco->estado;
        $this->enderecoRepository->cadastrar($endereco); // cadastra ou atualiza
        $usuario->endereco = $endereco;
      }

      // Atualizar contatos (remove todos e adiciona os novos)
      if ($dados->contatos !== null) {
        // Remove contatos antigos
        foreach ($usuario->informacoesContato as $contatoAntigo) {
          $this->database()->remove($contatoAntigo);
        }
        $this->database()->flush();
        // Adiciona novos contatos
        $contatosArray = [];
        if ($dados->contatos instanceof \App\DTOs\ContatosDTO) {
          $map = [
            'contato_email' => 'Email',
            'contato_celular' => 'Celular',
            'contato_facebook' => 'Facebook',
            'contato_instagram' => 'Instagram',
            'contato_whatsapp' => 'WhatsApp',
            'contato_outro' => 'Outro',
          ];
          foreach ($map as $prop => $tipo) {
            $valor = $dados->contatos->$prop;
            if (!empty($valor)) {
              $contatosArray[] = [
                'tipo' => $tipo,
                'valor' => $valor
              ];
            }
          }
        } elseif (is_array($dados->contatos)) {
          $contatosArray = $dados->contatos;
        }
        foreach ($contatosArray as $contato) {
          if (!empty($contato['valor'])) {
            $categoria = $this->database()->getRepository(\App\Entities\Categorias\CategoriaContato::class)
              ->findOneBy(['nome' => $contato['tipo']]);
            if ($categoria) {
              $novaInfo = new \App\Entities\Servico\InformacaoContato();
              $novaInfo->contato = $contato['valor'];
              $novaInfo->usuario = $usuario;
              $novaInfo->categoria = $categoria;
              $this->database()->persist($novaInfo);
            }
          }
        }
      }

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
