// Função de alerta customizado reutilizável
export function alertaCustomizado(mensagem, tipo = 'info') {
  const notification = document.createElement('div');
  notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ` +
    (tipo === 'success' ? 'bg-green-500 text-white' :
     tipo === 'error' ? 'bg-red-500 text-white' :
     'bg-blue-500 text-white');
  notification.textContent = mensagem;
  document.body.appendChild(notification);
  setTimeout(() => {
    notification.remove();
  }, 3000);
} 