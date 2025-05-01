document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function () {
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;
        });
    }

    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function (e) {
            const username = document.querySelector("input[name='username']")?.value.trim();
            const password = document.querySelector("input[name='password']")?.value;

            if (!username) {
                alert("Username is required.");
                e.preventDefault();
                return;
            }

            if (!password) {
                alert("Password is required.");
                e.preventDefault();
                return;
            }
        });
    }
});