
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= htmlspecialchars($loker['judul']) ?> - Global Time</title>
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/home.css"> 
  
  <style>
    .detail-container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; }
    .detail-header img { width: 100%; height: 320px; object-fit: cover; }
    .detail-content { padding: 25px 30px; }
    .detail-content h2 { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 10px; }
    .detail-meta { display: flex; flex-wrap: wrap; gap: 15px; font-size: 14px; color: #666; margin-bottom: 15px; }
    .detail-meta span i { color: #c62828; margin-right: 6px; }
    .detail-desc { line-height: 1.7; color: #444; margin-bottom: 25px; font-size: 15px; white-space: pre-line; }
    .back-btn { display: inline-block; background: #c62828; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.3s ease; }
    .back-btn:hover { background: #a81f1f; }
  </style>
</head>

<body>

  <?php include 'header_user.php'; ?>

  <main class="content">
    <div class="detail-container">
      <div class="detail-header">
        <img src="<?= htmlspecialchars($loker['gambar']) ?>" alt="<?= htmlspecialchars($loker['judul']) ?>">
      </div>

      <div class="detail-content">
        <h2><?= htmlspecialchars($loker['judul']) ?></h2>
        
        <div class="detail-meta">
          <span><i class="fas fa-building"></i> <?= htmlspecialchars($loker['perusahaan']) ?></span>
          <span><i class="fas fa-tag"></i> <?= htmlspecialchars($loker['nama_kategori'] ?? '-') ?></span>
          <span><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($loker['tanggal'])) ?></span>
        </div>

        <div class="detail-desc">
          <?= nl2br(htmlspecialchars($loker['deskripsi'])) ?>
        </div>

        <a href="index.php?page=loker" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Loker
        </a>
      </div>
    </div>
  </main>

  <?php include 'footer_user.php'; ?>

</body>
</html>