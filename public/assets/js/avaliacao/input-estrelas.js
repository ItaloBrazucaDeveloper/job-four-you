document.addEventListener('DOMContentLoaded', function () {
  const avaliacaoDiv = document.getElementById('avaliacao');
  if (!avaliacaoDiv) return;

  const radios = avaliacaoDiv.querySelectorAll('input[type="radio"][name="nota"]');
  const starLabels = avaliacaoDiv.querySelectorAll('label[for^="star-"]');
  const starOverlays = avaliacaoDiv.querySelectorAll('.star-full-overlay, .star-half-overlay');

  function updateStars(nota) {
    starLabels.forEach((label, idx) => {
      const starIndex = idx + 1;
      const icon = label.querySelector('i');
      if (starIndex <= nota) {
        icon.classList.remove('bi-star');
        icon.classList.add('bi-star-fill', 'text-gray-800');
      } else {
        icon.classList.remove('bi-star-fill', 'text-gray-800');
        icon.classList.add('bi-star');
      }
    });
  }

  // Permitir desmarcar todas as estrelas ao clicar na estrela já selecionada
  starOverlays.forEach(overlay => {
    overlay.addEventListener('click', function (e) {
      const nota = parseInt(this.getAttribute('data-star'));
      if (radios[nota - 1].checked) {
        radios.forEach(r => (r.checked = false));
        updateStars(0);
      } else {
        radios[nota - 1].checked = true;
        updateStars(nota);
      }
    });
  });

  // Clique direto no input radio (acessibilidade)
  radios.forEach((radio, idx) => {
    radio.addEventListener('change', function () {
      updateStars(this.checked ? idx + 1 : 0);
    });
  });

  // Inicialização: verifica se já existe uma nota selecionada
  const checkedRadio = Array.from(radios).find(r => r.checked);
  if (checkedRadio) {
    updateStars(parseInt(checkedRadio.value));
  } else {
    updateStars(0);
  }
});
