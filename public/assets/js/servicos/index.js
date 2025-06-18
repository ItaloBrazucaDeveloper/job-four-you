function initFiltrosModal() {
  const btnFiltros = document.getElementById('btnFiltros');
  const filtrosContainer = document.getElementById('filtrosContainer');
  const btnFecharFiltros = document.getElementById('btnFecharFiltros');

  // Abrir modal (mobile)
  if (btnFiltros) {
    btnFiltros.addEventListener('click', function() {
      filtrosContainer.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    });
  }

  // Fechar modal (mobile)
  if (btnFecharFiltros) {
    btnFecharFiltros.addEventListener('click', function() {
      filtrosContainer.classList.add('hidden');
      document.body.style.overflow = '';
    });
  }

  // Fechar ao clicar fora do conteúdo (mobile)
  if (filtrosContainer) {
    filtrosContainer.addEventListener('click', function(e) {
      if (window.innerWidth < 1024 && e.target === filtrosContainer) {
        filtrosContainer.classList.add('hidden');
        document.body.style.overflow = '';
      }
    });
  }

  // Fechar com ESC (mobile)
  document.addEventListener('keydown', function(e) {
    if (window.innerWidth < 1024 && e.key === 'Escape' && !filtrosContainer.classList.contains('hidden')) {
      filtrosContainer.classList.add('hidden');
      document.body.style.overflow = '';
    }
  });

  // Garantir que em telas grandes o filtro sempre aparece
  function handleResize() {
    if (window.innerWidth >= 1024) {
      filtrosContainer.classList.remove('hidden');
      document.body.style.overflow = '';
    } else {
      filtrosContainer.classList.add('hidden');
      document.body.style.overflow = '';
    }
  }
  window.addEventListener('resize', handleResize);
  handleResize();
}

document.addEventListener('DOMContentLoaded', function () {
  initFiltrosModal();
});