<?php
// views/dashboard-kepsek.php
$page_title = "Dashboard Monitoring";
$page_css   = "dashboard-kepsek.css";

require_once '../includes/header.php';
require_once '../includes/session.php';
require_once '../config/database.php';

check_role(['kepala_sekolah']);

// Ambil ringkasan statistik sekolah untuk Kepala Sekolah
$q_tot_siswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa WHERE status_siswa = 'aktif'");
$total_siswa = mysqli_fetch_assoc($q_tot_siswa)['total'];

$q_tot_guru = mysqli_query($conn, "SELECT COUNT(*) as total FROM pengguna WHERE role = 'guru'");
$total_guru = mysqli_fetch_assoc($q_tot_guru)['total'];

$q_tot_kelas = mysqli_query($conn, "SELECT COUNT(*) as total FROM kelas");
$total_kelas = mysqli_fetch_assoc($q_tot_kelas)['total'];

// Ambil data progres pengisian nilai per kelas
$q_progres = mysqli_query($conn, "
  SELECT k.id_kelas, k.nama_kelas, p.nama_pengguna as nama_guru, 
         (SELECT COUNT(DISTINCT id_siswa) FROM siswa WHERE siswa.id_kelas = k.id_kelas) as jml_siswa,
         COUNT(n.id_nilai) as total_input
  FROM kelas k
  LEFT JOIN pengguna p ON k.id_guru = p.id_pengguna
  LEFT JOIN siswa s ON k.id_kelas = s.id_kelas
  LEFT JOIN nilai n ON s.id_siswa = n.id_siswa
  GROUP BY k.id_kelas
");
?>

<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>

  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>

    <section class="content-body">
      <!-- Kartu Sambutan Modern -->
      <div class="welcome-card">
        <h1>Monitoring Akademik Sekolah</h1>
        <p>Selamat datang, Kepala Sekolah. Pantau progres pengisian nilai guru dan rekapitulasi belajar siswa secara menyeluruh (Mode <em>View Only</em>).</p>
      </div>

      <!-- Kartu Ringkasan Statistik Sekolah -->
      <div class="stats-grid">
        <div class="stat-card">
          <span class="stat-label">Total Siswa Aktif</span>
          <span class="stat-value"><?= $total_siswa; ?></span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Total Guru Pengampu</span>
          <span class="stat-value"><?= $total_guru; ?></span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Jumlah Kelas</span>
          <span class="stat-value"><?= $total_kelas; ?></span>
        </div>
      </div>

      <!-- Tabel Monitoring Status Pengisian Nilai -->
      <div class="table-container">
        <h3>Status Pengisian & Progres Nilai Per Kelas</h3>
        <table class="data-table">
          <thead>
            <tr>
              <th>Kelas</th>
              <th>Guru Pengampu</th>
              <th>Jumlah Siswa</th>
              <th>Status Entri Nilai</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($q_progres) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($q_progres)): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($row['nama_kelas']); ?></strong></td>
                  <td><?= htmlspecialchars($row['nama_guru'] ?? 'Belum Ditugaskan'); ?></td>
                  <td><?= $row['jml_siswa']; ?> Siswa</td>
                  <td>
                    <?php if ($row['total_input'] > 0): ?>
                      <span class="badge badge-success">Sudah Terisi (<?= $row['total_input']; ?> Data)</span>
                    <?php else: ?>
                      <span class="badge badge-warning">Belum Ada Nilai</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="rekap-nilai.php?kelas=<?= $row['id_kelas']; ?>" class="btn-link">Lihat Rekap</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">Belum ada data kelas terdaftar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<?php require_once '../includes/footer.php'; ?>