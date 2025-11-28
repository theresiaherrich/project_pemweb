<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tentang Kami - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/aboutUs.css">
</head>
<body>
<?php include 'header_user.php'; ?>

<!-- HERO SECTION -->
<section class="hero content">
  <div class="hero-content" style="display:flex;gap:30px;align-items:center;flex-wrap:wrap;">
    <div class="hero-text" style="flex:1 1 420px;">
      <h2><?= e($data['hero_title']) ?></h2>
      <h1><?= e($data['hero_subtitle']) ?></h1>
      <p class="hero-description"><?= e($data['hero_description']) ?></p>
    </div>
    <div class="hero-image" style="flex:1 1 320px;min-width:280px;">
      <img src="Views/uploads/<?= e($data['hero_image']) ?>" 
           alt="<?= e($data['hero_title']) ?>" 
           onerror="this.src='Views/uploads/default-hero.jpg'"
           style="width:100%;border-radius:12px;object-fit:cover;max-height:400px;">
    </div>
  </div>
</section>

<!-- SERVICES MARQUEE -->
<section class="services-banner content" style="overflow:hidden;">
  <div class="services-scroll" style="display:flex;gap:20px;white-space:nowrap;animation:scrollx 30s linear infinite;">
    <div class="service-item">Berita Ekonomi</div>
    <div class="service-item">Berita Internasional</div>
    <div class="service-item">Berita Olahraga</div>
    <div class="service-item">Berita Teknologi</div>
    <div class="service-item">Berita Hiburan</div>
    <div class="service-item">Berita Politik</div>
    <div class="service-item">Berita Ekonomi</div>
    <div class="service-item">Berita Internasional</div>
    <div class="service-item">Berita Olahraga</div>
  </div>
</section>

<!-- ABOUT SECTION -->
<section class="about content">
  <div class="about-container">
    <div class="about-text">
      <h3><?= e($data['about_heading']) ?></h3>
      <h2><?= e($data['about_subheading']) ?></h2>
    </div>
    <div class="about-content" style="margin-top:15px;">
      <p><?= e($data['about_paragraph1']) ?></p>
      <p><?= e($data['about_paragraph2']) ?></p>
    </div>
  </div>
</section>

<?php include 'footer_user.php'; ?>
<script src="aboutus.js"></script>
</body>
</html>