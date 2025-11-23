<?php
session_start();
include __DIR__ . '/../model/koneksi.php';

$error = ""; // <- pastikan variabel selalu ada
$max_attempts = 5;
$lockout_time = 300;

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

if ($_SESSION['login_attempts'] >= $max_attempts && (time() - $_SESSION['last_attempt_time']) < $lockout_time) {

    $remaining = ceil(($lockout_time - (time() - $_SESSION['last_attempt_time'])) / 60);
    $error = "Terlalu banyak percobaan login gagal. Coba lagi dalam $remaining menit.";

} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ((time() - $_SESSION['last_attempt_time']) > $lockout_time) {
        $_SESSION['login_attempts'] = 0;
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {

        $query = "SELECT id, name, email, password, address, phone, photo FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['login_attempts'] = 0;
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => htmlspecialchars($user['name']),
                'email' => htmlspecialchars($user['email']),
                'address' => htmlspecialchars($user['address']),
                'phone' => htmlspecialchars($user['phone']),
                'photo' => htmlspecialchars($user['photo']),
                'login_time' => time()
            ];

            header("Location: ?page=home");
            exit;

        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            $error = "Email atau password salah!";
        }

        mysqli_stmt_close($stmt);
    }
}

// PENTING: kirim variabel ke view
$error = $error ?? "";
include __DIR__ . '/../Views/login.php';
