<?php
session_start();
include '../config/koneksi.php';

// Set header JSON agar respon bisa dibaca JavaScript
header('Content-Type: application/json');

if (isset($_POST['action']) && $_POST['action'] == 'simpan') {
    
    if (!isset($_SESSION['username'])) {
        echo json_encode(['status' => 'error', 'message' => 'Sesi habis']);
        exit;
    }

    // Ambil data POST dari JavaScript scanner_check.php
    $sap    = trim(mysqli_real_escape_string($conn, $_POST['sap'] ?? ''));
    $model  = trim(mysqli_real_escape_string($conn, $_POST['model'] ?? ''));
    $cll    = trim(mysqli_real_escape_string($conn, $_POST['cll'] ?? ''));
    $size   = trim(mysqli_real_escape_string($conn, $_POST['size'] ?? ''));
    $type   = trim(mysqli_real_escape_string($conn, $_POST['type'] ?? ''));
    $pitch  = trim(mysqli_real_escape_string($conn, $_POST['pitch'] ?? ''));
    $cycle  = trim(mysqli_real_escape_string($conn, $_POST['cycle'] ?? ''));
    $is_diff = trim(mysqli_real_escape_string($conn, $_POST['is_diff'] ?? '0'));
    $user   = $_SESSION['username'];

    // Ambil data karyawan dari tabel users
    $u_query = mysqli_query($conn, "SELECT nama_karyawan, role FROM users WHERE username = '$user'");
    $u_data = mysqli_fetch_assoc($u_query);
    $nama_karyawan = $u_data['nama_karyawan'] ?? 'Unknown';
    $role = $u_data['role'] ?? 'User';

    // INSERT KE TABEL scanner_history
    // Disesuaikan dengan urutan kolom: tanggal, nama_karyawan, username, role, model_name, sap_code, status, cll, size, type, pitch, cycle, is_diff, status_sync
    $sql = "INSERT INTO scanner_history 
            (tanggal, nama_karyawan, username, role, model_name, sap_code, status, cll, size, type, pitch, cycle, is_diff, status_sync) 
            VALUES 
            (NOW(), '$nama_karyawan', '$user', '$role', '$model', '$sap', 'COMPLETED', '$cll', '$size', '$type', '$pitch', '$cycle', '$is_diff', 0)";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Cycle ' . $cycle . ' Recorded']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}