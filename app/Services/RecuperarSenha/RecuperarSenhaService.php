<?php
namespace App\Services\RecuperarSenha;

use Resend;
use KissPhp\Support\Env;
use App\Repositories\Credenciais\CredencialRepository;

class RecuperarSenhaService {
  private int $periodoExpiracao = 300; // 5 minutos em segundos

  public function __construct(private CredencialRepository $credencialRepository) { }

  private function gerarCodigoDeterministico(string $email): string {
    // Calcular período atual (5 minutos)
    $periodo = floor(time() / $this->periodoExpiracao);

    $chaveSecreta = Env::get('APP_SECRET_KEY');
    $dados = "{$email}|{$periodo}|{$chaveSecreta}";

    $hash = hash('sha256', $dados);
    $numeros = preg_replace('#[^0-9]#', '', $hash);
    return substr($numeros, 0, 4);
  }

  public function enviarCodigoDeVerificacao(string $email): bool {
    if (!$this->credencialRepository->verificarEmailExistente($email)) {
      return false;
    }
    
    $codigoDeVerificacao = $this->gerarCodigoDeterministico($email);
    return $this->enviarEmail($email, $codigoDeVerificacao);
  }

  public function validarCodigo(string $email, string $codigoDigitado): bool {
    $codigoEsperado = $this->gerarCodigoDeterministico($email);
    return hash_equals($codigoDigitado, $codigoEsperado);
  }

  private function enviarEmail(string $email, string $codigo): bool {
    $resend = Resend::client(Env::get('API_RESEND_KEY'));

    try {
      $emailEnviado = $resend->emails->send([
        'from' => 'JOB4YOU <onboarding@resend.dev>',
        'to' => [$email],
        'subject' => 'Código de verificação para recuperar a senha.',
        'html' => "<h1>{$codigo}<h1>",
        'text' => "{$codigo}"
      ]);

      // Verifica se o email foi enviado com sucesso verificando se tem um ID
      $resultado = isset($emailEnviado) && $emailEnviado->id && $emailEnviado->id !== '';
      return $resultado;
    } catch(\Throwable $th) {
      error_log("RecuperarSenhaService::enviarEmail Fallha ao enviar o email - {$th->getMessage}");
      return false;
    }
  }

  public function redefinirSenha(string $email, string $novaSenha): bool {
    $foiAtualizado = $this->credencialRepository->atualizarSenha($email, $novaSenha);
    return $foiAtualizado;
  }
}