
<?php
// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Jika role bukan admin → larang akses
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ?page=home");
    exit();
}

// Hubungkan ke Database
include __DIR__ . '/../model/koneksi.php';

// ==========================================
// 1. CEK KEAMANAN (Login & Role)
// ==========================================
// Pastikan user sudah login. 
// Jika kamu punya kolom role, bisa tambahkan: && $_SESSION['user']['role'] == 'admin'
if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

// ==========================================
// 2. HELPER: Ambil Data Kategori (Untuk Dropdown)
// ==========================================
$kategori_list = [];
$stmtKat = $conn->prepare("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
$stmtKat->execute();
$resultKat = $stmtKat->get_result();
while ($row = $resultKat->fetch_assoc()) {
    $kategori_list[] = $row;
}

// ==========================================
// 3. INIT VARIABEL VIEW
// ==========================================
$message = '';
$message_type = '';
$edit_mode = false;
$edit_data = null;
$action = isset($_GET['action']) ? $_GET['action'] : '';

// ==========================================
// 4. LOGIC: AJAX REQUEST (Untuk Edit Data)
// ==========================================
// Ini dipanggil oleh Javascript fetch() saat tombol edit diklik
if (isset($_GET['ajax']) && $_GET['ajax'] === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmtAjax = $conn->prepare("SELECT * FROM loker WHERE id = ?");
    $stmtAjax->bind_param("i", $id);
    $stmtAjax->execute();
    $resAjax = $stmtAjax->get_result();
    
    if ($resAjax && $resAjax->num_rows > 0) {
        $data = $resAjax->fetch_assoc();
        // Bersihkan path gambar biar aman
        $data['gambar'] = ltrim($data['gambar'], '/');
        
        // Kirim response JSON ke Javascript
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    } else {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Data Loker tidak ditemukan']);
        exit();
    }
}

// ==========================================
// 5. LOGIC: HAPUS DATA (DELETE)
// ==========================================
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Ambil info gambar lama dulu (buat dihapus fisiknya)
    $stmtGetImg = $conn->prepare("SELECT gambar FROM loker WHERE id = ?");
    $stmtGetImg->bind_param("i", $id);
    $stmtGetImg->execute();
    $resultImg = $stmtGetImg->get_result();
    
    if ($rowImg = $resultImg->fetch_assoc()) {
        $old_image = $rowImg['gambar'];
        
        // Hapus record dari database
        $stmtDel = $conn->prepare("DELETE FROM loker WHERE id = ?");
        $stmtDel->bind_param("i", $id);
        
        if ($stmtDel->execute()) {
            // Hapus file fisik jika ada di folder lokal (bukan link online)
            if (!empty($old_image) && strpos($old_image, 'http') === false) {
                // Bersihkan path dari spasi atau slash ganda
                $cleanPath = ltrim($old_image, '/');
                $pathToOld = __DIR__ . '/../' . $cleanPath;
                
                if (file_exists($pathToOld)) {
                    @unlink($pathToOld); // Hapus file
                }
            }
            $message = "Loker berhasil dihapus!";
            $message_type = "success";
        } else {
            $message = "Gagal menghapus database: " . $conn->error;
            $message_type = "error";
        }
    }
    
    // Redirect supaya URL bersih
    header("Location: ?page=admin_loker&msg=" . urlencode($message) . "&type=" . $message_type);
    exit();
}

// ==========================================
// 6. LOGIC: SIMPAN DATA (CREATE / UPDATE)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_loker = isset($_POST['id_loker']) ? intval($_POST['id_loker']) : 0;
    
    // Sanitasi Input
    $judul       = trim($_POST['judul']);
    $perusahaan  = trim($_POST['perusahaan']);
    $lokasi      = trim($_POST['lokasi']);
    $deskripsi   = trim($_POST['deskripsi']);
    $kategori_id = intval($_POST['kategori_id']);
    $tanggal     = $_POST['tanggal']; // Format YYYY-MM-DD
    
    // Proses Upload Gambar
    $upload_path = '';
    $old_image = isset($_POST['old_image']) ? $_POST['old_image'] : '';
    
    // Cek ada file baru diupload gak?
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK && !empty($_FILES['gambar']['name'])) {
        
        $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        $file_type = $_FILES['gambar']['type'];
        $file_size = $_FILES['gambar']['size'];
        
        // Max 5MB & Tipe Valid
        if (in_array($file_type, $allowed) && $file_size < 5000000) { 
            
            // Siapkan folder: uploads/loker/
            $upload_dir = __DIR__ . '/../uploads/loker/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate nama file unik
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $new_name = 'loker_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $dest_path = $upload_dir . $new_name;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $dest_path)) {
                // Simpan path relatif untuk database
                $upload_path = 'uploads/loker/' . $new_name;

                // Hapus gambar lama kalau ada (dan kalau lokal)
                if (!empty($old_image) && strpos($old_image, 'http') === false) {
                    $pathOldFile = __DIR__ . '/../' . ltrim($old_image, '/');
                    if (file_exists($pathOldFile)) {
                        @unlink($pathOldFile);
                    }
                }
            } else {
                $message = "Gagal memindahkan file upload.";
                $message_type = "error";
            }
        } else {
            $message = "Format file tidak didukung atau terlalu besar (Max 5MB).";
            $message_type = "error";
        }
    } else {
        // Kalau gak upload baru, pakai gambar lama
        $upload_path = $old_image; 
    }
    
    // EKSEKUSI QUERY
    if ($message_type !== "error") { // Lanjut kalau gak ada error upload
        
        if ($id_loker > 0) {
            // --- UPDATE DATA ---
            $stmtUpd = $conn->prepare("
                UPDATE loker 
                SET judul=?, perusahaan=?, lokasi=?, deskripsi=?, kategori_id=?, gambar=?, tanggal=?
                WHERE id=?
            ");
            $stmtUpd->bind_param("ssssissi", $judul, $perusahaan, $lokasi, $deskripsi, $kategori_id, $upload_path, $tanggal, $id_loker);
            
            if ($stmtUpd->execute()) {
                $message = "Data Loker berhasil diperbarui!";
                $message_type = "success";
            } else {
                $message = "Gagal Update: " . $stmtUpd->error;
                $message_type = "error";
            }
            
        } else {
            // --- CREATE DATA BARU ---
            // Jika gambar kosong, kasih default
            if (empty($upload_path)) {
                $upload_path = 'Views/img/default-job.jpg';
            }
            
            $stmtIns = $conn->prepare("
                INSERT INTO loker (judul, perusahaan, lokasi, deskripsi, kategori_id, gambar, tanggal, views)
                VALUES (?, ?, ?, ?, ?, ?, ?, 0)
            ");
            $stmtIns->bind_param("ssssiss", $judul, $perusahaan, $lokasi, $deskripsi, $kategori_id, $upload_path, $tanggal);
            
            if ($stmtIns->execute()) {
                $message = "Lowongan baru berhasil ditambahkan!";
                $message_type = "success";
            } else {
                $message = "Gagal Insert: " . $stmtIns->error;
                $message_type = "error";
            }
        }
    }
    
    // Redirect agar tidak resubmit saat refresh
    header("Location: ?page=admin_loker&msg=" . urlencode($message) . "&type=" . $message_type);
    exit();
}

// ==========================================
// 7. LOGIC: AMBIL DATA UNTUK TABEL (READ)
// ==========================================
// Ambil pesan notifikasi dari URL (jika ada)
if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
    $message_type = isset($_GET['type']) ? $_GET['type'] : 'info';
}

// Filter Pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_kategori = isset($_GET['filter_kategori']) ? intval($_GET['filter_kategori']) : 0;

// Query Dasar
$sql = "
    SELECT l.*, k.nama_kategori AS kategori
    FROM loker l
    LEFT JOIN kategori k ON l.kategori_id = k.id
    WHERE 1=1
";

// Tambah Filter SQL
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND (l.judul LIKE ? OR l.perusahaan LIKE ? OR l.lokasi LIKE ?)";
    $like = "%$search%";
    $params[] = $like; $params[] = $like; $params[] = $like;
    $types .= "sss";
}

if ($filter_kategori > 0) {
    $sql .= " AND l.kategori_id = ?";
    $params[] = $filter_kategori;
    $types .= "i";
}

$sql .= " ORDER BY l.id DESC"; // Urutan terbaru di atas

$stmtList = $conn->prepare($sql);
if (!empty($params)) {
    $stmtList->bind_param($types, ...$params);
}
$stmtList->execute();
$resultList = $stmtList->get_result();

$semua_loker = [];
while ($row = $resultList->fetch_assoc()) {
    // Bersihkan path gambar untuk view
    $row['gambar'] = ltrim($row['gambar'], '/');
    $semua_loker[] = $row;
}

// ==========================================
// 8. LOAD TAMPILAN (VIEW)
// ==========================================
// Panggil file HTML-nya di folder Views
include __DIR__ . '/../Views/admin_loker.php';
?>