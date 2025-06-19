<?php
namespace App\Utils;

class Paginacao {
  private const ITENS_POR_PAGINA = 15;

  public static function getOffset(int $paginaAtual): int {
    $offset = ($paginaAtual - 1) * self::ITENS_POR_PAGINA;
    return $offset;
  }

  public static function getItemsPorPagina(): int {
    return self::ITENS_POR_PAGINA;
  }
}