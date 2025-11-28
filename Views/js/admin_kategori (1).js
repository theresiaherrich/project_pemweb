
function openModal() {
  document.getElementById("kat_id").value = "";
  document.getElementById("nama_kategori").value = "";
  document.getElementById("modalTitle").innerHTML = '<i class="fas fa-plus"></i> Tambah Kategori';
  document.getElementById("modalKategori").style.display = 'flex';
}

function editKategori(id, nama) {
  document.getElementById("kat_id").value = id;
  document.getElementById("nama_kategori").value = nama;
  document.getElementById("modalTitle").innerHTML = '<i class="fas fa-edit"></i> Edit Kategori';
  document.getElementById("modalKategori").style.display = 'flex';
}

function closeModal() {
  document.getElementById("modalKategori").style.display = 'none';
}

function deleteKategori(id, nama) {
  if (confirm("Hapus kategori '" + nama + "'?")) {
    window.location = "?page=admin_kategori&action=delete&id=" + id;
  }
}

