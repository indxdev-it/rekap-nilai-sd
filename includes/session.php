<?php
// includes/session.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Memeriksa apakah pengguna sudah login.
 * Jika belum, redirect ke halaman login.
 */
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../views/login.php?error=Silakan login terlebih dahulu");
        exit;
    }
}

/**
 * Memeriksa role pengguna.
 * @param array $allowed_roles
 */
function check_role($allowed_roles = []) {
    check_login();
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: ../views/login.php?error=Akses ditolak");
        exit;
    }
}
?>