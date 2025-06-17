<?php
namespace App\Entities\Servico;

use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;

use App\Entities\Usuario;
use App\Entities\Categorias\CategoriaContato;

#[ORM\Entity, ORM\Table(name:"InformacaoContato")]
class InformacaoContato extends Entity {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer", name: "ID")]
  public ?int $id = null;

  #[ORM\Column(length: 255, nullable: true, name: "Contato")]
  public ?string $contato = null;

  #[ORM\ManyToOne(targetEntity: Usuario::class)]
  #[ORM\JoinColumn(name: "FKUsuario", referencedColumnName: "ID", nullable: false)]
  public Usuario $usuario;

  #[ORM\ManyToOne(targetEntity: CategoriaContato::class)]
  #[ORM\JoinColumn(name: "FKCategoriaContato", referencedColumnName: "ID", nullable: false)]
  public CategoriaContato $categoria;
} 