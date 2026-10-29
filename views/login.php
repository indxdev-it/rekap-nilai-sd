<?php
// views/login.php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') header("Location: dashboard-admin.php");
    elseif ($_SESSION['role'] === 'guru') header("Location: dashboard-guru.php");
    elseif ($_SESSION['role'] === 'kepala_sekolah') header("Location: dashboard-kepsek.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Rekap Nilai SDN 1 Benua Indah</title>
  <link rel="stylesheet" href="../assets/css/style-global.css">
  <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
  <div class="login-wrapper">
    <div class="login-card">
      <header class="login-header">
        <!-- Posisi Gambar: Logo Sekolah Atas Kartu Login -->
        <img src="../assets/img/logo/logo-sd.png" alt="Logo SDN 1 Benua Indah" class="login-logo">
        <h2>Rekap Nilai Siswa</h2>
        <p>SDN 1 Benua Indah</p>
      </header>

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>

      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']); ?></div>
      <?php endif; ?>

      <form action="../process/proses-login.php" method="POST" class="login-form">
        <div class="form-group">
          <label for="email">Alamat Email</label>
          <input type="email" id="email" name="email" placeholder="nama@sdn1benua.sch.id" required autofocus>
        </div>

        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Masuk ke Sistem</button>
      </form>

      <footer class="login-footer">
        <p>Belum memiliki akun? <a href="register.php">Daftar Akun Baru</a></p>
      </footer>
    </div>
  </div>
</body>
</html>