// MODAL FUNCTIONS
function openAddModal() {
  document.getElementById("addModal").style.display = "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

// Close modal on outside click
window.onclick = function (event) {
  if (event.target.classList.contains("modal")) {
    event.target.style.display = "none";
  }
};

// TAB SWITCHING
function switchPlayerTab(type) {
  // Update tab buttons
  const tabs = document.querySelectorAll(".tab-btn");
  tabs.forEach((tab) => tab.classList.remove("active"));
  event.target.classList.add("active");

  // Update sections
  const sections = document.querySelectorAll(".player-section");
  sections.forEach((section) => section.classList.remove("active"));

  if (type === "regular") {
    document.getElementById("regularPlayersSection").classList.add("active");
  } else {
    document.getElementById("scoutedPlayersSection").classList.add("active");
  }
}

// Tab switching for EDIT modal
function switchPlayerTabEdit(type) {
  // Update tab buttons
  const tabs = document.querySelectorAll("#editModal .tab-btn");
  tabs.forEach((tab) => tab.classList.remove("active"));
  event.target.classList.add("active");

  // Update sections
  document
    .getElementById("editRegularPlayersSection")
    .classList.remove("active");
  document
    .getElementById("editScoutedPlayersSection")
    .classList.remove("active");

  if (type === "regular") {
    document
      .getElementById("editRegularPlayersSection")
      .classList.add("active");
  } else {
    document
      .getElementById("editScoutedPlayersSection")
      .classList.add("active");
  }
}

// PLAYER SELECTION HELPERS
function selectByPosition(position, type) {
  const selector =
    type === "regular"
      ? 'input[name="regular_players[]"]'
      : 'input[name="scouted_players[]"]';
  const checkboxes = document.querySelectorAll(selector);
  checkboxes.forEach((cb) => {
    if (cb.dataset.position === position && !cb.disabled) {
      cb.checked = true;
    }
  });
}

function selectOnlyFit(type) {
  const selector =
    type === "regular"
      ? 'input[name="regular_players[]"]'
      : 'input[name="scouted_players[]"]';
  const checkboxes = document.querySelectorAll(selector);
  checkboxes.forEach((cb) => {
    if (cb.dataset.injury === "Fit") {
      cb.checked = true;
    } else {
      cb.checked = false;
    }
  });
}

function clearAllPlayers(type) {
  const selector =
    type === "regular"
      ? 'input[name="regular_players[]"]'
      : 'input[name="scouted_players[]"]';
  const checkboxes = document.querySelectorAll(selector);
  checkboxes.forEach((cb) => (cb.checked = false));
}

// CREATE SESSION FORM SUBMIT
document
  .getElementById("createSessionForm")
  ?.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Get selected regular players
    const regularPlayers = Array.from(
      document.querySelectorAll('input[name="regular_players[]"]:checked')
    ).map((cb) => cb.value);

    // Get selected scouted players
    const scoutedPlayers = Array.from(
      document.querySelectorAll('input[name="scouted_players[]"]:checked')
    ).map((cb) => cb.value);

    // Combine both arrays
    const allPlayers = [...regularPlayers, ...scoutedPlayers];

    if (allPlayers.length === 0) {
      alert("Please select at least one player for the session.");
      return;
    }

    // Clear default form data and add combined players
    formData.delete("regular_players[]");
    formData.delete("scouted_players[]");
    formData.append("players", JSON.stringify(allPlayers));

    try {
      const response = await fetch("create_training_session.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert("✅ Training session created successfully!");
        closeModal("addModal");
        location.reload();
      } else {
        alert("❌ Error: " + result.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("❌ Failed to create session. Please try again.");
    }
  });

// FILTERING (Player View)
const weekFilter = document.getElementById("weekFilter");
const statusFilter = document.getElementById("statusFilter");

if (weekFilter) {
  weekFilter.addEventListener("change", filterPlayerSessions);
}

if (statusFilter) {
  statusFilter.addEventListener("change", filterPlayerSessions);
}

function filterPlayerSessions() {
  const weekValue = weekFilter?.value || "all";
  const statusValue = statusFilter?.value || "all";

  const weekSections = document.querySelectorAll(".week-section");
  const sessionCards = document.querySelectorAll(".session-card");

  // Filter week sections
  weekSections.forEach((section) => {
    const sectionWeek = section.dataset.week;
    if (weekValue === "all" || sectionWeek === weekValue) {
      section.style.display = "block";
    } else {
      section.style.display = "none";
    }
  });

  // Filter by status within visible weeks
  sessionCards.forEach((card) => {
    const cardStatus = card.dataset.status;
    const weekSection = card.closest(".week-section");

    if (weekSection && weekSection.style.display !== "none") {
      if (statusValue === "all" || cardStatus === statusValue) {
        card.style.display = "flex";
      } else {
        card.style.display = "none";
      }
    }
  });
}

// FILTERING (Coach View)
const sessionStatusFilter = document.getElementById("sessionStatusFilter");
const sessionTypeFilter = document.getElementById("sessionTypeFilter");

if (sessionStatusFilter) {
  sessionStatusFilter.addEventListener("change", filterCoachSessions);
}

if (sessionTypeFilter) {
  sessionTypeFilter.addEventListener("change", filterCoachSessions);
}

function filterCoachSessions() {
  const statusValue = sessionStatusFilter?.value || "all";
  const typeValue = sessionTypeFilter?.value || "all";

  const cards = document.querySelectorAll(".session-card");

  cards.forEach((card) => {
    const cardStatus = card.dataset.status;
    const cardType = card.dataset.type;

    const matchesStatus = statusValue === "all" || cardStatus === statusValue;
    const matchesType = typeValue === "all" || cardType === typeValue;

    if (matchesStatus && matchesType) {
      card.style.display = "flex";
    } else {
      card.style.display = "none";
    }
  });
}

// COMPLETE SESSION (SIMPLIFIED)
async function completeSession(sessionId) {
  if (!confirm("Mark this training session as completed?")) {
    return;
  }

  // Get session details first
  try {
    const response = await fetch(
      `get_session_details.php?session_id=${sessionId}`
    );
    const data = await response.json();

    if (!data.success) {
      alert("Error loading session: " + data.message);
      return;
    }

    // Generate scores for all players
    const players = [];
    data.players.forEach((player) => {
      const scores = generatePositionBasedScores(
        player.Position,
        player.Current_Injury_Status
      );

      // Generate random remark
      const remarks = [
        "Good performance overall",
        "Shows improvement",
        "Solid work ethic",
        "Needs focus on positioning",
        "Excellent technical ability",
        "Strong performance today",
        "Shows good potential",
        "Consistent effort",
        "Great decision making",
        "Needs improvement in passing",
        "Reads the game well",
        "Needs better communication",
        "Demonstrated great leadership",
        "Lacked concentration late on",
        "Excellent spatial awareness",
        "Remained calm under pressure",
        "Tactically disciplined",
        "Needs to track back more",
        "Clinical finishing today",
        "First touch needs work",
        "Great ball control",
        "Dominant in the air",
        "Precise passing range",
        "Solid defensive display",
        "Creative playmaker",
        "Tackling was timed perfectly",
        "High energy levels",
        "Needs to improve stamina",
        "Fast and agile",
        "Outworked the opposition",
        "Lacked pace in key moments",
        "Physicality was a key asset",
        "Impact player off the bench",
        "Man of the match performance",
        "Needs to be more consistent",
        "Training efforts paying off",
        "Quiet game today",
        "Key contributor to the win",
      ];

      players.push({
        player_id: player.Player_ID,
        technical_score: scores.technical,
        physical_score: scores.physical,
        tactical_score: scores.tactical,
        remarks: remarks[Math.floor(Math.random() * remarks.length)],
      });
    });

    // Send to backend
    const completeResponse = await fetch("complete_training_session.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        session_id: sessionId,
        players: players,
      }),
    });

    const result = await completeResponse.json();

    if (result.success) {
      alert("Training session completed successfully!");
      location.reload();
    } else {
      alert("❌ Error: " + result.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Failed to complete session.");
  }
}

// GENERATE POSITION-BASED SCORES
function generatePositionBasedScores(position, injury) {
  const ranges = {
    Goalkeeper: {
      technical: [7.0, 9.5],
      physical: [6.5, 8.5],
      tactical: [6.0, 8.0],
    },
    Defender: {
      technical: [6.0, 8.5],
      physical: [7.0, 9.0],
      tactical: [7.5, 9.5],
    },
    Midfielder: {
      technical: [7.5, 9.5],
      physical: [6.5, 8.5],
      tactical: [7.0, 9.0],
    },
    Striker: {
      technical: [7.0, 9.0],
      physical: [7.5, 9.5],
      tactical: [6.0, 8.0],
    },
    Winger: {
      technical: [7.5, 9.0],
      physical: [7.0, 9.0],
      tactical: [6.5, 8.5],
    },
  };

  const range = ranges[position] || {
    technical: [6.0, 9.0],
    physical: [6.0, 9.0],
    tactical: [6.0, 9.0],
  };

  // Generate random scores within ranges
  let technical = (
    Math.random() * (range.technical[1] - range.technical[0]) +
    range.technical[0]
  ).toFixed(1);
  let physical = (
    Math.random() * (range.physical[1] - range.physical[0]) +
    range.physical[0]
  ).toFixed(1);
  let tactical = (
    Math.random() * (range.tactical[1] - range.tactical[0]) +
    range.tactical[0]
  ).toFixed(1);

  // Reduce scores for injured/recovering players
  if (injury === "Recovering" || injury === "Doubtful") {
    technical = Math.max(5.0, technical - 1.5).toFixed(1);
    physical = Math.max(5.0, physical - 2.0).toFixed(1);
    tactical = Math.max(5.0, tactical - 1.0).toFixed(1);
  }

  return { technical, physical, tactical };
}

// VIEW SESSION DETAILS
async function viewSessionDetails(sessionId) {
  const modal = document.getElementById("viewModal");
  const content = document.getElementById("viewContent");

  content.innerHTML = '<div class="loader">⏳ Loading session details...</div>';
  modal.style.display = "block";

  try {
    const response = await fetch(
      `get_session_details.php?session_id=${sessionId}`
    );
    const data = await response.json();

    if (data.success) {
      renderSessionDetails(data.session, data.players);
    } else {
      content.innerHTML = `<p style="color: #ef4444; text-align: center; padding: 20px;">❌ Error: ${data.message}</p>`;
    }
  } catch (error) {
    console.error("Error:", error);
    content.innerHTML =
      '<p style="color: #ef4444; text-align: center; padding: 20px;">❌ Failed to load session details.</p>';
  }
}

function renderSessionDetails(session, players) {
  const content = document.getElementById("viewContent");

  let html = `
        <div class="session-info-box">
            <h4>📋 ${session.Session_Type}</h4>
            <p><strong>Date:</strong> ${session.Session_date} | <strong>Time:</strong> ${session.Session_time}</p>
            <span class="status-badge">✅ COMPLETED</span>
        </div>
        
        <div class="eval-table-wrapper">
            <table class="eval-table">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>Position</th>
                        <th>Attendance</th>
                        <th>Technical</th>
                        <th>Physical</th>
                        <th>Tactical</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
    `;

  players.forEach((player) => {
    const attendanceColor =
      player.participation_status === "Attended" ? "#10b981" : "#ef4444";

    html += `
            <tr>
                <td><strong>${player.Name}</strong></td>
                <td>${player.Position}</td>
                <td>
                    <span style="padding: 6px 12px; background: ${attendanceColor}; color: white; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                        ${player.participation_status}
                    </span>
                </td>
                <td><strong>${
                  player.Technical_score ? player.Technical_score : "-"
                }</strong></td>
                <td><strong>${
                  player.Physical_score ? player.Physical_score : "-"
                }</strong></td>
                <td><strong>${
                  player.Tactical_score ? player.Tactical_score : "-"
                }</strong></td>
                <td>${player.Coach_remarks ? player.Coach_remarks : "-"}</td>
            </tr>
        `;
  });

  html += `
                </tbody>
            </table>
        </div>
        
        <button class="modal-close-btn" onclick="closeModal('viewModal')">Close</button>
    `;

  content.innerHTML = html;
}

// EDIT SESSION FUNCTIONALITY
async function editSession(sessionId) {
  try {
    const response = await fetch(
      `get_session_details.php?session_id=${sessionId}`
    );
    const data = await response.json();

    if (!data.success) {
      alert("Error loading session: " + data.message);
      return;
    }

    // 1. Populate basic fields
    document.getElementById("edit_session_id").value = sessionId;
    document.getElementById("edit_session_date").value =
      data.session.Session_date;
    document.getElementById("edit_session_time").value =
      data.session.Session_time;
    document.getElementById("edit_session_type").value =
      data.session.Session_Type;

    // 2. Pre-select the assigned coach
    if (data.coach_id) {
      document.getElementById("edit_assigned_coach").value = data.coach_id;
    }

    // 3. Clear all checkboxes first
    document
      .querySelectorAll('#editModal input[type="checkbox"]')
      .forEach((cb) => (cb.checked = false));

    // 4. Check the players who are in this session
    const playerIds = data.players.map((p) => p.Player_ID.toString());

    playerIds.forEach((playerId) => {
      // Check in regular players
      const regularCheckbox = document.querySelector(
        `#editModal input[name="edit_regular_players[]"][value="${playerId}"]`
      );
      if (regularCheckbox) {
        regularCheckbox.checked = true;
      }

      // Check in scouted players
      const scoutedCheckbox = document.querySelector(
        `#editModal input[name="edit_scouted_players[]"][value="${playerId}"]`
      );
      if (scoutedCheckbox) {
        scoutedCheckbox.checked = true;
      }
    });

    // 5. Open the modal
    document.getElementById("editModal").style.display = "block";
  } catch (error) {
    console.error("Error:", error);
    alert("Failed to load session details. Please try again.");
  }
}

// EDIT SESSION FORM SUBMIT
document
  .getElementById("editSessionForm")
  ?.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Get selected regular players
    const regularPlayers = Array.from(
      document.querySelectorAll('input[name="edit_regular_players[]"]:checked')
    ).map((cb) => cb.value);

    // Get selected scouted players
    const scoutedPlayers = Array.from(
      document.querySelectorAll('input[name="edit_scouted_players[]"]:checked')
    ).map((cb) => cb.value);

    // Combine both arrays
    const allPlayers = [...regularPlayers, ...scoutedPlayers];

    if (allPlayers.length === 0) {
      alert("❌ Please select at least one player for the session.");
      return;
    }

    // Clear default form data and add combined players
    formData.delete("edit_regular_players[]");
    formData.delete("edit_scouted_players[]");
    formData.append("players", JSON.stringify(allPlayers));

    try {
      const response = await fetch("edit_training_session.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert("✅ Training session updated successfully!");
        closeModal("editModal");
        location.reload();
      } else {
        alert("❌ Error: " + result.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("❌ Failed to update session. Please try again.");
    }
  });

// DELETE SESSION FUNCTIONALITY
async function deleteSession(sessionId) {
  // Confirm deletion
  if (
    !confirm(
      "⚠️ Are you sure you want to DELETE this training session?\n\nThis will remove:\n- The session\n- All player assignments\n- All recorded scores (if any)\n\nThis action cannot be undone!"
    )
  ) {
    return;
  }

  try {
    const response = await fetch("delete_training_session.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        session_id: sessionId,
      }),
    });

    const result = await response.json();

    if (result.success) {
      alert("✅ Training session deleted successfully!");
      location.reload();
    } else {
      alert("❌ Error: " + result.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Failed to delete session. Please try again.");
  }
}
