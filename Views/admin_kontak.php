<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Manajemen Kontak - Admin</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/admin_berita.css">
</head>

<body>
<?php include 'admin_header.php'; ?>

<main class="main-content">

  <!-- ALERT -->
  <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'error'; ?>">
      <i class="fas fa-info-circle"></i> 
      <?php echo htmlspecialchars($message); ?>
      <button class="alert-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    </div>
  <?php endif; ?>

  <div class="content-header">
    <h2><i class="fas fa-envelope"></i> Manajemen Pesan Kontak</h2>
  </div>

  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th width="50">ID</th>
          <th width="140">Tanggal</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Subjek</th>
          <th>Pesan</th>
          <th width="140">User</th>
          <th width="120">Status</th>
          <th width="80">Aksi</th>
        </tr>
      </thead>

      <tbody>
        <?php if (!empty($data_contact)): ?>
          <?php foreach ($data_contact as $c): ?>
            <tr>
              <td><?php echo $c['id']; ?></td>

              <td><?php echo date('d/m/Y H:i', strtotime($c['tanggal'])); ?></td>

              <td><?php echo htmlspecialchars($c['nama']); ?></td>
              <td><?php echo htmlspecialchars($c['email']); ?></td>
              <td><?php echo htmlspecialchars($c['subjek']); ?></td>

              <td class="title-cell">
                <span title="<?php echo htmlspecialchars($c['pesan']); ?>">
                  <?php echo htmlspecialchars($c['pesan']); ?>
                </span>
              </td>

              <td>
                <span class="badge">
                  <i class="fas fa-user"></i>
                  <?php echo htmlspecialchars($c['userName'] ?: '-'); ?>
                </span>
              </td>

              <!-- STATUS UPDATE -->
              <td>
                <form method="POST" class="status-form">
                  <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                  <input type="hidden" name="update_status" value="1">

                  <select name="status" onchange="this.form.submit()" class="filter-select">
                    <option value="baru"    <?php if ($c['status']=='baru') echo 'selected'; ?>>Baru</option>
                    <option value="dibaca"  <?php if ($c['status']=='dibaca') echo 'selected'; ?>>Dibaca</option>
                    <option value="selesai" <?php if ($c['status']=='selesai') echo 'selected'; ?>>Selesai</option>
                  </select>
                </form>
              </td>

              <!-- DELETE -->
              <td>
                <button 
                  class="btn-icon btn-delete" 
                  onclick="confirmDelete(<?php echo $c['id']; ?>)"
                  title="Hapus"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>

        <?php else: ?>
          <tr>
            <td colspan="9" class="text-center">
              <i class="fas fa-inbox"></i> Belum ada pesan kontak
            </td>
          </tr>
        <?php endif; ?>
      </tbody>

    </table>
  </div>
</main>

<script>
function confirmDelete(id) {
  if (confirm("Yakin ingin menghapus pesan ini?")) {
    window.location = "?page=admin_kontak&action=delete&id=" + id;
  }
}
</script>

</body>
</html>