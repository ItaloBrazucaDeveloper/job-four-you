<?php
namespace App\Entities;

use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name:"Credencial")]
class Credencial extends Entity {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer", name: "ID")]
  public ?int $id = null;

  #[ORM\Column(length: 100, unique: true, name: "Email")]
  public string $email;

  #[ORM\Column(length: 60, name: "Senha")]
  public string $senha;

  #[ORM\ManyToOne(targetEntity: NivelAcesso::class)]
  #[ORM\JoinColumn(name: "FKNivelAcesso", referencedColumnName: "ID", nullable: false)]
  public NivelAcesso $nivelAcesso;

  #[ORM\OneToOne(targetEntity: Usuario::class, mappedBy: "credencial")]
  public ?Usuario $usuario = null;
}