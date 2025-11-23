<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

$user = $_SESSION['user']; 
$userEmail = $user['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $photo = $user['photo'];

    // Hapus foto lama
    if (isset($_POST['delete_photo']) && $photo != "default.png") {
        if (file_exists("uploads/" . $photo)) {
            unlink("uploads/" . $photo);
        }
        $photo = "default.png";
    }

    // Upload foto baru
    if (!empty($_FILES['photo']['name'])) {

        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetFile = $targetDir . $fileName;

        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile);

        if ($photo != "default.png" && file_exists("uploads/" . $photo)) {
            unlink("uploads/" . $photo);
        }

        $photo = $fileName;
    }

    // UPDATE KE DATABASE
    $stmt = $conn->prepare("UPDATE users SET name=?, address=?, phone=?, photo=? WHERE email=?");
    $stmt->bind_param("sssss", $name, $address, $phone, $photo, $userEmail);
    $stmt->execute();

    // Update session
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['address'] = $address;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['photo'] = $photo;

    echo "<script>alert('Profil berhasil diperbarui!'); window.location='profile.php';</script>";
    exit();
}

include __DIR__ . '/../Views/edit_profile.php';
?>
