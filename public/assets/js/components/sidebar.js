const notifyToggle = document.getElementById("notifyToggle");
const notifyDropdown = document.getElementById("notifyDropdown");

notifyToggle.addEventListener("click", () => {
  notifyDropdown.classList.toggle("hidden");
});

// Optional: click outside to close
document.addEventListener("click", (e) => {
  if (!notifyToggle.contains(e.target) && !notifyDropdown.contains(e.target)) {
    notifyDropdown.classList.add("hidden");
  }
});

// Search Box Toggle Logic
const searchToggle = document.getElementById("searchToggle");
const searchBox = document.getElementById("searchBox");

searchToggle.addEventListener("click", () => {
  searchBox.classList.toggle("hidden");
});

//Click outside to close
document.addEventListener("click", (e) => {
  if (!searchToggle.contains(e.target) && !searchBox.contains(e.target)) {
    searchBox.classList.add("hidden");
  }
});

// Remove all sidebar collapse logic for md screens, only keep for lg+
const sidebar = document.getElementById("sidebar");
const toggleBtnInMain = document.getElementById("mainToggleSidebar");
function toggleSidebar() {
  const isCollapsed = sidebar.classList.toggle("collapsed");
  sidebar.classList.toggle("w-64", !isCollapsed);
  sidebar.classList.toggle("w-20", isCollapsed);
  // Show mainToggleSidebar only if sidebar is collapsed and on large screens
  if (isCollapsed && window.innerWidth >= 1024) {
    toggleBtnInMain.classList.remove("hidden");
  } else {
    toggleBtnInMain.classList.add("hidden");
  }
}
toggleBtnInMain.addEventListener("click", toggleSidebar);

// Mobile Sidebar Logic (now for md and below)
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

// Profile Dropdown Logic
const dropdownBtn = document.getElementById("profileDropdownBtn");
const dropdownMenu = document.getElementById("profileDropdown");

dropdownBtn.addEventListener("click", () => {
  dropdownMenu.classList.toggle("hidden");
});

// Close on click outside
document.addEventListener("click", (e) => {
  if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
    dropdownMenu.classList.add("hidden");
  }
});
