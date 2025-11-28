<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

// ===============================
// Proteksi Login
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

$errors = [];
$success_message = '';

// ==================== HANDLE ACTIONS ====================

// DELETE ACTION
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']);
    $type = $_GET['type'];
    
    if ($type == 'live') {
        $query = "DELETE FROM live_streams WHERE id = $id";
    } else {
        $query = "DELETE FROM upcoming_streams WHERE id = $id";
    }
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php?page=admin_live");
        exit();
    }
}

// CREATE/UPDATE ACTION
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $type   = $_POST['type'];

    // ============= LIVE STREAM =============
    if ($type == 'live') {
        $title       = mysqli_real_escape_string($conn, trim($_POST['title']));
        $description = mysqli_real_escape_string($conn, trim($_POST['description']));
        $category    = mysqli_real_escape_string($conn, trim($_POST['category']));
        $channel     = mysqli_real_escape_string($conn, trim($_POST['channel']));
        $video_url   = mysqli_real_escape_string($conn, trim($_POST['video_url']));
        $thumbnail   = mysqli_real_escape_string($conn, trim($_POST['thumbnail']));
        $is_live     = isset($_POST['is_live']) ? 1 : 0;
        $viewers     = intval($_POST['viewers']);
        $live_date   = mysqli_real_escape_string($conn, $_POST['live_date']);

        if (empty($title))      $errors[] = "Judul harus diisi";
        if (empty($video_url))  $errors[] = "URL video harus diisi";
        if (empty($thumbnail))  $errors[] = "URL thumbnail harus diisi";

        if (empty($errors)) {
            if ($action == 'create') {
                $query = "INSERT INTO live_streams (title, description, category, channel, video_url, thumbnail, is_live, viewers, live_date) 
                          VALUES ('$title', '$description', '$category', '$channel', '$video_url', '$thumbnail', $is_live, $viewers, '$live_date')";
                $success_type = 'created';
            } else {
                $id = intval($_POST['id']);
                $query = "UPDATE live_streams SET 
                          title = '$title',
                          description = '$description',
                          category = '$category',
                          channel = '$channel',
                          video_url = '$video_url',
                          thumbnail = '$thumbnail',
                          is_live = $is_live,
                          viewers = $viewers,
                          live_date = '$live_date'
                          WHERE id = $id";
                $success_type = 'updated';
            }

            if (mysqli_query($conn, $query)) {
                header("Location: index.php?page=admin_live");
                exit();
            } else {
                $errors[] = "Error: " . mysqli_error($conn);
            }
        }

    // ============= UPCOMING STREAM =============
    } else {
        $title       = mysqli_real_escape_string($conn, trim($_POST['title']));
        $schedule    = mysqli_real_escape_string($conn, trim($_POST['schedule']));
        $thumbnail   = mysqli_real_escape_string($conn, trim($_POST['thumbnail']));
        $event_date  = mysqli_real_escape_string($conn, $_POST['event_date']);

        if (empty($title))       $errors[] = "Judul harus diisi";
        if (empty($schedule))    $errors[] = "Jadwal harus diisi";
        if (empty($thumbnail))   $errors[] = "URL thumbnail harus diisi";
        if (empty($event_date))  $errors[] = "Tanggal event harus diisi";

        if (empty($errors)) {
            if ($action == 'create') {
                $query = "INSERT INTO upcoming_streams (title, schedule, thumbnail, event_date) 
                          VALUES ('$title', '$schedule', '$thumbnail', '$event_date')";
                $success_type = 'created';
            } else {
                $id = intval($_POST['id']);
                $query = "UPDATE upcoming_streams SET 
                          title = '$title',
                          schedule = '$schedule',
                          thumbnail = '$thumbnail',
                          event_date = '$event_date'
                          WHERE id = $id";
                $success_type = 'updated';
            }

            if (mysqli_query($conn, $query)) {
                header("Location: index.php?page=admin_live");
                exit();
            } else {
                $errors[] = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// ==================== GET DATA FOR EDIT ====================
$edit_data = null;
$edit_type = '';
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && isset($_GET['type'])) {
    $edit_id   = intval($_GET['id']);
    $edit_type = $_GET['type'];

    if ($edit_type == 'live') {
        $query = "SELECT * FROM live_streams WHERE id = $edit_id";
    } else {
        $query = "SELECT * FROM upcoming_streams WHERE id = $edit_id";
    }

    $result     = mysqli_query($conn, $query);
    $edit_data  = mysqli_fetch_assoc($result);
}

// ==================== GET ALL DATA ====================
$query_live = "SELECT * FROM live_streams ORDER BY id DESC";
$result_live = mysqli_query($conn, $query_live);
$live_streams = mysqli_fetch_all($result_live, MYSQLI_ASSOC);

$query_upcoming = "SELECT * FROM upcoming_streams ORDER BY id DESC";
$result_upcoming = mysqli_query($conn, $query_upcoming);
$upcoming_streams = mysqli_fetch_all($result_upcoming, MYSQLI_ASSOC);

// Success message
if (isset($_GET['success'])) {
    $success_message = $_GET['success'];
}

include __DIR__ . '/../Views/admin_live.php';
?>
