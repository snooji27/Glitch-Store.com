document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const newPass = document.querySelector("input[name='new_password']");
    const confirmPass = document.querySelector("input[name='confirm_password']");
  
    // Check if new password and confirm password match
    form.addEventListener("submit", function (e) {
      if (newPass.value !== confirmPass.value) {
        alert("Passwords do not match.");
        e.preventDefault();
      }
    });
  });
  