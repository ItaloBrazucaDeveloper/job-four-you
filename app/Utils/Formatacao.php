<?php
namespace App\Utils;

final class Formatacao {
  /**
   * Formata CPF no padrão 000.000.000-00
   */
  public static function formatarCPF(string $cpf): string {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) !== 11) {
      return $cpf; // Retorna sem formatação se não for válido
    }
    
    return substr($cpf, 0, 3) . '.' . 
           substr($cpf, 3, 3) . '.' . 
           substr($cpf, 6, 3) . '-' . 
           substr($cpf, 9, 2);
  }
  
  /**
   * Formata CNPJ no padrão 00.000.000/0000-00
   */
  public static function formatarCNPJ(string $cnpj): string {
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    
    if (strlen($cnpj) !== 14) {
      return $cnpj; // Retorna sem formatação se não for válido
    }
    
    return substr($cnpj, 0, 2) . '.' . 
           substr($cnpj, 2, 3) . '.' . 
           substr($cnpj, 5, 3) . '/' . 
           substr($cnpj, 8, 4) . '-' . 
           substr($cnpj, 12, 2);
  }
  
  /**
   * Formata CPF ou CNPJ automaticamente baseado no tamanho
   */
  public static function formatarDocumento(string $documento): string {
    $documento = preg_replace('/[^0-9]/', '', $documento);
    
    if (strlen($documento) === 11) {
      return self::formatarCPF($documento);
    } elseif (strlen($documento) === 14) {
      return self::formatarCNPJ($documento);
    }
    
    return $documento; // Retorna sem formatação se não for válido
  }
} 