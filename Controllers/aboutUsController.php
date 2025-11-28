<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

// Pastikan session sudah ada
if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit(); // Pastikan exit() dipanggil setelah header agar tidak terjadi eksekusi lebih lanjut
}

// Query untuk mengambil kategori unik dari tabel berita dan kategori
$kategori_query = "SELECT DISTINCT k.nama_kategori
                   FROM berita AS b
                   JOIN kategori AS k ON b.kategori_id = k.id
                   ORDER BY k.nama_kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_query);
$kategori_list = [];

if ($kategori_result && mysqli_num_rows($kategori_result) > 0) {
    while ($row = mysqli_fetch_assoc($kategori_result)) {
        $kategori_list[] = $row['nama_kategori'];
    }
}

// Pastikan koneksi database tersedia
if (!isset($koneksi) && isset($conn)) {
    $koneksi = $conn;
}

if (!($koneksi instanceof mysqli)) {
    http_response_code(500);
    exit('Kesalahan DB: koneksi tidak tersedia.');
}

// Fungsi untuk menghindari XSS
function e(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

// Mengambil data dari tabel about_us
$data = null;
$query = "SELECT hero_title, hero_subtitle, hero_description, hero_image, 
          about_heading, about_subheading, about_paragraph1, about_paragraph2 
          FROM about_us LIMIT 1";
$result = mysqli_query($koneksi, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
}

// Jika data tidak ada, set dengan nilai default
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

// Jika ada error, set variabel error
$error = $error ?? "";
include __DIR__ . '/../Views/aboutUs.php';
?>
