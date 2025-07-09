// Notification Dropdown
const notifyToggle = document.getElementById("notifyToggle");
const notifyDropdown = document.getElementById("notifyDropdown");

notifyToggle.addEventListener("click", () => {
  notifyDropdown.classList.toggle("hidden");
});

document.addEventListener("click", (e) => {
  if (!notifyToggle.contains(e.target) && !notifyDropdown.contains(e.target)) {
    notifyDropdown.classList.add("hidden");
  }
});

// Search Box Toggle
const searchToggle = document.getElementById("searchToggle");
const searchBox = document.getElementById("searchBox");

searchToggle.addEventListener("click", () => {
  searchBox.classList.toggle("hidden");
});

document.addEventListener("click", (e) => {
  if (!searchToggle.contains(e.target) && !searchBox.contains(e.target)) {
    searchBox.classList.add("hidden");
  }
});

// Sidebar Toggle for lg+
const sidebar = document.getElementById("sidebar");
const toggleBtnInMain = document.getElementById("mainToggleSidebar");
const mainContent = document.getElementById("mainContent");

function toggleSidebar() {
  const isCollapsed = sidebar.classList.toggle("collapsed");

  // Adjust sidebar width
  sidebar.classList.toggle("w-64", !isCollapsed);
  sidebar.classList.toggle("w-20", isCollapsed);

  // Adjust main content margin
  if (mainContent) {
    mainContent.classList.toggle("lg:ml-64", !isCollapsed);
    mainContent.classList.toggle("lg:ml-20", isCollapsed);
  }

  // Show the toggle button only when sidebar is collapsed on large screens
  if (isCollapsed && window.innerWidth >= 1024) {
    toggleBtnInMain.classList.remove("hidden");
  } else {
    toggleBtnInMain.classList.add("hidden");
  }
}

toggleBtnInMain.addEventListener("click", toggleSidebar);

// Mobile Sidebar Logic
const mobileSidebar = document.getElementById("mobileSidebar");
const mobileSidebarOverlay = document.getElementById("mobileSidebarOverlay");
const mobileSidebarOpen = document.getElementById("mobileSidebarOpen");
const mobileSidebarClose = document.getElementById("mobileSidebarClose");

function openMobileSidebar() {
  mobileSidebar.classList.remove("-translate-x-full");
  mobileSidebarOverlay.classList.remove("hidden");
}

function closeMobileSidebar() {
  mobileSidebar.classList.add("-translate-x-full");
  mobileSidebarOverlay.classList.add("hidden");
}

mobileSidebarOpen.addEventListener("click", openMobileSidebar);
mobileSidebarClose.addEventListener("click", closeMobileSidebar);
mobileSidebarOverlay.addEventListener("click", closeMobileSidebar);

// Profile Dropdown
const dropdownBtn = document.getElementById("profileDropdownBtn");
const dropdownMenu = document.getElementById("profileDropdown");

dropdownBtn.addEventListener("click", () => {
  dropdownMenu.classList.toggle("hidden");
});

document.addEventListener("click", (e) => {
  if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
    dropdownMenu.classList.add("hidden");
  }
});
