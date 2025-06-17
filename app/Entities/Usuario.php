<?php
namespace App\Entities;

use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\{ Collection, ArrayCollection };

use App\Entities\Status\StatusUsuario;
use App\Entities\Servico\{ InformacaoContato, PublicacaoServico };

#[ORM\Entity, ORM\Table(name:"Usuario")]
class Usuario extends Entity {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer", name:"ID")]
  public ?int $id = null;

  #[ORM\Column(length:50, name:'Nome')]
  public string $nome;

  #[ORM\Column(length:11, unique:true, name:'CPF')]
  public string $cpf;

  #[ORM\Column(length:255, nullable:true, name:'Foto')]
  public ?string $foto = null;

  #[ORM\Column(length:11, nullable:true, name:'Celular')]
  public ?string $celular = null;

  #[ORM\Column(type: "date", name:'DataNascimento')]
  public \DateTime $dataNascimento;

  #[ORM\OneToOne(targetEntity: Credencial::class, inversedBy: "usuario")]
  #[ORM\JoinColumn(name:"FKCredencial", referencedColumnName:"ID", nullable:false)]
  public Credencial $credencial;

  #[ORM\ManyToOne(targetEntity: Endereco::class)]
  #[ORM\JoinColumn(name:"FKEndereco", referencedColumnName:"ID", nullable:true)]
  public ?Endereco $endereco = null;

  #[ORM\Column(type:"datetime", name:'DataCriacao')]
  public \DateTime $dataCriacao;

  #[ORM\Column(type:"datetime", nullable:true, name:'UltimaAtualizacao')]
  public ?\DateTime $ultimaAtualizacao = null;

  #[ORM\Column(type:"string", enumType:StatusUsuario::class)]
  public StatusUsuario $statusUsuario = StatusUsuario::ATIVO;

  #[ORM\OneToMany(targetEntity:InformacaoContato::class, mappedBy:"usuario")]
  public Collection $informacoesContato;

  #[ORM\OneToMany(targetEntity:PublicacaoServico::class, mappedBy:"usuario")]
  public Collection $publicacoesServico;

  #[ORM\ManyToMany(targetEntity:PublicacaoServico::class, mappedBy:"usuariosFavoritos")]
  public Collection $servicosFavoritos;

  public function __construct() {
    $this->dataCriacao = new \DateTime();
    $this->informacoesContato = new ArrayCollection();
    $this->publicacoesServico = new ArrayCollection();
    $this->servicosFavoritos = new ArrayCollection();
  }
} 