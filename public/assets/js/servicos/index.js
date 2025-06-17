import { prestadorModal } from './modal.js';
import { initCarousel } from './carousel.js';

// Objeto para controlar o modal
const prestadorModal = {
  open: function (nome, profissao, valor, avaliacao, numAvaliacoes, foto, sobre, whatsapp) {
    document.getElementById('modalNome').textContent = nome;
    document.getElementById('modalProfissao').textContent = profissao;
    document.getElementById('modalValor').textContent = valor;
    document.getElementById('modalValor2').textContent = valor;
    document.getElementById('modalFoto').src = foto;
    document.getElementById('modalAvaliacoes').textContent = numAvaliacoes;
    document.getElementById('modalSobre').textContent = sobre || 'Profissional altamente qualificado com experiência comprovada na área.';

    // Configurar link do WhatsApp
    if (whatsapp) {
      const whatsappBtn = document.getElementById('modalWhatsapp');
      whatsappBtn.href = `https://wa.me/55${whatsapp}`;
      whatsappBtn.target = '_blank';
    }

    // Criar estrelas de avaliação
    const estrelasContainer = document.getElementById('modalEstrelas');
    estrelasContainer.innerHTML = '';
    const rating = parseFloat(avaliacao);

    for (let i = 1; i <= 5; i++) {
      const star = document.createElement('i');
      if (i <= Math.floor(rating)) {
        star.className = 'bi bi-star-fill';
      } else if (i === Math.ceil(rating) && rating % 1 > 0) {
        star.className = 'bi bi-star-half';
      } else {
        star.className = 'bi bi-star';
      }
      estrelasContainer.appendChild(star);
    }

    document.getElementById('prestadorModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  },

  close: function () {
    document.getElementById('prestadorModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
  }
};

// Inicializa o carrossel
document.addEventListener('DOMContentLoaded', function () {
  initCarousel();
});

// Fechar modal ao clicar fora
document.getElementById('prestadorModal').addEventListener('click', function (e) {
  if (e.target === this) {
    prestadorModal.close();
  }
});

// Exporta o modal para uso em outros arquivos
export { prestadorModal };