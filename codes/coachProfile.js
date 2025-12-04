document.addEventListener('DOMContentLoaded', function() {
    // 1. HIGHLIGHT ACTIVE SIDEBAR LINK
    const currentLocation = location.href;
    const menuItems = document.querySelectorAll('.nav-item');

    menuItems.forEach(item => {
        // Using includes() helps match "mySquad.php" even if URL has query params
        if(currentLocation.includes(item.getAttribute('href'))) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
});