<?php
namespace App\DTOs\Usuario;

use App\DTOs\EnderecoDTO;

class UsuarioMeuPerfilDTO {
  public function __construct(
    public readonly ?int $id,
    public readonly ?string $nome,
    public readonly ?string $cpf,
    public readonly ?string $foto,
    public readonly ?string $celular,
    public readonly ?string $dataNascimento,
    public readonly ?string $email,
    public readonly ?string $grupo,
    public readonly EnderecoDTO $endereco,
    public readonly ?string $contato_email = null,
    public readonly ?string $contato_celular = null,
    public readonly ?string $contato_facebook = null,
    public readonly ?string $contato_instagram = null,
    public readonly ?string $contato_whatsapp = null,
    public readonly ?string $contato_outro = null
  ) { }
}
