<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

// Ambil data lowongan dari database
$query = "SELECT * FROM loker ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);
$loker_list = [];
if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $loker_list[] = $row;
  }
}

$kategori_query = "SELECT DISTINCT kategori FROM berita ORDER BY kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_query);
$kategori_list = [];
if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
  while ($row = mysqli_fetch_assoc($kategori_result)) {
    $kategori_list[] = $row['kategori'];
  }
}

$error = $error ?? "";
include __DIR__ . '/../Views/loker.php';