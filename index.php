<?php
// Cek apakah user sudah login atau belum
session_start();

if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    // Jika sudah login, arahkan ke dashboard
    header("location:admin/dashboard.php");
} else {
    // Jika belum login, arahkan ke halaman login
    header("location:login.php");
}
exit();
?>