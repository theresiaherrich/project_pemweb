<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

// Validasi parameter id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: loker.php");
    exit();
}

$id = (int) $_GET['id'];

// Gunakan prepared statement agar aman dari SQL Injection
$stmt = $conn->prepare("SELECT id, judul, perusahaan, kategori, tanggal, gambar, deskripsi FROM loker WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$loker = $result->fetch_assoc();

if (!$loker) {
    echo "<p>Lowongan tidak ditemukan.</p>";
    exit();
}

// Fungsi helper untuk menampilkan teks dengan aman
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
$error = $error ?? "";
include __DIR__ . '/../Views/loker_detail.php';