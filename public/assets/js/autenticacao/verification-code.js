// Script para inputs de código de verificação de 4 dígitos

document.addEventListener('DOMContentLoaded', function () {
  const inputs = Array.from(document.querySelectorAll('#verification-code-inputs input'));

  inputs.forEach((input, idx) => {
    input.addEventListener('input', function (e) {
      const value = this.value.replace(/\D/g, '');
      this.value = value;
      if (value && idx < inputs.length - 1) {
        inputs[idx + 1].focus();
      }
    });

    input.addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' && !this.value && idx > 0) {
        inputs[idx - 1].focus();
      }
      // Permite apenas números, backspace, tab, setas
      if (!e.ctrlKey && !e.metaKey && !e.altKey && e.key.length === 1 && !/[0-9]/.test(e.key)) {
        e.preventDefault();
      }
    });
  });
}); 