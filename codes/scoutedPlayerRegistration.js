document.addEventListener("DOMContentLoaded", function () {
  const registrationForm = document.getElementById("registrationForm");
  const dobInput = document.getElementById("date_of_birth");
  const ageInput = document.getElementById("age");

  // 1. Auto-Calculate Age from Date of Birth
  dobInput.addEventListener("change", function () {
    if (this.value) {
      const dob = new Date(this.value);
      const today = new Date();
      let age = today.getFullYear() - dob.getFullYear();
      const monthDiff = today.getMonth() - dob.getMonth();

      // Adjust age if birthday hasn't happened yet this year
      if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < dob.getDate())
      ) {
        age--;
      }
      ageInput.value = age;
    }
  });

  // 2. Simple Validation on Submit
  registrationForm.addEventListener("submit", function (e) {
    let hasError = false;

    // List of all required input IDs
    const requiredIds = [
      "player_name",
      "age",
      "date_of_birth",
      "phone_no",
      "height",
      "weight",
      "preferred_foot",
      "injury_status",
      "position",
      "previous_club",
      "experience",
      "bio",
    ];

    // Loop through inputs to check if empty
    requiredIds.forEach((id) => {
      const el = document.getElementById(id);
      if (!el.value.trim()) {
        el.style.borderColor = "#ef4444"; // Turn border Red
        hasError = true;
      } else {
        el.style.borderColor = "#e2e8f0"; // Reset border
      }
    });

    // Check Bio Length (must be > 20 chars)
    const bio = document.getElementById("bio");
    if (bio.value.length < 20) {
      alert("Please write a longer Bio (at least 20 characters).");
      bio.style.borderColor = "#ef4444";
      hasError = true;
    }

    // If there are errors, stop the form from sending to PHP
    if (hasError) {
      e.preventDefault();
      // Scroll to top so user sees what is missing
      window.scrollTo(0, 0);
    }
  });
});
