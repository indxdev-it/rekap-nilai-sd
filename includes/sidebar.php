<?php
// includes/sidebar.php
$role = $_SESSION['role'] ?? '';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <div class="sidebar-header">
    <!-- Posisi Gambar: Logo Sekolah di Kiri Atas Sidebar Navigasi -->
    <img src="../assets/img/logo/logo-sd.png" alt="Logo Sekolah" class="sidebar-logo">
    <div class="sidebar-title">
      <h3>SDN 1 Benua</h3>
      <span>Rekap Nilai</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <ul>
      <?php if ($role === 'admin'): ?>
        <!-- Menu Khusus Role Admin -->
        <li class="<?= $current_page === 'dashboard-admin.php' ? 'active' : ''; ?>">
          <a href="dashboard-admin.php">Dashboard Admin</a>
        </li>
        <li class="<?= $current_page === 'data-siswa.php' ? 'active' : ''; ?>">
          <a href="data-siswa.php">Kelola Data Siswa</a>
        </li>
        <li class="<?= $current_page === 'kelola-pengguna.php' ? 'active' : ''; ?>">
          <a href="kelola-pengguna.php">Kelola Pengguna</a>
        </li>
        <li class="<?= $current_page === 'kelola-akademik.php' ? 'active' : ''; ?>">
          <a href="kelola-akademik.php">Data Kelas & Mapel</a>
        </li>

      <?php elseif ($role === 'guru'): ?>
        <!-- Menu Khusus Role Guru -->
        <li class="<?= $current_page === 'dashboard-guru.php' ? 'active' : ''; ?>">
          <a href="dashboard-guru.php">Dashboard Guru</a>
        </li>
        <li class="<?= $current_page === 'input-nilai.php' ? 'active' : ''; ?>">
          <a href="input-nilai.php">Input Nilai Siswa</a>
        </li>
        <li class="<?= $current_page === 'rekap-nilai.php' ? 'active' : ''; ?>">
          <a href="rekap-nilai.php">Rekapitulasi Nilai</a>
        </li>
        <li class="<?= $current_page === 'cetak-rapor.php' ? 'active' : ''; ?>">
          <a href="cetak-rapor.php">Cetak Rapor</a>
        </li>

      <?php elseif ($role === 'kepala_sekolah'): ?>
        <!-- Menu Khusus Role Kepala Sekolah -->
        <li class="<?= $current_page === 'dashboard-kepsek.php' ? 'active' : ''; ?>">
          <a href="dashboard-kepsek.php">Dashboard Monitoring</a>
        </li>
        <li class="<?= $current_page === 'rekap-nilai.php' ? 'active' : ''; ?>">
          <a href="rekap-nilai.php">Laporan Rekap Nilai</a>
        </li>
        <li class="<?= $current_page === 'kinerja-guru.php' ? 'active' : ''; ?>">
          <a href="kinerja-guru.php">Monitoring Kinerja Guru</a>
        </li>
      <?php endif; ?>

      <!-- Menu Umum Seluruh Role -->
      <li class="<?= $current_page === 'profil.php' ? 'active' : ''; ?>">
        <a href="profil.php">Profil Saya</a>
      </li>
    </ul>
  </nav>

  <div class="sidebar-footer">
    <a href="../process/logout.php" class="btn-logout">Keluar</a>
  </div>
</aside>