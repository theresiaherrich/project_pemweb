<?php
session_start();

// Jika sudah login, redirect ke halaman home
if (isset($_SESSION['user'])) {
    header("Location: ?page=home"); 
    exit();
}

include __DIR__ . '/../model/koneksi.php';

$error = ""; 
$max_attempts = 5;
$lockout_time = 300;

// Inisialisasi percobaan login jika belum ada di session
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// Jika sudah melebihi batas percobaan login, kunci akun untuk beberapa menit
if ($_SESSION['login_attempts'] >= $max_attempts && (time() - $_SESSION['last_attempt_time']) < $lockout_time) {
    $remaining = ceil(($lockout_time - (time() - $_SESSION['last_attempt_time'])) / 60);
    $error = "Terlalu banyak percobaan login gagal. Coba lagi dalam $remaining menit.";
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Reset percobaan login jika waktu lockout sudah lewat
    if ((time() - $_SESSION['last_attempt_time']) > $lockout_time) {
        $_SESSION['login_attempts'] = 0;
    }

    // Ambil input email dan password dari form login
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Cek apakah email valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        // Ambil data user dari database berdasarkan email
        $query = "SELECT id, name, email, password, address, phone, photo, role FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        // Cek apakah user ditemukan dan password cocok
        if ($user && password_verify($password, $user['password'])) {
            // Reset percobaan login
            $_SESSION['login_attempts'] = 0;
            session_regenerate_id(true); // Mengganti ID session untuk keamanan

            // Simpan data user ke session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => htmlspecialchars($user['name']),
                'email' => htmlspecialchars($user['email']),
                'address' => htmlspecialchars($user['address']),
                'phone' => htmlspecialchars($user['phone']),
                'photo' => htmlspecialchars($user['photo']),
                'role' => htmlspecialchars($user['role']),  // Menyimpan role di session
                'login_time' => time()
            ];

            // Redirect sesuai role (jika admin, arahkan ke dashboard admin)
            if ($_SESSION['user']['role'] === 'admin') {
                header("Location: ?page=admin_berita"); // Misal, halaman admin
            } else {
                header("Location: ?page=home"); // Halaman untuk user biasa
            }
            exit();

        } else {
            // Jika login gagal, increment percobaan login
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            $error = "Email atau password salah!";
        }

        mysqli_stmt_close($stmt);
    }
}

// Kirim variabel error ke tampilan
$error = $error ?? "";
include __DIR__ . '/../Views/login.php';
?>
