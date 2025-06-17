<?php
namespace App\Entities;

use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\{ Collection, ArrayCollection };

#[ORM\Entity, ORM\Table(name:"Endereco")]
class Endereco extends Entity {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer", name: "ID")]
  public ?int $id = null;

  #[ORM\Column(length: 8, name: "CEP")]
  public string $cep;

  #[ORM\Column(length: 2, name: "Estado")]
  public string $estado;

  #[ORM\Column(length: 255, name: "Cidade")]
  public string $cidade;

  #[ORM\Column(length: 255, name: "Bairro")]
  public string $bairro;

  #[ORM\Column(length: 255, name: "Rua")]
  public string $rua;

  #[ORM\OneToMany(targetEntity: Usuario::class, mappedBy: "endereco")]
  public Collection $usuarios;

  public function __construct() {
    $this->usuarios = new ArrayCollection();
  }
} 