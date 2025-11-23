<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Berita - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/project/Views/css/berita.css">
</head>

<body>

  <body>
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
            <form class="search-form" action="?page=berita" method="GET">
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
              <a href="berita.php" class="dropdown-toggle"><i class="fas fa-newspaper"></i> Berita <i class="fas fa-chevron-down"></i></a>
              <ul class="dropdown-menu">
                <ul class="dropdown-menu">
                  <?php foreach ($kategori_list as $kat): ?>
                    <li>
                      <a href="berita.php?kategori=<?= $kat['id'] ?>">
                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>

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

    <section class="news-section">
      <h2>
        <?php
        if ($keyword !== '') {
          echo 'Hasil Pencarian untuk "' . htmlspecialchars($keyword) . '"';
        } elseif ($kategori !== '') {
          echo 'Kategori: ' . htmlspecialchars($kategori);
        } else {
          echo 'Berita Terbaru';
        }
        ?>
      </h2>
      <div class="news-grid">
        <?php if (empty($semua_berita)): ?>
          <p class="no-news">Tidak ada berita ditemukan.</p>
        <?php else: ?>
          <?php foreach ($semua_berita as $berita): ?>
            <a href="detail_berita.php?id=<?= htmlspecialchars($berita['id_berita']) ?>">
              <article class="news-card">
                <img src="<?= htmlspecialchars($berita['gambar']) ?>"
                  alt="<?= htmlspecialchars($berita['judul']) ?>" class="news-img">
                <div class="news-content">
                  <span class="category"><?= htmlspecialchars($berita['kategori']) ?></span>
                  <h3><?= htmlspecialchars($berita['judul']) ?></h3>
                  <p><?= htmlspecialchars($berita['isi_singkat']) ?></p>
                  <div class="meta">
                    <span><i class="fas fa-clock"></i> <?= date("d M Y H:i", strtotime($berita['waktu'])) ?></span>
                    <span><i class="fas fa-eye"></i> <?= htmlspecialchars($berita['views']) ?></span>
                  </div>
                </div>
              </article>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

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
            <p>Global Time adalah platform berita terpercaya yang menghadirkan informasi terkini dari seluruh dunia dengan perspektif yang objektif dan mendalam. Kami berkomitmen memberikan berita berkualitas untuk masyarakat Indonesia.</p>
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
        <p>&copy; 2025 Global Time. Hak Cipta Dilindungi. | <a href="#">Kebijakan Privasi</a> | <a href="#">Syarat & Ketentuan</a></p>
      </div>
    </footer>

    <script src="/project/Views/js/berita.js"></script>
  </body>

</html>