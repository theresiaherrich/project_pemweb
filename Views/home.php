
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Home - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/home.css">
</head>

<body>
  <!-- HEADER -->
  <?php include 'header_user.php'; ?>

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

  <?php include 'footer_user.php'; ?>

  <script src="home.js"></script>
</body>

</html>