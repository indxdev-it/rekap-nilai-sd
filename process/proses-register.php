<?php
// process/proses-register.php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama_pengguna']);
    $email    = trim($_POST['email']);
    $no_hp    = trim($_POST['no_hp']);
    $password = trim($_POST['password']);
    $role     = trim($_POST['role']);

    // Validasi role hanya guru atau kepala_sekolah
    if (!in_array($role, ['guru', 'kepala_sekolah'])) {
        header("Location: ../views/register.php?error=Role pendaftaran tidak valid");
        exit;
    }

    if (empty($nama) || empty($email) || empty($password)) {
        header("Location: ../views/register.php?error=Semua kolom wajib diisi");
        exit;
    }

    // Cek apakah email sudah terdaftar
    $check_stmt = mysqli_prepare($conn, "SELECT id_pengguna FROM pengguna WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        header("Location: ../views/register.php?error=Email sudah terdaftar");
        exit;
    }

    // Insert pengguna baru (password plain text)
    $stmt = mysqli_prepare($conn, "INSERT INTO pengguna (nama_pengguna, email, no_hp, password, role) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $nama, $email, $no_hp, $password, $role);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../views/login.php?success=Pendaftaran berhasil, silakan login");
        exit;
    } else {
        header("Location: ../views/register.php?error=Gagal mendaftar, coba lagi");
        exit;
    }
} else {
    header("Location: ../views/register.php");
    exit;
}
?>