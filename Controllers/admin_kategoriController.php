<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

// ===============================
// Proteksi Login
// ===============================
if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

// Jika role bukan admin → larang akses
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ?page=home");
    exit();
}
// ===============================
// Pesan
// ===============================
$message = '';
$message_type = '';
if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
    $message_type = $_GET['type'] ?? 'info';
}

// ===============================
// Handle Delete
// ===============================
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmtDel = $conn->prepare("DELETE FROM kategori WHERE id = ?");
    $stmtDel->bind_param("i", $id);

    if ($stmtDel->execute()) {
        $msg = "Kategori berhasil dihapus!";
        $type = "success";
    } else {
        $msg = "Gagal menghapus kategori.";
        $type = "error";
    }

    header("Location: ?page=admin_kategori&msg=" . urlencode($msg) . "&type=$type");
    exit();
}

// ===============================
// Handle Create / Update
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nama = trim($_POST['nama_kategori']);

    if ($id > 0) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE kategori SET nama_kategori=? WHERE id=?");
        $stmt->bind_param("si", $nama, $id);
        $stmt->execute();

        $msg = "Kategori berhasil diupdate!";
        $type = "success";
    } else {
        // CREATE
        $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        $stmt->bind_param("s", $nama);
        $stmt->execute();

        $msg = "Kategori berhasil ditambahkan!";
        $type = "success";
    }

    header("Location: ?page=admin_kategori&msg=" . urlencode($msg) . "&type=$type");
    exit();
}

// ===============================
// Ambil Semua Kategori
// ===============================
$result = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
$kategori_list = $result->fetch_all(MYSQLI_ASSOC);

// ===============================
// Load View
// ===============================
include __DIR__ . '/../Views/admin_kategori.php';
