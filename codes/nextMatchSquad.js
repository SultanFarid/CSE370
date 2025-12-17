let nextMatch = null;
let allPlayers = [];
let selectedSquad = {
  starting: {},
  substitutes: [],
};

document.addEventListener("DOMContentLoaded", function () {
  loadNextMatch();
});

// LOAD NEXT MATCH
async function loadNextMatch() {
  try {
    const response = await fetch("nextMatchSquad.php?action=get_next_match");
    const data = await response.json();

    document.getElementById("loadingIndicator").style.display = "none";

    if (!data.success) {
      document.getElementById("noMatchMessage").style.display = "block";
      return;
    }

    nextMatch = data.match;
    displayMatchInfo(nextMatch);

    if (nextMatch.Match_status === "Published") {
      // Show Read-Only View
      loadPublishedSquad(nextMatch.Match_id);

      // Hide selection area & action buttons
      document.getElementById("selectionArea").style.display = "none";
      document.getElementById("actionButtons").style.display = "none";

      // Show Unpublish Button to Head Coach only
      if (isHeadCoach) {
        const unpubDiv = document.getElementById("unpublishContainer");
        unpubDiv.style.display = "flex";
        unpubDiv.className = "unpublish-container-styled";
        unpubDiv.innerHTML = `
            <button class="btn-danger" onclick="unpublishSquad()">
                <span class="btn-icon">🔓</span> Unlock & Unpublish Squad
            </button>
        `;
      }
    } else {
      if (isHeadCoach) {
        loadEligiblePlayers();
        document.getElementById("actionButtons").style.display = "flex";
        document.getElementById("selectionArea").style.display = "grid";
        document.getElementById("unpublishContainer").style.display = "none";
      } else {
        document.getElementById("notPublishedMessage").style.display = "block";
      }
    }
  } catch (error) {
    console.error("Error loading next match:", error);
    document.getElementById("loadingIndicator").innerHTML = `
            <span style="font-size: 3rem;">❌</span>
            <p>Error loading match data</p>
        `;
  }
}

// DISPLAY MATCH INFO
function displayMatchInfo(match) {
  const venue = match.Stadium.includes("BRAC University") ? "home" : "away";
  const date = new Date(match.Match_date + "T" + match.Match_time);

  const homeLogo = `images/teams/BUFC.png`;
  const opponentLogo = `images/teams/${match.Opponent}.png`;

  const html = `
        <div class="match-header-section">
            <div class="match-logos">
                 <div class="team-container" style="text-align: center;">
                    <img src="${homeLogo}" alt="BUFC" class="team-logo-lg" onerror="this.src='images/default_team.png'">
                    <div style="font-weight:800; margin-top:5px;">BUFC</div>
                 </div>
                 <div class="versus-text">VS</div>
                 <div class="team-container" style="text-align: center;">
                    <img src="${opponentLogo}" alt="${
    match.Opponent
  }" class="team-logo-lg" onerror="this.src='images/default_team.png'">
                    <div style="font-weight:800; margin-top:5px;">${
                      match.Opponent
                    }</div>
                 </div>
            </div>
            <div class="match-badge">${match.Match_status}</div>
        </div>
        
        <div class="match-details-grid">
            <div class="detail-item">
                <span class="detail-icon">📅</span>
                <div class="detail-content">
                    <div class="detail-label">Date</div>
                    <div class="detail-value">${date.toLocaleDateString()}</div>
                </div>
            </div>
            <div class="detail-item">
                <span class="detail-icon">⏰</span>
                <div class="detail-content">
                    <div class="detail-label">Time</div>
                    <div class="detail-value">${date.toLocaleTimeString([], {
                      hour: "2-digit",
                      minute: "2-digit",
                    })}</div>
                </div>
            </div>
            <div class="detail-item">
                <span class="detail-icon">📍</span>
                <div class="detail-content">
                    <div class="detail-label">Stadium</div>
                    <div class="detail-value">${
                      match.Stadium
                    } <span class="venue-badge ${venue}">${venue.toUpperCase()}</span></div>
                </div>
            </div>
        </div>
    `;

  document.getElementById("matchInfoCard").innerHTML = html;
  document.getElementById("matchInfoCard").style.display = "block";
}

// LOAD PLAYERS
async function loadEligiblePlayers() {
  try {
    const response = await fetch(
      "nextMatchSquad.php?action=get_eligible_players"
    );
    const data = await response.json();

    if (data.success) {
      allPlayers = data.players;
      renderPlayersList(allPlayers);
      document.getElementById("availableCount").textContent = allPlayers.length;
    }
  } catch (error) {
    console.error("Error loading players:", error);
  }
}

function renderPlayersList(players) {
  const container = document.getElementById("playersList");
  container.innerHTML = "";

  const positionMap = {
    Goalkeeper: "GK",
    Defender: "DEF",
    Midfielder: "MID",
    Striker: "ST",
  };

  players.forEach((player) => {
    const playerCard = document.createElement("div");
    playerCard.className = "draggable-player";
    playerCard.draggable = true;
    playerCard.setAttribute("data-player-id", player.User_ID);
    playerCard.setAttribute("data-position", player.Position);

    const shortPos = positionMap[player.Position] || player.Position;

    playerCard.innerHTML = `
            <img src="images/players/${player.User_ID}.jpg" alt="${
      player.Name
    }" class="player-avatar-drag"
                 onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(
                   player.Name
                 )}&background=3b82f6&color=fff&size=100&bold=true'">
            <div class="player-info-drag">
                <span class="player-name-drag">${player.Name}</span>
                <div class="player-meta-drag">
                    <span class="player-jersey-drag">#${player.Jersey_No}</span>
                    <span class="player-position-drag">${shortPos}</span>
                    <span class="player-stats-drag">GS:${
                      player.total_goals
                    } MP:${player.total_matches}</span>
                </div>
            </div>
        `;

    playerCard.addEventListener("dragstart", dragStart);
    playerCard.addEventListener("dragend", dragEnd);
    container.appendChild(playerCard);
  });
}

function filterPlayersByPosition(position) {
  document
    .querySelectorAll(".pos-tab")
    .forEach((tab) => tab.classList.remove("active"));
  event.target.classList.add("active");
  const filtered =
    position === "all"
      ? allPlayers
      : allPlayers.filter((p) => p.Position === position);
  renderPlayersList(filtered);
}

// DRAG & DROP
let draggedElement = null;

function dragStart(e) {
  draggedElement = e.target;
  e.target.classList.add("dragging");
  e.dataTransfer.effectAllowed = "move";
  e.dataTransfer.setData("text/html", e.target.innerHTML);
}

function dragEnd(e) {
  e.target.classList.remove("dragging");
}

function allowDrop(e) {
  e.preventDefault();
  e.currentTarget.classList.add("drag-over");
}

function dragLeave(e) {
  e.currentTarget.classList.remove("drag-over");
}

function drop(e) {
  e.preventDefault();
  e.currentTarget.classList.remove("drag-over");
  if (!draggedElement) return;

  const slot = e.currentTarget;
  const slotPosition = slot.getAttribute("data-position");
  const playerId = draggedElement.getAttribute("data-player-id");
  const playerData = allPlayers.find((p) => p.User_ID == playerId);

  if (slot.classList.contains("filled")) {
    alert("This position is already filled. Remove the player first.");
    return;
  }

  placePlayerInSlot(slot, playerData, slotPosition);
  draggedElement.classList.add("placed");

  if (slotPosition.startsWith("SUB")) {
    selectedSquad.substitutes.push(playerId);
  } else {
    selectedSquad.starting[slotPosition] = playerId;
  }
  updateSelectionCount();
}

function placePlayerInSlot(slot, player, slotPosition) {
  slot.classList.add("filled");
  slot.classList.remove("drop-zone");

  const clickAction = `onclick="removePlayer('${slotPosition}', ${player.User_ID})"`;
  const style = `cursor: pointer;`;

  if (slotPosition.startsWith("SUB")) {
    slot.innerHTML = `
            <div class="bench-player-card" data-player-id="${
              player.User_ID
            }" draggable="false" style="${style}" ${clickAction} title="Click to remove">
                <img src="images/players/${player.User_ID}.jpg" alt="${
      player.Name
    }" class="bench-avatar" draggable="false"
                     onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(
                       player.Name
                     )}&background=f59e0b&color=fff&size=100&bold=true'">
                <div class="bench-player-name">${player.Name}</div>
                <div class="bench-player-number">#${player.Jersey_No}</div>
            </div>`;
  } else {
    slot.innerHTML = `
            <div class="player-card-field" data-player-id="${
              player.User_ID
            }" draggable="false" style="${style}" ${clickAction} title="Click to remove">
                <img src="images/players/${player.User_ID}.jpg" alt="${
      player.Name
    }" class="player-avatar-field" draggable="false"
                     onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(
                       player.Name
                     )}&background=3b82f6&color=fff&size=120&bold=true'">
                <div class="player-name-field">${player.Name}</div>
                <div class="player-number-field">#${player.Jersey_No}</div>
            </div>`;
  }
}

function removePlayer(slotPosition, playerId) {
  const slot = document.querySelector(`[data-position="${slotPosition}"]`);

  if (slotPosition.startsWith("SUB")) {
    selectedSquad.substitutes = selectedSquad.substitutes.filter(
      (id) => id != playerId
    );
    const num = slotPosition.replace("SUB", "");
    slot.innerHTML = `<div class="slot-number">${num}</div>`;
  } else {
    delete selectedSquad.starting[slotPosition];
    const label = slotPosition.replace(/[0-9]/g, "");
    slot.innerHTML = `<div class="slot-label">${label}</div>`;
  }

  slot.classList.remove("filled");
  slot.classList.add("drop-zone");

  const playerCard = document.querySelector(
    `.draggable-player[data-player-id="${playerId}"]`
  );
  if (playerCard) playerCard.classList.remove("placed");

  updateSelectionCount();
}

function updateSelectionCount() {
  const startingCount = Object.keys(selectedSquad.starting).length;
  const subsCount = selectedSquad.substitutes.length;
  document.getElementById("selectedCount").textContent = startingCount;
  document.getElementById("subsCount").textContent = subsCount;
  document.getElementById("subsCount2").textContent = subsCount;

  const publishBtn = document.getElementById("publishBtn");
  if (startingCount === 11 && subsCount <= 9) {
    publishBtn.disabled = false;
  } else {
    publishBtn.disabled = true;
  }
}

function resetSelection() {
  selectedSquad = { starting: {}, substitutes: [] };
  document
    .querySelectorAll(".position-slot.filled, .bench-slot.filled")
    .forEach((slot) => {
      const slotPosition = slot.getAttribute("data-position");
      if (slotPosition.startsWith("SUB")) {
        const num = slotPosition.replace("SUB", "");
        slot.innerHTML = `<div class="slot-number">${num}</div>`;
      } else {
        const label = slotPosition.replace(/[0-9]/g, "");
        slot.innerHTML = `<div class="slot-label">${label}</div>`;
      }
      slot.classList.remove("filled");
      slot.classList.add("drop-zone");
    });
  document
    .querySelectorAll(".draggable-player.placed")
    .forEach((card) => card.classList.remove("placed"));
  updateSelectionCount();
}

// PUBLISH SQUAD
async function publishSquad() {
  const btn = document.getElementById("publishBtn");
  btn.disabled = true;
  btn.innerHTML = "Publishing...";

  try {
    const response = await fetch("nextMatchSquad.php?action=publish_squad", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        match_id: nextMatch.Match_id,
        starting_xi: selectedSquad.starting,
        substitutes: selectedSquad.substitutes,
      }),
    });

    const data = await response.json();
    if (data.success) {
      alert("✅ Squad published successfully!");
      location.reload();
    } else {
      alert("❌ Error: " + data.message);
      btn.disabled = false;
      btn.innerHTML = '<span class="btn-icon">✅</span> Publish Squad';
    }
  } catch (error) {
    console.error("Error publishing squad:", error);
    alert("❌ Error publishing squad");
    btn.disabled = false;
    btn.innerHTML = '<span class="btn-icon">✅</span> Publish Squad';
  }
}

// UNPUBLISH SQUAD
async function unpublishSquad() {
  if (!confirm("Unpublish this squad?")) {
    return;
  }

  try {
    const response = await fetch("nextMatchSquad.php?action=unpublish_squad", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ match_id: nextMatch.Match_id }),
    });

    const data = await response.json();
    if (data.success) {
      location.reload();
    } else {
      alert("❌ Error: " + data.message);
    }
  } catch (error) {
    console.error("Error unpublishing:", error);
  }
}

// AI AUTO-SELECT
async function aiAutoSelect() {
  if (
    !confirm(
      "AI will analyze player stats and select the best squad. Continue?"
    )
  )
    return;

  const btn = document.querySelector(".btn-ai");
  const originalText = btn.innerHTML;
  btn.innerHTML =
    '<span class="spinner" style="width:20px;height:20px;border-width:2px;margin:0;"></span> Analyzing...';
  btn.disabled = true;

  try {
    resetSelection();
    const response = await fetch("api_squad_selection.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        match_id: nextMatch.Match_id,
        players: allPlayers,
      }),
    });

    const rawText = await response.text();
    let squadData;
    try {
      squadData = JSON.parse(rawText);
    } catch (e) {
      throw new Error("Invalid JSON: " + rawText);
    }

    if (squadData.error) throw new Error(squadData.error);

    // Apply Starting XI
    if (squadData.starting_xi) {
      Object.entries(squadData.starting_xi).forEach(([pos, id]) => {
        const player = allPlayers.find((p) => p.User_ID == id);
        if (player) {
          const slot = document.querySelector(`[data-position="${pos}"]`);
          if (slot) {
            placePlayerInSlot(slot, player, pos);
            selectedSquad.starting[pos] = id;
            const card = document.querySelector(
              `.draggable-player[data-player-id="${id}"]`
            );
            if (card) card.classList.add("placed");
          }
        }
      });
    }

    // Apply Substitutes
    if (squadData.substitutes) {
      squadData.substitutes.forEach((id, index) => {
        const player = allPlayers.find((p) => p.User_ID == id);
        if (player) {
          const pos = `SUB${index + 1}`;
          const slot = document.querySelector(`[data-position="${pos}"]`);
          if (slot) {
            placePlayerInSlot(slot, player, pos);
            selectedSquad.substitutes.push(id);
            const card = document.querySelector(
              `.draggable-player[data-player-id="${id}"]`
            );
            if (card) card.classList.add("placed");
          }
        }
      });
    }
    updateSelectionCount();
  } catch (error) {
    console.error("AI Error:", error);
    alert("AI Error: " + error.message);
  } finally {
    btn.innerHTML = originalText;
    btn.disabled = false;
  }
}

// READ ONLY VIEW
async function loadPublishedSquad(matchId) {
  try {
    const response = await fetch(
      `nextMatchSquad.php?action=get_published_squad&match_id=${matchId}`
    );
    const data = await response.json();
    if (data.success) {
      displayPublishedSquad(data.starting_xi, data.substitutes);
    }
  } catch (error) {
    console.error("Error loading published squad:", error);
  }
}

function displayPublishedSquad(startingXI, substitutes) {
  const formation = { gk: null, def: [], mid: [], fwd: [] };

  startingXI.forEach((p) => {
    if (p.Position === "Goalkeeper") formation.gk = p;
    else if (p.Position === "Defender") formation.def.push(p);
    else if (p.Position === "Midfielder") formation.mid.push(p);
    else if (p.Position === "Striker") formation.fwd.push(p);
  });

  const card = (p) => `
        <div class="position-slot filled">
            <div class="player-card-field">
                <img src="images/players/${p.User_ID}.jpg" alt="${
    p.Name
  }" class="player-avatar-field"
                     onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(
                       p.Name
                     )}&background=3b82f6&color=fff&size=120&bold=true'">
                <div class="player-name-field">${p.Name}</div>
                <div class="player-number-field">#${p.Jersey_No}</div>
            </div>
        </div>`;
  const html = `
        <div class="field-panel full-width-panel">
            <div class="field-header" style="width: 100%; max-width: 1100px; margin: 0 auto 20px auto;">
                <h3>📋 Published Squad - 4-3-3 Formation</h3>
            </div>
            
            <div class="football-field">
                <div class="field-lines">
                    <div class="center-circle"></div><div class="center-line"></div>
                    <div class="penalty-box left"></div><div class="goal-box left"></div>
                    <div class="penalty-box right"></div><div class="goal-box right"></div>
                </div>
                <div class="tactical-grid">
                    <div class="position-col goalkeeper-col">${
                      formation.gk ? card(formation.gk) : ""
                    }</div>
                    <div class="position-col defenders-col">${formation.def
                      .map((p) => card(p))
                      .join("")}</div>
                    <div class="position-col midfielders-col">${formation.mid
                      .map((p) => card(p))
                      .join("")}</div>
                    <div class="position-col strikers-col">${formation.fwd
                      .map((p) => card(p))
                      .join("")}</div>
                </div>
            </div>
            
            ${
              substitutes.length > 0
                ? `
            <div class="substitutes-bench">
                <div class="bench-header"><h4>🪑 Substitutes Bench</h4></div>
                <div class="bench-slots">
                    ${substitutes
                      .map(
                        (player) => `
                        <div class="bench-slot filled">
                            <div class="bench-player-card">
                                <img src="images/players/${
                                  player.User_ID
                                }.jpg" alt="${player.Name}" class="bench-avatar"
                                     onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(
                                       player.Name
                                     )}&background=f59e0b&color=fff&size=100&bold=true'">
                                <div class="bench-player-name">${
                                  player.Name
                                }</div>
                                <div class="bench-player-number">#${
                                  player.Jersey_No
                                }</div>
                            </div>
                        </div>`
                      )
                      .join("")}
                </div>
            </div>`
                : ""
            }
        </div>
    `;
  document.getElementById("readOnlyView").innerHTML = html;
}
