<?php
session_start();

include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

$kategori_query = "SELECT DISTINCT kategori FROM berita ORDER BY kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_query);
$kategori_list = [];
if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
  while ($row = mysqli_fetch_assoc($kategori_result)) {
    $kategori_list[] = $row['kategori'];
  }
}
// Ambil ID berita dari parameter URL
$id_terpilih = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$berita_ditemukan = null;

// Ambil data berita berdasarkan ID
if ($id_terpilih > 0) {
    $query = "SELECT * FROM berita WHERE id_berita = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_terpilih);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $berita_ditemukan = mysqli_fetch_assoc($result);

    // Tambah jumlah views jika berita ditemukan
    if ($berita_ditemukan) {
        $update_views = "UPDATE berita SET views = views + 1 WHERE id_berita = ?";
        $stmt_update = mysqli_prepare($conn, $update_views);
        mysqli_stmt_bind_param($stmt_update, "i", $id_terpilih);
        mysqli_stmt_execute($stmt_update);
    }
}
$error = $error ?? "";
include __DIR__ . '/../Views/detail_berita.php';

