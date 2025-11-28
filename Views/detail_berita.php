
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $berita_ditemukan ? htmlspecialchars($berita_ditemukan['judul']) : 'Berita Tidak Ditemukan' ?> - Global Time</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Views/css/detail_berita.css">

</head>

<body>

<?php include 'header_user.php'; ?>

<div class="detail-container">
    <?php if ($berita_ditemukan): ?>

        <h1><?= htmlspecialchars($berita_ditemukan['judul']) ?></h1>

        <div class="meta">
            <span class="category">
                <?= htmlspecialchars($berita_ditemukan['nama_kategori']) ?>
            </span>

            <span><i class="fas fa-clock"></i> 
                <?= date("d M Y H:i", strtotime($berita_ditemukan['waktu'])) ?>
            </span>

            <span><i class="fas fa-eye"></i> 
                <?= htmlspecialchars($berita_ditemukan['views'] + 1) ?>
            </span>
        </div>

        <img src="<?= htmlspecialchars($berita_ditemukan['gambar']) ?>" 
             alt="<?= htmlspecialchars($berita_ditemukan['judul']) ?>">

        <div class="isi-lengkap">
            <p><?= nl2br(htmlspecialchars($berita_ditemukan['isi_lengkap'])) ?></p>
        </div>

        <a href="berita.php" class="btn-kembali">← Kembali ke Berita</a>

    <?php else: ?>

        <h1>Berita Tidak Ditemukan</h1>
        <p>Maaf, berita tidak ditemukan atau mungkin sudah dihapus.</p>
        <a href="berita.php" class="btn-kembali">← Kembali ke Berita</a>

    <?php endif; ?>
</div>
<?php include 'footer_user.php'; ?>

</body>
</html>
