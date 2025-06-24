<?php
namespace App\Services\RecuperarSenha;

use Resend;
use KissPhp\Support\Env;

use App\Repositories\Credenciais\CredencialRepository;

class RecuperarSenhaService {
  private string $periodoExpiracao = 300; // 5 minutos em segundos

  public function __construct(private CredencialRepository $credencialRepository) { }

  private function gerarCodigoDeterministico(string $email): string {
    // Calcular período atual (5 minutos)
    $periodo = floor(time() / $this->periodoExpiracao);

    $chaveSecreta = Env::get('APP_SECRET_KEY');
    $dados = "{$email}|{$periodo}|{$chaveSecreta}";

    $hash = hash('256', $dados);
    $numeros = preg_replace('#[^0-9]#', '', $hash);
    return substr($numeros, 0, 4);
  }

  public function enviarCodigoDeVerificacao(string $email): bool {
    if (!$this->credencialRepository->verificarEmailExistente($email)) {
      return false;
    }
    $codigoDeVerificacao = $this->gerarCodigoDeterministico($email);
    $resend = Resend::client(Env::get('API_RESEND_KEY'));

    try {
      $emailEnviado = $resend->emails->send([
        'from' => 'JOB4YOU <onboarding@resend.dev>',
        'to' => [$email],
        'subject' => 'Código de verificação para recuperar a senha.',
        'html' => "<h1>{$codigoDeVerificacao}<h1>",
        'text' => "{$codigoDeVerificacao}"
      ]);

      return $emailEnviado->last_event === 'delivered';
    } catch(\Throwable $th) {
      error_log("RecuperarSenhaService::enviarCodigoDeVerificacao Falha ao enviar o email: {$th->getMessage()}");
      return false;
    }
  }

  public function validarCodigo(string $email, string $codigoDigitado): bool {
    $codigoEsperado = $this->gerarCodigoDeterministico($email);
    return hash_equals($codigoDigitado, $codigoEsperado);
  }
}