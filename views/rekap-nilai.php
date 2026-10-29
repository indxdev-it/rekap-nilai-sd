<?php
// views/rekap-nilai.php
$page_title = "Rekapitulasi Nilai Siswa";
$page_css   = "rekap-nilai.css";

require_once '../includes/header.php';
require_once '../includes/session.php';
require_once '../config/database.php';

check_role(['admin', 'guru', 'kepala_sekolah']);

$user_role = $_SESSION['role'];
$user_id   = $_SESSION['user_id'];

// Ambil daftar kelas untuk filter
$q_kelas_all = mysqli_query($conn, "SELECT * FROM kelas");

// Penentuan ID Kelas yang ditampilkan
$selected_kelas = isset($_GET['kelas']) ? intval($_GET['kelas']) : 0;

if ($user_role === 'guru' && $selected_kelas === 0) {
    // Default kelas guru yang mengampu
    $q_k = mysqli_query($conn, "SELECT id_kelas FROM kelas WHERE id_guru = '$user_id' LIMIT 1");
    $dt_k = mysqli_fetch_assoc($q_k);
    $selected_kelas = $dt_k['id_kelas'] ?? 0;
}

// Ambil mata pelajaran & daftar siswa jika kelas dipilih
$rekap_data = [];
if ($selected_kelas > 0) {
    $q_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE id_kelas = '$selected_kelas' AND status_siswa = 'aktif' ORDER BY nama_siswa ASC");
    while ($s = mysqli_fetch_assoc($q_siswa)) {
        $id_s = $s['id_siswa'];
        
        // Query nilai per mapel untuk siswa ini
        $q_nil = mysqli_query($conn, "
            SELECT n.*, m.nama_mapel 
            FROM nilai n 
            JOIN mata_pelajaran m ON n.id_mapel = m.id_mapel 
            WHERE n.id_siswa = '$id_s'
        ");
        
        $s['nilai_mapel'] = [];
        while ($n = mysqli_fetch_assoc($q_nil)) {
            $s['nilai_mapel'][$n['id_mapel']] = $n;
        }
        $rekap_data[] = $s;
    }
}

// Ambil semua mapel untuk header tabel
$q_mapel_all = mysqli_query($conn, "SELECT * FROM mata_pelajaran");
$list_mapel = [];
while ($m = mysqli_fetch_assoc($q_mapel_all)) {
    $list_mapel[] = $m;
}
?>

<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>

  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>

    <section class="content-body">
      <!-- Filter Kelas & Tombol Aksi -->
      <div class="filter-header">
        <form method="GET" class="filter-form">
          <label for="kelas">Pilih Kelas:</label>
          <select name="kelas" id="kelas" onchange="this.form.submit()" class="select-input">
            <option value="0">-- Pilih Kelas --</option>
            <?php 
            if ($q_kelas_all && mysqli_num_rows($q_kelas_all) > 0) {
                mysqli_data_seek($q_kelas_all, 0);
                while ($k = mysqli_fetch_assoc($q_kelas_all)) {
                    $selected = ($selected_kelas == $k['id_kelas']) ? 'selected' : '';
                    echo '<option value="' . $k['id_kelas'] . '" ' . $selected . '>' . htmlspecialchars($k['nama_kelas']) . '</option>';
                }
            }
            ?>
          </select>
        </form>

        <?php if ($selected_kelas > 0): ?>
          <div class="export-actions">
            <a href="cetak-rapor.php?kelas=<?= $selected_kelas; ?>" class="btn btn-primary" target="_blank">Cetak Rapor PDF</a>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($selected_kelas > 0 && count($rekap_data) > 0): ?>
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th class="sticky-col">No</th>
                <th class="sticky-col">Nama Siswa</th>
                <?php foreach ($list_mapel as $mp): ?>
                  <th><?= htmlspecialchars($mp['kode_mapel']); ?></th>
                <?php endforeach; ?>
                <th>Rata-rata</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($rekap_data as $rd): ?>
                <tr>
                  <td class="sticky-col"><?= $no++; ?></td>
                  <td class="sticky-col student-cell">
                    <!-- Posisi Gambar: Foto Kecil Siswa pada Tabel Rekapitulasi Nilai -->
                    <img src="../assets/img/students/<?= htmlspecialchars($rd['foto_siswa']); ?>" alt="Foto" class="avatar-sm">
                    <strong><?= htmlspecialchars($rd['nama_siswa']); ?></strong>
                  </td>

                  <?php 
                  $total_nilai = 0;
                  $count_mapel = 0;
                  foreach ($list_mapel as $mp): 
                      $n_akhir = $rd['nilai_mapel'][$mp['id_mapel']]['nilai_akhir'] ?? null;
                      if ($n_akhir !== null) {
                          $total_nilai += $n_akhir;
                          $count_mapel++;
                      }
                  ?>
                    <td><?= $n_akhir !== null ? number_format($n_akhir, 1) : '-'; ?></td>
                  <?php endforeach; ?>

                  <td>
                    <strong>
                      <?= $count_mapel > 0 ? number_format($total_nilai / $count_mapel, 2) : '-'; ?>
                    </strong>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="info-box">
          <p>Silakan pilih kelas terlebih dahulu untuk melihat rekapitulasi nilai.</p>
        </div>
      <?php endif; ?>
    </section>
  </main>
</div>

<?php require_once '../includes/footer.php'; ?>