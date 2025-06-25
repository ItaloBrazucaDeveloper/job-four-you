<?php
namespace App\Services\Admin;

use App\Repositories\Admin\AdminRepository;
use App\DTOs\Admin\{ DashboardDTO, UsuarioAdminDTO, SolicitacaoAdminDTO };
use App\Entities\Status\{ StatusUsuario, StatusPublicacao };
use App\Utils\Paginacao;

class AdminService {
  
  public function __construct(
    private AdminRepository $adminRepository
  ) { }
  
  public function obterDadosDashboard(): DashboardDTO {
    try {
      return $this->adminRepository->obterDadosDashboard();
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::obterDadosDashboard: {$th->getMessage()}");
      throw new \Exception("Erro ao obter dados do dashboard: {$th->getMessage()}");
    }
  }
  
  public function listarUsuarios(int $pagina = 1, int $limite = 10, ?string $filtro = null): array {
    try {
      $usuarios = $this->adminRepository->listarUsuarios($pagina, $limite, $filtro);
      $total = $this->adminRepository->contarUsuarios($filtro);
      
      $totalPaginas = ceil($total / $limite);
      $inicio = (($pagina - 1) * $limite) + 1;
      $fim = min($pagina * $limite, $total);
      
      return [
        'usuarios' => $usuarios,
        'paginacao' => [
          'total' => $total,
          'pagina_atual' => $pagina,
          'total_paginas' => $totalPaginas,
          'inicio' => $inicio,
          'fim' => $fim
        ]
      ];
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::listarUsuarios: {$th->getMessage()}");
      throw new \Exception("Erro ao listar usuários: {$th->getMessage()}");
    }
  }
  
  public function listarSolicitacoesServicos(int $pagina = 1, int $limite = 10): array {
    try {
      $servicos = $this->adminRepository->listarSolicitacoesServicos($pagina, $limite);
      
      return [
        'servicos_pendentes' => $servicos,
        'total' => count($servicos)
      ];
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::listarSolicitacoesServicos: {$th->getMessage()}");
      throw new \Exception("Erro ao listar solicitações de serviços: {$th->getMessage()}");
    }
  }
  
  public function listarSolicitacoesPrestadores(int $pagina = 1, int $limite = 10): array {
    try {
      $prestadores = $this->adminRepository->listarSolicitacoesPrestadores($pagina, $limite);
      
      return [
        'prestadores_pendentes' => $prestadores,
        'total' => count($prestadores)
      ];
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::listarSolicitacoesPrestadores: {$th->getMessage()}");
      throw new \Exception("Erro ao listar solicitações de prestadores: {$th->getMessage()}");
    }
  }
  
  public function aprovarServico(int $servicoId): bool {
    try {
      return $this->adminRepository->aprovarServico($servicoId);
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::aprovarServico: {$th->getMessage()}");
      return false;
    }
  }
  
  public function rejeitarServico(int $servicoId): bool {
    try {
      return $this->adminRepository->rejeitarServico($servicoId);
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::rejeitarServico: {$th->getMessage()}");
      return false;
    }
  }
  
  public function ativarUsuario(int $usuarioId): bool {
    try {
      return $this->adminRepository->alterarStatusUsuario($usuarioId, StatusUsuario::ATIVO);
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::ativarUsuario: {$th->getMessage()}");
      return false;
    }
  }
  
  public function inativarUsuario(int $usuarioId): bool {
    try {
      return $this->adminRepository->alterarStatusUsuario($usuarioId, StatusUsuario::INATIVO);
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::inativarUsuario: {$th->getMessage()}");
      return false;
    }
  }
  
  public function bloquearUsuario(int $usuarioId): bool {
    try {
      return $this->adminRepository->alterarStatusUsuario($usuarioId, StatusUsuario::BLOQUEADO);
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::bloquearUsuario: {$th->getMessage()}");
      return false;
    }
  }
  
  public function banirUsuario(int $usuarioId): bool {
    try {
      return $this->adminRepository->alterarStatusUsuario($usuarioId, StatusUsuario::BANIDO);
    } catch (\Throwable $th) {
      error_log("[Error] AdminService::banirUsuario: {$th->getMessage()}");
      return false;
    }
  }
} 