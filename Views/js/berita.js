// BERITA.JS - Halaman Berita Global Time

// Profile Dropdown Menu
function toggleProfileMenu(event) {
  event.preventDefault();
  const dropdown = document.querySelector('.profile-dropdown');
  if (dropdown) {
    dropdown.classList.toggle('active');
  }
}

// Close profile menu when clicking outside
document.addEventListener('click', function(e) {
  const dropdown = document.querySelector('.profile-dropdown');
  if (dropdown && !dropdown.contains(e.target)) {
    dropdown.classList.remove('active');
  }
});

// Logout function
function logout() {
  if (confirm("Yakin ingin logout?")) {
    window.location.href = "index.php?page=logout";
  }
}

// Mobile Menu
document.addEventListener('DOMContentLoaded', function() {
  const navContainer = document.querySelector('.nav-container');
  const navMenu = document.querySelector('.nav-menu');
  
  // Create hamburger button
  if (!document.querySelector('.hamburger-btn')) {
    const hamburgerBtn = document.createElement('button');
    hamburgerBtn.className = 'hamburger-btn';
    hamburgerBtn.innerHTML = '<i class="fas fa-bars"></i>';
    navContainer.insertBefore(hamburgerBtn, navMenu);
  }
  
  // Create overlay
  if (!document.querySelector('.nav-overlay')) {
    const overlay = document.createElement('div');
    overlay.className = 'nav-overlay';
    document.body.appendChild(overlay);
  }
  
  const hamburger = document.querySelector('.hamburger-btn');
  const overlay = document.querySelector('.nav-overlay');
  
  // Open/Close menu
  function toggleMenu() {
    navMenu.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
  }
  
  hamburger.addEventListener('click', toggleMenu);
  overlay.addEventListener('click', toggleMenu);
  
  // Handle dropdown in mobile
  const dropdowns = document.querySelectorAll('.dropdown > a');
  dropdowns.forEach(link => {
    link.addEventListener('click', function(e) {
      if (window.innerWidth <= 1024) {
        e.preventDefault();
        this.closest('.dropdown').classList.toggle('active');
      }
    });
  });
  
  // Close menu when clicking links
  const allLinks = document.querySelectorAll('.nav-menu a:not(.dropdown > a)');
  allLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 1024) {
        toggleMenu();
      }
    });
  });
  
  // SEMUA CODE ACTIVE STATE SUDAH DIHAPUS - PHP yang handle!
});