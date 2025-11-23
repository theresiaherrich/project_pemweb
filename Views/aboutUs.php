<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tentang Kami - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="/project/Views/css/aboutUs.css">
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
          <span class="hello">Halo, <strong><?php echo e($_SESSION['user']['name']); ?></strong></span>
          <i class="fas fa-chevron-down"></i>
        </a>
        <div class="profile-menu">
          <a href="profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a>
          <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- NAVIGASI -->
  <nav class="news-nav">
    <div class="nav-container">
      <ul class="nav-menu">
        <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
        <li class="dropdown">
          <a href="berita.php" class="dropdown-toggle"><i class="fas fa-newspaper"></i> Berita <i class="fas fa-chevron-down"></i></a>
          <ul class="dropdown-menu">
            <?php foreach ($kategori_list as $kat): ?>
              <li><a href="berita.php?kategori=<?= urlencode($kat) ?>"><?= e($kat) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li><a href="aboutUs.php" class="active"><i class="fas fa-info-circle"></i> About Us</a></li>
        <li><a href="galeri.php"><i class="fas fa-images"></i> Galeri</a></li>
        <li><a href="live.php"><i class="fas fa-video"></i> Live Streaming</a></li>
        <li><a href="loker.php"><i class="fas fa-briefcase"></i> Loker</a></li>
        <li><a href="kontak.php"><i class="fas fa-envelope"></i> Kontak</a></li>
      </ul>
    </div>
  </nav>
</header>

<!-- HERO SECTION -->
<section class="hero content">
  <div class="hero-content" style="display:flex;gap:30px;align-items:center;flex-wrap:wrap;">
    <div class="hero-text" style="flex:1 1 420px;">
      <h2><?= e($data['hero_title']) ?></h2>
      <h1><?= e($data['hero_subtitle']) ?></h1>
      <p class="hero-description"><?= e($data['hero_description']) ?></p>
      <div class="hero-buttons">
        <a href="#konsultasi" class="join-button">Konsultasi Gratis</a>
        <a href="#join-form" class="join-button">Daftar Sekarang</a>
      </div>
    </div>
    <div class="hero-image" style="flex:1 1 320px;min-width:280px;">
      <img src="images/<?= e($data['hero_image']) ?>" alt="Hero Image" style="width:100%;border-radius:12px;object-fit:cover;max-height:400px;">
    </div>
  </div>
</section>

<!-- SERVICES MARQUEE -->
<section class="services-banner content" style="overflow:hidden;">
  <div class="services-scroll" style="display:flex;gap:20px;white-space:nowrap;animation:scrollx 30s linear infinite;">
    <div class="service-item">Berita Ekonomi</div>
    <div class="service-item">Berita Internasional</div>
    <div class="service-item">Berita Olahraga</div>
    <div class="service-item">Berita Teknologi</div>
    <div class="service-item">Berita Hiburan</div>
    <div class="service-item">Berita Politik</div>
    <div class="service-item">Berita Ekonomi</div>
    <div class="service-item">Berita Internasional</div>
    <div class="service-item">Berita Olahraga</div>
  </div>
</section>

<!-- ABOUT SECTION -->
<section class="about content">
  <div class="about-container">
    <div class="about-text">
      <h3><?= e($data['about_heading']) ?></h3>
      <h2><?= e($data['about_subheading']) ?></h2>
    </div>
    <div class="about-content" style="margin-top:15px;">
      <p><?= e($data['about_paragraph1']) ?></p>
      <p><?= e($data['about_paragraph2']) ?></p>
    </div>
  </div>
</section>

<!-- JOIN SECTION (Promo) -->
<section class="join-section content">
  <div class="join-container">
    <h2>Bergabung dengan Premium Membership</h2>
    <p class="join-description">Dapatkan akses penuh ke berita eksklusif, analisis mendalam, dan konten premium tanpa iklan</p>
    
    <div class="benefits">
      <div class="benefit-item"><span class="benefit-icon">✓</span> Berita Eksklusif & Investigasi Mendalam</div>
      <div class="benefit-item"><span class="benefit-icon">✓</span> Akses Tanpa Iklan</div>
      <div class="benefit-item"><span class="benefit-icon">✓</span> Newsletter Premium Harian</div>
      <div class="benefit-item"><span class="benefit-icon">✓</span> Arsip Berita Lengkap</div>
    </div>
    
    <div class="pricing">
      <span class="price">Rp 99.000</span><span class="period"> / bulan</span>
    </div>
    
    <a href="#join-form" class="join-button">Daftar Sekarang</a>
  </div>
</section>

<?php if ($join_success): ?>
<!-- SUCCESS SECTION -->
<section id="join-success" class="success-container">
  <div class="success-wrapper">
    <div class="checkmark-circle">
      <div class="checkmark">✓</div>
    </div>
    <h1>Pendaftaran Berhasil!</h1>
    <p class="success-message">
      Terima kasih, <strong><?= e($new_member['name']) ?></strong>.<br>
      Kami telah mengirimkan konfirmasi ke <strong><?= e($new_member['email']) ?></strong> untuk paket
      <strong><?= e(ucfirst($new_member['plan'])) ?></strong>.
    </p>
    <div class="info-box">
      <h3>Langkah Selanjutnya</h3>
      <ul>
        <li>Cek email untuk petunjuk pembayaran/aktivasi.</li>
        <li>Nikmati konten premium tanpa iklan setelah aktif.</li>
        <li>Butuh bantuan? Hubungi editorial@globaltime.com</li>
      </ul>
    </div>
    <div class="button-group">
      <a href="home.php" class="home-button">Kembali ke Beranda</a>
      <a href="aboutUs.php" class="content-button">Daftarkan Orang Lain</a>
    </div>
  </div>
</section>
<?php else: ?>

<section id="join-form" class="form-container">
  <div class="form-wrapper">
    <div class="form-header">
      <h1>Premium Membership</h1>
      <h2>Daftar Sekarang</h2>
      <p>Akses berita eksklusif & tanpa iklan</p>
    </div>
    <div class="form-content">
      <?php if (!empty($errors)): ?>
        <div class="form-errors">
          <?php foreach ($errors as $err): ?>
            <div>• <?= e($err) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      
      <form action="aboutUs.php#join-form" method="POST">
        <input type="hidden" name="join_membership" value="1">
        
        <div class="input-group">
          <label for="name">Nama Lengkap</label>
          <input type="text" id="name" name="name" 
                 value="<?= e($_POST['name'] ?? ($_SESSION['user']['name'] ?? '')) ?>" 
                 required placeholder="Masukkan nama lengkap">
        </div>
        
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" 
                 value="<?= e($_POST['email'] ?? '') ?>" 
                 required placeholder="email@contoh.com">
        </div>
        
        <div class="input-group">
          <label for="phone">No. HP</label>
          <input type="tel" id="phone" name="phone" 
                 value="<?= e($_POST['phone'] ?? '') ?>" 
                 required placeholder="08xx xxxx xxxx">
        </div>
        
        <div class="input-group">
          <label for="plan">Pilih Paket</label>
          <select id="plan" name="plan">
            <option value="bulanan" <?= (($_POST['plan'] ?? '')==='bulanan' ? 'selected' : '') ?>>
              Bulanan - Rp 99.000/bulan
            </option>
            <option value="tahunan" <?= (($_POST['plan'] ?? '')==='tahunan' ? 'selected' : '') ?>>
              Tahunan - Rp 990.000/tahun (hemat 2 bulan)
            </option>
          </select>
        </div>
        
        <div class="terms">
          <input type="checkbox" id="agree" name="agree" 
                 <?= isset($_POST['agree']) ? 'checked' : '' ?> required>
          <label for="agree">Saya setuju dengan Syarat & Ketentuan.</label>
        </div>
        
        <button type="submit" class="submit-button">Kirim Pendaftaran</button>
      </form>
    </div>
  </div>
</section>
<?php endif; ?>


<footer class="contact-foote">
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
          <a href="#" class="social-link" title="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-link" title="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" class="social-link" title="TikTok"><i class="fab fa-tiktok"></i></a>
          <a href="#" class="social-link" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2025 Global Time. Hak Cipta Dilindungi. | 
       <a href="#">Kebijakan Privasi</a> | 
       <a href="#">Syarat & Ketentuan</a>
    </p>
  </div>
</footer>

<script src="aboutus.js"></script>
</body>
</html>