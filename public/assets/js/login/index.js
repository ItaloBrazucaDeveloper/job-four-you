import { initPasswordToggle } from './passwordToggle.js';
import { initFormValidation } from './formValidation.js';

// Aguarda todo o conteúdo da página carregar antes de executar os scripts
document.addEventListener('DOMContentLoaded', function () {
  initPasswordToggle();
  initFormValidation();
});