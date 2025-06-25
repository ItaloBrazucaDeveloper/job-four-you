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