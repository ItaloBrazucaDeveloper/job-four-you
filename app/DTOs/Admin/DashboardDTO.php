<?php
namespace App\DTOs\Admin;

class DashboardDTO {
  public function __construct(
    public int $totalUsuarios,
    public int $totalPrestadores,
    public int $totalServicos,
    public int $totalAprovacoes,
    public array $notificacoes,
    public array $ultimosServicos,
    public array $aprovacoesPrestadores
  ) { }
} 