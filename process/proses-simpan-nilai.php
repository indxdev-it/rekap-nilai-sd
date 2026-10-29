<?php
// process/proses-simpan-nilai.php
session_start();
require_once '../config/database.php';
require_once '../includes/session.php';

check_role(['guru']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mapel        = intval($_POST['id_mapel']);
    $id_tahun_ajaran = intval($_POST['id_tahun_ajaran']);
    $id_semester     = intval($_POST['id_semester']);
    $id_pengguna     = $_SESSION['user_id'];
    
    $siswa_ids       = $_POST['id_siswa']; // Array ID Siswa
    $tugas           = $_POST['tugas'];
    $ulangan         = $_POST['ulangan'];
    $pts             = $_POST['pts'];
    $pas             = $_POST['pas'];

    // Loop data per siswa yang diinput
    for ($i = 0; $i < count($siswa_ids); $i++) {
        $id_s   = intval($siswa_ids[$i]);
        $n_tug  = floatval($tugas[$i]);
        $n_ulg  = floatval($ulangan[$i]);
        $n_pts  = floatval($pts[$i]);
        $n_pas  = floatval($pas[$i]);

        // Formula Perhitungan Nilai Akhir (Contoh: Rata-rata komponen / Bobot sekolah)
        // Formula standar: (Tugas + Ulangan + PTS + PAS) / 4 atau disesuaikan ketentuan sekolah
        $nilai_akhir = ($n_tug + $n_ulg + $n_pts + $n_pas) / 4;

        // Penentuan Predikat Sederhana
        $predikat = 'C';
        if ($nilai_akhir >= 85) {
            $predikat = 'A';
        } elseif ($nilai_akhir >= 75) {
            $predikat = 'B';
        } elseif ($nilai_akhir >= 60) {
            $predikat = 'C';
        } else {
            $predikat = 'D';
        }

        // Simpan ke database (Gunakan UPSERT / ON DUPLICATE KEY UPDATE agar data ter-update jika sudah ada)
        $query = "INSERT INTO nilai 
                  (id_siswa, id_mapel, id_pengguna, id_tahun_ajaran, id_semester, nilai_tugas, nilai_ulangan, nilai_pts, nilai_pas, nilai_akhir, predikat) 
                  VALUES ('$id_s', '$id_mapel', '$id_pengguna', '$id_tahun_ajaran', '$id_semester', '$n_tug', '$n_ulg', '$n_pts', '$n_pas', '$nilai_akhir', '$predikat')
                  ON DUPLICATE KEY UPDATE 
                  nilai_tugas = '$n_tug', 
                  nilai_ulangan = '$n_ulg', 
                  nilai_pts = '$n_pts', 
                  nilai_pas = '$n_pas', 
                  nilai_akhir = '$nilai_akhir', 
                  predikat = '$predikat'";

        mysqli_query($conn, $query);
    }

    header("Location: ../views/input-nilai.php?mapel=$id_mapel&success=Nilai berhasil disimpan dan dikalkulasi");
    exit;
} else {
    header("Location: ../views/input-nilai.php");
    exit;
}
?>