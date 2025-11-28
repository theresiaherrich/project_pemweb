<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Home - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/galeri.css">
</head>

<body>
  <!-- HEADER -->
  <?php include 'header_user.php'; ?>


    <!-- MAIN CONTENT -->
    <main>
        <div class="content">
            <section class="video-grid">
                <?php foreach ($videos as $video): ?>
                    <div class="video-card">
                        <a href="<?= $video['url']; ?>" target="_blank">
                            <div class="thumbnail">
                                <img src="<?= $video['thumbnail']; ?>" alt="thumbnail">
                                <span class="duration"><?= $video['duration']; ?></span>
                            </div>
                        </a>
                        <div class="video-info">
                            <h3><?= $video['title']; ?></h3>
                            <p class="category"><?= $video['category']; ?></p>
                            <p class="date"><?= $video['date']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>
    </main>

    <!-- FOOTER -->
      <?php include 'footer_user.php'; ?>

  <script src="galeri.js"></script>
</body>

</html>