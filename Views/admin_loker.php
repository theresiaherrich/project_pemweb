

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Loker - Global Time</title>
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
        <h2><i class="fas fa-briefcase"></i> Kelola Lowongan Kerja</h2>
        <button class="btn btn-primary" onclick="openModal()">
          <i class="fas fa-plus"></i> Tambah Loker
        </button>
      </div>

      <div class="filter-section">
        <form method="GET" class="filter-form">
          <input type="hidden" name="page" value="admin_loker">
          
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari posisi, perusahaan..." 
                   value="<?php echo htmlspecialchars($search); ?>">
          </div>

          <select name="filter_kategori" class="filter-select">
            <option value="0">Semua Kategori</option>
            <?php foreach ($kategori_list as $kat): ?>
              <option value="<?php echo $kat['id']; ?>" 
                      <?php echo $filter_kategori == $kat['id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($kat['nama_kategori']); ?>
              </option>
            <?php endforeach; ?>
          </select>

          <button type="submit" class="btn btn-secondary">
            <i class="fas fa-filter"></i> Filter
          </button>

          <?php if ($search || $filter_kategori): ?>
            <a href="?page=admin_loker" class="btn btn-light">
              <i class="fas fa-times"></i> Reset
            </a>
          <?php endif; ?>
        </form>
      </div>

      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th width="50">ID</th>
              <th width="80">Gambar</th>
              <th>Posisi / Judul</th>
              <th>Perusahaan</th>
              <th>Lokasi</th>
              <th width="120">Kategori</th>
              <th width="100">Tanggal</th>
              <th width="120">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($semua_loker)): ?>
              <tr>
                <td colspan="8" class="text-center">Tidak ada data loker</td>
              </tr>
            <?php else: ?>
              <?php foreach ($semua_loker as $loker): ?>
                <tr>
                  <td><?php echo $loker['id']; ?></td>
                  <td>
                    <?php 
                        $imgSrc = $loker['gambar'];
                        if (strpos($imgSrc, 'http') === false) {
                            $imgSrc = "/" . $imgSrc;
                        }
                    ?>
                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                         alt="Thumbnail" class="table-img" 
                         onerror="this.src='/Views/img/default-job.jpg'">
                  </td>
                  <td class="title-cell">
                      <strong><?php echo htmlspecialchars($loker['judul']); ?></strong>
                  </td>
                  <td><?php echo htmlspecialchars($loker['perusahaan']); ?></td>
                  <td><?php echo htmlspecialchars($loker['lokasi']); ?></td>
                  <td>
                    <span class="badge"><?php echo htmlspecialchars($loker['kategori'] ?? 'Umum'); ?></span>
                  </td>
                  <td><?php echo date("d M Y", strtotime($loker['tanggal'])); ?></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-edit" 
                              onclick="openEditModal(<?php echo $loker['id']; ?>)"
                              title="Edit">
                        <i class="fas fa-edit"></i>
                      </button>
                      
                      <button class="btn-icon btn-delete" 
                              onclick="confirmDelete(<?php echo $loker['id']; ?>, '<?php echo htmlspecialchars(addslashes($loker['judul'])); ?>')"
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

  <div class="modal" id="modalForm">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="modalTitle">
          <i class="fas fa-briefcase"></i> Tambah Loker
        </h3>
        <button class="modal-close" onclick="closeModal()">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form method="POST" enctype="multipart/form-data" id="formLoker">
        <input type="hidden" name="id_loker" id="id_loker" value="">
        <input type="hidden" name="old_image" id="old_image" value="">

        <div class="form-grid">
          <div class="form-group">
            <label for="judul">Posisi / Judul <span class="required">*</span></label>
            <input type="text" id="judul" name="judul" required placeholder="Contoh: Staff IT">
          </div>

          <div class="form-group">
            <label for="perusahaan">Perusahaan <span class="required">*</span></label>
            <input type="text" id="perusahaan" name="perusahaan" required placeholder="Contoh: PT. Maju Mundur">
          </div>

          <div class="form-group">
            <label for="lokasi">Lokasi <span class="required">*</span></label>
            <input type="text" id="lokasi" name="lokasi" required placeholder="Contoh: Jakarta Selatan">
          </div>

          <div class="form-group">
            <label for="tanggal">Tanggal Deadline <span class="required">*</span></label>
            <input type="date" id="tanggal" name="tanggal" required value="<?php echo date('Y-m-d'); ?>">
          </div>

          <div class="form-group">
            <label for="kategori_id">Kategori <span class="required">*</span></label>
            <select id="kategori_id" name="kategori_id" required>
              <option value="">Pilih Kategori</option>
              <?php foreach ($kategori_list as $kat): ?>
                <option value="<?php echo $kat['id']; ?>">
                  <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group" style="grid-column: 1 / -1;">
            <label for="deskripsi">Deskripsi Pekerjaan <span class="required">*</span></label>
            <textarea id="deskripsi" name="deskripsi" rows="5" required placeholder="Jelaskan detail pekerjaan..."></textarea>
          </div>

          <div class="form-group" style="grid-column: 1 / -1;">
            <label for="gambar">Logo / Poster</label>
            <input type="file" id="gambar" name="gambar" accept="image/*" onchange="previewImage(this)">
            <small>Format: JPG, PNG, GIF (Max 5MB)</small>
            <div id="imagePreview" class="image-preview"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" onclick="closeModal()">
            <i class="fas fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById('modalForm');
    const form = document.getElementById('formLoker');
    const modalTitle = document.getElementById('modalTitle');
    const previewDiv = document.getElementById('imagePreview');

    // Buka Modal Tambah
    function openModal() {
        form.reset();
        document.getElementById('id_loker').value = '';
        document.getElementById('old_image').value = '';
        previewDiv.innerHTML = '';
        modalTitle.innerHTML = '<i class="fas fa-plus-circle"></i> Tambah Loker';
        modal.style.display = 'flex';
    }

    // Buka Modal Edit (AJAX)
    function openEditModal(id) {
        // Fetch data
        fetch(`?page=admin_loker&ajax=edit&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if(data.error) {
                    alert(data.error);
                    return;
                }

                document.getElementById('id_loker').value = data.id;
                document.getElementById('judul').value = data.judul;
                document.getElementById('perusahaan').value = data.perusahaan;
                document.getElementById('lokasi').value = data.lokasi;
                document.getElementById('deskripsi').value = data.deskripsi;
                document.getElementById('tanggal').value = data.tanggal;
                document.getElementById('kategori_id').value = data.kategori_id;
                document.getElementById('old_image').value = data.gambar;

                // Preview Image
                if(data.gambar) {
                    let imgSrc = data.gambar.includes('http') ? data.gambar : '/' + data.gambar;
                    previewDiv.innerHTML = `<img src="${imgSrc}" alt="Preview" style="max-height:100px; border-radius:5px; margin-top:10px;">`;
                } else {
                    previewDiv.innerHTML = '';
                }

                modalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Loker';
                modal.style.display = 'flex';
            })
            .catch(err => console.error(err));
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    function confirmDelete(id, title) {
        if(confirm(`Yakin ingin menghapus lowongan "${title}"?`)) {
            window.location.href = `?page=admin_loker&action=delete&id=${id}`;
        }
    }

    // Preview Gambar saat upload
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-height:100px; border-radius:5px; margin-top:10px;">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Close modal kalau klik di luar
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
  </script>

</body>
</html>