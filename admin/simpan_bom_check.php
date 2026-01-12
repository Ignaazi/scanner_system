<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['action']) && $_POST['action'] == 'sync_to_bom_check') {
    $bom_id = mysqli_real_escape_string($conn, $_POST['bom_id']);
    $sap_code = mysqli_real_escape_string($conn, $_POST['sap_code']);
    $user = $_SESSION['username'];

    // 1. Cek apakah sudah ada di bom_check supaya tidak double
    $cek = mysqli_query($conn, "SELECT id FROM bom_check WHERE bom_id = '$bom_id' AND sap_code = '$sap_code' AND status_proses = 'pending'");
    
    if (mysqli_num_rows($cek) == 0) {
        // Simpan ke tabel bom_check untuk notifikasi admin
        $query = mysqli_query($conn, "INSERT INTO bom_check (bom_id, sap_code, scan_by, status_proses, tanggal_scan) 
                                      VALUES ('$bom_id', '$sap_code', '$user', 'pending', NOW())");
        
        // 2. Update status atau data di tabel bom_items (BOM LIST) jika diperlukan
        // Contoh: mengupdate bahwa item ini sudah diverifikasi secara fisik
        mysqli_query($conn, "UPDATE bom_items SET last_scan = NOW() WHERE bom_id = '$bom_id' AND sap_code = '$sap_code'");
    }

    echo json_encode(['status' => 'success']);
    exit;
}