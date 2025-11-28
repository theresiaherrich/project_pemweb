// ===== PROFILE DROPDOWN =====
function toggleProfileMenu(event) {
  event.preventDefault();
  const dropdown = document.querySelector('.profile-dropdown');
  dropdown.classList.toggle('active');
}

document.addEventListener('click', function(e) {
  const dropdown = document.querySelector('.profile-dropdown');
  const trigger = document.querySelector('.profile-btn');
  if (!dropdown) return;
  if (!dropdown.contains(e.target) && e.target !== trigger) dropdown.classList.remove('active');
});

// ===== LOGOUT =====
function logout() {
  if (confirm("Yakin ingin logout?")) {
    window.location.href = "index.php?page=logout";
  }
}

// ===== OPEN MODAL TAMBAH =====
function openModal() {
  const modal = document.getElementById('modalForm');
  const form = document.getElementById('formBerita');

  form.reset();
  document.getElementById('id_berita').value = '';
  document.getElementById('old_image').value = '';
  const preview = document.getElementById('imagePreview');
  preview.innerHTML = '<div class="image-preview empty">Tidak ada gambar</div>';

  document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Tambah Berita';
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  const modal = document.getElementById('modalForm');
  modal.classList.remove('active');
  document.body.style.overflow = 'auto';
}

// Close modal when clicking the backdrop ONLY
document.addEventListener('click', function(e) {
  const modal = document.getElementById('modalForm');
  if (!modal) return;
  if (modal.classList.contains('active') && e.target === modal) closeModal();
});

// ESC to close
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') closeModal();
});

// ===== EDIT MODAL (AJAX) =====
function openEditModal(id) {
  fetch(`?page=admin_berita&ajax=edit&id=${id}`)
    .then(res => {
      if (!res.ok) throw new Error('Berita tidak ditemukan');
      return res.json();
    })
    .then(data => {
      // populate form
      document.getElementById('id_berita').value = data.id_berita;
      document.getElementById('judul').value = data.judul;
      document.getElementById('kategori_id').value = data.kategori_id;
      document.getElementById('isi_singkat').value = data.isi_singkat;
      document.getElementById('isi_lengkap').value = data.isi_lengkap;
      document.getElementById('old_image').value = data.gambar || '';

      const preview = document.getElementById('imagePreview');
      preview.innerHTML = '';
      if (data.gambar) {
        const img = document.createElement('img');
        img.src = `/project/${data.gambar}`;
        img.alt = 'Preview';
        preview.appendChild(img);
      } else {
        preview.innerHTML = '<div class="image-preview empty">Tidak ada gambar</div>';
      }

      document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Berita';
      const modal = document.getElementById('modalForm');
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    })
    .catch(err => {
      alert(err.message || 'Terjadi kesalahan saat mengambil data.');
      console.error(err);
    });
}

// ===== IMAGE PREVIEW =====
function previewImage(input) {
  const preview = document.getElementById('imagePreview');
  preview.innerHTML = '';

  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = document.createElement('img');
      img.src = e.target.result;
      preview.appendChild(img);
    };
    reader.readAsDataURL(input.files[0]);
  } else {
    preview.innerHTML = '<div class="image-preview empty">Tidak ada gambar</div>';
  }
}

// ===== DELETE CONFIRMATION =====
function confirmDelete(id, judul) {
  const message = `Apakah Anda yakin ingin menghapus berita:\n\n"${judul}"?\n\nData yang dihapus tidak dapat dikembalikan!`;
  if (confirm(message)) {
    window.location.href = `?page=admin_berita&action=delete&id=${id}`;
  }
}

// ===== FORM VALIDATION =====
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formBerita');
  if (form) {
    form.addEventListener('submit', function(event) {
      const judul = document.getElementById('judul').value.trim();
      const kategori = document.getElementById('kategori_id').value;
      const isiSingkat = document.getElementById('isi_singkat').value.trim();
      const isiLengkap = document.getElementById('isi_lengkap').value.trim();

      if (!judul) { alert('Judul berita harus diisi!'); event.preventDefault(); return false; }
      if (!kategori) { alert('Kategori harus dipilih!'); event.preventDefault(); return false; }
      if (!isiSingkat) { alert('Ringkasan berita harus diisi!'); event.preventDefault(); return false; }
      if (isiSingkat.length > 200) { alert('Ringkasan maksimal 200 karakter!'); event.preventDefault(); return false; }
      if (!isiLengkap) { alert('Isi berita harus diisi!'); event.preventDefault(); return false; }

      // When editing, ensure old_image value kept if no new upload (already handled server-side)
    });
  }

  // Character counter for isi_singkat
  const isiSingkat = document.getElementById('isi_singkat');
  if (isiSingkat) {
    const counterDiv = document.createElement('div');
    counterDiv.style.cssText = 'font-size: 12px; color: #6c757d; margin-top: 5px;';
    isiSingkat.parentElement.appendChild(counterDiv);

    function updateCounter() {
      const length = isiSingkat.value.length;
      counterDiv.textContent = `${length}/200 karakter`;
      counterDiv.style.color = length > 200 ? '#dc3545' : '#6c757d';
    }
    isiSingkat.addEventListener('input', updateCounter);
    updateCounter();
  }
});

// ===== AUTO HIDE ALERT =====
document.addEventListener('DOMContentLoaded', function() {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.animation = 'slideUp 0.3s ease';
      setTimeout(() => alert.remove(), 300);
    }, 5000);
  });
});

// TABLE SORTING, SCROLL TO TOP, etc. (kehilangan tidak diperlukan — kamu bisa re-add jika mau)
