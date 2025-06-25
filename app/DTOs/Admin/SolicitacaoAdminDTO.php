<?php
namespace App\DTOs\Admin;

class SolicitacaoAdminDTO {
  public function __construct(
    public int $id,
    public string $nome,
    public string $prestadorNome,
    public string $categoriaNome,
    public float $valor,
    public string $dataCadastro,
    public string $tipo, // 'servico' ou 'prestador'
    public ?string $linkDocumentos = null
  ) { }
} 