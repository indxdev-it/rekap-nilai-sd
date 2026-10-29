<?php
// views/data-siswa.php
$page_title = "Data Siswa";
$page_css   = "data-siswa.css";

require_once '../includes/header.php';
require_once '../includes/session.php';
require_once '../config/database.php';

check_role(['admin', 'guru']);

$user_role =$_SESSION['role'];
$user_id   =$_SESSION['user_id'];

// Filter Kelas
$filter_kelas = isset($_GET['kelas']) ? intval($_GET['kelas']) : 0;

// Query Siswa berdasarkan Role
if ($user_role === 'guru') {
    // Guru hanya bisa melihat siswa di kelas yang diampunya
    $q_guru_kelas = mysqli_query($conn, "SELECT id_kelas FROM kelas WHERE id_guru = '$user_id' LIMIT 1");
    $dt_guru_kelas = mysqli_fetch_assoc($q_guru_kelas);
    $kelas_diampu_id =$dt_guru_kelas['id_kelas'] ?? 0;

    $sql_siswa = "SELECT s.*, k.nama_kelas FROM siswa s 
                  LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                  WHERE s.id_kelas = '$kelas_diampu_id' AND s.status_siswa = 'aktif'";
} else {
    // Admin bisa melihat seluruh siswa atau melakukan filter per kelas
    $sql_siswa = "SELECT s.*, k.nama_kelas FROM siswa s 
                  LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                  WHERE s.status_siswa = 'aktif'";
    if ($filter_kelas > 0) {$sql_siswa .= " AND s.id_kelas = '$filter_kelas'";
    }
}

$res_siswa = mysqli_query($conn,$sql_siswa);
$res_kelas = mysqli_query($conn, "SELECT * FROM kelas");
?>

<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>

  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>

    <section class="content-body">
      <div class="table-header-actions">
        <h3>Daftar Siswa Aktif</h3>
        
        <?php if ($user_role === 'admin'): ?>
          <div class="actions-right">
            <!-- Filter Kelas khusus Admin -->
            <form method="GET" class="filter-form">
              <select name="kelas" onchange="this.form.submit()" class="select-input">
                <option value="0">-- Semua Kelas --</option>
                <?php while ($k = mysqli_fetch_assoc($res_kelas)): ?>
                  <option value="<?= $k['id_kelas']; ?>" <?= $filter_kelas ==$k['id_kelas'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($k['nama_kelas']); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </form>
            <button type="button" class="btn btn-primary" onclick="openModalTambahSiswa()">+ Tambah Siswa</button>
          </div>
        <?php endif; ?>
      </div>

      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Foto</th>
              <th>NIS / NISN</th>
              <th>Nama Siswa</th>
              <th>Kelas</th>
              <th>No. HP Ortu</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($res_siswa) > 0): ?>
              <?php while ($s = mysqli_fetch_assoc($res_siswa)): ?>
                <tr>
                  <td>
                    <!-- Posisi Gambar: Foto Siswa dalam Tabel List Data Siswa -->
                    <img src="../assets/img/students/<?= htmlspecialchars($s['foto_siswa']); ?>" alt="Foto <?= htmlspecialchars($s['nama_siswa']); ?>" class="avatar-sm">
                  </td>
                  <td><?= htmlspecialchars($s['nis']); ?> / <?= htmlspecialchars($s['nisn'] ?? '-'); ?></td>
                  <td><strong><?= htmlspecialchars($s['nama_siswa']); ?></strong></td>
                  <td><?= htmlspecialchars($s['nama_kelas'] ?? 'Belum Diatur'); ?></td>
                  <td><?= htmlspecialchars($s['no_hp_ortu'] ?? '-'); ?></td>
                  <td>
                    <a href="detail-siswa.php?id=<?= $s['id_siswa']; ?>" class="btn-sm btn-info">Detail / Profil</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center">Belum ada data siswa.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<?php require_once '../includes/footer.php'; ?>