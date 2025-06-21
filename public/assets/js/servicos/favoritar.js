export function initFavoritar() {
  const favoritarButtons = document.querySelectorAll('.favoritar-btn');

  favoritarButtons.forEach(button => {
    button.addEventListener('click', function() {
      const idPublicacao = this.getAttribute('data-id');
      const url = `/servicos/favoritos/${idPublicacao}`;
      const isFavoritado = button.classList.contains('bg-red-500');

      fetch(url, { method: isFavoritado ? 'DELETE' : 'POST' })
        .then(response => response.json())
        .then(data => {
          if (data.sucesso) {
            if (isFavoritado) {
              button.classList.remove('bg-rose-500', 'text-gray-200');
              button.querySelector('i').classList.remove('bi-heart-fill');
              button.querySelector('i').classList.add('bi-heart');
            } else {
              button.classList.add('bg-rose-500', 'text-gray-200');
              button.querySelector('i').classList.remove('bi-heart');
              button.querySelector('i').classList.add('bi-heart-fill');
            }
          } else {
            console.error('Erro ao favoritar/desfavoritar serviço:', data.mensagem);
          }
        })
        .catch(error => console.error('Erro ao fazer requisição:', error));
    });
  });
}
