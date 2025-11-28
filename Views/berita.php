
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Berita - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/berita.css">
</head>

<body>

  <?php include 'header_user.php'; ?>

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
          <a href="?page=detail_berita&id=<?= htmlspecialchars($berita['id_berita']) ?>">
            <article class="news-card">
              <img src="<?= htmlspecialchars($berita['gambar']) ?>" 
                   alt="<?= htmlspecialchars($berita['judul']) ?>" 
                   class="news-img">

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

  <?php include 'footer_user.php'; ?>

  <script src="/Views/js/berita.js"></script>
</body>

</html>
