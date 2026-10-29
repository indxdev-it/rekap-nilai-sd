<?php
// views/cetak-rapor.php
require_once '../includes/session.php';
require_once '../config/database.php';

check_role(['admin', 'guru', 'kepala_sekolah']);

$id_kelas = isset($_GET['kelas']) ? intval($_GET['kelas']) : 0;
$id_siswa = isset($_GET['siswa']) ? intval($_GET['siswa']) : 0;

// Query Data Sekolah & Kelas
$q_kelas = mysqli_query($conn, "
    SELECT k.*, p.nama_pengguna as nama_wali, p.nip as nip_wali 
    FROM kelas k 
    LEFT JOIN pengguna p ON k.id_guru = p.id_pengguna 
    WHERE k.id_kelas = '$id_kelas' LIMIT 1
");
$kelas_info = mysqli_fetch_assoc($q_kelas);

// Query Data Kepsek
$q_kepsek = mysqli_query($conn, "SELECT nama_pengguna, nip FROM pengguna WHERE role = 'kepala_sekolah' LIMIT 1");
$kepsek = mysqli_fetch_assoc($q_kepsek);

// Query Siswa
$sql_s = "SELECT * FROM siswa WHERE id_kelas = '$id_kelas' AND status_siswa = 'aktif'";
if ($id_siswa > 0) {
    $sql_s .= " AND id_siswa = '$id_siswa'";
}
$q_siswa_list = mysqli_query($conn, $sql_s);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cetak Rapor Siswa - SDN 1 Benua Indah</title>
  <link rel="stylesheet" href="../assets/css/cetak-rapor.css">
</head>
<body onload="window.print()">
  <?php while ($siswa = mysqli_fetch_assoc($q_siswa_list)): ?>
    <div class="rapor-page">
      <!-- Kop Rapor Resmi -->
      <header class="rapor-header">
        <!-- Posisi Gambar: Logo Kiri Atas Kop Rapor Resmi -->
        <img src="../assets/img/logo/logo-sd.png" alt="Logo SD" class="rapor-logo">
        <div class="header-text">
          <h2>LAPORAN HASIL BELAJAR SISWA (RAPOR)</h2>
          <h3>SDN 1 BENUA INDAH</h3>
          <p>Jl. Pendidikan No. 1, Benua Indah, Kec. Sejahtera</p>
        </div>
      </header>

      <hr class="line-double">

      <!-- Identitas Siswa -->
      <table class="identity-table">
        <tr>
          <td><strong>Nama Siswa</strong></td>
          <td>: <?= htmlspecialchars($siswa['nama_siswa']); ?></td>
          <td><strong>Kelas</strong></td>
          <td>: <?= htmlspecialchars($kelas_info['nama_kelas'] ?? '-'); ?></td>
        </tr>
        <tr>
          <td><strong>NIS / NISN</strong></td>
          <td>: <?= htmlspecialchars($siswa['nis']); ?> / <?= htmlspecialchars($siswa['nisn'] ?? '-'); ?></td>
          <td><strong>Semester / TA</strong></td>
          <td>: Genap / 2026/2027</td>
        </tr>
      </table>

      <!-- Tabel Capaian Nilai Akademik -->
      <table class="score-table">
        <thead>
          <tr>
            <th width="5%">No</th>
            <th width="40%">Mata Pelajaran</th>
            <th width="15%">Nilai Akhir</th>
            <th width="15%">Predikat</th>
            <th width="25%">Capaian Kompetensi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $id_s = $siswa['id_siswa'];
          $q_nilai = mysqli_query($conn, "
              SELECT n.*, m.nama_mapel 
              FROM nilai n 
              JOIN mata_pelajaran m ON n.id_mapel = m.id_mapel 
              WHERE n.id_siswa = '$id_s'
          ");
          $no = 1;
          while ($nil = mysqli_fetch_assoc($q_nilai)):
          ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td><?= htmlspecialchars($nil['nama_mapel']); ?></td>
              <td class="text-center"><?= number_format($nil['nilai_akhir'], 1); ?></td>
              <td class="text-center"><?= htmlspecialchars($nil['predikat']); ?></td>
              <td><?= htmlspecialchars($nil['predikat'] === 'A' ? 'Sangat Baik' : 'Baik'); ?> dalam menguasai materi.</td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Kolom Pengesahan Tanda Tangan -->
      <div class="signature-section">
        <div class="sig-box">
          <p>Mengetahui,<br>Orang Tua/Wali Siswa</p>
          <br><br><br>
          <p><strong>( ..................................... )</strong></p>
        </div>

        <div class="sig-box">
          <p>Benua Indah, 25 Juni 2026<br>Wali Kelas</p>
          <br><br><br>
          <p><strong><?= htmlspecialchars($kelas_info['nama_wali'] ?? '.....................'); ?></strong></p>
          <p>NIP. <?= htmlspecialchars($kelas_info['nip_wali'] ?? '-'); ?></p>
        </div>
      </div>

      <div class="sig-center">
        <p>Mengetahui,<br>Kepala Sekolah SDN 1 Benua Indah</p>
        <br><br><br>
        <p><strong><?= htmlspecialchars($kepsek['nama_pengguna'] ?? '.....................'); ?></strong></p>
        <p>NIP. <?= htmlspecialchars($kepsek['nip'] ?? '-'); ?></p>
      </div>
    </div>
  <?php endwhile; ?>
</body>
</html>