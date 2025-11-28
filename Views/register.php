<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="/Views/css/login.css">
</head>

<body>
  <div class="form-container">
    <h2>Register Portal Berita</h2>
    <?php if (!empty($error)) : ?>
      <div style="background:#ffdddd;color:#a00;padding:10px;border-radius:5px;margin-bottom:10px;">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)) : ?>
      <div style="background:#ddffdd;color:#0a0;padding:10px;border-radius:5px;margin-bottom:10px;">
        <?= $success ?>
      </div>

      <script>
        setTimeout(() => {
          window.location = "index.php?page=login";
        }, 1500);
      </script>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="name" placeholder="Nama Lengkap" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password (min. 6 karakter)" required>
      <input type="password" name="confirm" placeholder="Konfirmasi Password" required>
      <input type="text" name="address" placeholder="Alamat" required>
      <input type="text" name="phone" placeholder="No. HP" required pattern="[0-9]{10,13}"
        title="Masukkan nomor HP yang valid (10-13 digit)">
      <button type="submit" class="btn">Register</button>
    </form>
    <p>Sudah punya akun? <a href="?page=login">Login di sini</a></p>
  </div>
</body>

</html>