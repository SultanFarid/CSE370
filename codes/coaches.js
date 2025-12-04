document.addEventListener('DOMContentLoaded', function() {
    
    // 1. SELECT ELEMENTS
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const availabilityFilter = document.getElementById('availabilityFilter');
    const cards = document.querySelectorAll('.player-card');

    // 2. MAIN FILTER FUNCTION
    function runFilter() {
        // A. Get Filter Values
        const searchText = searchInput.value.toLowerCase().trim();
        const selectedStatus = availabilityFilter.value; 

        // B. Loop through every card
        cards.forEach(card => {
            // C. Retrieve Data from VISIBLE ELEMENTS
            const nameEl = card.querySelector('.player-name');
            
            // --- FIX: CHANGED CLASS FROM 'status-text' TO 'status-indicator' ---
            const statusEl = card.querySelector('.status-indicator'); 
            // -------------------------------------------------------------------

            // Safety Check
            if (!nameEl || !statusEl) return;

            const name = nameEl.textContent.toLowerCase();
            const status = statusEl.textContent.trim();

            // D. LOGIC: Match Checks
            
            // Search: Match Name
            const matchSearch = (searchText === '') || name.includes(searchText);

            // Status: Match 'all' OR Exact Match
            const matchStatus = (selectedStatus === 'all') || (status === selectedStatus);

            // E. Apply Visibility
            if (matchSearch && matchStatus) {
                card.style.display = 'flex'; // Show Card
            } else {
                card.style.display = 'none'; // Hide Card
            }
        });
    }

    // 3. EVENT LISTENERS
    if(searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault(); 
            runFilter();
        });
    }

    searchInput.addEventListener('input', runFilter);

    // Allow pressing "Enter" in search box
    searchInput.addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            runFilter();
        }
    });

    availabilityFilter.addEventListener('change', runFilter);

    // Run once on load to ensure everything is visible
    runFilter();
});