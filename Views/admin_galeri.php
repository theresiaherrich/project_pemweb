

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Admin - Kelola Galeri Video</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Views/css/admin_berita.css">
</head>

<body>
    <?php include 'admin_header.php'; ?>

    <!-- MAIN -->
    <main class="main-content">

        <!-- ALERT MESSAGE -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>

        <div class="content-header">
            <h2><i class="fas fa-video"></i> Kelola Galeri Video</h2>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="fas fa-plus"></i> Tambah Video
            </button>
        </div>

        <!-- TABEL VIDEO -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thumbnail</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Durasi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($videos)): ?>
                        <tr><td colspan="7" class="text-center">Tidak ada data video.</td></tr>
                    <?php else: ?>
                        <?php foreach ($videos as $video): ?>
                            <tr>
                                <td><?php echo $video['id']; ?></td>

                                <td>
                                    <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>" 
                                          class="table-img"
                                          onerror="this.src='https://via.placeholder.com/120x90?text=No+Image';">
                                </td>

                                <td>
                                    <strong><?php echo htmlspecialchars($video['title']); ?></strong><br>
                                    <small style="color:#888;">
                                        <i class="fas fa-link"></i>
                                        <?php echo substr(htmlspecialchars($video['url']), 0, 40); ?>...
                                    </small>
                                </td>

                                <td>
                                    <span class="badge"><?php echo htmlspecialchars($video['category']); ?></span>
                                </td>

                                <td><?php echo htmlspecialchars($video['duration']); ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($video['date'])); ?></td>

                                <td>
                                    <button class="btn-icon btn-edit"
                                        onclick='openEditModal(<?php echo json_encode($video); ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn-icon btn-delete"
                                        onclick="confirmDelete(<?php echo $video['id']; ?>)">
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

    <!-- MODAL -->
    <div class="modal" id="modalForm">
        <div class="modal-content">

            <div class="modal-header">
                <h3 id="modalTitle"><i class="fas fa-plus-circle"></i> Tambah Video</h3>
                <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
            </div>

            <form id="videoForm" method="POST">
                <input type="hidden" name="id_video" id="id_video">

                <div class="form-group">
                    <label>Judul Video</label>
                    <input type="text" name="title" id="title" required>
                </div>

                <div class="form-group">
                    <label>URL YouTube</label>
                    <input type="url" name="url" id="url" required>
                    <button type="button" onclick="generateThumbnail()" class="btn btn-secondary" style="margin-top:8px;">
                        <i class="fas fa-magic"></i> Generate Thumbnail
                    </button>
                </div>

                <div class="form-group">
                    <label>Thumbnail</label>
                    <input type="url" name="thumbnail" id="thumbnail" required>
                    <img id="thumbnail-img" style="width:150px;margin-top:10px;display:none;" />
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category" id="category" required>
                        <option value="">Pilih Kategori</option>

                        <?php foreach ($kategori_list as $kat): ?>
                            <option value="<?= $kat['nama_kategori']; ?>">
                                <?= $kat['nama_kategori']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Durasi</label>
                    <input type="text" name="duration" id="duration" required placeholder="05:30">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="closeModal()">Batal</button>
                    <button type="submit" id="saveButton" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>

    <!-- FORM DELETE -->
    <form id="deleteForm" method="POST" style="display:none;">
        <input type="hidden" name="id_video" id="delete_id">
        <input type="hidden" name="delete_video" value="1">
    </form>

<script>
/* ====== OPEN MODAL TAMBAH ====== */
function openModal() {
    document.getElementById("modalTitle").innerHTML = "<i class='fas fa-plus'></i> Tambah Video";
    document.getElementById("videoForm").reset();
    document.getElementById("id_video").value = "";
    document.getElementById("saveButton").name = "add_video";

    document.getElementById("thumbnail-img").style.display = "none";
    document.getElementById("modalForm").classList.add("active");
}

/* ====== OPEN MODAL EDIT ====== */
function openEditModal(video) {
    document.getElementById("modalTitle").innerHTML = "<i class='fas fa-edit'></i> Edit Video";

    document.getElementById("id_video").value = video.id;
    document.getElementById("title").value = video.title;
    document.getElementById("url").value = video.url;
    document.getElementById("thumbnail").value = video.thumbnail;
    document.getElementById("category").value = video.category;
    document.getElementById("duration").value = video.duration;

    document.getElementById("thumbnail-img").src = video.thumbnail;
    document.getElementById("thumbnail-img").style.display = "block";

    document.getElementById("saveButton").name = "update_video";
    document.getElementById("modalForm").classList.add("active");
}

/* ====== TUTUP MODAL ====== */
function closeModal() {
    document.getElementById("modalForm").classList.remove("active");
}

/* ====== DELETE ====== */
function confirmDelete(id) {
    if (confirm("Yakin ingin menghapus video ini?")) {
        document.getElementById("delete_id").value = id;
        document.getElementById("deleteForm").submit();
    }
}

/* ====== GENERATE THUMBNAIL YouTube ====== */
function generateThumbnail() {
    let url = document.getElementById("url").value;
    let match = url.match(/(?:v=|\/)([0-9A-Za-z_-]{11})/);

    if (!match) {
        alert("URL YouTube tidak valid!");
        return;
    }

    let videoId = match[1];
    let thumbnail = "https://img.youtube.com/vi/" + videoId + "/hqdefault.jpg";

    document.getElementById("thumbnail").value = thumbnail;
    document.getElementById("thumbnail-img").src = thumbnail;
    document.getElementById("thumbnail-img").style.display = "block";
}
</script>

</body>
</html>
