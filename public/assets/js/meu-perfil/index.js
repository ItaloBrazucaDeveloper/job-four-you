// Upload de foto de perfil
document.querySelector('.relative button').addEventListener('click', function () {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.click();

  input.onchange = function (e) {
    if (e.target.files && e.target.files[0]) {
      const reader = new FileReader();
      reader.onload = function (event) {
        document.querySelector('.profile-image').src = event.target.result;
        // Aqui você pode adicionar o código para enviar a imagem ao servidor
      };
      reader.readAsDataURL(e.target.files[0]);
    }
  };
});

// Alternar entre abas
const links = document.querySelectorAll('[href="#perfil"], [href="#servico"]');
links.forEach(link => {
  link.addEventListener('click', function (e) {
    e.preventDefault();

    // Atualiza aba ativa
    document.querySelectorAll('[href="#perfil"], [href="#servico"]').forEach(el => {
      el.classList.remove('border-primary-500', 'text-primary-500');
      el.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700');
    });
    this.classList.add('border-primary-500', 'text-primary-500');
    this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700');

    // Mostra seção correspondente
    document.getElementById('perfil').classList.add('hidden');
    document.getElementById('servico').classList.add('hidden');
    document.querySelector(this.getAttribute('href')).classList.remove('hidden');
  });
});