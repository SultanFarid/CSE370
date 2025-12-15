// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get all elements
    const linkLogin = document.getElementById('link-login');
    const linkSignup = document.getElementById('link-signup');
    const panelLogin = document.getElementById('panel-login');
    const panelSignup = document.getElementById('panel-signup');
    const gotoSignup = document.getElementById('goto-signup');
    const gotoLogin = document.getElementById('goto-login');

    // Show panel function
    function showPanel(which) {
        if (which === 'signup') {
            panelSignup.classList.remove('hidden');
            panelLogin.classList.add('hidden');
            if (linkSignup) linkSignup.classList.add('active');
            if (linkLogin) linkLogin.classList.remove('active');
        } else {
            panelLogin.classList.remove('hidden');
            panelSignup.classList.add('hidden');
            if (linkLogin) linkLogin.classList.add('active');
            if (linkSignup) linkSignup.classList.remove('active');
        }
    }

    // Add click event listeners
    if (linkLogin) {
        linkLogin.addEventListener('click', function(e) {
            e.preventDefault();
            showPanel('login');
        });
    }
    
    if (linkSignup) {
        linkSignup.addEventListener('click', function(e) {
            e.preventDefault();
            showPanel('signup');
        });
    }
    
    if (gotoSignup) {
        gotoSignup.addEventListener('click', function(e) {
            e.preventDefault();
            showPanel('signup');
        });
    }
    
    if (gotoLogin) {
        gotoLogin.addEventListener('click', function(e) {
            e.preventDefault();
            showPanel('login');
        });
    }

    // Start with login panel
    showPanel('login');
});

// Password validation
document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signupForm');
    
    if (signupForm) {
        signupForm.addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm').value;
            
            // Check if passwords match
            if (password !== confirm) {
                alert('Passwords do not match! Please check and try again.');
                event.preventDefault();
                return false;
            }
            
            // Check password length
            if (password.length < 6) {
                alert('Password must be at least 6 characters long!');
                event.preventDefault();
                return false;
            }
            
            return true;
        });
    }
});