// HOME.JS - Global Time Navigation Handler

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

// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
  
  // Get user name from PHP session
  const userNameElement = document.querySelector('.hello strong');
  const userName = userNameElement ? userNameElement.textContent : 'User';
  
  // Create hamburger button if not exists
  const navContainer = document.querySelector('.nav-container');
  const navMenu = document.querySelector('.nav-menu');
  
  if (navContainer && !document.querySelector('.hamburger-btn')) {
    const hamburgerBtn = document.createElement('button');
    hamburgerBtn.className = 'hamburger-btn';
    hamburgerBtn.innerHTML = '<i class="fas fa-bars"></i>';
    hamburgerBtn.setAttribute('aria-label', 'Toggle navigation menu');
    navContainer.appendChild(hamburgerBtn);
  }
  
  // Create overlay if not exists
  if (!document.querySelector('.nav-overlay')) {
    const overlay = document.createElement('div');
    overlay.className = 'nav-overlay';
    document.body.appendChild(overlay);
  }
  
  // Create sidebar profile section for mobile
  if (navMenu && !document.querySelector('.sidebar-profile')) {
    const sidebarProfile = document.createElement('div');
    sidebarProfile.className = 'sidebar-profile';
    sidebarProfile.innerHTML = `
      <button class="sidebar-close-btn" aria-label="Close menu">
        <i class="fas fa-times"></i>
      </button>
      <div class="sidebar-profile-content">
        <div class="sidebar-avatar">
          <i class="fas fa-user"></i>
        </div>
        <div class="sidebar-user-info">
          <h3>${userName}</h3>
          <p>Member Global Time</p>
        </div>
      </div>
      <div class="sidebar-profile-actions">
        <a href="index.php?page=profile" class="sidebar-profile-btn">
          <i class="fas fa-user-edit"></i> Profile
        </a>
        <button onclick="logout()" class="sidebar-profile-btn">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </div>
    `;
    navMenu.insertBefore(sidebarProfile, navMenu.firstChild);
  }
  
  const hamburger = document.querySelector('.hamburger-btn');
  const overlay = document.querySelector('.nav-overlay');
  const sidebarCloseBtn = document.querySelector('.sidebar-close-btn');
  
  // Function to close menu
  function closeMenu() {
    if (navMenu) {
      navMenu.classList.remove('active');
      if (hamburger) {
        hamburger.innerHTML = '<i class="fas fa-bars"></i>'; // Reset icon
      }
    }
    if (overlay) overlay.classList.remove('active');
    document.body.style.overflow = '';
  }
  
  // Function to open menu
  function openMenu() {
    if (navMenu) {
      navMenu.classList.add('active');
      if (hamburger) {
        hamburger.innerHTML = '<i class="fas fa-bars"></i>'; // Keep bars icon
      }
    }
    if (overlay) overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  
  // Toggle mobile menu
  if (hamburger) {
    hamburger.addEventListener('click', function(e) {
      e.stopPropagation();
      
      if (navMenu && navMenu.classList.contains('active')) {
        closeMenu();
      } else {
        openMenu();
      }
    });
  }
  
  // Close menu with X button in sidebar
  if (sidebarCloseBtn) {
    sidebarCloseBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      closeMenu();
    });
  }
  
  // Close menu when clicking overlay
  if (overlay) {
    overlay.addEventListener('click', function() {
      closeMenu();
    });
  }
  
  // Handle dropdown in mobile
  const dropdownToggles = document.querySelectorAll('.dropdown > a');
  dropdownToggles.forEach(toggle => {
    const newToggle = toggle.cloneNode(true);
    toggle.parentNode.replaceChild(newToggle, toggle);
    
    newToggle.addEventListener('click', function(e) {
      if (window.innerWidth <= 1024) {
        e.preventDefault();
        e.stopPropagation();
        const dropdown = this.closest('.dropdown');
        
        // Close other dropdowns
        document.querySelectorAll('.dropdown').forEach(d => {
          if (d !== dropdown) {
            d.classList.remove('active');
          }
        });
        
        dropdown.classList.toggle('active');
      }
    });
  });
  
  // Close mobile menu when clicking nav links
  const navLinks = document.querySelectorAll('.nav-menu > li > a:not(.dropdown > a)');
  navLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 1024) {
        closeMenu();
      }
    });
  });
  
  // Close menu when clicking dropdown menu items
  const dropdownLinks = document.querySelectorAll('.dropdown-menu a');
  dropdownLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 1024) {
        closeMenu();
      }
    });
  });
  
  // Close menu on window resize
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      if (window.innerWidth > 1024) {
        closeMenu();
        document.querySelectorAll('.dropdown').forEach(dropdown => {
          dropdown.classList.remove('active');
        });
      }
    }, 250);
  });
  
});