<?php
namespace App\Controllers;

use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\{ Get, Post };

use App\Middlewares\VerificaSeUsuarioLogado;

#[Controller('/avaliacao', [VerificaSeUsuarioLogado::class])]
class AvaliacaoController extends WebController {
  #[Get('/:token:{alphanumeric}?')]
  public function exibirPaginaDeAvaliacao() {
    $this->render('Pages/servicos/avaliacao.twig', []);
  }

  #[Post('/:token:{alphanumeric}?')]
  public function avaliarUsuario() {

  }
}