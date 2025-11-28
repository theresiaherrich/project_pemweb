<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

// ===============================
// 0. Proteksi Login
// ===============================
if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

// ===============================
// 1. Ambil Semua Kategori (Prepared)
// ===============================
$kategori_list = [];
$stmtKat = $conn->prepare("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
$stmtKat->execute();
$resultKat = $stmtKat->get_result();

while ($row = $resultKat->fetch_assoc()) {
    $kategori_list[] = $row;
}

// ===============================
// 2. Ambil Input & Sanitasi
// ===============================
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

// ===============================
// 3. Siapkan query dasar
// ===============================
$sql = "
    SELECT b.id_berita, b.judul, k.nama_kategori AS kategori,
           b.isi_singkat, b.gambar, b.waktu, b.views
    FROM berita b
    JOIN kategori k ON b.kategori_id = k.id
";

$params = [];
$types = "";

// Filter pencarian berdasarkan keyword
if ($keyword !== '') {
    $sql .= " WHERE b.judul LIKE ? OR b.isi_singkat LIKE ? OR k.nama_kategori LIKE ? ";
    $like = "%$keyword%";
    $params = [$like, $like, $like];
    $types = "sss";
} 
// Filter berdasarkan nama kategori (bukan ID)
elseif ($kategori !== '') {
    $sql .= " WHERE k.nama_kategori = ? ";
    $params = [$kategori];
    $types = "s";
}

$sql .= " ORDER BY b.waktu DESC ";

// ===============================
// 4. Eksekusi Query Aman (Prepared Statement)
// ===============================
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// ===============================
// 5. Simpan ke array
// ===============================
$semua_berita = [];
while ($row = $result->fetch_assoc()) {
    $semua_berita[] = $row;
}

// ===============================
// 6. Load View (header sudah include di dalam berita.php)
// ===============================
include __DIR__ . '/../Views/berita.php';
?>