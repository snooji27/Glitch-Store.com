document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("forgotForm");
    form.addEventListener("submit", function (e) {
        const email = form.email.value.trim();
        // Define basic email format pattern example@example.example
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;  
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email (e.g. example@example.com)");
            e.preventDefault();
        }
    });
});
