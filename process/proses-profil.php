<?php
// process/proses-profil.php
session_start();
require_once '../config/database.php';
require_once '../includes/session.php';

check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id       = $_SESSION['user_id'];
    $nama_pengguna = trim($_POST['nama_pengguna']);
    $email         = trim($_POST['email']);
    $no_hp         = trim($_POST['no_hp']);
    $tanggal_lahir = !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : NULL;
    $alamat        = trim($_POST['alamat']);

    if (empty($nama_pengguna) || empty($email)) {
        header("Location: ../views/profil.php?error=Nama dan Email wajib diisi");
        exit;
    }

    // Cek apakah ada unggahan foto profil baru
    $foto_profil_sql = "";
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $file_tmp   = $_FILES['foto_profil']['tmp_name'];
        $file_name  = $_FILES['foto_profil']['name'];
        $file_ext   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
            $upload_dir    = "../assets/img/avatars/";

            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                $foto_profil_sql = ", foto_profil = '$new_file_name'";
                // Update session foto
                $_SESSION['foto'] = $new_file_name;
            }
        }
    }

    // Update data di database
    $stmt = mysqli_prepare($conn, "UPDATE pengguna SET nama_pengguna = ?, email = ?, no_hp = ?, tanggal_lahir = ?, alamat = ? {$foto_profil_sql} WHERE id_pengguna = ?");
    mysqli_stmt_bind_param($stmt, "sssssi", $nama_pengguna, $email, $no_hp, $tanggal_lahir, $alamat, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        // Perbarui juga session nama
        $_SESSION['nama'] = $nama_pengguna;
        header("Location: ../views/profil.php?success=Profil berhasil diperbarui");
        exit;
    } else {
        header("Location: ../views/profil.php?error=Gagal memperbarui profil");
        exit;
    }
} else {
    header("Location: ../views/profil.php");
    exit;
}
?>