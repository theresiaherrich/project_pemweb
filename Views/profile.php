<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="/project/Views/css/profile.css">
</head>

<body>

  <div class="card">
    <div class="card-header">
      <a href="home.php" class="back-btn"><i class="fas fa-times"></i></a>
      <div class="profile-pic">
        <img src="<?= htmlspecialchars($photoPath) ?>" alt="Profile">
      </div>
    </div>

    <div class="card-body">
      <h2><?= htmlspecialchars($user['name']) ?></h2>
      <p class="user-bio">Data Profil</p>

      <div class="info">
        <div class="info-item">
          <i class="fas fa-envelope"></i>
          <div>
            <span class="label">Email</span>
            <span class="value"><?= htmlspecialchars($user['email']) ?></span>
          </div>
        </div>

        <div class="info-item">
          <i class="fas fa-map-marker-alt"></i>
          <div>
            <span class="label">Alamat</span>
            <span class="value"><?= htmlspecialchars($user['address']) ?></span>
          </div>
        </div>

        <div class="info-item">
          <i class="fas fa-phone"></i>
          <div>
            <span class="label">Phone</span>
            <span class="value"><?= htmlspecialchars($user['phone']) ?></span>
          </div>
        </div>
      </div>

      <div class="button-group">
        <button class="btn btn-edit" onclick="window.location.href='edit-profile.php'">
          <i class="fas fa-user-edit"></i> Edit Profile
        </button>
        <form method="get" style="width: 100%;">
          <button type="submit" name="logout" class="btn btn-logout" onclick="return confirm('Yakin ingin logout?')">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>
      </div>
    </div>
  </div>

</body>

</html>