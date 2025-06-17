<?php
namespace App\Entities\Categorias;

use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\{ Collection, ArrayCollection };

use App\Entities\Servico\InformacaoContato;

#[ORM\Entity, ORM\Table(name:"CategoriaContato")]
class CategoriaContato extends Entity {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "smallint", name: "ID")]
  public ?int $id = null;

  #[ORM\Column(length: 255, name: "Nome")]
  public string $nome;

  #[ORM\OneToMany(targetEntity: InformacaoContato::class, mappedBy: "categoria")]
  public Collection $informacoesContato;

  public function __construct() {
    $this->informacoesContato = new ArrayCollection();
  }
} 