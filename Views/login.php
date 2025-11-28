
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
<link rel="stylesheet" href="/Views/css/login.css">

</head>

<body>
    <div class="form-container">
        <h2>Login Portal Berita</h2>
        <form method="POST" autocomplete="off">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <p style="color:red;"><?php echo $error; ?></p>
        <p>Belum punya akun? <a href="index.php?page=register">Register di sini</a></p>
    </div>
</body>

</html>