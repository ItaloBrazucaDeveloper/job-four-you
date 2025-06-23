<?php
namespace App\DTOs\Usuario;

class UsuarioCadastroDTO {
  public readonly string $nome;
  public readonly string $sobrenome;
  public readonly string $email;
  public readonly string $senha;
  public readonly string $cpf;
  public readonly string $celular;
  public readonly string $dataNascimento;
  
  public readonly string $cep;
  public readonly string $estado;
  public readonly string $rua;
  public readonly string $bairro;
  public readonly string $cidade;
}