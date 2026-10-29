<?php
// process/proses-login.php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: ../views/login.php?error=Email dan Password wajib diisi");
        exit;
    }

    $stmt = mysqli_prepare($conn, "SELECT id_pengguna, nama_pengguna, email, password, role, foto_profil FROM pengguna WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Pengecekan password plain text (tanpa hash)
        if ($password === $row['password']) {
            $_SESSION['user_id']   = $row['id_pengguna'];
            $_SESSION['nama']      = $row['nama_pengguna'];
            $_SESSION['email']     = $row['email'];
            $_SESSION['role']      = $row['role'];
            $_SESSION['foto']      = $row['foto_profil'];

            // Redirect berdasarkan role
            switch ($row['role']) {
                case 'admin':
                    header("Location: ../views/dashboard-admin.php");
                    break;
                case 'guru':
                    header("Location: ../views/dashboard-guru.php");
                    break;
                case 'kepala_sekolah':
                    header("Location: ../views/dashboard-kepsek.php");
                    break;
                default:
                    header("Location: ../views/login.php?error=Role tidak valid");
                    break;
            }
            exit;
        }
    }

    header("Location: ../views/login.php?error=Email atau Password salah");
    exit;
} else {
    header("Location: ../views/login.php");
    exit;
}
?>