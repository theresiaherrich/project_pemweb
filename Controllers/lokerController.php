<?php
session_start(); // Pastikan hanya dipanggil sekali

// Menghubungkan ke database
include __DIR__ . '/../model/koneksi.php';

// Mengecek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

// Ambil data loker + kategori (JOIN)
$query = "
    SELECT loker.*, kategori.nama_kategori
    FROM loker
    LEFT JOIN kategori ON loker.kategori_id = kategori.id
    ORDER BY loker.tanggal DESC
";
$result = mysqli_query($conn, $query);

$loker_list = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $loker_list[] = $row; // Menambahkan data lowongan ke dalam array
    }
}

// Ambil semua kategori (opsional, jika diperlukan)
$kategori_query = "SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_query);

$kategori_list = [];
if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
    while ($row = mysqli_fetch_assoc($kategori_result)) {
        $kategori_list[] = $row; // Menambahkan kategori ke dalam array
    }
}

// Mengarahkan ke halaman view
include __DIR__ . '/../Views/loker.php';
?>
