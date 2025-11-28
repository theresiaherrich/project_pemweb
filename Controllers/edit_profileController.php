<?php 
session_start();
include __DIR__ . '/../model/koneksi.php';

$error = "";
$success = "";

if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

$user = $_SESSION['user']; 
$userEmail = $user['email'];
$currentPhoto = $user['photo'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $photo = $currentPhoto;

    $forbidden = '/[<>"\'&]/';

    if (preg_match($forbidden, $name)) {
        $error = "Nama mengandung karakter terlarang: < > \" ' &";
    } elseif (preg_match($forbidden, $address)) {
        $error = "Alamat mengandung karakter terlarang: < > \" ' &";
    } elseif (!preg_match('/^[0-9]+$/', $phone)) {
        $error = "Nomor HP hanya boleh angka!";
    }

    if ($error === "" && !empty($_FILES['photo']['name'])) {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 2 * 1024 * 1024;

        $fileType = $_FILES['photo']['type'];
        $fileSize = $_FILES['photo']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            $error = "Format foto hanya boleh JPG atau PNG!";
        } elseif ($fileSize > $maxSize) {
            $error = "Ukuran foto maksimal 2 MB!";
        }
    }

    if ($error === "") {

        if (isset($_POST['delete_photo']) && $photo != "default.png") {
            if (file_exists("uploads/" . $photo)) {
                unlink("uploads/" . $photo);
            }
            $photo = "default.png";
        }

        if (!empty($_FILES['photo']['name'])) {

            $targetDir = "uploads/";
            $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {

                if ($photo != "default.png" && file_exists("uploads/" . $photo)) {
                    unlink("uploads/" . $photo);
                }

                $photo = $fileName;

            } else {
                $error = "Gagal mengupload foto!";
            }
        }

        if ($error === "") {

            $stmt = $conn->prepare("UPDATE users SET name=?, address=?, phone=?, photo=? WHERE email=?");
            $stmt->bind_param("sssss", $name, $address, $phone, $photo, $userEmail);
            $stmt->execute();
            $stmt->close();

            $_SESSION['user']['name'] = htmlspecialchars($name);
            $_SESSION['user']['address'] = htmlspecialchars($address);
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['photo'] = $photo;

            $success = "Profil berhasil diperbarui!";
        }
    }
}

include __DIR__ . '/../Views/edit_profile.php';
