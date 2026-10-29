<?php
// views/dashboard-admin.php
$page_title = "Dashboard Admin";

require_once '../includes/header.php';
require_once '../includes/session.php';
require_once '../config/database.php';

check_role(['admin']);

// Mengambil data ringkasan statistik
$q_siswa   = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa WHERE status_siswa = 'aktif'");
$total_siswa = mysqli_fetch_assoc($q_siswa)['total'];

$q_guru    = mysqli_query($conn, "SELECT COUNT(*) as total FROM pengguna WHERE role = 'guru'");
$total_guru = mysqli_fetch_assoc($q_guru)['total'];

$q_kelas   = mysqli_query($conn, "SELECT COUNT(*) as total FROM kelas");
$total_kelas = mysqli_fetch_assoc($q_kelas)['total'];

$q_mapel   = mysqli_query($conn, "SELECT COUNT(*) as total FROM mata_pelajaran");
$total_mapel = mysqli_fetch_assoc($q_mapel)['total'];
?>

<link rel="stylesheet" href="../assets/css/dashboard-admin.css">

<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>

  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>

    <section class="content-body">
      <div class="welcome-card">
        <div class="welcome-text">
          <h1>Selamat Datang, Administrator!</h1>
          <p>Kelola data master, akun pengguna, serta kelancaran akademik SDN 1 Benua Indah.</p>
        </div>
      </div>

      <!-- Kartu Ringkasan Statistik -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Total Siswa Aktif</span>
            <span class="stat-value"><?= $total_siswa; ?></span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Total Guru</span>
            <span class="stat-value"><?= $total_guru; ?></span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Total Kelas</span>
            <span class="stat-value"><?= $total_kelas; ?></span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Mata Pelajaran</span>
            <span class="stat-value"><?= $total_mapel; ?></span>
          </div>
        </div>
      </div>

      <!-- Pintasan Aksi Cepat Admin -->
      <div class="quick-actions">
        <h3>Aksi Cepat Pengelolaan</h3>
        <div class="actions-grid">
          <a href="data-siswa.php" class="action-btn">
            <span>Kelola Data Siswa</span>
          </a>
          <a href="kelola-pengguna.php" class="action-btn">
            <span>Manajemen Akun & Role</span>
          </a>
          <a href="kelola-akademik.php" class="action-btn">
            <span>Kelola Kelas & Mapel</span>
          </a>
          <a href="kenaikan-kelas.php" class="action-btn">
            <span>Proses Kenaikan Kelas Massal</span>
          </a>
        </div>
      </div>
    </section>
  </main>
</div>

<?php require_once '../includes/footer.php'; ?>