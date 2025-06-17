<?php
namespace App\DTOs\Usuario;

use App\DTOs\Usuario\EnderecoDTO;

class UsuarioMeuPerfilDTO {
  public function __construct(
    public readonly ?int $id,
    public readonly ?string $nome,
    public readonly ?string $cpf,
    public readonly ?string $foto,
    public readonly ?string $celular,
    public readonly ?string $dataNascimento,
    public readonly ?string $email,
    public readonly ?EnderecoDTO $endereco
  ) { }
}