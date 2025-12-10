// ==========================================
// FIXTURES.JS - 7-Line Formation Support
// ==========================================
// This JS handles tab switching, filtering, and squad detail toggling
// Field layout now supports: GK, LB/RB, CB, DM, CM, LW/RW, ST

// Switch between Upcoming and Previous tabs
function switchMainTab(tabName) {
    // Update tab buttons
    const tabs = document.querySelectorAll('.main-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');

    // Update sections
    const sections = document.querySelectorAll('.tab-section');
    sections.forEach(section => section.classList.remove('active'));

    if (tabName === 'upcoming') {
        document.getElementById('upcoming-section').classList.add('active');
    } else {
        document.getElementById('previous-section').classList.add('active');
    }
}

// Filter upcoming fixtures
function filterUpcoming(filter) {
    // Update filter tabs
    const upcomingSection = document.getElementById('upcoming-section');
    const tabs = upcomingSection.querySelectorAll('.filter-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');

    // Filter matches
    const matches = upcomingSection.querySelectorAll('.match-box');
    matches.forEach(match => {
        if (filter === 'all') {
            match.classList.remove('hidden');
        } else {
            const venue = match.dataset.venue;
            if (venue === filter) {
                match.classList.remove('hidden');
            } else {
                match.classList.add('hidden');
            }
        }
    });
}

// Filter previous matches
function filterPrevious(filter) {
    // Update filter tabs
    const previousSection = document.getElementById('previous-section');
    const tabs = previousSection.querySelectorAll('.filter-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');

    // Filter matches
    const matches = previousSection.querySelectorAll('.match-box');
    matches.forEach(match => {
        if (filter === 'all') {
            match.classList.remove('hidden');
        } else if (filter === 'home' || filter === 'away') {
            const venue = match.dataset.venue;
            if (venue === filter) {
                match.classList.remove('hidden');
            } else {
                match.classList.add('hidden');
            }
        } else {
            // Filter by result (won/lost/draw)
            const result = match.dataset.result;
            if (result === filter) {
                match.classList.remove('hidden');
            } else {
                match.classList.add('hidden');
            }
        }
    });
}

// Toggle squad details
function toggleSquad(index) {
    const previousSection = document.getElementById('previous-section');
    const boxes = previousSection.querySelectorAll('.match-box:not(.hidden)');
    const box = boxes[index];
    const details = box.querySelector('.match-details');

    if (box.classList.contains('expanded')) {
        box.classList.remove('expanded');
        details.classList.remove('active');
        return;
    }

    // Close all other boxes
    document.querySelectorAll('.match-box').forEach(b => {
        b.classList.remove('expanded');
    });
    document.querySelectorAll('.match-details').forEach(d => {
        d.classList.remove('active');
    });

    box.classList.add('expanded');
    details.classList.add('active');
}
