<?php
namespace App\Entities\Views;

use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ViewUsuarioLogin')]
class ViewUsuarioLogin extends Entity {
  #[ORM\Id]
  #[ORM\Column(type: "integer", name: "ID")]
  public int $id;

  #[ORM\Column(length: 50, name: "Nome")]
  public string $nome;

  #[ORM\Column(length: 255, nullable: true, name: "Foto")]
  public ?string $foto = null;

  #[ORM\Column(length: 50, name: "StatusUsuario")]
  public string $statusUsuario;

  #[ORM\Column(length: 255, name: "Email")]
  public string $email;

  #[ORM\Column(length: 255, name: "Senha")]
  public string $senha;

  #[ORM\Column(length: 50, name: "Grupo")]
  public string $grupo;
} 