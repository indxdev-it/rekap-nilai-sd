<?php
// views/input-nilai.php
$page_title = "Input Nilai Siswa";
$page_css   = "input-nilai.css";

require_once '../includes/header.php';
require_once '../includes/session.php';
require_once '../config/database.php';

check_role(['guru']);

$id_guru = $_SESSION['user_id'];

// Ambil data kelas yang diampu guru
$q_kelas = mysqli_query($conn, "SELECT * FROM kelas WHERE id_guru = '$id_guru' LIMIT 1");
$kelas   = mysqli_fetch_assoc($q_kelas);
$id_kelas = $kelas['id_kelas'] ?? 0;

// Ambil daftar mata pelajaran
$q_mapel = mysqli_query($conn, "SELECT * FROM mata_pelajaran");

// Ambil tahun ajaran aktif & semester
$q_ta  = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE status_aktif = 'ya' LIMIT 1");
$ta    = mysqli_fetch_assoc($q_ta);
$id_ta = $ta['id_tahun_ajaran'] ?? 1;

$q_sem = mysqli_query($conn, "SELECT * FROM semester");

// Mapel yang dipilih via GET filter
$selected_mapel = isset($_GET['mapel']) ? intval($_GET['mapel']) : 0;
$id_semester    = isset($_GET['semester']) ? intval($_GET['semester']) : 1;

// Ambil daftar siswa di kelas guru tersebut
$siswa_list = [];
if ($id_kelas > 0) {
    $q_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE id_kelas = '$id_kelas' AND status_siswa = 'aktif' ORDER BY nama_siswa ASC");
    while ($s = mysqli_fetch_assoc($q_siswa)) {
        // Cek apakah sudah ada nilai tersimpan untuk mapel & semester ini
        if ($selected_mapel > 0) {
            $id_s = $s['id_siswa'];
            $q_nil = mysqli_query($conn, "SELECT * FROM nilai WHERE id_siswa = '$id_s' AND id_mapel = '$selected_mapel' AND id_tahun_ajaran = '$id_ta' AND id_semester = '$id_semester' LIMIT 1");
            $s['existing_nilai'] = mysqli_fetch_assoc($q_nil);
        }
        $siswa_list[] = $s;
    }
}

$msg_success = $_GET['success'] ?? '';
?>

<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>

  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>

    <section class="content-body">
      <?php if (!empty($msg_success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg_success); ?></div>
      <?php endif; ?>

      <!-- Form Filter Mata Pelajaran & Semester -->
      <div class="filter-card">
        <form method="GET" class="filter-grid">
          <div class="form-group">
            <label for="mapel">Pilih Mata Pelajaran</label>
            <select name="mapel" id="mapel" class="select-input" required onchange="this.form.submit()">
              <option value="0">-- Pilih Mata Pelajaran --</option>
              <?php while ($m = mysqli_fetch_assoc($q_mapel)): ?>
                <option value="<?= $m['id_mapel']; ?>" <?= $selected_mapel == $m['id_mapel'] ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($m['nama_mapel']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="semester">Semester</label>
            <select name="semester" id="semester" class="select-input" onchange="this.form.submit()">
              <?php while ($sem = mysqli_fetch_assoc($q_sem)): ?>
                <option value="<?= $sem['id_semester']; ?>" <?= $id_semester == $sem['id_semester'] ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($sem['nama_semester']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
        </form>
      </div>

      <?php if ($selected_mapel > 0 && $id_kelas > 0): ?>
        <form action="../process/proses-simpan-nilai.php" method="POST">
          <input type="hidden" name="id_mapel" value="<?= $selected_mapel; ?>">
          <input type="hidden" name="id_tahun_ajaran" value="<?= $id_ta; ?>">
          <input type="hidden" name="id_semester" value="<?= $id_semester; ?>">

          <div class="table-container">
            <table class="data-table">
              <thead>
                <tr>
                  <th class="sticky-col">Nama Siswa</th>
                  <th>Nilai Tugas</th>
                  <th>Nilai Ulangan</th>
                  <th>Nilai PTS</th>
                  <th>Nilai PAS</th>
                  <th>Nilai Akhir</th>
                  <th>Predikat</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($siswa_list) > 0): ?>
                  <?php foreach ($siswa_list as $s): ?>
                    <input type="hidden" name="id_siswa[]" value="<?= $s['id_siswa']; ?>">
                    <tr>
                      <td class="sticky-col student-cell">
                        <!-- Posisi Gambar: Foto Profil Kecil Siswa dalam Baris Tabel Input Nilai -->
                        <img src="../assets/img/students/<?= htmlspecialchars($s['foto_siswa']); ?>" alt="Foto" class="avatar-sm">
                        <span><?= htmlspecialchars($s['nama_siswa']); ?></span>
                      </td>
                      <td>
                        <input type="number" step="0.01" min="0" max="100" name="tugas[]" class="input-score" value="<?= $s['existing_nilai']['nilai_tugas'] ?? '0'; ?>" required>
                      </td>
                      <td>
                        <input type="number" step="0.01" min="0" max="100" name="ulangan[]" class="input-score" value="<?= $s['existing_nilai']['nilai_ulangan'] ?? '0'; ?>" required>
                      </td>
                      <td>
                        <input type="number" step="0.01" min="0" max="100" name="pts[]" class="input-score" value="<?= $s['existing_nilai']['nilai_pts'] ?? '0'; ?>" required>
                      </td>
                      <td>
                        <input type="number" step="0.01" min="0" max="100" name="pas[]" class="input-score" value="<?= $s['existing_nilai']['nilai_pas'] ?? '0'; ?>" required>
                      </td>
                      <td>
                        <span class="score-final"><?= $s['existing_nilai']['nilai_akhir'] ?? '0.00'; ?></span>
                      </td>
                      <td>
                        <span class="badge-predikat"><?= $s['existing_nilai']['predikat'] ?? '-'; ?></span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" class="text-center">Tidak ada siswa aktif di kelas ini.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan & Hitung Nilai</button>
          </div>
        </form>
      <?php else: ?>
        <div class="info-box">
          <p>Silakan pilih Mata Pelajaran terlebih dahulu untuk memuat lembar kerja input nilai siswa.</p>
        </div>
      <?php endif; ?>
    </section>
  </main>
</div>

<?php require_once '../includes/footer.php'; ?>