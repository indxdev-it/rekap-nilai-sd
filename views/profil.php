<?php
// views/profil.php
$page_title = "Profil Saya";
$page_css   = "profil.css";

require_once '../includes/header.php';
require_once '../includes/session.php';
require_once '../config/database.php';

$user_id = $_SESSION['user_id'];

// Ambil data detail profil dari database
$q = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_pengguna = '$user_id' LIMIT 1");
$u = mysqli_fetch_assoc($q);

// Pesan Notifikasi Sukses/Gagal
$msg_success = $_GET['success'] ?? '';
$msg_error   = $_GET['error'] ?? '';
?>

<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>

  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>

    <section class="content-body">
      <?php if (!empty($msg_success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg_success); ?></div>
      <?php endif; ?>
      <?php if (!empty($msg_error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($msg_error); ?></div>
      <?php endif; ?>

      <div class="profile-card">
        <div class="profile-header">
          <!-- Posisi Gambar: Foto Profil Utama pada Halaman Profil Pengguna -->
          <img src="../assets/img/avatars/<?= htmlspecialchars($u['foto_profil']); ?>" alt="Foto Profil" class="profile-avatar-lg">
          <div class="profile-header-info">
            <h2><?= htmlspecialchars($u['nama_pengguna']); ?></h2>
            <p>NIP: <?= htmlspecialchars($u['nip'] ?? '-'); ?></p>
            <span class="badge-role"><?= strtoupper(htmlspecialchars($u['role'])); ?></span>
          </div>
        </div>

        <hr class="divider">

        <form action="../process/proses-profil.php" method="POST" enctype="multipart/form-data" class="profile-form">
          <div class="form-grid">
            <div class="form-group">
              <label for="nama_pengguna">Nama Lengkap</label>
              <input type="text" id="nama_pengguna" name="nama_pengguna" value="<?= htmlspecialchars($u['nama_pengguna']); ?>" required>
            </div>

            <div class="form-group">
              <label for="email">Alamat Email</label>
              <input type="email" id="email" name="email" value="<?= htmlspecialchars($u['email']); ?>" required>
            </div>

            <div class="form-group">
              <label for="no_hp">No. Handphone / WA</label>
              <input type="text" id="no_hp" name="no_hp" value="<?= htmlspecialchars($u['no_hp'] ?? ''); ?>">
            </div>

            <div class="form-group">
              <label for="tanggal_lahir">Tanggal Lahir</label>
              <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?= htmlspecialchars($u['tanggal_lahir'] ?? ''); ?>">
            </div>

            <div class="form-group full-width">
              <label for="alamat">Alamat Domisili</label>
              <textarea id="alamat" name="alamat" rows="3"><?= htmlspecialchars($u['alamat'] ?? ''); ?></textarea>
            </div>

            <div class="form-group full-width">
              <label for="foto_profil">Ubah Foto Profil</label>
              <input type="file" id="foto_profil" name="foto_profil" accept="image/*">
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan Profil</button>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>

<?php require_once '../includes/footer.php'; ?>