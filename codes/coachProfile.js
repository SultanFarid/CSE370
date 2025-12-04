document.addEventListener('DOMContentLoaded', function() {
    const currentLocation = location.href;
    const menuItems = document.querySelectorAll('.nav-item');

    // 1. SMART CHECK: Did PHP mark the 'Coaches' tab as active?
    // If yes, we are definitely viewing someone else. 
    // If no, we are viewing ourselves (even if URL has coach_id).
    const coachesTab = document.querySelector('a[href="coaches.php"]');
    const isModeViewingOthers = coachesTab && coachesTab.classList.contains('active');

    menuItems.forEach(item => {
        const linkAttr = item.getAttribute('href');

        /* --- SPECIAL LOGIC FOR VIEWING OTHERS --- */
        if (isModeViewingOthers) {
            // If PHP set us to "View Others", ensure Profile tab is NOT active
            if (linkAttr === 'coachProfile.php') {
                item.classList.remove('active');
            }
            // Do not touch anything else (Coaches tab remains active)
            return;
        }

        /* --- STANDARD LOGIC (VIEWING SELF) --- */
        // This runs if PHP decided we are viewing ourselves
        if(currentLocation.includes(linkAttr)) {
            // Only add 'active' if it's not already there
            if (!item.classList.contains('active')) {
                item.classList.add('active');
            }
        } else {
            item.classList.remove('active');
        }
    });
});