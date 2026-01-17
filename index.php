<?php
session_start();
// Logika pengecekan login tetap dipertahankan
$target_url = "login.php";
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    $target_url = "admin/dashboard.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIIX-Scan</title>
    
    <link rel="manifest" href="manifest.json">
    <link rel="apple-touch-icon" href="assets/img/iconapk.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/profile/iconapk.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="SIIX-Scan">
    
    <script>
        setTimeout(function(){
            window.location.href = "<?php echo $target_url; ?>";
        }, 100); // Jeda 0.1 detik agar browser sempat membaca Manifest
    </script>
</head>
<body style="background-color: #020617; display: flex; justify-content: center; align-items: center; height: 100vh;">
    <img src="assets/img/iconapk.png" alt="SIIX" style="width: 80px; height: 80px; border-radius: 15px;">
</body>
</html>