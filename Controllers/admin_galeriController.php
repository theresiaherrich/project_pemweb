<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

// Jika belum login → redirect
if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

// Jika role bukan admin → larang akses
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ?page=home");
    exit();
}
// =============================
// AMBIL DATA KATEGORI
// =============================
$kategori_list = [];
$stmtKat = $conn->prepare("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
$stmtKat->execute();
$resultKat = $stmtKat->get_result();
while ($row = $resultKat->fetch_assoc()) {
    $kategori_list[] = $row;
}

// =============================
// VARIABEL PESAN
// =============================
$message = '';
$message_type = 'info';

// =============================
// HANDLE DELETE VIDEO
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_video'])) {

    $id_video = (int)$_POST['id_video'];

    $stmt = $conn->prepare("DELETE FROM galeri WHERE id = ?");
    $stmt->bind_param("i", $id_video);

    if ($stmt->execute()) {
        header("Location: ?page=admin_galeri&success=3");
        exit();
    } else {
        $message = "Gagal menghapus video!";
        $message_type = "error";
    }

    $stmt->close();
}

// =============================
// AMBIL PESAN DARI REDIRECT
// =============================
if (isset($_GET['success'])) {
    $success = $_GET['success'];

    if ($success == 1) {
        $message = "Video berhasil ditambahkan!";
        $message_type = "success";
    } elseif ($success == 2) {
        $message = "Video berhasil diupdate!";
        $message_type = "success";
    } elseif ($success == 3) {
        $message = "Video berhasil dihapus!";
        $message_type = "success";
    }
}

// =============================
// TAMBAH VIDEO
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_video'])) {

    $title     = trim($_POST['title']);
    $url       = trim($_POST['url']);
    $thumbnail = trim($_POST['thumbnail']);
    $category  = trim($_POST['category']);
    $duration  = trim($_POST['duration']);

    if (!filter_var($thumbnail, FILTER_VALIDATE_URL)) {
        $message = "URL Thumbnail tidak valid!";
        $message_type = "error";
    } else {

        $stmt = $conn->prepare("INSERT INTO galeri (title, url, thumbnail, category, duration) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $url, $thumbnail, $category, $duration);

        if ($stmt->execute()) {
            header("Location: ?page=admin_galeri&success=1");
            exit();
        } else {
            $message = "Gagal menambahkan video!";
            $message_type = "error";
        }

        $stmt->close();
    }
}

// =============================
// UPDATE VIDEO
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_video'])) {

    $id_video  = (int)$_POST['id_video'];
    $title     = trim($_POST['title']);
    $url       = trim($_POST['url']);
    $thumbnail = trim($_POST['thumbnail']);
    $category  = trim($_POST['category']);
    $duration  = trim($_POST['duration']);

    $stmt = $conn->prepare("
        UPDATE galeri SET title=?, url=?, thumbnail=?, category=?, duration=? WHERE id=?
    ");
    $stmt->bind_param("sssssi", $title, $url, $thumbnail, $category, $duration, $id_video);

    if ($stmt->execute()) {
        header("Location: ?page=admin_galeri&success=2");
        exit();
    } else {
        $message = "Gagal mengupdate video!";
        $message_type = "error";
    }

    $stmt->close();
}

// =============================
// AMBIL SEMUA VIDEO
// =============================
$videos = [];
$result = $conn->query("SELECT * FROM galeri ORDER BY date DESC, id DESC");

while ($row = $result->fetch_assoc()) {
    $videos[] = $row;
}

// =============================
// LOAD VIEW
// =============================
include __DIR__ . '/../Views/admin_galeri.php';
