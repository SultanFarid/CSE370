document.addEventListener('DOMContentLoaded', function() {

    // HIGHLIGHT ACTIVE SIDEBAR LINK
    const currentLocation = location.href;
    const menuItems = document.querySelectorAll('.nav-item');
    menuItems.forEach(item => {
        if(currentLocation.includes(item.getAttribute('href'))) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });

    // SEARCH & FILTER LOGIC
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const cards = document.querySelectorAll('.injury-card');

    function filterReports() {
        const searchText = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value;

        cards.forEach(card => {
            const name = card.querySelector('.player-name').textContent.toLowerCase();
            const injury = card.querySelector('.injury-name').textContent.toLowerCase();
            const status = card.querySelector('.status-pill').textContent.trim();

            const matchesSearch = name.includes(searchText) || injury.includes(searchText);
            const matchesStatus = (statusValue === 'all') || (status === statusValue);

            // Toggle Visibility
            if (matchesSearch && matchesStatus) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if(searchInput) {
        searchInput.addEventListener('input', filterReports);
    }
    if(statusFilter) {
        statusFilter.addEventListener('change', filterReports);
    }
});

// MODAL
function openEditModal(presId, playerId, currentStatus, currentDate) {
    const modal = document.getElementById("editModal");
    if(modal) {
        document.getElementById("modal_pres_id").value = presId;
        document.getElementById("modal_player_id").value = playerId;
        document.getElementById("modal_status").value = currentStatus;
        document.getElementById("modal_date").value = currentDate;
        modal.style.display = "block";
    }
}

function openAddModal() {
    const modal = document.getElementById("addModal");
    if(modal) modal.style.display = "block";
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if(modal) modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
    }
}