<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin About Us - Global Time</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/admin_berita.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<div class="main-content">

  <!-- ALERT -->
  <?php if ($success_message): ?>
    <div class="alert alert-success">
      <i class="fas fa-check-circle"></i> <?= e($success_message) ?>
      <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    </div>
  <?php endif; ?>

  <?php if ($error_message): ?>
    <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i> <?= e($error_message) ?>
      <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    </div>
  <?php endif; ?>

  <!-- HEADER -->
  <div class="content-header">
    <h2><i class="fas fa-info-circle"></i> Kelola Halaman About Us</h2>
    <button class="btn btn-primary" onclick="openAboutModal()">
      <i class="fas fa-plus"></i> Tambah Konten
    </button>
  </div>

  <!-- TABLE -->
  <div class="table-container">
    <table class="data-table" id="aboutTable">
      <thead>
        <tr>
          <th style="width: 60px;">ID</th>
          <th style="width: 120px;">Hero Image</th>
          <th style="width: 25%;">Hero Title</th>
          <th style="width: 25%;">Hero Subtitle</th>
          <th style="width: 20%;">Heading</th>
          <th style="width: 150px;">Aksi</th>
        </tr>
      </thead>

      <tbody>
      <?php if (mysqli_num_rows($result) === 0): ?>
        <tr>
          <td colspan="6" class="text-center">Belum ada data About Us</td>
        </tr>
      <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $row['id'] ?></td>

          <td>
            <img src="Views/uploads/<?= e($row['hero_image']) ?>"
                 alt="Hero"
                 class="table-img"
                 onerror="this.src='Views/uploads/default-hero.jpg'">
          </td>

          <td class="title-cell"><?= e($row['hero_title']) ?></td>
          <td><?= e($row['hero_subtitle']) ?></td>
          <td><?= e($row['about_heading']) ?></td>

          <td>
            <div class="action-buttons">

              <button class="btn-icon btn-edit"
                      onclick='openAboutEditModal(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                      title="Edit">
                <i class="fas fa-edit"></i>
              </button>

              <button class="btn-icon btn-delete"
                      onclick="confirmAboutDelete(<?= $row['id'] ?>)"
                      title="Hapus">
                <i class="fas fa-trash"></i>
              </button>

            </div>
          </td>
        </tr>
        <?php endwhile; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Hidden Form untuk Delete -->
<form id="deleteAboutForm" method="POST" action="" style="display:none;">
  <input type="hidden" name="action" value="delete">
  <input type="hidden" name="delete_id" id="delete_about_id">
</form>

<!-- ==========================
       MODAL
============================ -->
<div class="modal" id="modalAboutForm">
  <div class="modal-content">

    <div class="modal-header">
      <h3 id="modalAboutTitle">
        <i class="fas fa-plus-circle"></i> Tambah Konten About Us
      </h3>
      <button class="modal-close" onclick="closeAboutModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form method="POST" action="" enctype="multipart/form-data" id="aboutUsForm">
      <input type="hidden" name="id" id="about_id">
      <input type="hidden" name="current_image" id="about_current_image">

      <div class="form-grid">

        <div class="form-group">
          <label>Hero Title <span class="required">*</span></label>
          <input type="text" name="hero_title" id="about_hero_title" required>
        </div>

        <div class="form-group">
          <label>Hero Subtitle <span class="required">*</span></label>
          <input type="text" name="hero_subtitle" id="about_hero_subtitle" required>
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label>Hero Description</label>
          <textarea name="hero_description" id="about_hero_description" rows="3"></textarea>
        </div>

        <div class="form-group">
          <label>About Heading</label>
          <input type="text" name="about_heading" id="about_heading">
        </div>

        <div class="form-group">
          <label>About Subheading</label>
          <input type="text" name="about_subheading" id="about_subheading">
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label>Paragraph 1</label>
          <textarea name="about_paragraph1" id="about_paragraph1" rows="3"></textarea>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label>Paragraph 2</label>
          <textarea name="about_paragraph2" id="about_paragraph2" rows="3"></textarea>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label>Hero Image</label>
          <input type="file" name="hero_image" id="about_hero_image" 
                 accept="image/jpeg,image/jpg,image/png,image/webp" 
                 onchange="previewAboutImage(this)">
          <small>Format: JPG, PNG, WEBP (Max 2MB)</small>
          
          <div class="image-preview empty" id="aboutImagePreview">
            Tidak ada gambar dipilih
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" onclick="closeAboutModal()">
          <i class="fas fa-times"></i> Batal
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Simpan
        </button>
      </div>

    </form>

  </div>
</div>

<style>
/* Fix untuk mencegah glitch pada tabel */
.data-table {
  opacity: 1 !important;
  transition: none !important;
}

.data-table tbody tr {
  opacity: 1 !important;
  animation: none !important;
}
</style>

<script>
// =============================================
// FUNGSI UNTUK ABOUT US - NAMESPACE UNIQUE
// =============================================

// Reset form
function resetAboutForm() {
    const form = document.getElementById("aboutUsForm");
    if (form) form.reset();
    
    document.getElementById("about_id").value = "";
    document.getElementById("about_current_image").value = "";
    
    const preview = document.getElementById("aboutImagePreview");
    if (preview) {
        preview.innerHTML = "Tidak ada gambar dipilih";
        preview.classList.add("empty");
    }
}

// Buka modal tambah (form kosong)
function openAboutModal() {
    const modal = document.getElementById("modalAboutForm");
    if (!modal) return;
    
    // Reset form
    resetAboutForm();
    
    // Set judul modal
    document.getElementById("modalAboutTitle").innerHTML =
        '<i class="fas fa-plus-circle"></i> Tambah Konten About Us';
    
    // Buka modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Tutup modal
function closeAboutModal() {
    const modal = document.getElementById("modalAboutForm");
    if (!modal) return;
    
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
    resetAboutForm();
}

// Buka modal edit (isi dengan data)
function openAboutEditModal(data) {
    const modal = document.getElementById("modalAboutForm");
    if (!modal) return;
    
    // Set judul modal edit
    document.getElementById("modalAboutTitle").innerHTML =
        '<i class="fas fa-edit"></i> Edit Konten About Us';
    
    // Isi form dengan data
    document.getElementById("about_id").value = data.id || "";
    document.getElementById("about_hero_title").value = data.hero_title || "";
    document.getElementById("about_hero_subtitle").value = data.hero_subtitle || "";
    document.getElementById("about_hero_description").value = data.hero_description || "";
    document.getElementById("about_heading").value = data.about_heading || "";
    document.getElementById("about_subheading").value = data.about_subheading || "";
    document.getElementById("about_paragraph1").value = data.about_paragraph1 || "";
    document.getElementById("about_paragraph2").value = data.about_paragraph2 || "";
    document.getElementById("about_current_image").value = data.hero_image || "";
    
    // Tampilkan preview gambar jika ada
    const preview = document.getElementById("aboutImagePreview");
    if (data.hero_image) {
        preview.innerHTML = '<img src="Views/uploads/' + data.hero_image + '" alt="Preview">';
        preview.classList.remove("empty");
    } else {
        preview.innerHTML = "Tidak ada gambar dipilih";
        preview.classList.add("empty");
    }
    
    // Buka modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Konfirmasi delete
function confirmAboutDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?\nTindakan ini tidak dapat dibatalkan.')) {
        document.getElementById("delete_about_id").value = id;
        document.getElementById("deleteAboutForm").submit();
    }
}

// Preview gambar saat dipilih
function previewAboutImage(input) {
    const preview = document.getElementById("aboutImagePreview");
    if (!preview) return;
    
    if (input.files && input.files[0]) {
        // Validasi ukuran file (max 2MB)
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            input.value = '';
            preview.innerHTML = "Tidak ada gambar dipilih";
            preview.classList.add("empty");
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
            preview.classList.remove("empty");
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = "Tidak ada gambar dipilih";
        preview.classList.add("empty");
    }
}

// Klik di luar modal → tutup modal
window.addEventListener('click', function(e) {
    const modal = document.getElementById("modalAboutForm");
    if (e.target === modal) {
        closeAboutModal();
    }
});

// Validasi form sebelum submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('aboutUsForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const heroTitle = document.getElementById('about_hero_title').value.trim();
            const heroSubtitle = document.getElementById('about_hero_subtitle').value.trim();
            
            if (!heroTitle || !heroSubtitle) {
                e.preventDefault();
                alert('Hero Title dan Subtitle wajib diisi!');
                return false;
            }
        });
    }
});
</script>

<script src="/Views/js/admin_berita.js"></script>
</body>
</html>