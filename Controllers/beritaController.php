<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

/* ===============================
   1. Ambil semua kategori
   =============================== */
$kategori_query = "SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_query);

$kategori_list = [];
if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
    while ($row = mysqli_fetch_assoc($kategori_result)) {
        $kategori_list[] = $row;
    }
}

/* ===============================
   2. Cek apakah ada pencarian / filter kategori
   =============================== */
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

/* ===============================
   3. Query pencarian
   =============================== */
if ($keyword !== '') {

    $stmt = $conn->prepare("
        SELECT b.id_berita, b.judul, k.nama_kategori AS kategori, 
               b.isi_singkat, b.gambar, b.waktu, b.views
        FROM berita b
        JOIN kategori k ON b.kategori_id = k.id
        WHERE b.judul LIKE ? 
           OR b.isi_singkat LIKE ?
           OR k.nama_kategori LIKE ?
        ORDER BY b.waktu DESC
    ");

    $like = "%$keyword%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

/* ===============================
   4. Query berdasarkan kategori
   =============================== */
} elseif ($kategori !== '') {

    $stmt = $conn->prepare("
        SELECT b.id_berita, b.judul, k.nama_kategori AS kategori, 
               b.isi_singkat, b.gambar, b.waktu, b.views
        FROM berita b
        JOIN kategori k ON b.kategori_id = k.id
        WHERE b.kategori_id = ?
        ORDER BY b.waktu DESC
    ");
    $stmt->bind_param("i", $kategori);
    $stmt->execute();
    $result = $stmt->get_result();

/* ===============================
   5. Tampilkan semua berita
   =============================== */
} else {

    $query = "
        SELECT b.id_berita, b.judul, k.nama_kategori AS kategori, 
               b.isi_singkat, b.gambar, b.waktu, b.views
        FROM berita b
        JOIN kategori k ON b.kategori_id = k.id
        ORDER BY b.waktu DESC
    ";

    $result = mysqli_query($conn, $query);
}

/* ===============================
   6. Simpan hasil ke array
   =============================== */
$semua_berita = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $semua_berita[] = $row;
    }
}

$error = $error ?? "";

/* ===============================
   7. Load tampilan
   =============================== */
include __DIR__ . '/../Views/berita.php';
