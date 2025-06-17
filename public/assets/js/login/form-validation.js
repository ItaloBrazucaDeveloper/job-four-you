export function initFormValidation() {
  const loginForm = document.getElementById('loginForm');

  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      const email = document.getElementById('email');
      const senha = document.getElementById('senha');

      if (!email.value || !senha.value) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos!');
      }
    });
  }
} 