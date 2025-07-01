// Mostra contagem de ONGs pendentes
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se o usuário é admin
    if (document.getElementById('link-approve')) {
        fetch('/api/ongs/pending/count')
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    document.getElementById('pending-count').textContent = data.count;
                }
            })
            .catch(error => console.error('Error:', error));
    }
});