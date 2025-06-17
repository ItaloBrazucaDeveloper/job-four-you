export function initPasswordToggle() {
  const togglePasswordButton = document.getElementById('togglePassword');

  if (togglePasswordButton) {
    togglePasswordButton.addEventListener('click', function () {
      const senhaInput = document.getElementById('senha');
      const eyeIcon = document.getElementById('eyeIcon');

      if (senhaInput.type === "password") {
        senhaInput.type = "text";
        eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        senhaInput.type = "password";
        eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
      }
    });
  }
} 