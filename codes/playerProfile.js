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
});