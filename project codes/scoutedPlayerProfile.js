// scoutedPlayerProfile.js - Simple navigation

document.addEventListener('DOMContentLoaded', function () {
    // Navigation functionality
    const navLinks = document.querySelectorAll('.nav-link');
    const contentSections = document.querySelectorAll('.content-section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Don't prevent default for logout link
            if (this.classList.contains('logout-link')) {
                return;
            }
            
            e.preventDefault();

            // Remove active class from all links and sections
            navLinks.forEach(nl => {
                nl.parentElement.classList.remove('active');
            });
            contentSections.forEach(section => {
                section.classList.remove('active');
            });

            // Add active class to clicked link
            this.parentElement.classList.add('active');

            // Show corresponding section
            const targetId = this.getAttribute('data-target');
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.classList.add('active');
            }
        });
    });
});
