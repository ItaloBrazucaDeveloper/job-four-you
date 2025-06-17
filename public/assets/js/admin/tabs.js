export function initTabs() {
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
}