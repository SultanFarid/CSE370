document.addEventListener('DOMContentLoaded', function() {
    // 1. HIGHLIGHT ACTIVE SIDEBAR LINK
    const currentLocation = location.href;
    const menuItems = document.querySelectorAll('.nav-item');

    menuItems.forEach(item => {
        
        /* --- FIX: RESPECT PHP ACTIVE CLASS --- */
        // If PHP already marked an item (e.g., 'My Squad' for coaches),
        // we skip the logic so JS doesn't remove it.
        if (item.classList.contains('active')) {
            return;
        }
        /* ------------------------------------- */

        // Standard Logic for other items
        if(currentLocation.includes(item.getAttribute('href'))) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
});