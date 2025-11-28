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

$message = '';
$message_type = '';

$action = isset($_GET['action']) ? $_GET['action'] : '';

// ===============================
// 1. AJAX — Ambil data kontak untuk edit
// ===============================
if (isset($_GET['ajax']) && $_GET['ajax'] === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM contact WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        header("Content-Type: application/json");
        echo json_encode($res->fetch_assoc());
    } else {
        echo json_encode(["error" => "Data tidak ditemukan"]);
    }
    exit();
}

// ===============================
// 2. DELETE
// ===============================
if ($action === "delete" && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM contact WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Pesan kontak berhasil dihapus!";
        $message_type = "success";
    } else {
        $message = "Gagal menghapus data!";
        $message_type = "error";
    }

    header("Location: ?page=admin_kontak&msg=$message&type=$message_type");
    exit();
}

// ===============================
// 3. UPDATE STATUS
// ===============================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE contact SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        $message = "Status berhasil diperbarui!";
        $message_type = "success";
    } else {
        $message = "Gagal memperbarui status!";
        $message_type = "error";
    }

    header("Location: ?page=admin_kontak&msg=$message&type=$message_type");
    exit();
}

// ===============================
// 4. AMBIL SEMUA DATA KONTAK
// ===============================
$sql = "
    SELECT c.*, u.name AS userName
    FROM contact c
    LEFT JOIN users u ON c.user_id = u.id
    ORDER BY c.tanggal DESC
";

$result = $conn->query($sql);

$data_contact = [];
while ($row = $result->fetch_assoc()) {
    $data_contact[] = $row;
}

// Ambil pesan dari URL
if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
    $message_type = $_GET['type'] ?? 'info';
}

// ===============================
// 5. Tampilkan View
// ===============================
include __DIR__ . '/../Views/admin_kontak.php';
?>