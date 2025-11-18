// editScoutedPlayerProfile.js - Fixed validation

document.addEventListener('DOMContentLoaded', function() {
    const dobInput = document.getElementById('scout_date_of_birth');
    const ageInput = document.getElementById('scout_age');

    // Set max and min date for date of birth
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());
    const minDate = new Date(today.getFullYear() - 40, today.getMonth(), today.getDate());
    
    dobInput.max = maxDate.toISOString().split('T')[0];
    dobInput.min = minDate.toISOString().split('T')[0];

    // Auto-calculate age from date of birth
    dobInput.addEventListener('change', function() {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        // Only show alert if age is invalid
        if (age < 16 || age > 40) {
            alert('Player must be between 16 and 40 years old');
            this.value = '';
            ageInput.value = '';
        } else {
            // Valid age - update the age field
            ageInput.value = age;
        }
    });

    // Phone number validation - only allow numbers and symbols
    const phoneInput = document.getElementById('scout_phone_no');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+\-() ]/g, '');
        });
    }
});
