<?php
namespace App\Controllers;

use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\Get;

#[Controller('/painel')]
class AdminController extends WebController {
  #[Get]
  public function exbibirPaginaPainel() {
    $this->render('Pages/admin/painel.twig', []);
  }

  #[Get('/usuarios')]
  public function exbibirPaginaPainelUsuarios() {
    $this->render('Pages/admin/painel.twig', []);
  }

  #[Get('/solicitacoes')]
  public function exbibirPaginaPainelSolicitacoes() {
    $this->render('Pages/admin/painel.twig', []);
  }
}