import { initFavoritar } from "./favoritar.js";

function initFiltrosModal() {
  const btnFiltros = document.getElementById('btnFiltros');
  const filtrosContainer = document.getElementById('filtrosContainer');
  const btnFecharFiltros = document.getElementById('btnFecharFiltros');
  const btnLimparFiltros = document.getElementById('btnLimparFiltros');
  const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');

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

  // Limpar filtros (mobile)
  if (btnLimparFiltros) {
    btnLimparFiltros.addEventListener('click', function() {
      const form = document.getElementById('filtros');
      if (form) {
        // Desmarcar todos os checkboxes e radio buttons
        const inputs = form.querySelectorAll('input[type="checkbox"], input[type="radio"]');
        inputs.forEach(input => {
          input.checked = false;
        });
        
        // Marcar "Nenhum" na categoria
        const nenhumRadio = form.querySelector('input[value="0"]');
        if (nenhumRadio) {
          nenhumRadio.checked = true;
        }
      }
    });
  }

  // Aplicar filtros (mobile)
  if (btnAplicarFiltros) {
    btnAplicarFiltros.addEventListener('click', function() {
      const form = document.getElementById('filtros');
      if (form) {
        form.submit();
      }
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

function initFiltrosAtivos() {
  // Adicionar funcionalidade aos botões de remover filtro individual
  const filtrosAtivos = document.querySelectorAll('.filtro-ativo');
  
  filtrosAtivos.forEach(filtro => {
    const btnRemover = filtro.querySelector('button');
    if (btnRemover) {
      btnRemover.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Obter o formulário pai
        const form = btnRemover.closest('form');
        if (form) {
          // Remover o input hidden correspondente ao filtro
          const inputHidden = form.querySelector('input[type="hidden"]');
          if (inputHidden) {
            inputHidden.remove();
          }
          
          // Submeter o formulário
          form.submit();
        }
      });
    }
  });

  // Funcionalidade do botão "Limpar filtros"
  const btnLimparTodos = document.querySelector('.btn-limpar-filtros');
  if (btnLimparTodos) {
    btnLimparTodos.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Redirecionar para a página sem parâmetros
      const url = new URL(window.location);
      url.search = '';
      window.location.href = url.toString();
    });
  }
}

document.addEventListener('DOMContentLoaded', function () {
  initFiltrosModal();
  initFavoritar();
  initFiltrosAtivos();

  // Submissão automática dos filtros em telas grandes
  const filtrosForm = document.getElementById('filtros');
  if (filtrosForm) {
    filtrosForm.addEventListener('change', function (e) {
      if (window.innerWidth >= 1024) {
        filtrosForm.submit();
      }
    });
  }
});