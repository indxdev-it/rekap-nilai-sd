<?php
// views/dashboard-guru.php
$page_title = "Dashboard Guru";

require_once '../includes/header.php';
require_once '../includes/session.php';
require_once '../config/database.php';

check_role(['guru']);

$id_guru = $_SESSION['user_id'];

// Cari kelas yang diampu guru ini
$q_kelas = mysqli_query($conn, "SELECT * FROM kelas WHERE id_guru = '$id_guru' LIMIT 1");
$kelas_diampu = mysqli_fetch_assoc($q_kelas);

$total_siswa_kelas = 0;
if ($kelas_diampu) {
    $id_kelas = $kelas_diampu['id_kelas'];
    $q_siswa  = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa WHERE id_kelas = '$id_kelas' AND status_siswa = 'aktif'");
    $total_siswa_kelas = mysqli_fetch_assoc($q_siswa)['total'];
}
?>

<link rel="stylesheet" href="../assets/css/dashboard-guru.css"></link>
<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>

  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>

    <section class="content-body">
      <div class="welcome-card">
        <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?>!</h1>
        <br>
        <p>
          <?= $kelas_diampu ? 'Anda bertugas sebagai Pengampu <strong>' . htmlspecialchars($kelas_diampu['nama_kelas']) . '</strong>' : 'Status: Belum ditugaskan pada kelas tertentu.'; ?>
        </p>
      </div>

      <?php if ($kelas_diampu): ?>
        <div class="stats-grid">
          <div class="stat-card">
            <span class="stat-label">Kelas Diampu</span>
            <span class="stat-value"><?= htmlspecialchars($kelas_diampu['nama_kelas']); ?></span>
          </div>
          <div class="stat-card">
            <span class="stat-label">Jumlah Siswa Active</span>
            <span class="stat-value"><?= $total_siswa_kelas; ?> Siswa</span>
          </div>
        </div>

        <div class="quick-actions">
          <h3>Menu Kerja Guru</h3>
          <div class="actions-grid">
            <a href="input-nilai.php" class="action-btn">Input Nilai Kelas</a>
            <a href="rekap-nilai.php" class="action-btn">Lihat Rekapitulasi Kelas</a>
            <a href="cetak-rapor.php" class="action-btn">Cetak Rapor Siswa</a>
          </div>
        </div>
      <?php else: ?>
        <div class="alert alert-info">
          Silakan hubungi Administrator jika penugasan kelas Anda belum dikonfigurasi.
        </div>
      <?php endif; ?>
    </section>
  </main>
</div>

<?php require_once '../includes/footer.php'; ?>