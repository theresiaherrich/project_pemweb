<?php
include 'koneksi.php';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
?>
<?php include 'header.php'; ?>

<div class="container">
  <h1>Hasil Pencarian</h1>

  <?php
  if ($q == '') {
      echo "<p>Ketikkan kata kunci untuk mencari berita.</p>";
  } else {
      $sql = "SELECT * FROM berita 
              WHERE judul LIKE '%$q%' 
                 OR isi_singkat LIKE '%$q%' 
                 OR kategori LIKE '%$q%' 
              ORDER BY waktu DESC";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
          echo "<p>Ditemukan " . mysqli_num_rows($result) . " berita untuk '<b>$q</b>'</p>";

          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div class='berita-item'>";
              echo "<h3>" . htmlspecialchars($row['judul']) . "</h3>";
              echo "<p><small>Kategori: " . htmlspecialchars($row['kategori']) . " | " . $row['waktu'] . "</small></p>";
              echo "<p>" . substr(strip_tags($row['isi_singkat']), 0, 150) . "...</p>";
              echo "<a href='detail_berita.php?id=" . $row['id_berita'] . "'>Baca selengkapnya</a>";
              echo "</div><hr>";
          }
      } else {
          echo "<p>Tidak ada hasil untuk '<b>$q</b>'.</p>";
      }
  }
  ?>
</div>

<?php include 'footer.php'; ?>
