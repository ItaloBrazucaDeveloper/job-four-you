<?php
namespace App\DTOs\Usuario;

class UsuarioCadastroDTO {
  public function __construct(
    public readonly string $nome,
    public readonly string $email,
    public readonly string $senha,
    public readonly string $cpf,
    public readonly string $celular,
    public readonly string $dataNascimento,
    public readonly EnderecoDTO $endereco
  ) { }
}