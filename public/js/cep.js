document.getElementById('cep').addEventListener('blur', function() {
    let cep = this.value.replace(/\D/g, ''); // Remove caracteres não numéricos

    if (cep.length !== 8) {
        alert('CEP inválido!');
        return;
    }

    let url = `https://viacep.com.br/ws/${cep}/json/`;

    fetch(url)
        .then(response => response.json())
        .then(data => {

            document.getElementById('endereco').value = data.logradouro;
            
            // Corrigir para garantir que o valor da UF seja em maiúsculo
            document.getElementById('uf').value = data.uf.toUpperCase(); 
        })
        .catch(error => console.error('Erro ao buscar CEP:', error));
});
