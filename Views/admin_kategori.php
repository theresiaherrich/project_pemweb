
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Kelola Kategori</title>
  <link rel="stylesheet" href="/Views/css/admin_berita.css">
</head>
<body>

<?php include "admin_header.php"; ?>

<main class="main-content">
  
  <?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
      <?php echo htmlspecialchars($message); ?>
      <button onclick="this.parentElement.remove()">×</button>
    </div>
  <?php endif; ?>

  <div class="content-header">
    <h2><i class="fas fa-list"></i> Kelola Kategori</h2>
    <button class="btn btn-primary" onclick="openModal()">
      <i class="fas fa-plus"></i> Tambah Kategori
    </button>
  </div>

  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th width="70">ID</th>
          <th>Nama Kategori</th>
          <th width="140">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($kategori_list)): ?>
          <tr><td colspan="3" class="text-center">Tidak ada kategori</td></tr>
        <?php else: ?>
          <?php foreach($kategori_list as $kat): ?>
            <tr>
              <td><?php echo $kat['id']; ?></td>
              <td><?php echo htmlspecialchars($kat['nama_kategori']); ?></td>
              <td>
                <button class="btn-icon btn-edit" onclick="editKategori(<?php echo $kat['id']; ?>,'<?php echo addslashes($kat['nama_kategori']); ?>')">
                  <i class="fas fa-edit"></i>
                </button>

                <button class="btn-icon btn-delete" onclick="deleteKategori(<?php echo $kat['id']; ?>, '<?php echo addslashes($kat['nama_kategori']); ?>')">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<!-- Modal -->
<div class="modal" id="modalKategori">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="modalTitle"><i class="fas fa-plus"></i> Tambah Kategori</h3>
      <button class="modal-close" onclick="closeModal()">×</button>
    </div>

    <form method="POST">
      <input type="hidden" name="id" id="kat_id">

      <label>Nama Kategori</label>
      <input type="text" name="nama_kategori" id="nama_kategori" required>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>

  </div>
</div>
<script src="/Views/js/admin_kategori.js"></script>
</body>
</html>
