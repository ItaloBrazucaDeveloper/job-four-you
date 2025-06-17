import { initTabs } from './tabs.js';
import { initDeleteConfirmation } from './deleteConfirmation.js';

document.addEventListener('DOMContentLoaded', function () {
  initTabs();
  initDeleteConfirmation();
});

// Sistema de abas
document.querySelectorAll('.tab-link').forEach(link => {
  link.addEventListener('click', function () {
    // Remove active de todas as abas e links
    document.querySelectorAll('.tab-link').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));

    // Adiciona active no link clicado
    this.classList.add('active');

    // Mostra o conteúdo correspondente
    const tabId = this.getAttribute('data-tab');
    document.getElementById(tabId).classList.add('active');
  });
});

// Confirmação para exclusão
document.querySelectorAll('[title="Excluir"]').forEach(btn => {
  btn.addEventListener('click', function (e) {
    if (!confirm('Tem certeza que deseja excluir este item?')) {
      e.preventDefault();
    }
  });
});