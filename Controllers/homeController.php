<?php
session_start();

include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

// Ambil 1 berita dengan views tertinggi sebagai highlight
$highlight_query = "SELECT * FROM berita ORDER BY views DESC LIMIT 1";
$highlight_result = mysqli_query($conn, $highlight_query);
$highlight = mysqli_fetch_assoc($highlight_result);


// Ambil daftar kategori unik dari berita
// $kategori_query = "SELECT DISTINCT kategori FROM berita ORDER BY kategori ASC";
// $kategori_result = mysqli_query($conn, $kategori_query);
// $kategori_list = [];
// if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
//   while ($row = mysqli_fetch_assoc($kategori_result)) {
//     $kategori_list[] = $row['kategori'];
//   }
// }


// Ambil berita terbaru (misal 8 berita terakhir)
$berita_query = "SELECT * FROM berita ORDER BY waktu DESC LIMIT 8";
$berita_result = mysqli_query($conn, $berita_query);
$berita_list = [];
if ($berita_result && mysqli_num_rows($berita_result) > 0) {
  while ($row = mysqli_fetch_assoc($berita_result)) {
    $berita_list[] = $row;
  }
}

// Ambil 5 berita trending (berdasarkan views tertinggi)
$trending_query = "SELECT * FROM berita ORDER BY views DESC LIMIT 5";
$trending_result = mysqli_query($conn, $trending_query);
$trending_list = [];
if ($trending_result && mysqli_num_rows($trending_result) > 0) {
  while ($row = mysqli_fetch_assoc($trending_result)) {
    $trending_list[] = $row;
  }
}
$error = $error ?? "";
include __DIR__ . '/../Views/home.php';