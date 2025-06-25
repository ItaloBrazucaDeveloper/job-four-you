<?php
namespace App\Factories\Usuarios;

use App\Entities\Usuario;
use App\DTOs\EnderecoDTO;
use App\DTOs\Usuario\UsuarioMeuPerfilDTO;

class UsuarioMeuPerfilDTOFactory {
  public static function fromEntity(Usuario $usuario): UsuarioMeuPerfilDTO {
    try {
      return new UsuarioMeuPerfilDTO(
        id: $usuario->id,
        nome: $usuario->nome ?? '',
        cpf: $usuario->cpf,
        foto: $usuario->foto,
        celular: $usuario->celular,
        dataNascimento: $usuario->dataNascimento instanceof \DateTime ? $usuario->dataNascimento->format('Y-m-d') : '',
        email: $usuario->credencial->email,
        grupo: $usuario->credencial->nivelAcesso->grupo,
        endereco: new EnderecoDTO(
          cep: $usuario->endereco->cep,
          rua: $usuario->endereco->rua,
          bairro: $usuario->endereco->bairro,
          cidade: $usuario->endereco->cidade,
          estado: $usuario->endereco->estado
        )
      );
    } catch (\Throwable $e) {
      error_log("[Error] Factory: Erro geral na conversão: {$e->getMessage()}");
      throw $e;
    }
  }
}