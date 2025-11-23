<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Contact Us - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="kontak.css?v=3">
</head>

<body>
  <!-- HEADER -->
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

  <!-- MAIN CONTENT -->
  <main class="content">
    <section class="contact-header">
      <h1><i class="fas fa-envelope"></i> Hubungi Kami</h1>
      <p>Kami siap mendengar kritik, saran, dan pertanyaan Anda</p>
    </section>

    <div class="contact-wrapper">
      <!-- INFO KONTAK -->
      <div class="contact-info-section">
        <div class="info-card">
          <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <h3>Alamat Kantor</h3>
            <p>Jl. Soekarno Hatta No.15<br>Lowokwaru, Malang<br>Jawa Timur, Indonesia</p>
          </div>
        </div>

        <div class="info-card">
          <div class="info-icon"><i class="fas fa-phone"></i></div>
          <div>
            <h3>Telepon</h3>
            <p>+62 341 1234567<br>+62 341 7654321</p>
          </div>
        </div>

        <div class="info-card">
          <div class="info-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <h3>Email</h3>
            <p>editorial@globaltime.com<br>info@globaltime.com</p>
          </div>
        </div>

        <div class="info-card">
          <div class="info-icon"><i class="fas fa-clock"></i></div>
          <div>
            <h3>Jam Operasional</h3>
            <p>Senin - Jumat: 08:00 - 17:00<br>Sabtu: 08:00 - 14:00<br>Minggu: Tutup</p>
          </div>
        </div>
      </div>

      <!-- FORM KONTAK -->
      <div class="contact-form-section">
        <h2><?= $edit_mode ? '<i class="fas fa-edit"></i> Edit Pesan' : 'Kirim Pesan' ?></h2>

        <!-- PESAN SUKSES -->
        <?php if (isset($success)): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <p><?php echo $success; ?></p>
          </div>
        <?php endif; ?>

        <!-- PESAN ERROR -->
        <?php if (isset($error)): ?>
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <p><?php echo $error; ?></p>
          </div>
        <?php endif; ?>

        <!-- FORM -->
        <form method="POST" id="contactForm">
          <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
          <?php endif; ?>

          <div class="form-group">
            <label for="name"><i class="fas fa-user"></i> Nama Lengkap</label>
            <input type="text" id="name" name="name" required placeholder="Masukkan nama lengkap Anda"
              value="<?= $edit_mode ? htmlspecialchars($edit_data['nama']) : '' ?>">
          </div>

          <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" required placeholder="nama@email.com"
              value="<?= $edit_mode ? htmlspecialchars($edit_data['email']) : '' ?>">
          </div>

          <div class="form-group">
            <label for="subject"><i class="fas fa-tag"></i> Subjek</label>
            <input type="text" id="subject" name="subject" required placeholder="Subjek pesan"
              value="<?= $edit_mode ? htmlspecialchars($edit_data['subjek']) : '' ?>">
          </div>

          <div class="form-group">
            <label for="message"><i class="fas fa-comment"></i> Pesan</label>
            <textarea id="message" name="message" rows="6" required
              placeholder="Tulis pesan Anda di sini..."><?= $edit_mode ? htmlspecialchars($edit_data['pesan']) : '' ?></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-submit">
              <i class="fas fa-paper-plane"></i> <?= $edit_mode ? 'Update Pesan' : 'Kirim Pesan' ?>
            </button>
            <?php if ($edit_mode): ?>
              <a href="kontak.php" class="btn-cancel">
                <i class="fas fa-times"></i> Batal
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- RIWAYAT PESAN -->
    <?php if (!empty($messages)): ?>
      <section class="messages-history">
        <h2><i class="fas fa-history"></i> Riwayat Pesan Anda</h2>
        <div style="overflow-x:auto;">
          <table class="messages-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($messages as $msg):
                ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($msg['tanggal'])) ?></td>
                  <td><strong><?= htmlspecialchars($msg['subjek']) ?></strong></td>
                  <td><?= htmlspecialchars(substr($msg['pesan'], 0, 50)) ?>...</td>
                  <td>
                    <span class="status-badge status-<?= $msg['status'] ?>">
                      <?= ucfirst($msg['status']) ?>
                    </span>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="kontak.php?edit=<?= $msg['id'] ?>" class="btn-edit" title="Edit">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                      <a href="kontak.php?delete=<?= $msg['id'] ?>" class="btn-delete"
                        onclick="return confirm('Yakin ingin menghapus pesan ini?')" title="Hapus">
                        <i class="fas fa-trash"></i> Hapus
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    <?php endif; ?>
  </main>

  <!-- FOOTER -->
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
      <p>&copy; 2025 Global Time. Hak Cipta Dilindungi. | <a href="#">Kebijakan Privasi</a> | <a href="#">Syarat &
          Ketentuan</a></p>
    </div>
  </footer>

  <script src="kontak.js"></script>
</body>

</html>