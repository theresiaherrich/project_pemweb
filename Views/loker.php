
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lowongan Kerja - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/home.css">
</head>

<body>
  <?php include 'header_user.php'; ?>

  <main class="content">
    <section class="news-section">
      <h2>Lowongan Kerja Terbaru</h2>

      <div class="news-grid">
        <?php if (!empty($loker_list)): ?>
          <?php foreach ($loker_list as $loker): ?>
            
            <article class="news-card">
              <img src="<?= $loker['gambar'] ?>" 
                   alt="<?= htmlspecialchars($loker['judul']) ?>" 
                   class="news-img"
                   style="object-fit: cover; width: 100%; height: 200px;">

              <div class="news-content">
                <span class="category">
                  <?= htmlspecialchars($loker['nama_kategori'] ?? 'Umum') ?>
                </span>

                <h3><?= htmlspecialchars($loker['judul']) ?></h3>
                <p><?= htmlspecialchars(substr($loker['deskripsi'], 0, 100)) ?>...</p>

                <div class="meta">
                  <span><i class="fas fa-building"></i> 
                    <?= htmlspecialchars($loker['perusahaan']) ?></span>
                  <span><i class="fas fa-calendar"></i> 
                    <?= date('d M Y', strtotime($loker['tanggal'])) ?></span>
                </div>

                <a href="index.php?page=loker_detail&id=<?= $loker['id'] ?>" 
                   class="apply-btn"
                   style="display:inline-block;margin-top:8px;background:#c62828;color:white;padding:8px 15px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:600;">
                  Lihat Detail
                </a>
              </div>
            </article>

          <?php endforeach; ?>
        <?php else: ?>
          <p>Tidak ada lowongan kerja tersedia saat ini.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <?php include 'footer_user.php'; ?>
</body>
</html>