<?php
namespace App\Entities\Status;

enum StatusUsuario: string {
  case ATIVO = 'ATIVO';
  case INATIVO = 'INATIVO';
  case BLOQUEADO = 'BLOQUEADO';
  case BANIDO = 'BANIDO';
} 