<?php
namespace App\DTOs\Admin;

class UsuarioAdminDTO {
  public function __construct(
    public int $id,
    public string $nome,
    public string $documento,
    public ?string $especialidade,
    public int $totalServicos,
    public float $avaliacao,
    public int $totalAvaliacoes,
    public string $status,
    public ?string $foto = null
  ) { }
} 