<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

// Jika role bukan admin → larang akses
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ?page=home");
    exit();
}

$current_page = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Berita - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/admin_berita.css">
</head>
<body>
  <!-- KODE LENGKAP ADA DI ARTIFACT INI -->

  <header class="main-header">
    <div class="header-inner">
      <div class="brand-left">
        <img src="/Views/css/Globaltime.png" alt="Logo Global Time" class="logo">
        <div>
          <h1 class="brand">Global Time Admin</h1>
          <p class="brand-sub">Panel Manajemen Berita</p>
        </div>
      </div>
      <div class="user-right">
        <a href="?page=berita" class="btn-view-site">
          <i class="fas fa-eye"></i> Lihat Situs
        </a>
        <div class="profile-dropdown">
          <a href="#" class="user-profile" onclick="toggleProfileMenu(event)">
            <div class="user-avatar">
              <i class="fas fa-user"></i>
            </div>
            <span class="hello">Halo, <strong><?php echo $_SESSION['user']['name']; ?></strong></span>
            <i class="fas fa-chevron-down"></i>
          </a>
          <div class="profile-menu">
            <a href="index.php?page=profile"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="admin-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <nav class="sidebar-nav">
         <a href="?page=admin_berita" class="nav-item <?php echo $current_page === 'admin_berita' ? 'active' : ''; ?>">
          <i class="fas fa-newspaper"></i>
          <span>Kelola Berita</span>
        </a>
        <a href="?page=admin_kategori" class="nav-item <?php echo $current_page === 'admin_kategori' ? 'active' : ''; ?>">
          <i class="fas fa-tags"></i>
          <span>Kategori</span>
        </a>
        <a href="?page=admin_user" class="nav-item <?php echo $current_page === 'admin_user' ? 'active' : ''; ?>">
          <i class="fas fa-users"></i>
          <span>Users</span>
        </a>
        <a href="?page=admin_galeri" class="nav-item <?php echo $current_page === 'admin_galeri' ? 'active' : ''; ?>">
          <i class="fas fa-images"></i>
          <span>Galeri</span>
        </a>
        <a href="?page=admin_kontak" class="nav-item <?php echo $current_page === 'admin_kontak' ? 'active' : ''; ?>">
          <i class="fas fa-envelope"></i>
          <span>Contact Us</span>
        </a>
        <a href="?page=admin_aboutUs" class="nav-item <?php echo $current_page === 'admin_aboutUs' ? 'active' : ''; ?>">
          <i class="fas fa-info-circle"></i>
          <span>About Us</span>
        </a>
        <a href="?page=admin_live" class="nav-item <?php echo $current_page === 'admin_live' ? 'active' : ''; ?>">
          <i class="fas fa-broadcast-tower"></i>
          <span>Live Streaming</span>
        </a>
        <a href="?page=admin_loker" class="nav-item <?php echo $current_page === 'admin_loker' ? 'active' : ''; ?>">
          <i class="fas fa-images"></i>
          <span>Loker</span>
        </a>
      </nav>
    </aside>
  <script src="/Views/js/admin_berita.js"></script>
    </html>