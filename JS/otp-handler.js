document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll('.code-inputs input');
  
    inputs.forEach((input, index) => {
      // Restrict to numbers only
      input.addEventListener('keypress', (e) => {
        const char = String.fromCharCode(e.which);
        if (!/[0-9]/.test(char)) {
          e.preventDefault();
        }
      });
  
      input.addEventListener('input', () => {
        if (input.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
        // Auto-submit on last digit
        if (index === inputs.length - 1 && input.value.length === 1) {
          input.form.submit();
        }
      });
  
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && input.value === '' && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });
  
    // prevent empty submission
    document.querySelector("form").addEventListener("submit", function (e) {
      let allFilled = true;
      inputs.forEach(input => {
        if (input.value.trim() === "") {
          allFilled = false;
        }
      });
  
      if (!allFilled) {
        alert("Please enter all 6 digits.");
        e.preventDefault();
      }
    });
  });
  