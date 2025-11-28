
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Hubungi Kami - Portal Berita</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Views/css/kontak.css">
</head>

<body>
  <!-- HEADER -->
  <?php include 'header_user.php'; ?>

  <!-- MAIN CONTENT -->
  <main class="content">
    
    <!-- CONTACT HEADER -->
    <section class="contact-header">
      <h1><i class="fas fa-envelope"></i> Hubungi Kami</h1>
      <p>Kami siap mendengar kritik, saran, dan pertanyaan Anda</p>
    </section>

    <div class="contact-wrapper">
      
      <!-- INFO KONTAK -->
      <div class="contact-info-section">
        <div class="info-card">
          <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <h3>Alamat Kantor</h3>
            <p>Jl. Soekarno Hatta No.15<br>Lowokwaru, Malang<br>Jawa Timur, Indonesia</p>
          </div>
        </div>

        <div class="info-card">
          <div class="info-icon"><i class="fas fa-phone"></i></div>
          <div>
            <h3>Telepon</h3>
            <p>+62 341 1234567<br>+62 341 7654321</p>
          </div>
        </div>

        <div class="info-card">
          <div class="info-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <h3>Email</h3>
            <p>editorial@globaltime.com<br>info@globaltime.com</p>
          </div>
        </div>

        <div class="info-card">
          <div class="info-icon"><i class="fas fa-clock"></i></div>
          <div>
            <h3>Jam Operasional</h3>
            <p>Senin - Jumat: 08:00 - 17:00<br>Sabtu: 08:00 - 14:00<br>Minggu: Tutup</p>
          </div>
        </div>
      </div>

      <!-- FORM KONTAK -->
      <div class="contact-form-section">
        <h2>
          <?php if ($edit_mode): ?>
            <i class="fas fa-edit"></i> Edit Pesan
          <?php else: ?>
            <i class="fas fa-paper-plane"></i> Kirim Pesan
          <?php endif; ?>
        </h2>

        <!-- ✅ PESAN SUKSES -->
        <?php if (!empty($success)): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <p><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endif; ?>

        <!-- ✅ PESAN ERROR -->
        <?php if (!empty($error)): ?>
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endif; ?>

        <!-- ✅ FORM -->
        <form method="POST" id="contactForm">
          <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= intval($edit_data['id']) ?>">
          <?php endif; ?>

          <div class="form-group">
            <label for="name"><i class="fas fa-user"></i> Nama Lengkap</label>
            <input type="text" id="name" name="name" required minlength="3" maxlength="100"
              placeholder="Masukkan nama lengkap Anda"
              value="<?= $edit_mode ? htmlspecialchars($edit_data['nama'], ENT_QUOTES, 'UTF-8') : '' ?>">
          </div>

          <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" required 
              placeholder="nama@email.com"
              value="<?= $edit_mode ? htmlspecialchars($edit_data['email'], ENT_QUOTES, 'UTF-8') : '' ?>">
          </div>

          <div class="form-group">
            <label for="subject"><i class="fas fa-tag"></i> Subjek</label>
            <input type="text" id="subject" name="subject" required minlength="5" maxlength="200"
              placeholder="Subjek pesan"
              value="<?= $edit_mode ? htmlspecialchars($edit_data['subjek'], ENT_QUOTES, 'UTF-8') : '' ?>">
          </div>

          <div class="form-group">
            <label for="message"><i class="fas fa-comment"></i> Pesan</label>
            <textarea id="message" name="message" rows="6" required minlength="10"
              placeholder="Tulis pesan Anda di sini..."><?= $edit_mode ? htmlspecialchars($edit_data['pesan'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" name="submit_message" value="1" class="btn-submit">
              <i class="fas fa-paper-plane"></i> 
              <?= $edit_mode ? 'Update Pesan' : 'Kirim Pesan' ?>
            </button>
            <?php if ($edit_mode): ?>
              <a href="?page=kontak" class="btn-cancel">
                <i class="fas fa-times"></i> Batal
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- ✅ RIWAYAT PESAN -->
    <?php if (!empty($messages)): ?>
      <section class="messages-history">
        <h2><i class="fas fa-history"></i> Riwayat Pesan Anda</h2>
        <div style="overflow-x:auto;">
          <table class="messages-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($messages as $msg):
                ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($msg['tanggal'])) ?></td>
                  <td><strong><?= htmlspecialchars($msg['subjek'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                  <td><?= htmlspecialchars(substr($msg['pesan'], 0, 50), ENT_QUOTES, 'UTF-8') ?>...</td>
                  
                  <!-- ✅ STATUS BADGE -->
                  <td>
                    <span class="status-badge status-<?= $msg['status'] ?>">
                      <?php 
                        if ($msg['status'] === 'baru') echo '🆕 Baru';
                        elseif ($msg['status'] === 'dibaca') echo '👁️ Dibaca';
                        else echo '✓ Selesai';
                      ?>
                    </span>
                  </td>
                  
                  <!-- ✅ AKSI -->
                  <td>
                    <div class="action-buttons">
                      <!-- ✅ EDIT HANYA JIKA STATUS = BARU -->
                      <?php if ($msg['status'] === 'baru'): ?>
                        <a href="?page=kontak&edit=<?= intval($msg['id']) ?>" class="btn-edit" title="Edit">
                          <i class="fas fa-edit"></i> Edit
                        </a>
                      <?php else: ?>
                        <button class="btn-edit-disabled" disabled title="Hanya bisa edit pesan berstatus Baru">
                          <i class="fas fa-edit"></i> Edit
                        </button>
                      <?php endif; ?>

                      <!-- ✅ DELETE KAPAN SAJA -->
                      <a href="?page=kontak&delete=<?= intval($msg['id']) ?>" class="btn-delete"
                        onclick="return confirm('Yakin ingin menghapus pesan ini?')" title="Hapus">
                        <i class="fas fa-trash"></i> Hapus
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    <?php else: ?>
      <section class="messages-history">
        <div class="no-messages">
          <i class="fas fa-inbox" style="font-size: 48px;"></i>
          <p>Belum ada riwayat pesan</p>
        </div>
      </section>
    <?php endif; ?>
    
  </main>

  <!-- FOOTER -->
  <?php include 'footer_user.php'; ?>

</body>

</html>