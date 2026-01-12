<?php
session_start();
include '../config/koneksi.php';

// Cek apakah ada kiriman data dari scanner
if (isset($_POST['action']) && $_POST['action'] == 'simpan') {
    $sap    = mysqli_real_escape_string($conn, $_POST['sap']);
    $model  = mysqli_real_escape_string($conn, $_POST['model']);
    $cll    = mysqli_real_escape_string($conn, $_POST['cll']);
    $size   = mysqli_real_escape_string($conn, $_POST['size']);
    $type   = mysqli_real_escape_string($conn, $_POST['type']);
    $pitch  = mysqli_real_escape_string($conn, $_POST['pitch']);
    $cycle  = mysqli_real_escape_string($conn, $_POST['cycle']);
    $user   = $_SESSION['username'];

    // Ambil nama karyawan dari session/database untuk melengkapi data
    $u_query = mysqli_query($conn, "SELECT nama_karyawan FROM users WHERE username = '$user'");
    $u_data = mysqli_fetch_assoc($u_query);
    $nama_karyawan = $u_data['nama_karyawan'] ?? 'Unknown';

    // Masukkan ke tabel scanner_history (sesuai tabel yang Anda pakai di history_scanner.php)
    $query = mysqli_query($conn, "INSERT INTO scanner_history 
        (tanggal, username, nama_karyawan, model_name, sap_code, cll, size, type, pitch, cycle) 
        VALUES 
        (NOW(), '$user', '$nama_karyawan', '$model', '$sap', '$cll', '$size', '$type', '$pitch', '$cycle')");

    if ($query) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}
?>