const txtPassword = document.querySelector("#password");
const btnToggle = document.querySelector("#togglePassword");

btnToggle.addEventListener("click", function () {

    if (txtPassword.type === "password") {

        txtPassword.type = "text";

        this.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';

    } else {

        txtPassword.type = "password";

        this.innerHTML = '<i class="fa-solid fa-eye"></i>';

    }

});