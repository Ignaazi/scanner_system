<?php
session_start(); // Memulai sesi agar bisa dihapus
session_unset(); // Menghapus semua isi variabel sesi
session_destroy(); // Menghancurkan sesi sepenuhnya

// Mengarahkan kembali ke halaman login dengan pesan sukses
header("location: login.php?pesan=logout");
exit();
?>