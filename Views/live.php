<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Live Streaming - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="live.css">
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

  <!-- MAIN CONTENT -->
  <main class="content">

    <!-- LIVE STREAM HEADER -->
    <section class="stream-header">
      <?php if ($has_live_stream): ?>
        <div class="live-badge-large">
          <span class="pulse"></span>
          <i class="fas fa-circle"></i> LIVE NOW
        </div>
      <?php endif; ?>
      <h1><i class="fas fa-video"></i> Siaran Langsung</h1>
      <p>Saksikan berita terkini dan event penting secara langsung</p>
    </section>

    <?php if ($has_live_stream): ?>
    <!-- MAIN STREAM (Video + Chat) -->
    <section class="main-stream">

      <!-- LEFT: VIDEO PLAYER -->
      <div class="stream-player">
        <div id="videoPlayer" class="video-container">
          <iframe
            id="mainVideo"
            src="<?php echo htmlspecialchars($main_stream['video_url']); ?>"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
          </iframe>
          <div class="video-overlay">
            <div class="live-indicator">
              <span class="pulse"></span>
              <i class="fas fa-circle"></i> LIVE
            </div>
            <div class="viewer-count">
              <i class="fas fa-eye"></i> <span id="viewerCount"><?php echo htmlspecialchars($main_stream['viewers']); ?></span> menonton
            </div>
          </div>
        </div>

        <div class="stream-info">
          <div class="stream-meta">
            <span class="category-badge"><?php echo htmlspecialchars($main_stream['category']); ?></span>
            <h2 id="streamTitle"><?php echo htmlspecialchars($main_stream['title']); ?></h2>
            <p class="channel-name">
              <i class="fas fa-broadcast-tower"></i> <?php echo htmlspecialchars($main_stream['channel']); ?>
            </p>
            <p class="stream-description" id="streamDescription">
              <?php echo htmlspecialchars($main_stream['description']); ?>
            </p>
            <p class="stream-date">
              <i class="far fa-calendar"></i>
              <?php echo date("d M Y", strtotime($main_stream['live_date'])); ?>
            </p>
          </div>

          <div class="stream-actions">
            <button class="btn-action" onclick="toggleLike()">
              <i class="far fa-thumbs-up" id="likeIcon"></i>
              <span id="likeCount">1.2K</span>
            </button>
            <button class="btn-action" onclick="shareStream()">
              <i class="fas fa-share"></i>
              Share
            </button>
            <button class="btn-action" onclick="toggleFullscreen()">
              <i class="fas fa-expand"></i>
              Fullscreen
            </button>
          </div>
        </div>
      </div>

      <!-- RIGHT: LIVE CHAT -->
      <div class="chat-area">
        <div class="chat-header">
          <h3><i class="fas fa-comments"></i> Live Chat</h3>
        </div>
        <div class="chat-box" id="chatMessages">
          <div class="chat-message"><strong>Andi:</strong> Wah keren banget!</div>
          <div class="chat-message"><strong>Bella:</strong> Setuju, rame banget acaranya 🔥</div>
          <div class="chat-message"><strong>Citra:</strong> Livestream lancar banget!</div>
          <div class="chat-message"><strong>Doni:</strong> Mantap pokoknya 👍</div>
        </div>
        <div class="chat-input">
          <input type="text" id="chatInput" placeholder="Ketik pesan...">
          <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
        </div>
      </div>

    </section>

    <!-- OTHER LIVE STREAMS -->
    <?php if (count($live_streams) > 1): ?>
    <section class="other-streams">
      <h2><i class="fas fa-broadcast-tower"></i> Siaran Live Lainnya</h2>
      <div class="stream-grid">
        <?php foreach (array_slice($live_streams, 1) as $stream): ?>
          <div class="stream-card" onclick="switchStream(<?php echo $stream['id']; ?>, '<?php echo addslashes($stream['title']); ?>', '<?php echo htmlspecialchars($stream['video_url']); ?>', '<?php echo addslashes($stream['description']); ?>', '<?php echo $stream['viewers']; ?>')">
            <div class="stream-thumbnail">
              <img src="<?php echo htmlspecialchars($stream['thumbnail']); ?>" alt="<?php echo htmlspecialchars($stream['title']); ?>">
              <div class="live-badge">
                <span class="pulse"></span>
                <i class="fas fa-circle"></i> LIVE
              </div>
              <div class="stream-viewers">
                <i class="fas fa-eye"></i> <?php echo htmlspecialchars($stream['viewers']); ?>
              </div>
            </div>
            <div class="stream-card-info">
              <span class="category-badge"><?php echo htmlspecialchars($stream['category']); ?></span>
              <h3><?php echo htmlspecialchars($stream['title']); ?></h3>
              <p class="channel">
                <i class="fas fa-broadcast-tower"></i> <?php echo htmlspecialchars($stream['channel']); ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <?php else: ?>
    <!-- NO LIVE STREAM MESSAGE -->
    <section class="no-stream" style="text-align:center; padding:60px 20px; background:#f5f5f5; border-radius:8px; margin:20px 0;">
      <i class="fas fa-video-slash" style="font-size:80px; color:#ccc; margin-bottom:20px;"></i>
      <h2 style="color:#666; margin-bottom:10px;">Tidak Ada Siaran Langsung</h2>
      <p style="color:#999;">Saat ini tidak ada siaran langsung yang sedang berlangsung. Silakan cek jadwal siaran mendatang di bawah.</p>
    </section>
    <?php endif; ?>

    <!-- UPCOMING STREAMS -->
    <?php if (!empty($upcoming_streams)): ?>
    <section class="upcoming-streams">
      <h2><i class="far fa-clock"></i> Siaran Mendatang</h2>
      <div class="upcoming-grid">
        <?php foreach ($upcoming_streams as $upcoming): ?>
          <div class="upcoming-card">
            <div class="upcoming-thumbnail">
              <img src="<?php echo htmlspecialchars($upcoming['thumbnail']); ?>" alt="<?php echo htmlspecialchars($upcoming['title']); ?>">
              <div class="schedule-badge">
                <i class="far fa-calendar"></i> <?php echo date("d M Y", strtotime($upcoming['event_date'])); ?>
              </div>
            </div>
            <div class="upcoming-info">
              <h4><?php echo htmlspecialchars($upcoming['title']); ?></h4>
              <p class="schedule-time">
                <i class="far fa-clock"></i> <?php echo htmlspecialchars($upcoming['schedule']); ?>
              </p>
              <button class="btn-reminder" onclick="setReminder(this)">
                <i class="far fa-bell"></i> Ingatkan Saya
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php else: ?>
    <section class="no-upcoming" style="text-align:center; padding:40px 20px; background:#fff; border-radius:8px; margin:20px 0; border:1px dashed #ddd;">
      <i class="far fa-calendar-times" style="font-size:60px; color:#ddd; margin-bottom:15px;"></i>
      <h3 style="color:#999;">Belum Ada Jadwal Siaran Mendatang</h3>
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
      <p>&copy; 2025 Global Time. Hak Cipta Dilindungi. | <a href="#">Kebijakan Privasi</a> | <a href="#">Syarat & Ketentuan</a></p>
    </div>
  </footer>

  <script src="live.js"></script>
</body>

</html>