<?php
session_start();

// Proteksi: Hanya 'user' dan 'admin' yang bisa masuk
// Admin diperbolehkan masuk agar bisa melakukan testing/monitoring
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../login.php?pesan=belum_login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scanner Check - User Mode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="#">SCANNER SYSTEM (USER)</a>
            <div class="d-flex">
                <span class="navbar-text me-3 text-white">Operator: <strong><?php echo $_SESSION['username']; ?></strong></span>
                <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 text-center">
        <div class="card shadow-sm border-0 p-5">
            <h1>Scanner Check Module</h1>
            <p class="text-muted">Modul ini akan digunakan untuk membandingkan SAP-CODE dengan data registrasi.</p>
            <div class="alert alert-info">Status: Menunggu Integrasi Database Registrasi...</div>
        </div>
    </div>
</body>
</html>