<?php

namespace App\Entities\Views;

use DateTime;
use KissPhp\Abstractions\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ViewPublicacao')]
class ViewPublicacao extends Entity {
  #[ORM\Id, ORM\Column(type: "integer", name: "IDPublicacao")]
  public int $idPublicacao;

  #[ORM\Column(type: "string", length: 50, name: "NomeUsuario")]
  public string $nomeUsuario;

  #[ORM\Column(type: "string", length: 255, nullable: true, name: "FotoUsuario")]
  public ?string $fotoUsuario;

  #[ORM\Column(type: "string", length: 50, name: "Titulo")]
  public string $titulo;

  #[ORM\Column(type: "string", length: 255, nullable: true, name: "Sobre")]
  public string $sobre;

  #[ORM\Column(type: "decimal", precision: 7, scale: 2, name: "Valor")]
  public float $valor;

  #[ORM\Column(type: "integer", name: "QuantidadeFavorito")]
  public int $quantidadeFavorito;

  #[ORM\Column(type: "datetime", name: "PublicadoEm")]
  public DateTime $publicadoEm;

  #[ORM\Column(type: "datetime", nullable: true, name: "EditadoEm")]
  public ?DateTime $editadoEm;

  #[ORM\Column(type: "string", length: 25, name: "Categoria")]
  public string $categoria;

  #[ORM\Column(type: "string", length: 255, nullable: true, name: "Email")]
  public ?string $email;

  #[ORM\Column(type: "string", length: 255, nullable: true, name: "Facebook")]
  public ?string $facebook;

  #[ORM\Column(type: "string", length: 11, nullable: true, name: "Celular")]
  public ?string $celular;

  #[ORM\Column(type: "string", length: 255, nullable: true, name: "Whatsapp")]
  public ?string $whatsapp;

  #[ORM\Column(type: "string", length: 255, nullable: true, name: "Instagram")]
  public ?string $instagram;

  #[ORM\Column(type: "string", length: 255, nullable: true, name: "OutroContato")]
  public ?string $outroContato;
} 