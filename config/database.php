<?php
// config/database.php

$host     = "localhost";
$user     = "root";
$password = ""; // Default password Laragon
$database = "db_rekap_nilai_sd";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

// Set charset ke utf8mb4 agar mendukung karakter lengkap
mysqli_set_charset($conn, "utf8mb4");
?>