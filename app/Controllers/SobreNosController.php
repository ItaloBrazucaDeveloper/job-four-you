<?php
namespace App\Controllers;

use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\Controller;
use KissPhp\Attributes\Http\Methods\Get;

#[Controller('/sobre-nos')]
class SobreNosController extends WebController {
  #[Get]
  public function exbirPaginaHome() { $this->render('Pages/main.twig'); }
}