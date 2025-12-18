document.addEventListener("DOMContentLoaded", function () {
  // HIGHLIGHT SIDEBAR
  const currentLocation = location.href;
  const menuItems = document.querySelectorAll(".nav-item");
  menuItems.forEach((item) => {
    if (currentLocation.includes(item.getAttribute("href")))
      item.classList.add("active");
    else item.classList.remove("active");
  });

  // FILTER LOGIC
  const searchInput = document.getElementById("searchInput");
  const statusFilter = document.getElementById("statusFilter");
  const positionFilter = document.getElementById("positionFilter");
  const cards = document.querySelectorAll(".scout-card");

  function filterCards() {
    const text = searchInput.value.toLowerCase().trim();
    const stat = statusFilter.value;
    const posVal = positionFilter.value;
    cards.forEach((card) => {
      const name = card.querySelector("h3").textContent.toLowerCase();
      const status = card.querySelector(".status-pill").textContent.trim();
      const pos = card.querySelector(".pos-badge").textContent.trim();
      const matchSearch = name.includes(text);
      const matchStatus = stat === "all" || status === stat;
      const matchPos = posVal === "all" || pos === posVal;
      card.style.display =
        matchSearch && matchStatus && matchPos ? "flex" : "none";
    });
  }
  if (searchInput) searchInput.addEventListener("input", filterCards);
  if (statusFilter) statusFilter.addEventListener("change", filterCards);
  if (positionFilter) positionFilter.addEventListener("change", filterCards);
});

// GEMINI API TRIGGER
async function generateReport(btn) {
  const card = btn.closest(".scout-card");
  const id = card.querySelector(".hidden-id").textContent;
  const status = card.querySelector(".status-pill").textContent.trim();

  const modal = document.getElementById("aiModal");
  const loader = document.getElementById("aiLoader");
  const result = document.getElementById("aiResult");

  modal.style.display = "block";
  result.innerHTML = "";
  loader.style.display = "block";

  try {
    const params = new URLSearchParams();
    params.append("player_id", id);
    params.append("status", status);

    // Pointing to the SINGLE file
    const response = await fetch("api_scouted_report.php", {
      method: "POST",
      body: params,
    });
    const data = await response.text();
    loader.style.display = "none";
    result.innerHTML = data;
  } catch (error) {
    loader.style.display = "none";
    result.innerHTML = "Error connecting to AI Scout service.";
  }
}

// UPDATE STATUS
function updateStatus(userId, newStatus) {
  if (confirm(`Move this player to ${newStatus} list?`)) {
    const formData = new FormData();
    formData.append("user_id", userId);
    formData.append("status", newStatus);

    fetch("update_scout_status.php", { method: "POST", body: formData })
      .then((res) => res.text())
      .then((data) => {
        // Trim whitespace to ensure clean comparison
        if (data.trim() === "success") {
          location.reload();
        } else {
          // ALERT THE ACTUAL ERROR MESSAGE FROM PHP
          alert("Update Failed: " + data);
        }
      });
  }
}

// PROMOTE MODAL
function openPromoteModal(id, name) {
  document.getElementById("promoteModal").style.display = "block";
  document.getElementById("promoteId").value = id;
  document.getElementById("pName").textContent = name;
}
function closeModal(id) {
  document.getElementById(id).style.display = "none";
}
window.onclick = function (e) {
  if (e.target.classList.contains("modal")) e.target.style.display = "none";
};
