
    document.getElementById('telefone').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove caracteres não numéricos
        value = value.replace(/^(\d{2})(\d)/g, '($1) $2'); // Coloca o parêntese nos dois primeiros dígitos
        value = value.replace(/(\d{5})(\d)/, '$1-$2'); // Coloca o hífen após os cinco primeiros dígitos
        e.target.value = value.substring(0, 15); // Limita a 15 caracteres
    });
