<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="edit-profile.css">
</head>

<body>

  <div class="card">
    <div class="card-header">
      <a href="profile.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
      <div class="profile-pic">
        <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Profile">
      </div>
    </div>

    <div class="card-body">
      <h2>Edit Profil</h2>
      <p class="user-bio">Ubah data profilmu di bawah ini</p>

      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="name">Nama</label>
          <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="form-group">
          <label for="address">Alamat</label>
          <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address']) ?>">
        </div>

        <div class="form-group">
          <label for="phone">Telepon</label>
          <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
        </div>

        <div class="form-group">
          <label for="photo">Ganti Foto Profil</label>
          <input type="file" id="photo" name="photo" accept="image/*">
        </div>

        <div class="form-group" style="text-align:left;">
          <div class="checkbox-container">
            <input type="checkbox" name="delete_photo" id="delete_photo">
            <label for="delete_photo">Hapus foto profil</label>
          </div>
        </div>

        <div class="button-group">
          <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>