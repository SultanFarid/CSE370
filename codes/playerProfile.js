document.addEventListener("DOMContentLoaded", function () {
  // 1. HIGHLIGHT ACTIVE SIDEBAR LINK
  const currentLocation = location.href;
  const menuItems = document.querySelectorAll(".nav-item");

  menuItems.forEach((item) => {
    if (item.classList.contains("active")) {
      return;
    }
    // Standard Logic for other items
    if (currentLocation.includes(item.getAttribute("href"))) {
      item.classList.add("active");
    } else {
      item.classList.remove("active");
    }
  });
});
