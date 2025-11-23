<?php
session_start();

include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

$kategori_query = "SELECT DISTINCT kategori FROM berita ORDER BY kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_query);
$kategori_list = [];
if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
  while ($row = mysqli_fetch_assoc($kategori_result)) {
    $kategori_list[] = $row['kategori'];
  }
}

$user_id = $_SESSION['user']['id'];
$edit_mode = false;
$edit_data = null;


if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $delete_query = "DELETE FROM contact WHERE id = $id AND user_id = $user_id";
  if (mysqli_query($conn, $delete_query)) {
    $success = "Pesan berhasil dihapus!";
  } else {
    $error = "Gagal menghapus pesan!";
  }
  header("Location: kontak.php");
  exit();
}

if (isset($_GET['edit'])) {
  $id = (int) $_GET['edit'];
  $edit_query = "SELECT * FROM contact WHERE id = $id AND user_id = $user_id";
  $edit_result = mysqli_query($conn, $edit_query);
  if ($edit_result && mysqli_num_rows($edit_result) > 0) {
    $edit_data = mysqli_fetch_assoc($edit_result);
    $edit_mode = true;
  }
}

// ========== CREATE / UPDATE PESAN ==========
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = mysqli_real_escape_string($conn, trim($_POST['name']));
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));
  $subjek = mysqli_real_escape_string($conn, trim($_POST['subject']));
  $pesan = mysqli_real_escape_string($conn, trim($_POST['message']));

  // Validasi
  if (strlen($nama) < 3) {
    $error = "Nama minimal 3 karakter";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Format email tidak valid";
  } elseif (strlen($subjek) < 5) {
    $error = "Subjek minimal 5 karakter";
  } elseif (strlen($pesan) < 10) {
    $error = "Pesan minimal 10 karakter";
  } else {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
      // UPDATE
      $id = (int) $_POST['id'];
      $query = "UPDATE contact SET nama='$nama', email='$email', subjek='$subjek', pesan='$pesan' 
                      WHERE id=$id AND user_id=$user_id";
      if (mysqli_query($conn, $query)) {
        $success = "Pesan berhasil diupdate!";
        header("Location: kontak.php");
        exit();
      } else {
        $error = "Gagal mengupdate pesan!";
      }
    } else {
      // CREATE
      $query = "INSERT INTO contact (user_id, nama, email, subjek, pesan, tanggal, status) 
                      VALUES ($user_id, '$nama', '$email', '$subjek', '$pesan', NOW(), 'baru')";
      if (mysqli_query($conn, $query)) {
        $success = "Pesan berhasil dikirim!";
      } else {
        $error = "Gagal mengirim pesan!";
      }
    }
  }
}

// ========== READ (Ambil semua pesan user) ==========
$messages_query = "SELECT * FROM contact WHERE user_id = $user_id ORDER BY tanggal DESC";
$messages_result = mysqli_query($conn, $messages_query);
$messages = [];
if ($messages_result && mysqli_num_rows($messages_result) > 0) {
  while ($row = mysqli_fetch_assoc($messages_result)) {
    $messages[] = $row;
  }
}
$error = $error ?? "";
include __DIR__ . '/../Views/kontak.php';