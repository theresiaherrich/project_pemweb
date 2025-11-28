<?php 
session_start();
include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

// Ambil kategori dari tabel kategori
$kategori_query = "SELECT DISTINCT nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_query);
$kategori_list = [];
if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
  while ($row = mysqli_fetch_assoc($kategori_result)) {
    $kategori_list[] = $row['nama_kategori'];
  }
}

// Ambil data video dari tabel galeri
$videos = [];
$video_query = "SELECT 
                  id,
                  title,
                  url,
                  thumbnail,
                  duration,
                  category,
                  date
                FROM galeri
                ORDER BY date DESC";

$video_result = mysqli_query($conn, $video_query);

if ($video_result && mysqli_num_rows($video_result) > 0) {
  while ($row = mysqli_fetch_assoc($video_result)) {
    // Format tanggal jika perlu
    $tanggal_format = date('d M Y', strtotime($row['date']));
    
    $videos[] = [
      'id' => $row['id'],
      'title' => htmlspecialchars($row['title']),
      'url' => htmlspecialchars($row['url']),
      'thumbnail' => htmlspecialchars($row['thumbnail']),
      'duration' => htmlspecialchars($row['duration']),
      'category' => htmlspecialchars($row['category']),
      'date' => $tanggal_format
    ];
  }
}

$error = $error ?? "";
include __DIR__ . '/../Views/galeri.php';
?>