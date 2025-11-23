<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= e($loker['judul']) ?> - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="home.css">
  <style>
    .detail-container {
      max-width: 900px;
      margin: 40px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .detail-header img {
      width: 100%;
      height: 320px;
      object-fit: cover;
    }
    .detail-content { padding: 25px 30px; }
    .detail-content h2 {
      font-size: 24px;
      font-weight: 700;
      color: #333;
      margin-bottom: 10px;
    }
    .detail-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      font-size: 14px;
      color: #666;
      margin-bottom: 15px;
    }
    .detail-meta span i { color: #c62828; margin-right: 6px; }
    .detail-desc {
      line-height: 1.7;
      color: #444;
      margin-bottom: 25px;
      font-size: 15px;
      white-space: pre-line;
    }
    .back-btn {
      display: inline-block;
      background: #c62828;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }
    .back-btn:hover { background: #a81f1f; }
  </style>
</head>
<body>

<header class="main-header">
    <div class="header-inner">
      <div class="brand-left">
        <img src="Globaltime.png" alt="Logo Global Time" class="logo">
        <div>
          <h1 class="brand">Global Time</h1>
          <p class="brand-sub">Berita Terpercaya, Perspektif Global</p>
        </div>
      </div>
      <div class="user-right">
        <div class="search-container">
          <!-- Ubah action ke berita.php agar search tetap di halaman ini -->
          <form class="search-form" action="berita.php" method="GET">
            <input type="text" name="q" placeholder="Cari berita..." class="search-input">
            <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
          </form>
        </div>

        <div class="profile-dropdown">
          <a href="#" class="user-profile" onclick="toggleProfileMenu(event)">
            <div class="user-avatar">
              <i class="fas fa-user"></i>
            </div>
            <span class="hello">Halo, <strong><?php echo $_SESSION['user']['name']; ?></strong></span>
            <i class="fas fa-chevron-down"></i>
          </a>
          <div class="profile-menu">
            <a href="profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
      </div>
    </div>

<!-- NAVIGASI STANDAR - Copy paste ke semua file PHP -->
<nav class="news-nav">
  <div class="nav-container">
    <ul class="nav-menu">
      <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
      <li class="dropdown">
        <a href="berita.php" class="dropdown-toggle"><i class="fas fa-newspaper"></i> Berita <i class="fas fa-chevron-down"></i></a>
        <ul class="dropdown-menu">
          <?php foreach ($kategori_list as $kat): ?>
            <li><a href="berita.php?kategori=<?= urlencode($kat) ?>"><?= htmlspecialchars($kat) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </li>
      <li><a href="aboutUs.php"><i class="fas fa-info-circle"></i> About Us</a></li>
      <li><a href="galeri.php"><i class="fas fa-images"></i> Galeri</a></li>
      <li><a href="live.php"><i class="fas fa-video"></i> Live Streaming</a></li>
      <li><a href="loker.php"><i class="fas fa-briefcase"></i> Loker</a></li>
      <li><a href="kontak.php"><i class="fas fa-envelope"></i> Kontak</a></li>
    </ul>
  </div>
</nav>
  </header>

<main class="content">
  <div class="detail-container">
    <div class="detail-header">
      <img src="<?= e($loker['gambar']) ?>" alt="<?= e($loker['judul']) ?>">
    </div>

    <div class="detail-content">
      <h2><?= e($loker['judul']) ?></h2>
      <div class="detail-meta">
        <span><i class="fas fa-building"></i> <?= e($loker['perusahaan']) ?></span>
        <span><i class="fas fa-tag"></i> <?= e($loker['kategori']) ?></span>
        <span><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($loker['tanggal'])) ?></span>
      </div>

      <div class="detail-desc">
        <?= nl2br(e($loker['deskripsi'] ?: $loker['deskripsi'])) ?>
      </div>

      <a href="loker.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Loker</a>
    </div>
  </div>
</main>

<footer class="contact-footer">
  <div class="footer-container">
    <div class="footer-left">
      <div class="footer-brand">
        <img src="Globaltime.png" alt="Logo Global Time" class="footer-logo">
        <div class="footer-brand-text">
          <h2 class="agency-name">Global Time</h2>
          <p class="agency-tagline">Berita Terpercaya, Perspektif Global</p>
        </div>
      </div>
      <div class="about-section">
        <h3>TENTANG KAMI</h3>
        <p>Global Time menghadirkan berita dan informasi peluang kerja terkini dari seluruh dunia dengan keakuratan dan kredibilitas tinggi.</p>
      </div>
    </div>
    <div class="contact-grid">
      <div class="contact-section">
        <h3>KANTOR PUSAT</h3>
        <p><a href="mailto:editorial@globaltime.com">editorial@globaltime.com</a></p>
        <p>+62 341 1234567</p>
        <p>Jl. Soekarno Hatta No.15, Malang</p>
      </div>
      <div class="contact-section">
        <h3>IKUTI KAMI</h3>
        <div class="social-links">
          <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
          <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2025 Global Time. Hak Cipta Dilindungi.</p>
  </div>
</footer>

<script src="home.js"></script>
</body>
</html>
