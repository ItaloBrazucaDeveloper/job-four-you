<?php
namespace App\DTOs\Usuario;

use App\DTOs\EnderecoDTO;

class UsuarioAtualizarDTO {
  public readonly ?string $nome;
  public readonly ?string $email;
  public readonly ?string $telefone;
  public readonly ?string $foto;
  public readonly ?string $dataNascimento;
  public readonly ?EnderecoDTO $endereco;
  /**
   * @var array<array{tipo: string, valor: string|null}>|null
   */
  public readonly ?array $contatos;
}