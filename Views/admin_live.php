
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Kelola Live Streaming - Global Time Admin</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/admin_berita.css">
</head>
<body>

  <?php include 'admin_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      
      <?php if ($success_message): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i>
          <?php 
            if ($success_message == 'created') echo 'Data berhasil ditambahkan!';
            elseif ($success_message == 'updated') echo 'Data berhasil diperbarui!';
            elseif ($success_message == 'deleted') echo 'Data berhasil dihapus!';
          ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
          <i class="fas fa-exclamation-circle"></i>
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?php echo $error; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- ==================== FORM SECTION ==================== -->
      <?php if (isset($_GET['action']) && ($_GET['action'] == 'create' || $_GET['action'] == 'edit')): ?>
        <?php 
          $form_type = isset($_GET['type']) ? $_GET['type'] : 'live';
          $is_edit = $_GET['action'] == 'edit';
        ?>
        
        <!-- MODAL FORM -->
        <div id="modalForm" class="modal active">
          <div class="modal-content">
            <div class="modal-header">
              <h3 id="modalTitle">
                <i class="fas fa-<?php echo $form_type == 'live' ? 'broadcast-tower' : 'clock'; ?>"></i>
                <?php echo $is_edit ? 'Edit' : 'Tambah'; ?> <?php echo $form_type == 'live' ? 'Live Stream' : 'Siaran Mendatang'; ?>
              </h3>
              <a href="index.php?page=admin_live" class="modal-close">
                <i class="fas fa-times"></i>
              </a>
            </div>

            <form method="POST" id="formLiveStream" action="index.php?page=admin_live">
              <input type="hidden" name="action" value="<?php echo $is_edit ? 'update' : 'create'; ?>">
              <input type="hidden" name="type" value="<?php echo $form_type; ?>">
              <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
              <?php endif; ?>
              
              <?php if ($form_type == 'live'): ?>
                <!-- FORM LIVE STREAM -->
                <div class="form-group">
                  <label for="title"><i class="fas fa-heading"></i> Judul Stream <span class="required">*</span></label>
                  <input type="text" id="title" name="title" class="form-control" required 
                         value="<?php echo $is_edit ? htmlspecialchars($edit_data['title']) : ''; ?>"
                         placeholder="Contoh: Breaking News: Sidang Kabinet Terbaru">
                </div>

                <div class="form-group">
                  <label for="description"><i class="fas fa-align-left"></i> Deskripsi</label>
                  <textarea id="description" name="description" rows="4"
                            placeholder="Deskripsi singkat tentang live stream ini..."><?php echo $is_edit ? htmlspecialchars($edit_data['description']) : ''; ?></textarea>
                </div>

                <div class="form-grid">
                  <div class="form-group">
                    <label for="category"><i class="fas fa-tag"></i> Kategori</label>
                    <select id="category" name="category">
                      <option value="Berita" <?php echo ($is_edit && $edit_data['category'] == 'Berita') ? 'selected' : ''; ?>>Berita</option>
                      <option value="Olahraga" <?php echo ($is_edit && $edit_data['category'] == 'Olahraga') ? 'selected' : ''; ?>>Olahraga</option>
                      <option value="Politik" <?php echo ($is_edit && $edit_data['category'] == 'Politik') ? 'selected' : ''; ?>>Politik</option>
                      <option value="Ekonomi" <?php echo ($is_edit && $edit_data['category'] == 'Ekonomi') ? 'selected' : ''; ?>>Ekonomi</option>
                      <option value="Teknologi" <?php echo ($is_edit && $edit_data['category'] == 'Teknologi') ? 'selected' : ''; ?>>Teknologi</option>
                      <option value="Hiburan" <?php echo ($is_edit && $edit_data['category'] == 'Hiburan') ? 'selected' : ''; ?>>Hiburan</option>
                      <option value="Lainnya" <?php echo ($is_edit && $edit_data['category'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="channel"><i class="fas fa-broadcast-tower"></i> Channel</label>
                    <input type="text" id="channel" name="channel" 
                           value="<?php echo $is_edit ? htmlspecialchars($edit_data['channel']) : 'Global Time'; ?>"
                           placeholder="Contoh: Global Time Studio">
                  </div>
                </div>

                <div class="form-group">
                  <label for="video_url"><i class="fas fa-video"></i> URL Video Embed <span class="required">*</span></label>
                  <input type="url" id="video_url" name="video_url" required
                         value="<?php echo $is_edit ? htmlspecialchars($edit_data['video_url']) : ''; ?>"
                         placeholder="https://www.youtube.com/embed/VIDEO_ID">
                  <small>Gunakan URL embed dari YouTube, Vimeo, atau platform lainnya</small>
                </div>

                <div class="form-group">
                  <label for="thumbnail"><i class="fas fa-image"></i> URL Thumbnail <span class="required">*</span></label>
                  <input type="url" id="thumbnail" name="thumbnail" required
                         value="<?php echo $is_edit ? htmlspecialchars($edit_data['thumbnail']) : ''; ?>"
                         placeholder="https://example.com/thumbnail.jpg"
                         onchange="previewImage(this)">
                  <div id="imagePreview" class="image-preview">
                    <?php if ($is_edit && $edit_data['thumbnail']): ?>
                      <img src="<?php echo htmlspecialchars($edit_data['thumbnail']); ?>" alt="Preview">
                    <?php else: ?>
                      <div class="image-preview empty">Tidak ada gambar</div>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="form-grid">
                  <div class="form-group">
                    <label for="viewers"><i class="fas fa-eye"></i> Jumlah Viewers</label>
                    <input type="number" id="viewers" name="viewers" 
                           value="<?php echo $is_edit ? $edit_data['viewers'] : '0'; ?>" min="0">
                  </div>

                  <div class="form-group">
                    <label for="live_date"><i class="far fa-calendar"></i> Tanggal Live</label>
                    <input type="date" id="live_date" name="live_date"
                           value="<?php echo $is_edit ? $edit_data['live_date'] : date('Y-m-d'); ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label>
                    <input type="checkbox" id="is_live" name="is_live" value="1" 
                           <?php echo ($is_edit && $edit_data['is_live']) || !$is_edit ? 'checked' : ''; ?>>
                    <i class="fas fa-circle" style="color: #ff0000;"></i> Stream sedang LIVE
                  </label>
                </div>

              <?php else: ?>
                <!-- FORM UPCOMING STREAM -->
                <div class="form-group">
                  <label for="title"><i class="fas fa-heading"></i> Judul Stream <span class="required">*</span></label>
                  <input type="text" id="title" name="title" required
                         value="<?php echo $is_edit ? htmlspecialchars($edit_data['title']) : ''; ?>"
                         placeholder="Contoh: Debat Pilpres 2025">
                </div>

                <div class="form-group">
                  <label for="schedule"><i class="far fa-clock"></i> Jadwal Waktu <span class="required">*</span></label>
                  <input type="text" id="schedule" name="schedule" required
                         value="<?php echo $is_edit ? htmlspecialchars($edit_data['schedule']) : ''; ?>"
                         placeholder="Contoh: 19:00 WIB">
                </div>

                <div class="form-group">
                  <label for="event_date"><i class="far fa-calendar"></i> Tanggal Event <span class="required">*</span></label>
                  <input type="date" id="event_date" name="event_date" required
                         value="<?php echo $is_edit ? $edit_data['event_date'] : ''; ?>">
                </div>

                <div class="form-group">
                  <label for="thumbnail"><i class="fas fa-image"></i> URL Thumbnail <span class="required">*</span></label>
                  <input type="url" id="thumbnail" name="thumbnail" required
                         value="<?php echo $is_edit ? htmlspecialchars($edit_data['thumbnail']) : ''; ?>"
                         placeholder="https://example.com/thumbnail.jpg"
                         onchange="previewImage(this)">
                  <div id="imagePreview" class="image-preview">
                    <?php if ($is_edit && $edit_data['thumbnail']): ?>
                      <img src="<?php echo htmlspecialchars($edit_data['thumbnail']); ?>" alt="Preview">
                    <?php else: ?>
                      <div class="image-preview empty">Tidak ada gambar</div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>

              <div class="modal-footer">
                <a href="index.php?page=admin_live" class="btn btn-secondary">
                  <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> <?php echo $is_edit ? 'Update' : 'Simpan'; ?>
                </button>
              </div>
            </form>
          </div>
        </div>

      <?php else: ?>
        <!-- ==================== TABLE SECTION ==================== -->
        
        <!-- LIVE STREAMS SECTION -->
        <div class="content-header">
          <h2><i class="fas fa-broadcast-tower"></i> Live Streams Aktif</h2>
          <a href="index.php?page=admin_live&action=create&type=live" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Live Stream
          </a>
        </div>

        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Thumbnail</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Channel</th>
                <th>Status</th>
                <th>Viewers</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($live_streams)): ?>
                <tr>
                  <td colspan="9" class="text-center">Belum ada live stream</td>
                </tr>
              <?php else: ?>
                <?php foreach ($live_streams as $stream): ?>
                  <tr>
                    <td><?php echo $stream['id']; ?></td>
                    <td>
                      <img src="<?php echo $stream['thumbnail']; ?>" alt="Thumbnail" class="table-img">
                    </td>
                    <td class="title-cell"><?php echo $stream['title']; ?></td>
                    <td><span class="badge"><?php echo $stream['category']; ?></span></td>
                    <td><?php echo $stream['channel']; ?></td>
                    <td>
                      <?php if ($stream['is_live'] == 1): ?>
                        <span class="badge" style="background: #ff4444; color: white;">
                          <i class="fas fa-circle"></i> LIVE
                        </span>
                      <?php else: ?>
                        <span class="badge">OFFLINE</span>
                      <?php endif; ?>
                    </td>
                    <td class="views">
                      <i class="fas fa-eye"></i> <?php echo number_format($stream['viewers']); ?>
                    </td>
                    <td><?php echo date("d M Y", strtotime($stream['live_date'])); ?></td>
                    <td>
                      <div class="action-buttons">
                        <a href="index.php?page=admin_live&action=edit&id=<?php echo $stream['id']; ?>&type=live" class="btn-icon btn-edit" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete(<?php echo $stream['id']; ?>, 'live', '<?php echo addslashes($stream['title']); ?>')" class="btn-icon btn-delete" title="Hapus">
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

        <!-- UPCOMING STREAMS SECTION -->
        <div class="content-header" style="margin-top: 40px;">
          <h2><i class="far fa-clock"></i> Siaran Mendatang</h2>
          <a href="index.php?page=admin_live&action=create&type=upcoming" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Siaran Mendatang
          </a>
        </div>

        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Thumbnail</th>
                <th>Judul</th>
                <th>Jadwal</th>
                <th>Tanggal Event</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($upcoming_streams)): ?>
                <tr>
                  <td colspan="6" class="text-center">Belum ada siaran mendatang</td>
                </tr>
              <?php else: ?>
                <?php foreach ($upcoming_streams as $upcoming): ?>
                  <tr>
                    <td><?php echo $upcoming['id']; ?></td>
                    <td>
                      <img src="<?php echo $upcoming['thumbnail']; ?>" alt="Thumbnail" class="table-img">
                    </td>
                    <td class="title-cell"><?php echo $upcoming['title']; ?></td>
                    <td><?php echo $upcoming['schedule']; ?></td>
                    <td><?php echo date("d M Y", strtotime($upcoming['event_date'])); ?></td>
                    <td>
                      <div class="action-buttons">
                        <a href="index.php?page=admin_live&action=edit&id=<?php echo $upcoming['id']; ?>&type=upcoming" class="btn-icon btn-edit" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete(<?php echo $upcoming['id']; ?>, 'upcoming', '<?php echo addslashes($upcoming['title']); ?>')" class="btn-icon btn-delete" title="Hapus">
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

      <?php endif; ?>

    </main>
  </div>

  <!-- Load JavaScript -->
  <script>
  function confirmDelete(id, type, title) {
    if (confirm("Yakin ingin menghapus?\n\n" + title)) {
        window.location.href = "index.php?page=admin_live&action=delete&id=" + id + "&type=" + type;
    }
}
</script>
</body>
</html>