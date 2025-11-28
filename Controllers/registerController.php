<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

if (isset($_SESSION['user'])) {
    header("Location: ?page=home"); 
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = htmlspecialchars(trim($_POST['name']));
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = trim($_POST['password']);
  $confirm = trim($_POST['confirm']);
  $address = htmlspecialchars(trim($_POST['address']));
  $phone = htmlspecialchars(trim($_POST['phone']));

  // Validasi dasar
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Format email tidak valid!";
  } elseif (strlen($password) < 6) {
    $error = "Password minimal 6 karakter!";
  } elseif ($password !== $confirm) {
    $error = "Password tidak sama!";
  }

  if ($error === "") {
    // Cek email
    $checkQuery = "SELECT id FROM users WHERE email = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
      $error = "Email sudah terdaftar! Silakan login.";
    }
    mysqli_stmt_close($checkStmt);
  }

  if ($error === "") {
    // Simpan user
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (name, email, password, address, phone, photo) VALUES (?, ?, ?, ?, ?, 'default.png')";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $passwordHash, $address, $phone);

    if (mysqli_stmt_execute($stmt)) {
      $success = "Registrasi berhasil! Silakan login.";
    } else {
      $error = "Terjadi kesalahan saat registrasi.";
    }
    mysqli_stmt_close($stmt);
  }
}

include __DIR__ . '/../Views/register.php';
