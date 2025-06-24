<?php
namespace App\DTOs\Servicos;

class MaisDetalhesDTO {
  public function __construct(
    public readonly string $foto,
    public readonly string $prestador,
    public readonly string $titulo,
    public readonly string $descricacao,
    public readonly array $contatos,
    public readonly int $quantiadadeEstrelas,
    public readonly float $mediaAvaliacoes,
    public readonly ?array $avaliacoes
  ) { }
}