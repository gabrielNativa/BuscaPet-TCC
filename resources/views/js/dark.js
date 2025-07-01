const lightIcon = document.getElementById('light');
const darkIcon = document.getElementById('dark');
const body = document.body;

// Função para alternar entre modo claro e escuro
function toggleTheme() {
    body.classList.toggle('dark-mode'); // Alterna a classe 'dark-mode' no corpo
    lightIcon.classList.toggle('active'); // Alterna a classe 'active' no ícone de luz
    darkIcon.classList.toggle('active'); // Alterna a classe 'active' no ícone de sol

    // Salva a preferência do tema no localStorage
    if (body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'dark'); // Armazena 'dark' no localStorage
    } else {
        localStorage.setItem('theme', 'light'); // Armazena 'light' no localStorage
    }
}

// Verifica a preferência do tema ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme'); // Recupera o tema salvo no localStorage

    // Se o tema for 'dark', aplica o modo escuro
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        lightIcon.classList.remove('active');
        darkIcon.classList.add('active');
    } else {
        // Caso contrário, aplica o modo claro
        body.classList.remove('dark-mode');
        lightIcon.classList.add('active');
        darkIcon.classList.remove('active');
    }
});

// Adiciona o ouvinte de evento de clique para alternar o tema
lightIcon.addEventListener('click', toggleTheme);
darkIcon.addEventListener('click', toggleTheme);
