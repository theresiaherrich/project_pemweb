<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

$user = $_SESSION['user'];

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: ?page=login");
  exit;
}

$photoPath = "uploads/" . ($user['photo'] ?: 'default.png');
if (!file_exists($photoPath)) {
  $photoPath = "uploads/default.png";
}
$error = $error ?? "";
include __DIR__ . '/../Views/profile.php';