<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

// Ambil data live streams dari database
$query_live = "SELECT * FROM live_streams WHERE is_live = 1 ORDER BY id ASC";
$result_live = mysqli_query($conn, $query_live);
$live_streams = mysqli_fetch_all($result_live, MYSQLI_ASSOC);

// Ambil data upcoming streams dari database
$query_upcoming = "SELECT * FROM upcoming_streams ORDER BY id ASC";
$result_upcoming = mysqli_query($conn, $query_upcoming);
$upcoming_streams = mysqli_fetch_all($result_upcoming, MYSQLI_ASSOC);

// Ambil kategori dari tabel kategori
$kategori_query = "SELECT DISTINCT nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_query);
$kategori_list = [];
if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
  while ($row = mysqli_fetch_assoc($kategori_result)) {
    $kategori_list[] = $row['nama_kategori'];
  }
}

// Cek apakah ada data live stream
$has_live_stream = !empty($live_streams);
$main_stream = $has_live_stream ? $live_streams[0] : null;

$error = $error ?? "";
include __DIR__ . '/../Views/live.php';