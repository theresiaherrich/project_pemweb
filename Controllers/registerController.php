<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Sanitasi input agar lebih aman
  $name = htmlspecialchars(trim($_POST['name']));
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = trim($_POST['password']);
  $confirm = trim($_POST['confirm']);
  $address = htmlspecialchars(trim($_POST['address']));
  $phone = htmlspecialchars(trim($_POST['phone']));

  // Validasi dasar
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Format email tidak valid!'); window.history.back();</script>";
    exit;
  }
  if (strlen($password) < 6) {
    echo "<script>alert('Password minimal 6 karakter!'); window.history.back();</script>";
    exit;
  }
  if ($password !== $confirm) {
    echo "<script>alert('Password tidak sama!'); window.history.back();</script>";
    exit;
  }

  // Cek email sudah digunakan atau belum (pakai prepared statement)
  $checkQuery = "SELECT id FROM users WHERE email = ?";
  $checkStmt = mysqli_prepare($conn, $checkQuery);
  mysqli_stmt_bind_param($checkStmt, "s", $email);
  mysqli_stmt_execute($checkStmt);
  mysqli_stmt_store_result($checkStmt);

  if (mysqli_stmt_num_rows($checkStmt) > 0) {
    echo "<script>alert('Email sudah terdaftar! Silakan login.'); window.location='login.php';</script>";
    mysqli_stmt_close($checkStmt);
    exit;
  }
  mysqli_stmt_close($checkStmt);

  // Enkripsi password
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

  // Simpan data user
  $query = "INSERT INTO users (name, email, password, address, phone, photo) VALUES (?, ?, ?, ?, ?, 'default.png')";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $passwordHash, $address, $phone);

  if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
    exit;
  } else {
    echo "<script>alert('Terjadi kesalahan saat registrasi.'); window.history.back();</script>";
  }

  mysqli_stmt_close($stmt);
}

$error = $error ?? "";
include __DIR__ . '/../Views/register.php';