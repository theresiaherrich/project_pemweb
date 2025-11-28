<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

// ===============================
// 0. Proteksi Login & Role Admin
// ===============================
if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}
// Jika role bukan admin → larang akses
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ?page=home");
    exit();
}

// ===============================
// 1. Ambil Semua Kategori
// ===============================
$kategori_list = [];
$stmtKat = $conn->prepare("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
$stmtKat->execute();
$resultKat = $stmtKat->get_result();
while ($row = $resultKat->fetch_assoc()) {
    $kategori_list[] = $row;
}

// ===============================
// 2. Variabel untuk Pesan & Mode
// ===============================
$message = '';
$message_type = '';
$edit_mode = false;
$edit_data = null;

// ===============================
// 3. Handle Actions & AJAX
// ===============================
$action = isset($_GET['action']) ? $_GET['action'] : '';

// --- AJAX: ambil data berita untuk edit (JSON) ---
if (isset($_GET['ajax']) && $_GET['ajax'] === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmtAjax = $conn->prepare("SELECT * FROM berita WHERE id_berita = ?");
    $stmtAjax->bind_param("i", $id);
    $stmtAjax->execute();
    $resAjax = $stmtAjax->get_result();
    if ($resAjax && $resAjax->num_rows > 0) {
        $data = $resAjax->fetch_assoc();
        // Pastikan field gambar relatif tanpa leading slash
        $data['gambar'] = ltrim($data['gambar'], '/');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    } else {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Berita tidak ditemukan']);
        exit();
    }
}

// --- DELETE ---
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Ambil nama file gambar dulu
    $stmtGetImg = $conn->prepare("SELECT gambar FROM berita WHERE id_berita = ?");
    $stmtGetImg->bind_param("i", $id);
    $stmtGetImg->execute();
    $resultImg = $stmtGetImg->get_result();
    
    if ($rowImg = $resultImg->fetch_assoc()) {
        $old_image = $rowImg['gambar'];
        
        // Hapus dari database
        $stmtDel = $conn->prepare("DELETE FROM berita WHERE id_berita = ?");
        $stmtDel->bind_param("i", $id);
        
        if ($stmtDel->execute()) {
            // Hapus file gambar jika ada (safe)
            if (!empty($old_image)) {
                $pathToOld = __DIR__ . '/../' . ltrim($old_image, '/');
                if (file_exists($pathToOld)) {
                    @unlink($pathToOld);
                }
            }
            $message = "Berita berhasil dihapus!";
            $message_type = "success";
        } else {
            $message = "Gagal menghapus berita: " . $conn->error;
            $message_type = "error";
        }
    }
    
    header("Location: ?page=admin_berita&msg=" . urlencode($message) . "&type=" . $message_type);
    exit();
}

// --- EDIT MODE (tidak auto-open; dipakai untuk fallback) ---
if ($action === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmtEdit = $conn->prepare("SELECT * FROM berita WHERE id_berita = ?");
    $stmtEdit->bind_param("i", $id);
    $stmtEdit->execute();
    $resultEdit = $stmtEdit->get_result();
    
    if ($resultEdit->num_rows > 0) {
        $edit_data = $resultEdit->fetch_assoc();
        $edit_mode = true;
    }
}

// --- SUBMIT (CREATE / UPDATE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_berita = isset($_POST['id_berita']) ? intval($_POST['id_berita']) : 0;
    $judul = trim($_POST['judul']);
    $kategori_id = intval($_POST['kategori_id']);
    $isi_singkat = trim($_POST['isi_singkat']);
    $isi_lengkap = trim($_POST['isi_lengkap']);
    
    // Handle Upload Gambar
    $upload_path = '';
    $old_image = isset($_POST['old_image']) ? $_POST['old_image'] : '';
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK && !empty($_FILES['gambar']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $file_type = $_FILES['gambar']['type'];
        $file_size = $_FILES['gambar']['size'];
        
        if (in_array($file_type, $allowed_types) && $file_size < 5000000) { // Max 5MB
            $upload_dir = __DIR__ . '/../uploads/berita/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $new_filename = 'berita_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
            $upload_full_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_full_path)) {
                // Hapus gambar lama jika ada (safe)
                if (!empty($old_image)) {
                    $pathOld = __DIR__ . '/../' . ltrim($old_image, '/');
                    if (file_exists($pathOld)) {
                        @unlink($pathOld);
                    }
                }
                $upload_path = 'uploads/berita/' . $new_filename;
            } else {
                $message = "Gagal mengupload gambar!";
                $message_type = "error";
            }
        } else {
            $message = "File tidak valid atau terlalu besar!";
            $message_type = "error";
        }
    } else {
        $upload_path = $old_image; // Gunakan gambar lama (relatif)
    }
    
    // UPDATE
    if ($id_berita > 0) {
        $stmtUpdate = $conn->prepare("
            UPDATE berita 
            SET judul = ?, kategori_id = ?, isi_singkat = ?, isi_lengkap = ?, gambar = ?
            WHERE id_berita = ?
        ");
        $stmtUpdate->bind_param("sisssi", $judul, $kategori_id, $isi_singkat, $isi_lengkap, $upload_path, $id_berita);
        
        if ($stmtUpdate->execute()) {
            $message = "Berita berhasil diupdate!";
            $message_type = "success";
        } else {
            $message = "Gagal mengupdate berita: " . $stmtUpdate->error;
            $message_type = "error";
        }
    } 
    // CREATE
    else {
        if (empty($upload_path)) {
            $upload_path = 'Views/css/default-news.jpg'; // Gambar default relative
        }
        
        $stmtInsert = $conn->prepare("
            INSERT INTO berita (judul, kategori_id, isi_singkat, isi_lengkap, gambar, waktu, views)
            VALUES (?, ?, ?, ?, ?, NOW(), 0)
        ");
        $stmtInsert->bind_param("sisss", $judul, $kategori_id, $isi_singkat, $isi_lengkap, $upload_path);
        
        if ($stmtInsert->execute()) {
            $message = "Berita berhasil ditambahkan!";
            $message_type = "success";
        } else {
            $message = "Gagal menambahkan berita: " . $stmtInsert->error;
            $message_type = "error";
        }
    }
    
    header("Location: ?page=admin_berita&msg=" . urlencode($message) . "&type=" . $message_type);
    exit();
}

$sql = "
    SELECT b.id_berita, b.judul, b.isi_singkat, b.isi_lengkap, b.kategori_id, k.nama_kategori AS kategori,
           b.waktu, b.views, b.gambar
    FROM berita b
    JOIN kategori k ON b.kategori_id = k.id
    ORDER BY b.waktu DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();


$semua_berita = [];
while ($row = $result->fetch_assoc()) {
    // ensure gambar path has no leading slash
    $row['gambar'] = ltrim($row['gambar'], '/');
    $semua_berita[] = $row;
}

// Ambil pesan dari URL
if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
    $message_type = isset($_GET['type']) ? $_GET['type'] : 'info';
}

// ===============================
// 5. Load View
// ===============================
include __DIR__ . '/../Views/admin_berita.php';
?>
