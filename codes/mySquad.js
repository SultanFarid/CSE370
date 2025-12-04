document.addEventListener('DOMContentLoaded', function() {
    
    // 1. SELECT ELEMENTS
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const statusFilter = document.getElementById('statusFilter');
    const positionFilter = document.getElementById('positionFilter');
    const cards = document.querySelectorAll('.player-card');

    // 2. MAIN FILTER FUNCTION
    function runFilter() {
        // A. Get Filter Values
        const searchText = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value; 
        const selectedPos = positionFilter.value;  

        // B. Loop through every card
        cards.forEach(card => {
            // C. Retrieve Data from VISIBLE ELEMENTS
            const nameEl = card.querySelector('.player-name');
            const idEl = card.querySelector('.id-badge'); // Keeping ID logic here
            const jerseyEl = card.querySelector('.jersey-badge'); 
            const posEl = card.querySelector('.player-pos');
            const statusEl = card.querySelector('.status-indicator');

            // Safety Check (ID is optional to prevent crash if using older/newer HTML)
            if (!nameEl || !jerseyEl || !posEl || !statusEl) return;

            const name = nameEl.textContent.toLowerCase();
            const idText = idEl ? idEl.textContent.toLowerCase() : '';
            const jerseyText = jerseyEl.textContent.toLowerCase(); 
            const pos = posEl.textContent.trim();
            const status = statusEl.textContent.trim();

            // D. LOGIC: Match Checks
            
            // Search: Match Name OR Jersey OR ID
            const matchSearch = (searchText === '') || 
                                name.includes(searchText) || 
                                jerseyText.includes(searchText) ||
                                idText.includes(searchText);

            // Status: Match 'all' OR Exact Match
            const matchStatus = (selectedStatus === 'all') || (status === selectedStatus);

            // Position: Match 'all' OR Exact Match
            const matchPos = (selectedPos === 'all') || (pos === selectedPos);

            // E. Apply Visibility
            if (matchSearch && matchStatus && matchPos) {
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

    searchInput.addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            runFilter();
        }
    });

    statusFilter.addEventListener('change', runFilter);
    positionFilter.addEventListener('change', runFilter);

    // Run once on load
    runFilter();
});
