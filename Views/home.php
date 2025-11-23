<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Home - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/project/Views/css/home.css">
</head>

<body>
  <!-- HEADER -->
  <header class="main-header">
    <div class="header-inner">
      <div class="brand-left">
        <img src="/project/Views/css/Globaltime.png" alt="Logo Global Time" class="logo">
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
    <nav class="news-nav">
      <div class="nav-container">
        <ul class="nav-menu">
          <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
          <li class="dropdown">
            <a href="berita.php" class="dropdown-toggle"><i class="fas fa-newspaper"></i> Berita <i
                class="fas fa-chevron-down"></i></a>
            <ul class="dropdown-menu">
              
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
    <section class="highlight">
      <h2>Highlight Berita</h2>

      <?php if ($highlight): ?>
        <article class="highlight-article">
          <img src="<?= htmlspecialchars($highlight['gambar']) ?>" alt="Highlight" class="highlight-img">
          <div class="highlight-text">
            <h3><?= htmlspecialchars($highlight['judul']) ?></h3>
            <p><?= htmlspecialchars($highlight['isi_singkat']) ?></p>
            <div class="meta">
              <span><i class="fas fa-clock"></i> <?= date('d M Y, H:i', strtotime($highlight['waktu'])) ?></span>
              <span><i class="fas fa-eye"></i> <?= $highlight['views'] ?> views</span>
            </div>
          </div>
        </article>
      <?php else: ?>
        <p>Tidak ada berita highlight saat ini.</p>
      <?php endif; ?>
    </section>

    <section class="news-section">
      <h2>Berita Terbaru</h2>
      <div class="news-grid">
        <?php if (!empty($berita_list)): ?>
          <?php foreach ($berita_list as $berita): ?>
            <article class="news-card">
              <img src="<?= htmlspecialchars($berita['gambar']) ?>" alt="<?= htmlspecialchars($berita['judul']) ?>"
                class="news-img">
              <div class="news-content">
                <span class="category"><?= htmlspecialchars($berita['kategori']) ?></span>
                <h3><?= htmlspecialchars($berita['judul']) ?></h3>
                <p><?= htmlspecialchars($berita['isi_singkat']) ?></p>
                <div class="meta">
                  <span><i class="fas fa-clock"></i> <?= date('d M Y', strtotime($berita['waktu'])) ?></span>
                  <span><i class="fas fa-eye"></i> <?= $berita['views'] ?> views</span>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Tidak ada berita terbaru saat ini.</p>
        <?php endif; ?>
      </div>
      </article>
      </div>
    </section>

    <aside class="trending-section">
      <h3>Trending Hari Ini</h3>
      <div class="trending-list">
        <?php
        $rank = 1;
        foreach ($trending_list as $trend): ?>
          <div class="trending-item">
            <span class="number"><?= $rank++ ?></span>
            <span class="title"><?= htmlspecialchars($trend['judul']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </aside>
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
          <p>Global Time adalah platform berita terpercaya yang menghadirkan informasi terkini dari seluruh dunia dengan
            perspektif yang objektif dan mendalam. Kami berkomitmen memberikan berita berkualitas untuk masyarakat
            Indonesia.</p>
        </div>
      </div>

      <div class="contact-grid">
        <div class="contact-section">
          <h3>KANTOR INDONESIA</h3>
          <p><a href="mailto:editorial@globaltime.com">editorial@globaltime.com</a></p>
          <p>+62 341 1234567</p>
          <p>Jl. Soekarno Hatta No.15,<br>Lowokwaru, Malang, Jawa Timur</p>
          <p><a href="#">LIHAT PETA ↗</a></p>
        </div>
        <div class="contact-section">
          <h3>TETAP TERUPDATE</h3>
          <p><a href="#">LANGGANAN NEWSLETTER KAMI ↗</a></p>
          <h3 style="margin-top: 30px;">IKUTI GLOBAL TIME</h3>
          <div class="social-links">
            <a href="#" class="social-link" title="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-link" title="Instagram">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="social-link" title="Twitter">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-link" title="TikTok">
              <i class="fab fa-tiktok"></i>
            </a>
            <a href="#" class="social-link" title="WhatsApp">
              <i class="fab fa-whatsapp"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2025 Global Time. Hak Cipta Dilindungi. | <a href="#">Kebijakan Privasi</a> | <a href="#">Syarat &
          Ketentuan</a></p>
    </div>
  </footer>
  <script src="home.js"></script>
</body>

</html>