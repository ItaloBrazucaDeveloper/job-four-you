import { alertaCustomizado } from './alerta-customizado.js';

document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('btn-gerar-link-avaliacao');
  if (!btn) return;
  btn.addEventListener('click', async function() {
    const servicoId = btn.getAttribute('data-servico-id');
    if (!servicoId) {
      alertaCustomizado('Ocorreu um erro ao gerar o link para avaliação.', 'error');
      return;
    }
    try {
      const response = await fetch(`/avaliacao/gerar-url/${servicoId}`);
      if (!response.ok) throw new Error('Erro ao gerar link.');
      const data = await response.json();
      if (!data.url) throw new Error('URL não recebida.');
      await navigator.clipboard.writeText(window.location.origin + data.url);
      alertaCustomizado('URL gerada e copiada para o clipboard com sucesso!', 'success');
    } catch (e) {
      alertaCustomizado('Ocorreu um erro ao gerar o link para avaliação.', 'error');
    }
  });
}); 