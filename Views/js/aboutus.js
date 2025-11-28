// ABOUTUS.JS - Halaman Tentang Kami Global Time

// Profile Dropdown Menu
function toggleProfileMenu(event) {
  event.preventDefault(); // Mencegah aksi default seperti link atau form submission
  const dropdown = document.querySelector('.profile-dropdown');
  dropdown.classList.toggle('active'); // Toggle kelas 'active' untuk menampilkan atau menyembunyikan dropdown
}

// Close profile menu when clicking outside
document.addEventListener('click', function(e) {
  const dropdown = document.querySelector('.profile-dropdown');
  const profileButton = document.querySelector('.user-profile');
  
  // Cek apakah klik di luar dropdown dan profile button
  if (dropdown && !dropdown.contains(e.target) && !profileButton.contains(e.target)) {
    dropdown.classList.remove('active'); // Menutup dropdown jika klik di luar
  }
});

// Logout function
function logout() {
  // Menampilkan konfirmasi logout
  if (confirm("Yakin ingin logout?")) {
    // Jika pengguna memilih logout, arahkan ke halaman logout
    window.location.href = "index.php?page=logout";
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
      
      // Menambahkan kelas 'active' jika link sesuai dengan halaman saat ini
      if (linkPage === currentPage || 
          (currentPage === '' && linkPage === 'home') ||
          (currentPage === 'index' && linkPage === 'home')) {
        link.classList.add('active');
      }
    }
    
    // Menangani klik pada link navigasi
    link.addEventListener('click', function() {
      navLinks.forEach(nav => nav.classList.remove('active')); // Menghapus kelas 'active' dari semua link
      this.classList.add('active'); // Menambahkan kelas 'active' pada link yang dipilih
    });
  });
});
