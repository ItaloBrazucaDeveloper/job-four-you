export function initDeleteConfirmation() {
  document.querySelectorAll('[title="Excluir"]').forEach(btn => {
    btn.addEventListener('click', function (e) {
      if (!confirm('Tem certeza que deseja excluir este item?')) {
        e.preventDefault();
      }
    });
  });
} 