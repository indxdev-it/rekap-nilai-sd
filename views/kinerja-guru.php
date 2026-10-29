<?php
// views/kinerja-guru.php
$page_title = "Monitoring Kinerja Guru";
$page_css   = "../assets/css/kinerja-guru.css";

require_once '../includes/header.php';
require_once '../includes/session.php';
require_once '../config/database.php';

check_role(['kepala_sekolah']);

// Query untuk mengambil data rekap kinerja guru dan kelas yang diampu
$q_kinerja = mysqli_query($conn, "
    SELECT 
        p.id_pengguna, 
        p.nama_pengguna, 
        p.nip, 
        p.foto_profil, 
        p.no_hp,
        k.id_kelas,
        k.nama_kelas,
        (SELECT COUNT(*) FROM siswa WHERE siswa.id_kelas = k.id_kelas AND status_siswa = 'aktif') as total_siswa,
        (SELECT COUNT(DISTINCT n.id_mapel) FROM nilai n JOIN siswa s ON n.id_siswa = s.id_siswa WHERE s.id_kelas = k.id_kelas) as mapel_terisi
    FROM pengguna p
    LEFT JOIN kelas k ON p.id_pengguna = k.id_guru
    WHERE p.role = 'guru'
    ORDER BY p.nama_pengguna ASC
");
?>

<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>

  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>

    <section class="content-body">
      <!-- Welcome Card -->
      <div class="welcome-card">
        <h1>Monitoring Kinerja & Aktivitas Guru</h1>
        <p>Tinjau penugasan kelas, kontak, serta keaktifan guru dalam melakukan pengisian rekapitulasi nilai siswa.</p>
      </div>

      <!-- Tabel Daftar Kinerja Guru -->
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Foto & Nama Guru</th>
              <th>NIP</th>
              <th>Kelas Diampu</th>
              <th>Jumlah Siswa</th>
              <th>Progres Pengisian Mapel</th>
              <th>Status Kinerja</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($q_kinerja) > 0): ?>
              <?php while ($g = mysqli_num_rows($q_kinerja) ? mysqli_fetch_assoc($q_kinerja) : null): ?>
                <tr>
                  <td>
                    <div class="teacher-cell">
                      <!-- Posisi Gambar: Foto Profil Guru pada Tabel Kinerja -->
                      <img src="../assets/img/avatars/<?= htmlspecialchars($g['foto_profil'] ?? 'default-avatar.png'); ?>" alt="Foto" class="avatar-sm">
                      <div>
                        <strong><?= htmlspecialchars($g['nama_pengguna']); ?></strong>
                        <span class="teacher-phone"><?= htmlspecialchars($g['no_hp'] ?? '-'); ?></span>
                      </div>
                    </div>
                  </td>
                  <td><?= htmlspecialchars($g['nip'] ?? '-'); ?></td>
                  <td>
                    <?php if (!empty($g['nama_kelas'])): ?>
                      <span class="badge-class"><?= htmlspecialchars($g['nama_kelas']); ?></span>
                    <?php else: ?>
                      <span class="text-muted">Belum Ditugaskan</span>
                    <?php endif; ?>
                  </td>
                  <td><?= $g['total_siswa'] ?? 0; ?> Siswa</td>
                  <td>
                    <!-- Asumsi total mata pelajaran standar SD misalnya 6 mapel utama -->
                    <?= $g['mapel_terisi']; ?> / 6 Mapel Terinput
                  </td>
                  <td>
                    <?php if (($g['mapel_terisi'] ?? 0) >= 5): ?>
                      <span class="badge badge-success">Aktif / Tuntas</span>
                    <?php elseif (($g['mapel_terisi'] ?? 0) > 0): ?>
                      <span class="badge badge-warning">Dalam Proses</span>
                    <?php else: ?>
                      <span class="badge badge-danger">Belum Mengisi</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if (!empty($g['id_kelas'])): ?>
                      <a href="rekap-nilai.php?kelas=<?= $g['id_kelas']; ?>" class="btn-link">Lihat Nilai</a>
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center">Belum ada data guru terdaftar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<?php require_once '../includes/footer.php'; ?>