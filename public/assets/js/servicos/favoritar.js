export function initFavoritar() {
  const favoritarButtons = document.querySelectorAll('.favoritar-btn');

  favoritarButtons.forEach(button => {
    button.addEventListener('click', function() {
      const idPublicacao = this.getAttribute('data-id');
      const isFavoritado = button.classList.contains('bg-rose-500');
      const url = `http://localhost:3001/favoritar-servico?id=${idPublicacao}`;

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
