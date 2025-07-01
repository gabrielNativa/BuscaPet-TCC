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

// User script 

let currentDeleteFormId = null;

// Função para abrir o modal
function openModal(userId) {
    const modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'flex';
    currentDeleteFormId = userId; // Armazena o ID do formulário
}

// Função para fechar o modal
function closeModal() {
    const modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'none';
    currentDeleteFormId = null; // Limpa o ID do formulário
}

// Função para confirmar a exclusão
function confirmDelete() {
    if (currentDeleteFormId) {
        const form = document.getElementById('deleteForm-' + currentDeleteFormId);
        form.submit(); // Envia o formulário
    }
    closeModal(); // Fecha o modal
}



//Adm script


// Função para abrir o modal
function openModal(adminId) {
    const modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'flex';
    currentDeleteFormId = adminId; // Armazena o ID do formulário
}

// Função para fechar o modal
function closeModal() {
    const modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'none';
    currentDeleteFormId = null; // Limpa o ID do formulário
}

// Função para confirmar a exclusão
function confirmDelete() {
    if (currentDeleteFormId) {
        const form = document.getElementById('deleteForm-' + currentDeleteFormId);
        form.submit(); // Envia o formulário
    }
    closeModal(); // Fecha o modal
}

// Ong modal 



// Função para abrir o modal
function openModal(ongId) {
    const modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'flex';
    currentDeleteFormId = ongId; // Armazena o ID do formulário
}

// Função para fechar o modal
function closeModal() {
    const modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'none';
    currentDeleteFormId = null; // Limpa o ID do formulário
}

// Função para confirmar a exclusão
function confirmDelete() {
    if (currentDeleteFormId) {
        const form = document.getElementById('deleteForm-' + currentDeleteFormId);
        form.submit(); // Envia o formulário
    }
    closeModal(); // Fecha o modal
}


//Pets modal 


function openModal(animalId) {
    const modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'flex';
    currentDeleteFormId = animalId; // Armazena o ID do formulário
}

// Função para fechar o modal
function closeModal() {
    const modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'none';
    currentDeleteFormId = null; // Limpa o ID do formulário
}

// Função para confirmar a exclusão
function confirmDelete() {
    if (currentDeleteFormId) {
        const form = document.getElementById('deleteForm-' + currentDeleteFormId);
        form.submit(); // Envia o formulário
    }
    closeModal(); // Fecha o modal
}
