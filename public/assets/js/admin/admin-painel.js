// Admin Painel JavaScript
class AdminPainel {
  constructor() {
    this.init();
  }

  init() {
    this.initTabs();
    this.initSearch();
    this.initActions();
  }

  initTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
      button.addEventListener('click', () => {
        const targetId = button.id.replace('tab', '').toLowerCase() + 'Content';
        
        // Remove active class from all tabs
        tabButtons.forEach(btn => {
          btn.classList.remove('border-blue-500', 'text-blue-600');
          btn.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Hide all content
        tabContents.forEach(content => {
          content.classList.add('hidden');
        });
        
        // Add active class to clicked tab
        button.classList.remove('border-transparent', 'text-gray-500');
        button.classList.add('border-blue-500', 'text-blue-600');
        
        // Show target content
        const targetContent = document.getElementById(targetId);
        if (targetContent) {
          targetContent.classList.remove('hidden');
        }
      });
    });
  }

  initSearch() {
    const searchInputs = document.querySelectorAll('#searchInput, #searchServicos, #searchPrestadores');
    
    searchInputs.forEach(input => {
      input.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const table = e.target.closest('main').querySelector('table tbody');
        const rows = table.querySelectorAll('tr');
        
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          if (text.includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    });
  }

  initActions() {
    // Aprovar serviço
    document.addEventListener('click', (e) => {
      if (e.target.closest('[data-action="aprovar-servico"]')) {
        const button = e.target.closest('[data-action="aprovar-servico"]');
        const servicoId = button.dataset.servicoId;
        this.aprovarServico(servicoId);
      }
      
      if (e.target.closest('[data-action="rejeitar-servico"]')) {
        const button = e.target.closest('[data-action="rejeitar-servico"]');
        const servicoId = button.dataset.servicoId;
        this.rejeitarServico(servicoId);
      }
      
      if (e.target.closest('[data-action="alterar-status-usuario"]')) {
        const button = e.target.closest('[data-action="alterar-status-usuario"]');
        const usuarioId = button.dataset.usuarioId;
        const acao = button.dataset.acao;
        this.alterarStatusUsuario(usuarioId, acao);
      }
    });
  }

  async aprovarServico(servicoId) {
    try {
      const response = await fetch('/painel/aprovar-servico', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ servico_id: servicoId })
      });

      const result = await response.json();
      
      if (result.success) {
        this.showNotification('Serviço aprovado com sucesso!', 'success');
        // Recarregar a página ou remover a linha da tabela
        setTimeout(() => location.reload(), 1500);
      } else {
        this.showNotification(result.message || 'Erro ao aprovar serviço', 'error');
      }
    } catch (error) {
      console.error('Erro ao aprovar serviço:', error);
      this.showNotification('Erro interno do servidor', 'error');
    }
  }

  async rejeitarServico(servicoId) {
    try {
      const response = await fetch('/painel/rejeitar-servico', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ servico_id: servicoId })
      });

      const result = await response.json();
      
      if (result.success) {
        this.showNotification('Serviço rejeitado com sucesso!', 'success');
        // Recarregar a página ou remover a linha da tabela
        setTimeout(() => location.reload(), 1500);
      } else {
        this.showNotification(result.message || 'Erro ao rejeitar serviço', 'error');
      }
    } catch (error) {
      console.error('Erro ao rejeitar serviço:', error);
      this.showNotification('Erro interno do servidor', 'error');
    }
  }

  async alterarStatusUsuario(usuarioId, acao) {
    try {
      const response = await fetch('/painel/alterar-status-usuario', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
          usuario_id: usuarioId,
          acao: acao
        })
      });

      const result = await response.json();
      
      if (result.success) {
        this.showNotification(result.message, 'success');
        // Recarregar a página ou atualizar o status na tabela
        setTimeout(() => location.reload(), 1500);
      } else {
        this.showNotification(result.message || 'Erro ao alterar status do usuário', 'error');
      }
    } catch (error) {
      console.error('Erro ao alterar status do usuário:', error);
      this.showNotification('Erro interno do servidor', 'error');
    }
  }

  showNotification(message, type = 'info') {
    // Criar elemento de notificação
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
      type === 'success' ? 'bg-green-500 text-white' :
      type === 'error' ? 'bg-red-500 text-white' :
      'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover após 3 segundos
    setTimeout(() => {
      notification.remove();
    }, 3000);
  }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
  new AdminPainel();
}); 