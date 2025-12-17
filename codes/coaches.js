document.addEventListener('DOMContentLoaded', function() {
    
    // SELECT ELEMENTS
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const availabilityFilter = document.getElementById('availabilityFilter');
    const cards = document.querySelectorAll('.player-card');

    // MAIN FILTER FUNCTION
    function runFilter() {
        // Get Filter Values
        const searchText = searchInput.value.toLowerCase().trim();
        const selectedStatus = availabilityFilter.value; 

        // Loop through every card
        cards.forEach(card => {
            // Retrieve Data from VISIBLE ELEMENTS
            const nameEl = card.querySelector('.player-name');
            const statusEl = card.querySelector('.status-indicator'); 

            // Safety Check
            if (!nameEl || !statusEl) return;

            const name = nameEl.textContent.toLowerCase();
            const status = statusEl.textContent.trim();
            
            // Search: Match Name
            const matchSearch = (searchText === '') || name.includes(searchText);

            // Status: Match 'all' OR Exact Match
            const matchStatus = (selectedStatus === 'all') || (status === selectedStatus);

            // Apply Visibility
            if (matchSearch && matchStatus) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // EVENT LISTENERS
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

    // Run once
    runFilter();
});