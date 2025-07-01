document.getElementById("botao").addEventListener("click", function() {
    // Torna a nova seção visível e adiciona a classe para o efeito de fade-in
    var novaPagina = document.getElementById("novaPagina");
    novaPagina.style.display = "block";

    // Rola suavemente para a nova seção
    novaPagina.scrollIntoView({
        behavior: "smooth"
    });

    // Adiciona a classe 'visible' para ativar o efeito de fade-in
    setTimeout(function() {
        novaPagina.classList.add("visible");
    }, 300); // Atraso para garantir que a rolagem tenha começado
});
