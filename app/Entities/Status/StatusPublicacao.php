<?php
namespace App\Entities\Status;

enum StatusPublicacao: string {
  case ATIVO = 'ATIVO';
  case EM_ANALISE = 'EM_ANALISE';
  case REJEITADO = 'REJEITADO';
  case BLOQUEADO = 'BLOQUEADO';
} 