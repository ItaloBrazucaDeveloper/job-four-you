<?php
namespace App\DTOs\Usuario;

class UsuarioMeuPerfilDTO {
  public readonly ?int $id;
  public readonly ?string $nome;
  public readonly ?string $cpf;
  public readonly ?string $foto;
  public readonly ?string $celular;
  public readonly ?string $dataNascimento;
  public readonly ?string $email;
  
  public readonly string $cep;
  public readonly string $estado;
  public readonly string $rua;
  public readonly string $bairro;
  public readonly string $cidade;
}