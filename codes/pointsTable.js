// MODAL
function openTeamModal(teamData) {
  const modal = document.getElementById("teamModal");

  // Populate Data
  document.getElementById("modalTeamName").textContent = teamData.Team_Name;
  document.getElementById(
    "modalLogo"
  ).src = `images/teams/${teamData.Team_Name}.png`;
  document.getElementById("modalLogo").onerror = function () {
    this.src = "images/default_team.png";
  };

  document.getElementById("modalCoach").textContent =
    teamData.Coach_Name || "N/A";
  document.getElementById("modalCaptain").textContent =
    teamData.Captain || "N/A";
  document.getElementById("modalBestPlayer").textContent =
    teamData.Best_Player || "N/A";
  document.getElementById("modalTopScorer").textContent =
    teamData.Most_Goal_Player || "N/A";
  document.getElementById("modalAssist").textContent =
    teamData.Most_Assist_Player || "N/A";

  modal.classList.add("active");
}

function closeModal(event) {
  // Only close if clicked on overlay or close button
  document.getElementById("teamModal").classList.remove("active");
}

// UPDATE TABLE
async function updateLeagueGeneration() {
  const btn = document.getElementById("updateTableBtn");

  // Safety check for disabled button
  if (btn.classList.contains("disabled")) return;

  if (
    !confirm(
      "Start Match Simulation?\n\nThis will:\n1. Simulate the result for the next Published match.\n2. Simulate results for all other teams in the league.\n3. Update the table permanently."
    )
  ) {
    return;
  }

  btn.disabled = true;
  btn.innerHTML =
    '<span class="spinner" style="width:16px;height:16px;border-width:2px;display:inline-block;"></span> Simulating...';

  try {
    const response = await fetch("pointsTable_action.php?action=update_table");
    const data = await response.json();

    if (data.success) {
      alert(
        `✅ Generation Updated!\n\nResult: ${data.match_result}\n\nLeague table has been updated.`
      );
      location.reload();
    } else {
      alert("❌ Error: " + data.message);
      btn.disabled = false;
      btn.innerHTML = "UPDATE TABLE";
    }
  } catch (error) {
    console.error("Error:", error);
    alert("❌ System Error");
    btn.disabled = false;
    btn.innerHTML = "UPDATE TABLE";
  }
}
