const menuIcon = document.querySelector(".menu-icon");
const nav = document.querySelector("nav");

// Function to toggle the menu on small screens
function toggleMenu() {
  const width = window.innerWidth;

  if (width <= 990) {
    // On small screens, toggle the menu
    nav.classList.toggle("active");
  }
}

// Event listener for menu icon click
menuIcon.addEventListener("click", toggleMenu);

// Function to handle resizing behavior
function handleResize() {
  const width = window.innerWidth;

  if (width > 768) {
    // On large screens, ensure the menu is visible and not toggled
    nav.classList.remove("active");
  }
}

// Call the resize handler on window resize
window.addEventListener("resize", handleResize);

// Initial check when the page loads
handleResize();
