<?php
// process/logout.php
session_start();
session_unset();
session_destroy();
header("Location: ../views/login.php?success=Anda berhasil keluar");
exit;
?>