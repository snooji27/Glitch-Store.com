function showLogin() {
    document.querySelector(".form-box.login").style.display = "block";
    document.querySelector(".form-box.register").style.display = "none";
  }

  const container = document.querySelector(".container");
  const registerBtn = document.querySelector(".register-btn");
  const loginBtn = document.querySelector(".login-btn");

  registerBtn.addEventListener("click", () => {
    container.classList.add("active");
  });

  loginBtn.addEventListener("click", () => {
    container.classList.remove("active");
  });

  const registerForm = document.getElementById("registerForm");
  registerForm.addEventListener("submit", function (e) {
    const username = document.getElementById("regUsername").value.trim();
    const email = document.getElementById("regEmail").value.trim();
    const password = document.getElementById("regPassword").value.trim();

    if (!username || !email || !password) {
      e.preventDefault();
      alert("Please fill in all the required fields.");
    } else {
      window.location.href = "Signup_2.html";
    }
  });

  window.addEventListener("DOMContentLoaded", () => {
      const urlParams = new URLSearchParams(window.location.search);
      const formType = urlParams.get("form");

      if (formType === "signup") {
          document.querySelector(".container").classList.add("active");
      } else {
          document.querySelector(".container").classList.remove("active");
      }
  });