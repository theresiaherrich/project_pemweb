<?php
session_start();

include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

// Ambil 1 berita dengan views tertinggi sebagai highlight (dengan JOIN kategori)
$highlight_query = "SELECT berita.*, kategori.nama_kategori as kategori 
                    FROM berita 
                    LEFT JOIN kategori ON berita.kategori_id = kategori.id 
                    ORDER BY berita.views DESC 
                    LIMIT 1";
$highlight_result = mysqli_query($conn, $highlight_query);
$highlight = mysqli_fetch_assoc($highlight_result);

// Ambil berita terbaru (dengan JOIN kategori)
$berita_query = "SELECT berita.*, kategori.nama_kategori as kategori 
                 FROM berita 
                 LEFT JOIN kategori ON berita.kategori_id = kategori.id 
                 ORDER BY berita.waktu DESC 
                 LIMIT 8";
$berita_result = mysqli_query($conn, $berita_query);
$berita_list = [];
if ($berita_result && mysqli_num_rows($berita_result) > 0) {
  while ($row = mysqli_fetch_assoc($berita_result)) {
    $berita_list[] = $row;
  }
}

// Ambil 5 berita trending (dengan JOIN kategori)
$trending_query = "SELECT berita.*, kategori.nama_kategori as kategori 
                   FROM berita 
                   LEFT JOIN kategori ON berita.kategori_id = kategori.id 
                   ORDER BY berita.views DESC 
                   LIMIT 5";
$trending_result = mysqli_query($conn, $trending_query);
$trending_list = [];
if ($trending_result && mysqli_num_rows($trending_result) > 0) {
  while ($row = mysqli_fetch_assoc($trending_result)) {
    $trending_list[] = $row;
  }
}

$error = $error ?? "";
include __DIR__ . '/../Views/home.php';