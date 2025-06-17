const menuButton = document.getElementById('menuButton'); // botão do menu mobile

if (menuButton) {
  menuButton.addEventListener('click', function () {
    const menu = document.getElementById('mobileMenu'); // menu que será mostrado ou ocultado
    menu.classList.toggle('hidden'); // adiciona ou remove a classe 'hidden' do Tailwind
  });
}