<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../model/koneksi.php';

/* ============================
   AMBIL KATEGORI DARI TABEL kategori
   ============================ */
$kategori_query = "SELECT nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_query);

$kategori_list = [];
if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
    while ($row = mysqli_fetch_assoc($kategori_result)) {
        $kategori_list[] = $row['nama_kategori'];
    }
}

// AMBIL PAGE DARI ROUTER index.php
$current_page = $_GET['page'] ?? 'home';

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Global Time'; ?> - Global Time</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/project/Views/css/header_footer.css?v=<?php echo time(); ?>">
    <?php if (isset($extra_css))
        echo $extra_css; ?>
</head>

<body>

    <header class="main-header">
        <div class="header-inner">
            <div class="brand-left">
                <img src="/project/Views/css/Globaltime.png" alt="Logo Global Time" class="logo">
                <div class="brand-text">
                    <h1 class="brand">Global Time</h1>
                    <p class="brand-sub">Berita Terpercaya, Perspektif Global</p>
                </div>
            </div>

            <div class="user-right">
                <div class="search-container">
                    <form class="search-form" action="index.php" method="GET">
                        <input type="hidden" name="page" value="berita">
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
                        <a href="index.php?page=profile"><i class="fas fa-user-edit"></i> Edit Profile</a>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <a href="index.php?page=admin_berita"><i class="fas fa-newspaper"></i> Kelola Berita</a>
                        <?php endif; ?>
                        <a href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <nav class="news-nav">
            <div class="nav-container">
                <ul class="nav-menu">
                    <li>
                        <a href="index.php?page=home" class="<?php echo ($current_page === 'home') ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>

                    <!-- MENU BERITA + DROPDOWN KATEGORI -->
                    <li class="dropdown">
                        <a href="index.php?page=berita"
                            class="<?php echo ($current_page === 'berita' || $current_page === 'detail_berita') ? 'active' : ''; ?>">
                            <i class="fas fa-newspaper"></i> Berita <i class="fas fa-chevron-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="index.php?page=berita"><i class="fas fa-list"></i> Semua Berita</a></li>
                            <li class="dropdown-divider"></li>

                            <?php foreach ($kategori_list as $kat): ?>
                                <li>
                                    <a href="index.php?page=berita&kategori=<?= urlencode($kat) ?>">
                                        <i class="fas fa-tag"></i> <?= htmlspecialchars($kat) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>

                    <!-- Menu lainnya -->
                    <li>
                        <a href="index.php?page=aboutUs"
                            class="<?php echo ($current_page === 'aboutUs') ? 'active' : ''; ?>">
                            <i class="fas fa-info-circle"></i> About Us
                        </a>
                    </li>

                    <li>
                        <a href="index.php?page=galeri"
                            class="<?php echo ($current_page === 'galeri') ? 'active' : ''; ?>">
                            <i class="fas fa-images"></i> Galeri
                        </a>
                    </li>

                    <li>
                        <a href="index.php?page=live" class="<?php echo ($current_page === 'live') ? 'active' : ''; ?>">
                            <i class="fas fa-video"></i> Live Streaming
                        </a>
                    </li>

                    <li>
                        <a href="index.php?page=loker"
                            class="<?php echo ($current_page === 'loker') ? 'active' : ''; ?>">
                            <i class="fas fa-briefcase"></i> Loker
                        </a>
                    </li>

                    <li>
                        <a href="index.php?page=kontak"
                            class="<?php echo ($current_page === 'kontak') ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i> Kontak
                        </a>
                    </li>

                    <!-- Menu Kelola Berita - Muncul untuk SEMUA user di Sidebar Mobile -->
                    <li class="admin-only-mobile">
                        <a href="index.php?page=admin_berita"
                            class="<?php echo ($current_page === 'admin_berita') ? 'active' : ''; ?>">
                            <i class="fas fa-cog"></i> Kelola Berita
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

    </header>

    <script src="/project/Views/js/home.js"></script>

</body>

</html>