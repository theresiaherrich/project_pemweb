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


// Helper escape HTML
function e(?string $s): string { 
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); 
}

$success_message = '';
$error_message = '';

// =========================
// HANDLE POST REQUEST
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // HANDLE DELETE
  if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['delete_id'] ?? 0);

    if ($id > 0) {
      // Get image filename before delete
      $result = mysqli_query($conn, "SELECT hero_image FROM about_us WHERE id=$id");
      $img = '';
      if ($result && $row = mysqli_fetch_assoc($result)) {
        $img = $row['hero_image'];
      }

      // Delete from database
      $stmt = mysqli_prepare($conn, "DELETE FROM about_us WHERE id=?");
      mysqli_stmt_bind_param($stmt, 'i', $id);

      if (mysqli_stmt_execute($stmt)) {
        // Delete image file if not default
        if ($img && $img !== "default-hero.jpg") {
          $path = __DIR__ . '/../Views/uploads/' . $img;
          if (file_exists($path)) {
            unlink($path);
          }
        }
        mysqli_stmt_close($stmt);
      }
    }
    
    header("Location: index.php?page=admin_aboutUs");
    exit();
  }

  // HANDLE CREATE / UPDATE
  $id = intval($_POST['id'] ?? 0);
  $hero_title = trim($_POST['hero_title'] ?? '');
  $hero_subtitle = trim($_POST['hero_subtitle'] ?? '');
  $hero_description = trim($_POST['hero_description'] ?? '');
  $about_heading = trim($_POST['about_heading'] ?? '');
  $about_subheading = trim($_POST['about_subheading'] ?? '');
  $about_paragraph1 = trim($_POST['about_paragraph1'] ?? '');
  $about_paragraph2 = trim($_POST['about_paragraph2'] ?? '');

  // Default image
  $hero_image = $_POST['current_image'] ?? 'default-hero.jpg';

  // Upload file if provided
  if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {

    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $file_type = $_FILES['hero_image']['type'];

    if (in_array($file_type, $allowed_types)) {

      $upload_dir = __DIR__ . '/../Views/uploads/';
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
      }

      $ext = pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION);
      $new_filename = "hero_" . time() . "." . $ext;

      if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $upload_dir . $new_filename)) {
        // Delete old image if not default
        if ($hero_image !== "default-hero.jpg") {
          $old = $upload_dir . $hero_image;
          if (file_exists($old)) {
            unlink($old);
          }
        }
        $hero_image = $new_filename;
      } else {
        $error_message = "Gagal upload gambar.";
      }
    } else {
      $error_message = "Format gambar tidak valid.";
    }
  }

  // Validation
  if (!$error_message && (empty($hero_title) || empty($hero_subtitle))) {
    $error_message = "Hero Title dan Subtitle wajib diisi!";
  }

  if (!$error_message) {
    if ($id > 0) {
      // UPDATE
      $stmt = mysqli_prepare($conn, 
        "UPDATE about_us SET 
        hero_title=?, hero_subtitle=?, hero_description=?, hero_image=?,
        about_heading=?, about_subheading=?, about_paragraph1=?, about_paragraph2=?
        WHERE id=?"
      );

      mysqli_stmt_bind_param($stmt, 'ssssssssi',
        $hero_title, $hero_subtitle, $hero_description, $hero_image,
        $about_heading, $about_subheading, $about_paragraph1, $about_paragraph2,
        $id
      );

      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: index.php?page=admin_aboutUs");
        exit();
      } else {
        $error_message = "Gagal mengupdate data: " . mysqli_error($conn);
      }

    } else {
      // INSERT
      $stmt = mysqli_prepare($conn, 
        "INSERT INTO about_us
        (hero_title, hero_subtitle, hero_description, hero_image,
         about_heading, about_subheading, about_paragraph1, about_paragraph2)
        VALUES (?,?,?,?,?,?,?,?)"
      );

      mysqli_stmt_bind_param($stmt, 'ssssssss',
        $hero_title, $hero_subtitle, $hero_description, $hero_image,
        $about_heading, $about_subheading, $about_paragraph1, $about_paragraph2
      );

      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: index.php?page=admin_aboutUs");
        exit();
      } else {
        $error_message = "Gagal menambahkan data: " . mysqli_error($conn);
      }
    }

    if (isset($stmt)) {
      mysqli_stmt_close($stmt);
    }
  }
}

// =========================
// READ ALL DATA
// =========================
$result = mysqli_query($conn, "SELECT * FROM about_us ORDER BY id DESC");
?>

  <?php include __DIR__ . '/../Views/admin_aboutUs.php'; ?>