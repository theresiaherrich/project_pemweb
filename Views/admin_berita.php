<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Berita - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/admin_berita.css">
</head>
<body>
  <?php include 'admin_header.php'; ?>

  <!-- Main Content -->
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
      <h2><i class="fas fa-newspaper"></i> Kelola Berita</h2>
      <button class="btn btn-primary" onclick="openModal()">
        <i class="fas fa-plus"></i> Tambah Berita
      </button>
    </div>

    <!-- Table -->
    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th width="60">ID</th>
            <th width="80">Gambar</th>
            <th>Judul</th>
            <th>Isi Singkat</th>
            <th>Isi Lengkap</th>
            <th width="120">Kategori</th>
            <th width="150">Waktu</th>
            <th width="80">Views</th>
            <th width="150">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($semua_berita)): ?>
            <tr>
              <td colspan="9" class="text-center">Tidak ada data berita</td>
            </tr>
          <?php else: ?>
            <?php foreach ($semua_berita as $berita): ?>
              <tr>
                <td><?php echo $berita['id_berita']; ?></td>
                <td>
                  <img src="/<?php echo htmlspecialchars($berita['gambar']); ?>" 
                       alt="Thumbnail" class="table-img">
                </td>
                <td class="title-cell"><?php echo htmlspecialchars($berita['judul']); ?></td>
                <td class="title-cell"><?php echo htmlspecialchars(substr($berita['isi_singkat'], 0, 50)) . '...'; ?></td>
                <td class="title-cell"><?php echo htmlspecialchars(substr($berita['isi_lengkap'], 0, 50)) . '...'; ?></td>
                <td>
                  <span class="badge"><?php echo htmlspecialchars($berita['kategori']); ?></span>
                </td>
                <td><?php echo date("d M Y H:i", strtotime($berita['waktu'])); ?></td>
                <td>
                  <span class="views">
                    <i class="fas fa-eye"></i> <?php echo number_format($berita['views']); ?>
                  </span>
                </td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-edit" 
                            onclick="openEditModal(<?php echo $berita['id_berita']; ?>)"
                            title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon btn-delete" 
                            onclick="confirmDelete(<?php echo $berita['id_berita']; ?>, '<?php echo htmlspecialchars(addslashes($berita['judul'])); ?>')"
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

  <!-- Modal Form -->
  <div class="modal" id="modalForm">
    <div class="modal-content modal-large">
      <div class="modal-header">
        <h3 id="modalTitle">
          <i class="fas fa-plus-circle"></i> Tambah Berita
        </h3>
        <button class="modal-close" onclick="closeModal()">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form method="POST" enctype="multipart/form-data" id="formBerita">
        <input type="hidden" name="id_berita" id="id_berita" value="">
        <input type="hidden" name="old_image" id="old_image" value="">

        <div class="form-container">
          <!-- Left Column -->
          <div class="form-column">
            <div class="form-group">
              <label for="judul">
                <i class="fas fa-heading"></i> Judul Berita 
                <span class="required">*</span>
              </label>
              <input type="text" id="judul" name="judul" required 
                     placeholder="Masukkan judul berita"
                     value="<?php echo $edit_mode ? htmlspecialchars($edit_data['judul']) : ''; ?>">
            </div>

            <div class="form-group">
              <label for="kategori_id">
                <i class="fas fa-folder"></i> Kategori 
                <span class="required">*</span>
              </label>
              <select id="kategori_id" name="kategori_id" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategori_list as $kat): ?>
                  <option value="<?php echo $kat['id']; ?>"
                          <?php echo ($edit_mode && $edit_data['kategori_id'] == $kat['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="isi_singkat">
                <i class="fas fa-align-left"></i> Deskripsi Singkat 
                <span class="required">*</span>
              </label>
              <textarea id="isi_singkat" name="isi_singkat" required rows="4"
                        placeholder="Masukkan ringkasan berita (maks 200 karakter)"
                        maxlength="200"><?php echo $edit_mode ? htmlspecialchars($edit_data['isi_singkat']) : ''; ?></textarea>
            </div>
          </div>

          <!-- Right Column -->
          <div class="form-column">
            <div class="form-group">
              <label for="isi_lengkap">
                <i class="fas fa-file-alt"></i> Deskripsi Lengkap 
                <span class="required">*</span>
              </label>
              <textarea id="isi_lengkap" name="isi_lengkap" required rows="8"
                        placeholder="Masukkan isi lengkap berita"><?php echo $edit_mode ? htmlspecialchars($edit_data['isi_lengkap']) : ''; ?></textarea>
            </div>

            <div class="form-group">
              <label for="gambar">
                <i class="fas fa-image"></i> Gambar Berita
              </label>
              <div class="file-upload-wrapper">
                <input type="file" id="gambar" name="gambar" accept="image/*" 
                       onchange="previewImage(this)" hidden>
                <label for="gambar" class="file-upload-label">
                  <i class="fas fa-cloud-upload-alt"></i>
                  <span>Pilih gambar atau drag & drop</span>
                </label>
              </div>
              <small class="form-text">Format: JPG, PNG, GIF (Max 5MB)</small>
              
              <div id="imagePreview" class="image-preview" <?php echo ($edit_mode && $edit_data['gambar']) ? 'style="display:block;"' : ''; ?>>
                <?php if ($edit_mode && $edit_data['gambar']): ?>
                  <img src="/<?php echo htmlspecialchars($edit_data['gambar']); ?>" alt="Preview">
                  <button type="button" class="remove-image" onclick="removeImage()">
                    <i class="fas fa-times"></i>
                  </button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" onclick="closeModal()">
            <i class="fas fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Berita
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="/Views/js/admin_berita.js"></script>
</body>
</html>