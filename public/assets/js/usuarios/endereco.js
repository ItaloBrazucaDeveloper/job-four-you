document.addEventListener('DOMContentLoaded', () => {
  const cepInput = document.querySelector('input[name="cep"]');
  const bairroInput = document.querySelector('input[name="bairro"]');
  const cidadeInput = document.querySelector('input[name="cidade"]');
  const estadoInput = document.querySelector('input[name="estado"]');
  const ruaInput = document.querySelector('input[name="rua"]');

  if (!cepInput) return;

  const buscarEndereco = async (cep) => {
    try {
      const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
      const data = await response.json();

      if (data.erro) {
        throw new Error('CEP não encontrado');
      }

      ruaInput.value = data.logradouro || '';
      bairroInput.value = data.bairro || '';
      cidadeInput.value = data.localidade || '';
      estadoInput.value = data.uf || '';
    } catch (error) {
      console.error('Erro ao buscar CEP:', error);
      // Limpa os campos em caso de erro
      ruaInput.value = '';
      bairroInput.value = '';
      cidadeInput.value = '';
      estadoInput.value = '';
    }
  };

  // Formata o CEP enquanto o usuário digita
  cepInput.addEventListener('input', (e) => {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 8) value = value.slice(0, 8);
    if (value.length > 5) {
      value = value.slice(0, 5) + '-' + value.slice(5);
    }
    e.target.value = value;
  });

  // Busca o endereço quando o CEP estiver completo
  cepInput.addEventListener('blur', () => {
    const cep = cepInput.value.replace(/\D/g, '');
    if (cep.length === 8) {
      buscarEndereco(cep);
    }
  });
}); 