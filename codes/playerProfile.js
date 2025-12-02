document.addEventListener('DOMContentLoaded', function() {
    
    // 1. HIGHLIGHT ACTIVE SIDEBAR LINK
    const currentLocation = location.href;
    const menuItems = document.querySelectorAll('.nav-item');

    menuItems.forEach(item => {
        // Check if the link matches the current page URL
        if(item.href === currentLocation) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });

    // 2. IMAGE ERROR HANDLING
    // If the image (e.g. 15.jpg) doesn't exist, hide the <img> tag
    // This allows the text "Photo" (behind it) to be seen
    const profileImg = document.querySelector('.player-img');
    if (profileImg) {
        profileImg.addEventListener('error', function() {
            this.style.display = 'none';
        });
    }
    
    // NOTE: Logout confirmation removed as requested. 
    // Clicking logout will now happen instantly.
});