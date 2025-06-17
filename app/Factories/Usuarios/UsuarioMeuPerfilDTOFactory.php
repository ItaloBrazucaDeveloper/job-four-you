<?php
namespace App\Factories\Usuarios;

use App\Entities\Usuario;
use App\DTOs\Usuario\EnderecoDTO;
use App\DTOs\Usuario\UsuarioMeuPerfilDTO;

class UsuarioMeuPerfilDTOFactory {
  public static function fromEntity(Usuario $usuario): UsuarioMeuPerfilDTO {
    return new UsuarioMeuPerfilDTO(
      id: $usuario->id,
      nome: $usuario->nome ?? '',
      cpf: $usuario->cpf ?? '',
      foto: $usuario->foto,
      celular: $usuario->celular,
      dataNascimento: $usuario->dataNascimento instanceof \DateTime ? $usuario->dataNascimento->format('Y-m-d') : '',
      email: $usuario->credencial->email ?? '',
      endereco: $usuario->endereco
      ? new EnderecoDTO(
        cep: $usuario->endereco->cep ?? '',
        estado: $usuario->endereco->estado ?? '',
        rua: $usuario->endereco->rua ?? '',
        bairro: $usuario->endereco->bairro ?? '',
        cidade: $usuario->endereco->cidade ?? ''
      )
      : new EnderecoDTO(cep: '', estado: '', rua: '', bairro: '', cidade: '')
    );
  }
}