document.addEventListener("DOMContentLoaded", function () {
  // FORM VALIDATION
  const editForm = document.querySelector("form");
  if (editForm) {
    editForm.addEventListener("submit", function (e) {
      let hasError = false;

      // Check required fields
      const inputs = editForm.querySelectorAll("input[required], select");

      inputs.forEach((input) => {
        if (!input.value.trim()) {
          hasError = true;
          input.style.borderColor = "#ef4444";
        } else {
          input.style.borderColor = "#cbd5e1";
        }
      });

      if (hasError) {
        e.preventDefault();
        alert("Please fill in all required fields.");
      }
    });
  }

  // 3. Reset error on typing
  const inputs = document.querySelectorAll(".edit-input");
  inputs.forEach((input) => {
    input.addEventListener("input", function () {
      this.style.borderColor = "#cbd5e1";
    });
  });
});
