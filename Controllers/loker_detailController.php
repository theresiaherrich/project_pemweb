
<?php
// Cek apakah session sudah dimulai, jika belum, mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hubungkan ke database
// Pastikan path ini mengarah ke file koneksi.php kamu
include __DIR__ . '/../Model/koneksi.php';

// --- PERBAIKAN PENTING ---
// Kita buat jembatan: Jika di koneksi.php namanya $conn, kita ubah jadi $koneksi
// Ini mencegah error "Undefined variable $koneksi"
if (isset($conn) && !isset($koneksi)) {
    $koneksi = $conn;
}

// Cek darurat: Jika database masih gagal terhubung
if (!isset($koneksi)) {
    die("Error Fatal: Gagal terhubung ke database. Cek file Model/koneksi.php kamu.");
}
// -------------------------

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Siapkan Query
    $query = "SELECT loker.*, kategori.nama_kategori
              FROM loker
              LEFT JOIN kategori ON loker.kategori_id = kategori.id
              WHERE loker.id = ?";
    
    // Eksekusi Query dengan aman
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah data ditemukan
    if ($result && $result->num_rows > 0) {
        $loker = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data lowongan tidak ditemukan!'); window.location='index.php?page=loker';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location='index.php?page=loker';</script>";
    exit();
}

// Panggil Tampilan (View)
// Pastikan nama file View di folder Views adalah 'loker_detail.php'
include __DIR__ . '/../Views/loker_detail.php';
?>