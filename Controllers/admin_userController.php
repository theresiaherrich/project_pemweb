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
// VARIABEL PESAN
// =============================
$message = '';
$message_type = 'info';

// =============================
// HANDLE DELETE USER
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {

    $id_user = (int)$_POST['id_user'];

    // Ambil foto lama untuk dihapus
    $stmtFoto = $conn->prepare("SELECT photo FROM users WHERE id = ?");
    $stmtFoto->bind_param("i", $id_user);
    $stmtFoto->execute();
    $fotoData = $stmtFoto->get_result()->fetch_assoc();
    $oldPhoto = $fotoData['photo'] ?? '';
    $stmtFoto->close();

    // Hapus user dari database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id_user);

    if ($stmt->execute()) {

        // Hapus foto jika ada
        if (!empty($oldPhoto) && file_exists(__DIR__ . '/../' . $oldPhoto)) {
            unlink(__DIR__ . '/../' . $oldPhoto);
        }

        header("Location: ?page=admin_user&success=3");
        exit();
    } else {
        $message = "Gagal menghapus user!";
        $message_type = "error";
    }
}

// =============================
// AMBIL PESAN DARI REDIRECT
// =============================
if (isset($_GET['success'])) {
    $success = $_GET['success'];

    if ($success == 1) {
        $message = "User berhasil ditambahkan!";
        $message_type = "success";
    } elseif ($success == 2) {
        $message = "User berhasil diupdate!";
        $message_type = "success";
    } elseif ($success == 3) {
        $message = "User berhasil dihapus!";
        $message_type = "success";
    }
}

// =============================
// FUNCTION UPLOAD FOTO USER
// =============================
function uploadUserPhoto($file)
{
    $uploadDir = "assets/user/";
    $allowedExt = ['jpg', 'jpeg', 'png'];

    if (!isset($file['name']) || $file['error'] !== 0) {
        return false;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        return false;
    }

    $newName = "user_" . time() . "_" . rand(1000, 9999) . "." . $ext;
    $uploadPath = $uploadDir . $newName;

    if (!is_dir(__DIR__ . '/../' . $uploadDir)) {
        mkdir(__DIR__ . '/../' . $uploadDir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], __DIR__ . '/../' . $uploadPath)) {
        return $uploadPath;
    }

    return false;
}

// =============================
// TAMBAH USER
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {

    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $role     = trim($_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Upload foto
    $photoPath = null;
    if (!empty($_FILES['photo']['name'])) {
        $photoPath = uploadUserPhoto($_FILES['photo']);
    }

    $stmt = $conn->prepare("
        INSERT INTO users (name, username, email, password, phone, role, photo)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssss", $name, $username, $email, $password, $phone, $role, $photoPath);

    if ($stmt->execute()) {
        header("Location: ?page=admin_user&success=1");
        exit();
    } else {
        $message = "Gagal menambahkan user!";
        $message_type = "error";
    }

    $stmt->close();
}

// =============================
// UPDATE USER
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {

    $id_user  = (int)$_POST['id_user'];
    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $role     = trim($_POST['role']);
    $oldPhoto = trim($_POST['old_photo']);

    $newPhoto = $oldPhoto;

    // Jika user upload foto baru
    if (!empty($_FILES['photo']['name'])) {
        $up = uploadUserPhoto($_FILES['photo']);
        if ($up !== false) {
            $newPhoto = $up;

            // Hapus foto lama
            if (!empty($oldPhoto) && file_exists(__DIR__ . '/../' . $oldPhoto)) {
                unlink(__DIR__ . '/../' . $oldPhoto);
            }
        }
    }

    // Jika password diisi → update
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE users SET name=?, username=?, email=?, password=?, phone=?, photo=?, role=?
            WHERE id=?
        ");
        $stmt->bind_param("sssssssi", $name, $username, $email, $password, $phone, $newPhoto, $role, $id_user);

    } else {
        // Tidak update password
        $stmt = $conn->prepare("
            UPDATE users SET name=?, username=?, email=?, phone=?, photo=?, role=?
            WHERE id=?
        ");
        $stmt->bind_param("ssssssi", $name, $username, $email, $phone, $newPhoto, $role, $id_user);
    }

    if ($stmt->execute()) {
        header("Location: ?page=admin_user&success=2");
        exit();
    } else {
        $message = "Gagal mengupdate user!";
        $message_type = "error";
    }

    $stmt->close();
}


$query = "SELECT * FROM users";

$query .= " ORDER BY id DESC";

$result = $conn->query($query);

$semua_user = [];
while ($row = $result->fetch_assoc()) {
    $semua_user[] = $row;
}

// =============================
// LOAD VIEW
// =============================
include __DIR__ . '/../Views/admin_user.php';
