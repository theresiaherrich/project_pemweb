// BERITA.JS - Halaman Berita Global Time dengan Responsive Menu

// Profile Dropdown Menu (Desktop only)
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
    window.location.href = "logout.php";
  }
}

// Mobile Sidebar Navigation
document.addEventListener('DOMContentLoaded', function() {
  // Get user name from PHP session (if available in DOM)
  const userNameElement = document.querySelector('.hello strong');
  const userName = userNameElement ? userNameElement.textContent : 'User';
  
  // Create hamburger button and overlay if not exists
  const newsNav = document.querySelector('.news-nav');
  const navContainer = document.querySelector('.nav-container');
  const navMenu = document.querySelector('.nav-menu');
  
  if (!document.querySelector('.hamburger-btn')) {
    const hamburgerBtn = document.createElement('button');
    hamburgerBtn.className = 'hamburger-btn';
    hamburgerBtn.innerHTML = '<i class="fas fa-bars"></i>';
    hamburgerBtn.setAttribute('aria-label', 'Toggle navigation menu');
    navContainer.insertBefore(hamburgerBtn, navMenu);
  }
  
  if (!document.querySelector('.nav-overlay')) {
    const overlay = document.createElement('div');
    overlay.className = 'nav-overlay';
    document.body.appendChild(overlay);
  }
  
  // Create sidebar profile section for mobile
  if (!document.querySelector('.sidebar-profile')) {
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
        <a href="profile.php" class="sidebar-profile-btn">
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
    navMenu.classList.remove('active');
    overlay.classList.remove('active');
    if (hamburger) {
      hamburger.querySelector('i').className = 'fas fa-bars';
    }
    document.body.style.overflow = '';
  }
  
  // Function to open menu
  function openMenu() {
    navMenu.classList.add('active');
    overlay.classList.add('active');
    if (hamburger) {
      hamburger.querySelector('i').className = 'fas fa-bars';
    }
    document.body.style.overflow = 'hidden';
  }
  
  // Toggle mobile menu
  hamburger.addEventListener('click', function(e) {
    e.stopPropagation();
    
    if (navMenu.classList.contains('active')) {
      closeMenu();
    } else {
      openMenu();
    }
  });
  
  // Close menu with X button in sidebar
  if (sidebarCloseBtn) {
    sidebarCloseBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      closeMenu();
    });
  }
  
  // Close menu when clicking overlay
  overlay.addEventListener('click', function() {
    closeMenu();
  });
  
  // Handle dropdown in mobile
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  dropdownToggles.forEach(toggle => {
    // Clone the toggle to remove existing event listeners
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
  
  // Close mobile menu when clicking nav links (except dropdown toggles)
  const navLinks = document.querySelectorAll('.nav-menu > li > a:not(.dropdown-toggle)');
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
  
  // Close menu on window resize if opened
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      if (window.innerWidth > 1024) {
        closeMenu();
        
        // Remove active class from all dropdowns
        document.querySelectorAll('.dropdown').forEach(dropdown => {
          dropdown.classList.remove('active');
        });
      }
    }, 250);
  });
  
  // Navigation Active State
  const currentPage = window.location.pathname.split('/').pop().replace('.php', '');
  const allNavLinks = document.querySelectorAll('.nav-menu > li > a:not(.dropdown-toggle)');
  
  allNavLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href) {
      const linkPage = href.split('?')[0].replace('.php', '');
      
      // Remove active from all links first
      link.classList.remove('active');
      
      if (linkPage === currentPage || 
          (currentPage === '' && linkPage === 'home') ||
          (currentPage === 'index' && linkPage === 'home') ||
          (currentPage === 'berita' && linkPage === 'berita')) {
        link.classList.add('active');
      }
    }
  });
  
  // Also check dropdown menu items for berita page
  if (currentPage === 'berita' || currentPage === 'detail_berita') {
    const beritaLink = document.querySelector('.nav-menu a[href*="berita"]');
    if (beritaLink && !beritaLink.classList.contains('dropdown-toggle')) {
      beritaLink.classList.add('active');
    }
  }
  
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href !== '#' && href !== '#!' && href.length > 1) {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
          
          // Close mobile menu if open
          if (window.innerWidth <= 1024 && navMenu.classList.contains('active')) {
            closeMenu();
          }
        }
      }
    });
  });
  
  // Add loading animation for images
  const newsImages = document.querySelectorAll('.news-img');
  newsImages.forEach(img => {
    if (img.complete) {
      img.style.opacity = '1';
    } else {
      img.style.opacity = '0';
      img.addEventListener('load', function() {
        this.style.opacity = '1';
      });
    }
    img.style.transition = 'opacity 0.3s ease';
  });
  
  // Add scroll behavior to nav
  let lastScroll = 0;
  const header = document.querySelector('.main-header');
  
  window.addEventListener('scroll', function() {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll <= 0) {
      header.style.transform = 'translateY(0)';
    }
    
    lastScroll = currentScroll;
  }, { passive: true });
});