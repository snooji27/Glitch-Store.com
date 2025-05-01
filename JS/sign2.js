document.addEventListener("DOMContentLoaded", function () {
    const daySelect = document.getElementById("daySelect");
    const monthSelect = document.getElementById("monthSelect");
    const yearSelect = document.getElementById("yearSelect");
  
    function fillDays() {
      for (let day = 1; day <= 31; day++) {
        const option = document.createElement("option");
        option.value = day;
        option.text = day;
        daySelect.appendChild(option);
      }
    }
  
    function fillMonths() {
      const monthNames = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
      ];
      monthNames.forEach((month, index) => {
        const option = document.createElement("option");
        option.value = index + 1;
        option.text = month;
        monthSelect.appendChild(option);
      });
    }
  
    function fillYears() {
      for (let year = 2025; year >= 1960; year--) {
        const option = document.createElement("option");
        option.value = year;
        option.text = year;
        yearSelect.appendChild(option);
      }
    }
  
    fillDays();
    fillMonths();
    fillYears();
  
    const form = document.getElementById("dobForm");
  
    form.addEventListener("submit", function (event) {
      const day = daySelect.value;
      const month = monthSelect.value;
      const year = yearSelect.value;
      const terms = document.getElementById("termsCheckbox").checked;
  
      if (!day || !month || !year) {
        alert("Please fill out all the fields for your date of birth.");
        event.preventDefault();
        return;
      }

    if (age < 13) {
      event.preventDefault();
      window.location.href = "Too_young.html";
      return;
    }

    if (!terms) {
      alert("You must agree to the terms and conditions to continue.");
      event.preventDefault();
    }
    });
  });
  