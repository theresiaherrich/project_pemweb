
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin User - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/admin_berita.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<main class="main-content">

  <?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
      <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
      <?php echo htmlspecialchars($message); ?>
      <button class="alert-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    </div>
  <?php endif; ?>

  <div class="content-header">
    <h2><i class="fas fa-users"></i> Kelola User</h2>
    <button class="btn btn-primary" onclick="openModal()">
      <i class="fas fa-user-plus"></i> Tambah User
    </button>
  </div>

  

  <!-- TABLE -->
  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th width="60">ID</th>
          <th width="80">Foto</th>
          <th>Nama</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Role</th>
          <th width="150">Aksi</th>
        </tr>
      </thead>

      <tbody>
        <?php if (empty($semua_user)): ?>
          <tr>
            <td colspan="8" class="text-center">Tidak ada user</td>
          </tr>
        <?php else: ?>
          <?php foreach ($semua_user as $user): ?>
            <tr>
              <td><?php echo $user['id']; ?></td>

              <td>
                <img src="/<?php echo htmlspecialchars($user['photo']); ?>" 
                     alt="Foto User" class="table-img">
              </td>

              <td><?php echo htmlspecialchars($user['name']); ?></td>
              <td><?php echo htmlspecialchars($user['username']); ?></td>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
              <td><?php echo htmlspecialchars($user['phone']); ?></td>

              <td>
                <span class="badge"><?php echo htmlspecialchars($user['role']); ?></span>
              </td>

              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-edit"
                          onclick="openEditModal(<?php echo $user['id']; ?>)"
                          title="Edit">
                    <i class="fas fa-edit"></i>
                  </button>

                  <button class="btn-icon btn-delete"
                          onclick="confirmDelete(<?php echo $user['id']; ?>)"
                          title="Hapus">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</main>

<!-- MODAL -->
<div class="modal" id="modalForm">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="modalTitle"><i class="fas fa-user"></i> Tambah User</h3>
      <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
    </div>

    <form method="POST" enctype="multipart/form-data">

      <input type="hidden" id="id_user" name="id_user">
      <input type="hidden" id="old_photo" name="old_photo">

      <div class="form-grid">

        <div class="form-group">
          <label>Nama</label>
          <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" id="username" required>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
          <label>Password (kosongkan jika tidak ganti)</label>
          <input type="password" name="password" id="password">
        </div>

        <div class="form-group">
          <label>No. Telp</label>
          <input type="text" name="phone" id="phone">
        </div>

        <div class="form-group">
          <label>Role</label>
          <select name="role" id="role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label>Foto User</label>
          <input type="file" name="photo" id="photo" accept="image/*" onchange="previewImage(this)">
          <div id="imagePreview" class="image-preview"></div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>

    </form>

  </div>
</div>

<script src="/Views/js/admin_user.js"></script>

</body>
</html>
