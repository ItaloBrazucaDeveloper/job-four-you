<?php
namespace App\Factories\Usuarios;

use App\Entities\Usuario;
use App\DTOs\EnderecoDTO;
use App\DTOs\Usuario\UsuarioMeuPerfilDTO;

class UsuarioMeuPerfilDTOFactory {
  public static function fromEntity(Usuario $usuario): UsuarioMeuPerfilDTO {
    try {
      $contatos = [
        'contato_email' => null,
        'contato_celular' => null,
        'contato_facebook' => null,
        'contato_instagram' => null,
        'contato_whatsapp' => null,
        'contato_outro' => null,
      ];
      if ($usuario->credencial->nivelAcesso->grupo === 'PRESTADOR') {
        foreach ($usuario->informacoesContato as $contato) {
          $tipo = strtolower($contato->categoria->nome);
          switch ($tipo) {
            case 'email': $contatos['contato_email'] = $contato->contato; break;
            case 'celular': $contatos['contato_celular'] = $contato->contato; break;
            case 'facebook': $contatos['contato_facebook'] = $contato->contato; break;
            case 'instagram': $contatos['contato_instagram'] = $contato->contato; break;
            case 'whatsapp': $contatos['contato_whatsapp'] = $contato->contato; break;
            case 'outro': $contatos['contato_outro'] = $contato->contato; break;
          }
        }
      }
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
        ),
        contato_email: $contatos['contato_email'],
        contato_celular: $contatos['contato_celular'],
        contato_facebook: $contatos['contato_facebook'],
        contato_instagram: $contatos['contato_instagram'],
        contato_whatsapp: $contatos['contato_whatsapp'],
        contato_outro: $contatos['contato_outro']
      );
    } catch (\Throwable $e) {
      error_log("[Error] Factory: Erro geral na conversão: {$e->getMessage()}");
      throw $e;
    }
  }
}