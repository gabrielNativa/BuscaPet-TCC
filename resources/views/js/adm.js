document.addEventListener("DOMContentLoaded", function() {
    const sideMenu = document.querySelector('aside');
    const menuBtn = document.querySelector('#menu-btn');
    const closeBtn = document.querySelector('#close-btn');
   
    if (menuBtn && closeBtn && sideMenu) {
        // Ao clicar no menu, adiciona a classe .show-menu
        menuBtn.addEventListener('click', () => {
            sideMenu.classList.add('show-menu');
            sideMenu.style.display = 'block';
        });

        // Ao clicar no botão de fechar, remove a classe .show-menu
        closeBtn.addEventListener('click', () => {
            sideMenu.classList.remove('show-menu');
            sideMenu.style.display = 'none';
        });
    }



    
});
document.getElementById('formAdm').addEventListener('submit', function (event) {
    let formIsValid = true;

    // Selecionar todos os campos do formulário
    const fields = document.querySelectorAll('input[required]');
    fields.forEach(function (field) {
        if (!field.checkValidity()) {
            field.classList.add('is-invalid'); // Adicionar classe de erro
            formIsValid = false;
        } else {
            field.classList.remove('is-invalid'); // Remover classe de erro
        }
    });

    // Se o formulário não for válido, impedir o envio
    if (!formIsValid) {
        event.preventDefault();
    }
});

// Adicionando máscara de dados para os campos com máscara


function applyMask(event, mask) {
    let value = event.target.value.replace(/\D/g, '');
    let formattedValue = mask;
    let i = 0;
    for (let char of formattedValue) {
        if (/\d/.test(char) && i < value.length) {
            formattedValue = formattedValue.replace(char, value[i]);
            i++;
        }
    }
    event.target.value = formattedValue;
}



