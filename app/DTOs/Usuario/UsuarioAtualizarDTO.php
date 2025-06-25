<?php
namespace App\DTOs\Usuario;

use App\DTOs\{ EnderecoDTO, ContatosDTO };

class UsuarioAtualizarDTO {
  public function __construct(
    public readonly ?string $nome = null,
    public readonly ?string $email = null,
    public readonly ?string $telefone = null,
    public readonly ?string $foto = null,
    public readonly ?string $dataNascimento = null,
    public readonly ?EnderecoDTO $endereco = null,
    public readonly ?ContatosDTO $contatos = null
  ) {}
}