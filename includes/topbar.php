<?php
// includes/topbar.php
$nama_user = $_SESSION['nama'] ?? 'Pengguna';
$role_user = $_SESSION['role'] ?? '';
$foto_user = $_SESSION['foto'] ?? 'default-avatar.png';

$role_label = [
    'admin' => 'Administrator',
    'guru' => 'Guru Pengampu',
    'kepala_sekolah' => 'Kepala Sekolah'
];
?>
<header class="topbar">
  <div class="topbar-left">
    <h2><?= isset($page_title) ? htmlspecialchars($page_title) : 'Dashboard'; ?></h2>
  </div>

  <div class="topbar-right">
    <!-- Tombol Switch Mode Terang / Gelap (Light / Dark) -->
    <button type="button" id="themeToggleBtn" class="btn-theme-toggle" title="Ganti Mode Tema">
      <span class="theme-icon">🌙</span>
    </button>

    <div class="user-profile">
      <!-- Posisi Gambar: Foto Profil Avatar Pengguna di Topbar Kanan -->
      <img src="../assets/img/avatars/<?= htmlspecialchars($foto_user); ?>" alt="Foto Profil" class="avatar-topbar">
      <div class="user-info">
        <span class="user-name"><?= htmlspecialchars($nama_user); ?></span>
        <span class="user-role"><?= $role_label[$role_user] ?? $role_user; ?></span>
      </div>
    </div>
  </div>
</header>