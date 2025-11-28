<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ?page=login");
  exit();
}

$user_id = $_SESSION['user']['id'];
$edit_mode = false;
$edit_data = null;
$error = null;
$success = null;

// ============================================================================
// ✅ DELETE PESAN (user bisa delete kapan saja)
// ============================================================================
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  
  // Check ownership
  $check_query = "SELECT id FROM contact WHERE id = ? AND user_id = ?";
  $check_stmt = mysqli_prepare($conn, $check_query);
  mysqli_stmt_bind_param($check_stmt, "ii", $id, $user_id);
  mysqli_stmt_execute($check_stmt);
  
  if (mysqli_stmt_get_result($check_stmt)->num_rows > 0) {
    $delete_query = "DELETE FROM contact WHERE id = ? AND user_id = ?";
    $del_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($del_stmt, "ii", $id, $user_id);
    
    if (mysqli_stmt_execute($del_stmt)) {
      $_SESSION['success'] = "Pesan berhasil dihapus!";
    } else {
      $_SESSION['error'] = "Gagal menghapus pesan!";
    }
    mysqli_stmt_close($del_stmt);
  }
  
  header("Location: ?page=kontak");
  exit();
}

// ============================================================================
// ✅ EDIT MODE (hanya jika status = 'baru')
// ============================================================================
if (isset($_GET['edit'])) {
  $id = intval($_GET['edit']);
  
  $query = "SELECT * FROM contact WHERE id = ? AND user_id = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
  if ($result && mysqli_num_rows($result) > 0) {
    $edit_data = mysqli_fetch_assoc($result);
    
    // ✅ CHECK: Hanya bisa edit jika status = 'baru'
    if ($edit_data['status'] !== 'baru') {
      $_SESSION['error'] = "Anda hanya bisa edit pesan yang masih berstatus 'Baru'!";
      header("Location: ?page=kontak");
      exit();
    }
    
    $edit_mode = true;
  } else {
    $_SESSION['error'] = "Pesan tidak ditemukan!";
    header("Location: ?page=kontak");
    exit();
  }
  mysqli_stmt_close($stmt);
}

// ============================================================================
// ✅ CREATE / UPDATE PESAN
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_message'])) {
  
  $nama = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $subjek = htmlspecialchars(trim($_POST['subject']), ENT_QUOTES, 'UTF-8');
  $pesan = htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8');

  // ✅ VALIDASI INPUT
  if (strlen($nama) < 3) {
    $error = "Nama minimal 3 karakter!";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Format email tidak valid!";
  } elseif (strlen($subjek) < 5) {
    $error = "Subjek minimal 5 karakter!";
  } elseif (strlen($pesan) < 10) {
    $error = "Pesan minimal 10 karakter!";
  } else {

    // ✅ UPDATE PESAN (hanya jika status = 'baru')
    if (!empty($_POST['id'])) {
      $id = intval($_POST['id']);
      
      // Check status
      $status_query = "SELECT status FROM contact WHERE id = ? AND user_id = ?";
      $status_stmt = mysqli_prepare($conn, $status_query);
      mysqli_stmt_bind_param($status_stmt, "ii", $id, $user_id);
      mysqli_stmt_execute($status_stmt);
      $status_result = mysqli_stmt_get_result($status_stmt);
      
      if (mysqli_num_rows($status_result) > 0) {
        $status_row = mysqli_fetch_assoc($status_result);
        
        if ($status_row['status'] !== 'baru') {
          $error = "Anda hanya bisa edit pesan yang masih berstatus 'Baru'!";
        } else {
          $update = "UPDATE contact SET nama=?, email=?, subjek=?, pesan=? WHERE id=? AND user_id=?";
          $update_stmt = mysqli_prepare($conn, $update);
          mysqli_stmt_bind_param($update_stmt, "sssii", $nama, $email, $subjek, $pesan, $id, $user_id);
          
          if (mysqli_stmt_execute($update_stmt)) {
            $_SESSION['success'] = "Pesan berhasil diperbarui!";
            header("Location: ?page=kontak");
            exit();
          } else {
            $error = "Gagal mengupdate pesan!";
          }
          mysqli_stmt_close($update_stmt);
        }
      }
      mysqli_stmt_close($status_stmt);

    } else {
      // ✅ CREATE PESAN BARU (status otomatis = 'baru')
      $insert = "INSERT INTO contact (user_id, nama, email, subjek, pesan, tanggal, status) 
                VALUES (?, ?, ?, ?, ?, NOW(), 'baru')";
      $insert_stmt = mysqli_prepare($conn, $insert);
      mysqli_stmt_bind_param($insert_stmt, "issss", $user_id, $nama, $email, $subjek, $pesan);

      if (mysqli_stmt_execute($insert_stmt)) {
        $_SESSION['success'] = "Pesan berhasil dikirim!";
        header("Location: ?page=kontak");
        exit();
      } else {
        $error = "Gagal mengirim pesan!";
      }
      mysqli_stmt_close($insert_stmt);
    }
  }
}

// ============================================================================
// ✅ READ DAFTAR PESAN USER
// ============================================================================
$query = "SELECT * FROM contact WHERE user_id = ? ORDER BY tanggal DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
  $messages[] = $row;
}
mysqli_stmt_close($stmt);

// ✅ Get session messages
if (isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
  $success = $_SESSION['success'];
  unset($_SESSION['success']);
}

include __DIR__ . '/../Views/kontak.php';
?>