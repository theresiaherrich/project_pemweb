// ======================================================
// =============== PROFILE DROPDOWN ======================
// ======================================================
function toggleProfileMenu(event) {
  event.preventDefault();
  const dropdown = document.querySelector('.profile-dropdown');
  dropdown.classList.toggle('active');
}

document.addEventListener('click', function(e) {
  const dropdown = document.querySelector('.profile-dropdown');
  const trigger = document.querySelector('.profile-btn');
  if (!dropdown) return;
  if (!dropdown.contains(e.target) && e.target !== trigger) {
    dropdown.classList.remove('active');
  }
});

// ======================================================
// ======================= LOGOUT =======================
// ======================================================
function logout() {
  if (confirm("Yakin ingin logout?")) {
    window.location.href = "index.php?page=logout";
  }
}

// ======================================================
// ==================== OPEN MODAL ======================
// ======================================================
function openModal() {
  const modal = document.getElementById('modalForm');
  const form = modal.querySelector('form');

  form.reset();

  document.getElementById('id_user').value = '';
  document.getElementById('old_photo').value = '';

  const preview = document.getElementById('imagePreview');
  preview.innerHTML = '<div class="image-preview empty">Tidak ada gambar</div>';

  document.getElementById('modalTitle').innerHTML =
    '<i class="fas fa-user-plus"></i> Tambah User';

  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  const modal = document.getElementById('modalForm');
  modal.classList.remove('active');
  document.body.style.overflow = 'auto';
}

// Close modal if click backdrop
document.addEventListener('click', function(e) {
  const modal = document.getElementById('modalForm');
  if (!modal) return;
  if (modal.classList.contains('active') && e.target === modal) {
    closeModal();
  }
});

// ESC to close
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') closeModal();
});

// ======================================================
// ==================== EDIT MODAL AJAX =================
// ======================================================
function openEditModal(id) {
  fetch(`?page=admin_user&ajax=edit&id=${id}`)
    .then(res => {
      if (!res.ok) throw new Error('User tidak ditemukan');
      return res.json();
    })
    .then(data => {
      document.getElementById('id_user').value = data.id;
      document.getElementById('name').value = data.name;
      document.getElementById('username').value = data.username;
      document.getElementById('email').value = data.email;
      document.getElementById('phone').value = data.phone || '';
      document.getElementById('role').value = data.role;

      document.getElementById('old_photo').value = data.photo || '';

      // Preview image
      const preview = document.getElementById('imagePreview');
      preview.innerHTML = '';

      if (data.photo) {
        const img = document.createElement('img');
        img.src = `/project/${data.photo}`;
        img.alt = 'Preview';
        preview.appendChild(img);
      } else {
        preview.innerHTML =
          '<div class="image-preview empty">Tidak ada gambar</div>';
      }

      document.getElementById('modalTitle').innerHTML =
        '<i class="fas fa-edit"></i> Edit User';

      const modal = document.getElementById('modalForm');
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    })
    .catch(err => {
      alert(err.message || 'Terjadi kesalahan saat memuat data user.');
      console.error(err);
    });
}

// ======================================================
// ===================== IMAGE PREVIEW ==================
// ======================================================
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
    preview.innerHTML =
      '<div class="image-preview empty">Tidak ada gambar</div>';
  }
}

// ======================================================
// ================= DELETE CONFIRMATION ================
// ======================================================
function confirmDelete(id) {
  const message =
    `Apakah Anda yakin ingin menghapus user ini?\n` +
    `Data yang dihapus tidak dapat dikembalikan!`;

  if (confirm(message)) {
    window.location.href = `?page=admin_user&action=delete&id=${id}`;
  }
}

// ======================================================
// ==================== FORM VALIDATION =================
// ======================================================
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('#modalForm form');
  if (!form) return;

  form.addEventListener('submit', function (event) {
    const name = document.getElementById('name').value.trim();
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const role = document.getElementById('role').value;

    if (!name) { alert('Nama harus diisi!'); event.preventDefault(); return; }
    if (!username) { alert('Username harus diisi!'); event.preventDefault(); return; }
    if (!email) { alert('Email harus diisi!'); event.preventDefault(); return; }
    if (!role) { alert('Role harus dipilih!'); event.preventDefault(); return; }
  });
});

// ======================================================
// ==================== AUTO HIDE ALERT =================
// ======================================================
document.addEventListener('DOMContentLoaded', function() {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.animation = 'slideUp 0.3s ease';
      setTimeout(() => alert.remove(), 300);
    }, 5000);
  });
});
