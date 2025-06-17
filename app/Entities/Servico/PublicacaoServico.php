<?php
namespace App\Entities\Servico;

use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\{ Collection, ArrayCollection };

use App\Entities\Usuario;
use App\Entities\Status\StatusPublicacao;
use App\Entities\Categorias\CategoriaServico;

#[ORM\Entity, ORM\Table(name:"PublicacaoServico")]
class PublicacaoServico extends Entity {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer", name: "ID")]
  public ?int $id = null;

  #[ORM\Column(length: 50, name: "Titulo")]
  public string $titulo;

  #[ORM\Column(length: 255, nullable: true, name: "Sobre")]
  public ?string $sobre = null;

  #[ORM\Column(type: "decimal", precision: 7, scale: 2, name: "Valor")]
  public string $valor;

  #[ORM\Column(type: "integer", name: "QuantidadeFavorito")]
  public int $quantidadeFavorito = 0;

  #[ORM\ManyToOne(targetEntity: CategoriaServico::class)]
  #[ORM\JoinColumn(name: "FKCategoria", referencedColumnName: "ID", nullable: false)]
  public CategoriaServico $categoria;

  #[ORM\ManyToOne(targetEntity: Usuario::class)]
  #[ORM\JoinColumn(name: "FKUsuario", referencedColumnName: "ID", nullable: false)]
  public Usuario $usuario;

  #[ORM\Column(type: "datetime", name: "DataCriacao")]
  public \DateTime $dataCriacao;

  #[ORM\Column(type: "datetime", nullable: true, name: "UltimaAtualizacao")]
  public ?\DateTime $ultimaAtualizacao = null;

  #[ORM\Column(type: "string", enumType: StatusPublicacao::class, name: "StatusPublicacao")]
  public StatusPublicacao $statusPublicacao = StatusPublicacao::EM_ANALISE;

  #[ORM\ManyToMany(targetEntity: Usuario::class, inversedBy: "servicosFavoritos")]
  #[ORM\JoinTable(name: "ServicoFavorito",
    joinColumns: [new ORM\JoinColumn(name: "IDServico", referencedColumnName: "ID")],
    inverseJoinColumns: [new ORM\JoinColumn(name: "IDUsuario", referencedColumnName: "ID")]
  )]
  public Collection $usuariosFavoritos;

  #[ORM\OneToMany(targetEntity: AvaliacaoServico::class, mappedBy: "publicacao")]
  public Collection $avaliacoes;

  public function __construct() {
    $this->dataCriacao = new \DateTime();
    $this->avaliacoes = new ArrayCollection();
    $this->usuariosFavoritos = new ArrayCollection();
  }
} 