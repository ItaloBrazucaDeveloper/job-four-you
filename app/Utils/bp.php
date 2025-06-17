<?php

namespace App\Utils;

/**
 * Retorna o conteúdo de uma variável e encerra a execução do script.
 * Mostra o nome da variável e seu valor.
 * 
 * @param mixed $value O valor a ser exibido.
 */
function bp(mixed $value): void {
  $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
  
  $file = file($backtrace['file']);
  $line = $file[$backtrace['line'] - 1];
  preg_match('/bp\s*\(\s*([^)]+)\s*\)/', $line, $matches);
  $varName = $matches[1] ?? 'valor';

  echo "<div style='background: #f5f5f5; padding: 10px; margin: 10px 0; border: 1px solid #ddd;'>";
  echo "<strong>Variável:</strong> {$varName}<br>";
  echo "<strong>Valor:</strong><br>";

  if ($value === null) $value = 'NULL';

  if (is_array($value) || is_object($value)) {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
  }

  if (is_string($value) || is_int($value) || is_float($value) || is_bool($value)) {
    if (is_bool($value)) $value = $value ? 'TRUE' : 'FALSE';
    echo "<p>{$value}</p>";
  }

  echo "</div>";
  die();
}