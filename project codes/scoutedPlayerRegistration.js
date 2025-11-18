// scoutedPlayerRegistration.js - Form validation with new fields

document.addEventListener('DOMContentLoaded', function () {
    const registrationForm = document.getElementById('registrationForm');
    const formErrors = document.getElementById('formErrors');
    const dobInput = document.getElementById('date_of_birth');
    const ageInput = document.getElementById('age');
    const heightInput = document.getElementById('height');
    const weightInput = document.getElementById('weight');

    // Set max and min date for date of birth
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());
    const minDate = new Date(today.getFullYear() - 40, today.getMonth(), today.getDate());

    dobInput.max = maxDate.toISOString().split('T')[0];
    dobInput.min = minDate.toISOString().split('T')[0];

    // Date of birth validation and auto-calculate age
    dobInput.addEventListener('change', function() {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        if (age < 16 || age > 40) {
            formErrors.textContent = 'Player must be between 16 and 40 years old';
            this.value = '';
            ageInput.value = '';
        } else {
            formErrors.textContent = '';
            ageInput.value = age;
        }
    });

    // Height validation
    heightInput.addEventListener('input', function() {
        const height = parseFloat(this.value);
        if (height && (height < 150 || height > 220)) {
            formErrors.textContent = 'Height must be between 150 cm and 220 cm';
        } else {
            formErrors.textContent = '';
        }
    });

    // Weight validation
    weightInput.addEventListener('input', function() {
        const weight = parseFloat(this.value);
        if (weight && (weight < 50 || weight > 120)) {
            formErrors.textContent = 'Weight must be between 50 kg and 120 kg';
        } else {
            formErrors.textContent = '';
        }
    });

    // Phone number formatting
    const phoneInput = document.getElementById('phone_no');
    phoneInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9+\-() ]/g, '');
    });

    // Form submission validation
    registrationForm.addEventListener('submit', function(e) {
        formErrors.textContent = '';
        
        // Check all required fields
        const requiredFields = [
            { field: document.getElementById('player_name'), name: 'Full Name' },
            { field: ageInput, name: 'Age' },
            { field: dobInput, name: 'Date of Birth' },
            { field: phoneInput, name: 'Phone Number' },
            { field: heightInput, name: 'Height' },
            { field: weightInput, name: 'Weight' },
            { field: document.getElementById('injury_status'), name: 'Injury Status' },
            { field: document.getElementById('preferred_foot'), name: 'Preferred Foot' },
            { field: document.getElementById('position'), name: 'Position' },
            { field: document.getElementById('current_club'), name: 'Current Club' }
        ];

        let missingFields = [];
        requiredFields.forEach(item => {
            if (!item.field.value.trim()) {
                missingFields.push(item.name);
                item.field.style.borderColor = 'var(--danger)';
            } else {
                item.field.style.borderColor = '';
            }
        });

        if (missingFields.length > 0) {
            e.preventDefault();
            formErrors.textContent = 'Please fill in: ' + missingFields.join(', ');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }

        // Validate age range
        const age = parseInt(ageInput.value);
        if (age < 16 || age > 40) {
            e.preventDefault();
            formErrors.textContent = 'Age must be between 16 and 40 years';
            ageInput.style.borderColor = 'var(--danger)';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }

        // Validate height
        const height = parseFloat(heightInput.value);
        if (height < 150 || height > 220) {
            e.preventDefault();
            formErrors.textContent = 'Height must be between 150 cm and 220 cm';
            heightInput.style.borderColor = 'var(--danger)';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }

        // Validate weight
        const weight = parseFloat(weightInput.value);
        if (weight < 50 || weight > 120) {
            e.preventDefault();
            formErrors.textContent = 'Weight must be between 50 kg and 120 kg';
            weightInput.style.borderColor = 'var(--danger)';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }

        // All validations passed
        return true;
    });

    // Clear error styling when user starts typing
    const allInputs = registrationForm.querySelectorAll('input, select');
    allInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '';
        });
    });
});
