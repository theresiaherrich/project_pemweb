// ABOUTUS.JS - Halaman Tentang Kami Global Time

// Profile Dropdown Menu
function toggleProfileMenu(event) {
  event.preventDefault();
  const dropdown = document.querySelector('.profile-dropdown');
  dropdown.classList.toggle('active');
}

// Close profile menu when clicking outside
document.addEventListener('click', function(e) {
  const dropdown = document.querySelector('.profile-dropdown');
  const profileButton = document.querySelector('.user-profile');
  
  if (dropdown && !dropdown.contains(e.target)) {
    dropdown.classList.remove('active');
  }
});

// Logout function
function logout() {
  if (confirm("Yakin ingin logout?")) {
    window.location.href = "logout.php";
  }
}

// Navigation Active State
document.addEventListener('DOMContentLoaded', function() {
  const currentPage = window.location.pathname.split('/').pop().replace('.php', '');
  const navLinks = document.querySelectorAll('.nav-menu a');
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href) {
      const linkPage = href.replace('.php', '');
      
      if (linkPage === currentPage || 
          (currentPage === '' && linkPage === 'home') ||
          (currentPage === 'index' && linkPage === 'home')) {
        link.classList.add('active');
      }
    }
    
    link.addEventListener('click', function() {
      navLinks.forEach(nav => nav.classList.remove('active'));
      this.classList.add('active');
    });
  });
});