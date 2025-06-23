export function initFavoritar() {
  const favoritarButtons = document.querySelectorAll('.favoritar-btn');

  favoritarButtons.forEach(button => {
    button.addEventListener('click', function() {
      const idPublicacao = this.getAttribute('data-id');
      const isFavoritado = button.classList.contains('outline-rose-600');
      const url = `/favoritar-servico?id=${idPublicacao}`;

      console.log(`url: ${url} method: ${isFavoritado ? 'DELETE' : 'POST'}`);

      fetch(url, { method: isFavoritado ? 'DELETE' : 'POST' })
        .then(response => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          console.dir(data);
          if (data.sucesso) {
            if (isFavoritado) {
              button.classList.remove('outline-red-600', 'text-rose-500');
              button.classList.add('outline-zinc-700');
              button.querySelector('i').classList.remove('bi-heart-fill');
              button.querySelector('i').classList.add('bi-heart');
            } else {
              button.classList.remove('outline-zinc-700');
              button.classList.add('outline-red-600', 'text-rose-500');
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
