<?php
namespace App\Entities\Servico;

use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;

use App\Entities\Usuario;

#[ORM\Entity, ORM\Table(name:"AvaliacaoServico")]
class AvaliacaoServico extends Entity {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer", name: "ID")]
  public ?int $id = null;

  #[ORM\Column(type: "smallint", name: "Nota")]
  public int $nota = 0;

  #[ORM\Column(length: 255, nullable: true, name: "Comentario")]
  public ?string $comentario = null;

  #[ORM\ManyToOne(targetEntity: PublicacaoServico::class)]
  #[ORM\JoinColumn(name: "FKPublicacao", referencedColumnName: "ID", nullable: false)]
  public PublicacaoServico $publicacao;

  #[ORM\ManyToOne(targetEntity: Usuario::class)]
  #[ORM\JoinColumn(name: "FKUsuario", referencedColumnName: "ID", nullable: false)]
  public Usuario $usuario;

  #[ORM\Column(type: "datetime", name: "DataCriacao")]
  public \DateTime $dataCriacao;

  #[ORM\Column(type: "datetime", nullable: true, name: "UltimaAtualizacao")]
  public ?\DateTime $ultimaAtualizacao = null;

  public function __construct() {
    $this->dataCriacao = new \DateTime();
  }
} 