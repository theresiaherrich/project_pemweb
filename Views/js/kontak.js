// KONTAK.JS - Halaman Kontak Global Time

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
    window.location.href = "index.php?page=logout";
  }
}

// Validasi form dan fitur kontak
document.addEventListener('DOMContentLoaded', function() {
  // Set active navigation
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
  
  // Form validation
  const form = document.getElementById('contactForm');
  
  if (form) {
    form.addEventListener('submit', function(e) {
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const subject = document.getElementById('subject').value.trim();
      const message = document.getElementById('message').value.trim();
      
      if (name.length < 3) {
        e.preventDefault();
        alert('Nama harus minimal 3 karakter');
        document.getElementById('name').focus();
        return false;
      }
      
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Format email tidak valid');
        document.getElementById('email').focus();
        return false;
      }
      
      if (subject.length < 5) {
        e.preventDefault();
        alert('Subjek harus minimal 5 karakter');
        document.getElementById('subject').focus();
        return false;
      }
      
      if (message.length < 10) {
        e.preventDefault();
        alert('Pesan harus minimal 10 karakter');
        document.getElementById('message').focus();
        return false;
      }
      
      const btn = form.querySelector('.btn-submit');
      const originalContent = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
      btn.disabled = true;
      
      setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
      }, 3000);
    });
  }
  
  // Auto-hide alert setelah 5 detik
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      alert.style.transition = 'opacity 0.5s';
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });
  
  // Character counter untuk textarea
  const messageTextarea = document.getElementById('message');
  if (messageTextarea) {
    const maxLength = 500;
    
    const counter = document.createElement('div');
    counter.style.cssText = 'text-align: right; font-size: 12px; color: #888; margin-top: 5px;';
    counter.textContent = `0 / ${maxLength} karakter`;
    messageTextarea.parentNode.appendChild(counter);
    
    messageTextarea.addEventListener('input', function() {
      const length = this.value.length;
      counter.textContent = `${length} / ${maxLength} karakter`;
      
      if (length > maxLength) {
        counter.style.color = '#c62828';
        this.value = this.value.substring(0, maxLength);
      } else if (length > maxLength * 0.9) {
        counter.style.color = '#ff9800';
      } else {
        counter.style.color = '#888';
      }
    });
  }
  
  // Real-time validation feedback
  const nameInput = document.getElementById('name');
  const emailInput = document.getElementById('email');
  const subjectInput = document.getElementById('subject');
  
  if (nameInput) {
    nameInput.addEventListener('blur', function() {
      if (this.value.trim().length < 3 && this.value.length > 0) {
        this.style.borderColor = '#c62828';
      } else {
        this.style.borderColor = '#e0e0e0';
      }
    });
  }
  
  if (emailInput) {
    emailInput.addEventListener('blur', function() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(this.value) && this.value.length > 0) {
        this.style.borderColor = '#c62828';
      } else {
        this.style.borderColor = '#e0e0e0';
      }
    });
  }
  
  if (subjectInput) {
    subjectInput.addEventListener('blur', function() {
      if (this.value.trim().length < 5 && this.value.length > 0) {
        this.style.borderColor = '#c62828';
      } else {
        this.style.borderColor = '#e0e0e0';
      }
    });
  }
});