<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

// ===============================
// 0. Proteksi Login
// ===============================
if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

// ===============================
// 1. Ambil Kategori dari tabel kategori
// ===============================
$kategori_list = [];

$stmtKat = $conn->prepare("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
$stmtKat->execute();
$resultKat = $stmtKat->get_result();

while ($row = $resultKat->fetch_assoc()) {
    $kategori_list[] = $row; 
}

// ===============================
// 2. Ambil ID Berita dari URL + sanitasi
// ===============================
$id_terpilih = isset($_GET['id']) ? $_GET['id'] : 0;

// Validasi ID hanya angka
if (!ctype_digit($id_terpilih)) {
    $id_terpilih = 0;
} else {
    $id_terpilih = (int)$id_terpilih;
}

$berita_ditemukan = null;

// ===============================
// 3. Ambil berita berdasarkan ID (JOIN kategori)
// ===============================
if ($id_terpilih > 0) {

    $stmt = $conn->prepare("
        SELECT b.*, k.nama_kategori 
        FROM berita b
        JOIN kategori k ON b.kategori_id = k.id
        WHERE b.id_berita = ?
    ");

    $stmt->bind_param("i", $id_terpilih);
    $stmt->execute();
    $result = $stmt->get_result();
    $berita_ditemukan = $result->fetch_assoc();

    // ===============================
    // 4. Tambah jumlah views jika berita ditemukan
    // ===============================
    if ($berita_ditemukan) {
        $stmtUpdate = $conn->prepare("
            UPDATE berita SET views = views + 1 WHERE id_berita = ?
        ");
        $stmtUpdate->bind_param("i", $id_terpilih);
        $stmtUpdate->execute();
    }
}

$error = $error ?? "";

// ===============================
// 5. Load View
// ===============================
include __DIR__ . '/../Views/detail_berita.php';
