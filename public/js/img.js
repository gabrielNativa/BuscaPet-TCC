document.getElementById("foto").addEventListener("change", function (event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById("preview").src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
});
$(document).ready(function() {
    // Exibe a imagem pré-visualizada
    $('#fotos').on('change', function(e) {
        var reader = new FileReader();
        reader.onload = function(event) {
            $('#preview').attr('src', event.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    });
});
