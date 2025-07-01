document.getElementById("foto").addEventListener("change", function (event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById("preview").src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
});