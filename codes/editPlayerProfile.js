document.addEventListener('DOMContentLoaded', function() {
    
    // 1. IMAGE ERROR HANDLING
    // Keeps the look consistent if the image fails to load
    const profileImg = document.querySelector('.player-img');
    if (profileImg) {
        profileImg.addEventListener('error', function() {
            this.style.display = 'none';
        });
    }

    // 2. FORM VALIDATION
    // Prevents submitting empty fields
    const editForm = document.querySelector('form');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            let hasError = false;
            
            // Select all inputs that are required
            const inputs = editForm.querySelectorAll('input[required], textarea');
            
            inputs.forEach(input => {
                // If input is empty or just whitespace
                if (!input.value.trim()) {
                    hasError = true;
                    input.style.borderColor = '#ef4444'; // Turn border red
                } else {
                    input.style.borderColor = '#cbd5e1'; // Reset border
                }
            });

            if (hasError) {
                e.preventDefault(); // Stop the form from sending
                alert("Please fill in all required fields.");
            }
        });
    }

    // 3. REMOVE BORDER COLOR ON TYPING
    // If the user starts fixing a red input, turn it back to gray
    const inputs = document.querySelectorAll('.edit-input');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '#cbd5e1';
        });
    });
});