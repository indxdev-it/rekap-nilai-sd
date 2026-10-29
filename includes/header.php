<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/session.php';
check_login();

$css_file = isset($page_css) ? $page_css : 'dashboard-admin.css';
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Dashboard'; ?> - Rekap Nilai SDN 1 Benua Indah</title>
  
  <!-- Font Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Stylesheet Global Layout & Sidebar -->
  <link rel="stylesheet" href="../assets/css/style-global.css">
  
  <!-- Stylesheet Khusus Halaman Aktif -->
  <link rel="stylesheet" href="../assets/css/<?= $css_file; ?>">
</head>
<body>