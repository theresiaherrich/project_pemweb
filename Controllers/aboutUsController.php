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

if (!isset($koneksi) && isset($conn)) { 
  $koneksi = $conn; 
}

if (!($koneksi instanceof mysqli)) {
  http_response_code(500);
  exit('Kesalahan DB: koneksi tidak tersedia.');
}

function e(?string $s): string { 
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); 
}

$join_success = false;
$errors = [];
$new_member = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_membership'])) {
  $name  = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $plan  = ($_POST['plan'] ?? 'bulanan') === 'tahunan' ? 'tahunan' : 'bulanan';
  $agree = isset($_POST['agree']);
  
  if ($name === '') {
    $errors[] = 'Nama wajib diisi.';
  }
  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email tidak valid.';
  }
  if ($phone === '') {
    $errors[] = 'No. HP wajib diisi.';
  }
  if (!$agree) {
    $errors[] = 'Anda harus menyetujui S&K.';
  }
  
  if (empty($errors)) {
    $stmt = mysqli_prepare($koneksi, "INSERT INTO premium_membership (name, email, phone, plan) VALUES (?,?,?,?)");
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $phone, $plan);
      if (mysqli_stmt_execute($stmt)) {
        $join_success = true;
        $new_member = ['name'=>$name, 'email'=>$email, 'plan'=>$plan];
      } else {
        $errors[] = 'Gagal menyimpan data: '. e(mysqli_error($koneksi));
      }
      mysqli_stmt_close($stmt);
    } else {
      $errors[] = 'Gagal menyiapkan query.';
    }
  }
}

$data = null;
$query = "SELECT hero_title, hero_subtitle, hero_description, hero_image, 
          about_heading, about_subheading, about_paragraph1, about_paragraph2 
          FROM about_us LIMIT 1";
$result = mysqli_query($koneksi, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $data = mysqli_fetch_assoc($result);
}

if (!$data) {
  $data = [
    'hero_title'        => 'Tentang Kami',
    'hero_subtitle'     => 'Global Time - Solusi Waktu Global Anda',
    'hero_description'  => 'Global Time adalah platform yang membantu Anda melihat waktu dari seluruh dunia dengan mudah dan cepat.',
    'hero_image'        => 'default-hero.jpg',
    'about_heading'     => 'Misi Kami',
    'about_subheading'  => 'Memberikan kemudahan akses informasi waktu dunia',
    'about_paragraph1'  => 'Kami berdedikasi menghadirkan layanan yang membantu pengguna mengetahui perbedaan waktu antar negara secara akurat dan real-time.',
    'about_paragraph2'  => 'Dengan desain yang sederhana dan fitur yang bermanfaat, kami ingin jadi teman terbaik untuk kebutuhan waktu global Anda.',
  ];
}
$error = $error ?? "";
include __DIR__ . '/../Views/aboutUs.php';
