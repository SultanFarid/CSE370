document.addEventListener('DOMContentLoaded', function() {
    // 1. HIGHLIGHT ACTIVE SIDEBAR LINK
    const currentLocation = location.href;
    const menuItems = document.querySelectorAll('.nav-item');

    menuItems.forEach(item => {
        if(currentLocation.includes(item.getAttribute('href'))) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });

    // 2. IMAGE ERROR HANDLING
    const profileImg = document.querySelector('.player-img'); // Reuse class name if possible, or add it to img tag
    if (profileImg) {
        profileImg.addEventListener('error', function() {
            this.style.display = 'none';
        });
    }
});