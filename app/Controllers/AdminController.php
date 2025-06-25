<?php
namespace App\Controllers;

use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Request\Body;
use KissPhp\Attributes\Http\Methods\{ Get, Post };

use App\Services\Admin\AdminService;
use App\Middlewares\{ VerificaSeUsuarioLogado, VerificaSePertenceGrupoAdmin };

#[Controller('/painel', [VerificaSeUsuarioLogado::class, VerificaSePertenceGrupoAdmin::class])]
class AdminController extends WebController {
  public function __construct(private AdminService $adminService) { }

  #[Get]
  public function exbibirPaginaPrincipalPainel() {
    try {
      $dados = $this->adminService->obterDadosDashboard();
      
      $this->render('Pages/admin/painel.twig', [
        'total_usuarios' => $dados->totalUsuarios,
        'total_prestadores' => $dados->totalPrestadores,
        'total_servicos' => $dados->totalServicos,
        'total_aprovacoes' => $dados->totalAprovacoes,
        'notificacoes' => $dados->notificacoes,
        'ultimos_servicos' => $dados->ultimosServicos,
        'aprovacoes_prestadores' => $dados->aprovacoesPrestadores
      ]);
    } catch (\Throwable $th) {
      error_log("[Error] AdminController::exbibirPaginaPrincipalPainel: {$th->getMessage()}");
      $this->render('Pages/admin/painel.twig', [
        'total_usuarios' => 0,
        'total_prestadores' => 0,
        'total_servicos' => 0,
        'total_aprovacoes' => 0,
        'notificacoes' => [],
        'ultimos_servicos' => [],
        'aprovacoes_prestadores' => []
      ]);
    }
  }

  #[Get('/usuarios')]
  public function exbibirPaginaPainelUsuarios(Request $request) {
    try {
      $pagina = (int) ($request->getQueryString('pagina') ?? 1);
      $filtro = $request->getQueryString('filtro');
      
      $dados = $this->adminService->listarUsuarios($pagina, 10, $filtro);
      
      $this->render('Pages/admin/tabelas/usuarios.twig', [
        'prestadores' => $dados['usuarios'],
        'paginacao' => $dados['paginacao'],
        'admin' => [
          'nome' => 'Administrador',
          'foto' => null
        ]
      ]);
    } catch (\Throwable $th) {
      error_log("[Error] AdminController::exbibirPaginaPainelUsuarios: {$th->getMessage()}");
      $this->render('Pages/admin/tabelas/usuarios.twig', [
        'prestadores' => [],
        'paginacao' => [
          'total' => 0,
          'pagina_atual' => 1,
          'total_paginas' => 1,
          'inicio' => 0,
          'fim' => 0
        ],
        'admin' => [
          'nome' => 'Administrador',
          'foto' => null
        ]
      ]);
    }
  }

  #[Get('/solicitacoes')]
  public function exbibirPaginaPainelSolicitacoes(Request $request) {
    try {
      $pagina = (int) ($request->getQueryString('pagina') ?? 1);
      
      $servicos = $this->adminService->listarSolicitacoesServicos($pagina, 10);
      $prestadores = $this->adminService->listarSolicitacoesPrestadores($pagina, 10);
      
      $this->render('Pages/admin/tabelas/solicitacoes.twig', [
        'servicos_pendentes' => $servicos['servicos_pendentes'],
        'prestadores_pendentes' => $prestadores['prestadores_pendentes'],
        'admin' => [
          'nome' => 'Administrador',
          'foto' => null
        ]
      ]);
    } catch (\Throwable $th) {
      error_log("[Error] AdminController::exbibirPaginaPainelSolicitacoes: {$th->getMessage()}");
      $this->render('Pages/admin/tabelas/solicitacoes.twig', [
        'servicos_pendentes' => [],
        'prestadores_pendentes' => [],
        'admin' => [
          'nome' => 'Administrador',
          'foto' => null
        ]
      ]);
    }
  }

  #[Post('/aprovar-servico')]
  public function aprovarServico(#[Body] array $dados) {
    try {
      $servicoId = (int) ($dados['servico_id'] ?? 0);
      
      if ($servicoId <= 0) {
        return json_encode(['success' => false, 'message' => 'ID do serviço inválido']);
      }
      
      $sucesso = $this->adminService->aprovarServico($servicoId);
      
      if ($sucesso) {
        return json_encode(['success' => true, 'message' => 'Serviço aprovado com sucesso']);
      } else {
        return json_encode(['success' => false, 'message' => 'Erro ao aprovar serviço']);
      }
    } catch (\Throwable $th) {
      error_log("[Error] AdminController::aprovarServico: {$th->getMessage()}");
      return json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
    }
  }

  #[Post('/rejeitar-servico')]
  public function rejeitarServico(#[Body] array $dados) {
    try {
      $servicoId = (int) ($dados['servico_id'] ?? 0);
      
      if ($servicoId <= 0) {
        return json_encode(['success' => false, 'message' => 'ID do serviço inválido']);
      }
      
      $sucesso = $this->adminService->rejeitarServico($servicoId);
      
      if ($sucesso) {
        return json_encode(['success' => true, 'message' => 'Serviço rejeitado com sucesso']);
      } else {
        return json_encode(['success' => false, 'message' => 'Erro ao rejeitar serviço']);
      }
    } catch (\Throwable $th) {
      error_log("[Error] AdminController::rejeitarServico: {$th->getMessage()}");
      return json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
    }
  }

  #[Post('/alterar-status-usuario')]
  public function alterarStatusUsuario(#[Body] array $dados) {
    try {
      $usuarioId = (int) ($dados['usuario_id'] ?? 0);
      $acao = $dados['acao'] ?? '';
      
      if ($usuarioId <= 0) {
        return json_encode(['success' => false, 'message' => 'ID do usuário inválido']);
      }
      
      $sucesso = false;
      $mensagem = '';
      
      switch ($acao) {
        case 'ativar':
          $sucesso = $this->adminService->ativarUsuario($usuarioId);
          $mensagem = 'Usuário ativado com sucesso';
          break;
        case 'inativar':
          $sucesso = $this->adminService->inativarUsuario($usuarioId);
          $mensagem = 'Usuário inativado com sucesso';
          break;
        case 'bloquear':
          $sucesso = $this->adminService->bloquearUsuario($usuarioId);
          $mensagem = 'Usuário bloqueado com sucesso';
          break;
        case 'banir':
          $sucesso = $this->adminService->banirUsuario($usuarioId);
          $mensagem = 'Usuário banido com sucesso';
          break;
        default:
          return json_encode(['success' => false, 'message' => 'Ação inválida']);
      }
      
      if ($sucesso) {
        return json_encode(['success' => true, 'message' => $mensagem]);
      } else {
        return json_encode(['success' => false, 'message' => 'Erro ao alterar status do usuário']);
      }
    } catch (\Throwable $th) {
      error_log("[Error] AdminController::alterarStatusUsuario: {$th->getMessage()}");
      return json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
    }
  }
}