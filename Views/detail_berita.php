<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $berita_ditemukan ? htmlspecialchars($berita_ditemukan['judul']) : 'Berita Tidak Ditemukan' ?> - Global Time</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/project/Views/css/berita.css">
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
                    <span class="hello">Halo, <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong></span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <div class="profile-menu">
                    <a href="profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a>
                    <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigasi -->
    <nav class="news-nav">
        <div class="nav-container">
            <ul class="nav-menu">
                <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>

                <li class="dropdown">
                    <a href="berita.php" class="dropdown-toggle">
                        <i class="fas fa-newspaper"></i> Berita <i class="fas fa-chevron-down"></i>
                    </a>
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


<!-- ========== HALAMAN DETAIL BERITA ========== -->
<div class="detail-container">

    <?php if ($berita_ditemukan): ?>

        <h1><?= htmlspecialchars($berita_ditemukan['judul']) ?></h1>

        <div class="meta">
            <span class="category"><?= htmlspecialchars($berita_ditemukan['kategori']) ?></span>
            <span><i class="fas fa-clock"></i> <?= date("d M Y H:i", strtotime($berita_ditemukan['waktu'])) ?></span>
            <span><i class="fas fa-eye"></i> <?= htmlspecialchars($berita_ditemukan['views'] + 1) ?></span>
        </div>

        <img src="<?= htmlspecialchars($berita_ditemukan['gambar']) ?>" alt="<?= htmlspecialchars($berita_ditemukan['judul']) ?>" class="detail-image">

        <div class="isi-lengkap">
            <p><?= nl2br(htmlspecialchars($berita_ditemukan['isi_lengkap'])) ?></p>
        </div>

        <a href="berita.php" class="btn-kembali">← Kembali ke Berita</a>

    <?php else: ?>

        <h1>Berita Tidak Ditemukan</h1>
        <p>Maaf, berita yang Anda cari tidak dapat ditemukan.</p>
        <a href="berita.php" class="btn-kembali">← Kembali ke Berita</a>

    <?php endif; ?>

</div>


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
                <p>Global Time adalah platform berita terpercaya yang menghadirkan informasi terkini dari seluruh dunia dengan perspektif objektif dan mendalam.</p>
            </div>
        </div>

        <div class="contact-grid">
            <div class="contact-section">
                <h3>KANTOR INDONESIA</h3>
                <p><a href="mailto:editorial@globaltime.com">editorial@globaltime.com</a></p>
                <p>+62 341 1234567</p>
                <p>Jl. Soekarno Hatta No.15, Malang</p>
                <p><a href="#">LIHAT PETA ↗</a></p>
            </div>

            <div class="contact-section">
                <h3>TETAP TERUPDATE</h3>
                <p><a href="#">LANGGANAN NEWSLETTER</a></p>

                <h3 style="margin-top: 30px;">IKUTI GLOBAL TIME</h3>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 Global Time. Hak Cipta Dilindungi.</p>
    </div>
</footer>

</body>
</html>
