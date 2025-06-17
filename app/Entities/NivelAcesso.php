<?php
namespace App\Entities;

use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\{ Collection, ArrayCollection };

#[ORM\Entity, ORM\Table(name:"NivelAcesso")]
class NivelAcesso extends Entity {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "smallint", name: "ID")]
  public ?int $id = null;

  #[ORM\Column(length: 255, name: "Grupo")]
  public string $grupo;

  #[ORM\OneToMany(targetEntity: Credencial::class, mappedBy: "nivelAcesso")]
  public Collection $credenciais;

  public function __construct() {
    $this->credenciais = new ArrayCollection();
  }
} 