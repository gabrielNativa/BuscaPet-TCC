document.addEventListener("DOMContentLoaded", function () {
    const helpIcon = document.getElementById("helpIcon");
    const popupHelp = document.getElementById("popupHelp");
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");



    togglePassword.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            togglePassword.textContent = "visibility_off";
        } else {
            passwordInput.type = "password";
            togglePassword.textContent = "visibility";
        }
    });

  
    helpIcon.addEventListener("click", function (event) {
        event.stopPropagation();
        popupHelp.classList.toggle("show");
    });


    document.addEventListener("click", function (event) {
        if (!helpIcon.contains(event.target) && !popupHelp.contains(event.target)) {
            popupHelp.classList.remove("show");
        }
    });
});
