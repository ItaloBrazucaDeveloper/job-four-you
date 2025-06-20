<?php

namespace App\DTOs\Servicos;

class ServicoDTO {
  public readonly int $idPublicacao;
  public readonly string $nomeUsuario;
  public readonly ?string $fotoUsuario;
  public readonly string $titulo;
  public readonly string $sobre;
  public readonly float $valor;
  public readonly int $quantidadeFavorito;
  public readonly \DateTime $publicadoEm;
  public readonly ?\DateTime $editadoEm;
  public readonly string $categoria;
  public readonly string $cidade;
  public readonly string $estado;
  public readonly float $mediaAvaliacoes;
} 