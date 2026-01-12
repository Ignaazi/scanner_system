<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit();
}
include '../config/koneksi.php';

// Ambil data admin yang login
$user_login = $_SESSION['username'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$user_login'");
$data_user = mysqli_fetch_assoc($query_user);

// --- LOGIKA SINKRONISASI DATA ---
if (isset($_POST['sync_data'])) {
    $bid = $_POST['bom_id'];
    
    // Ambil SAP unik dari history scan aktual yang belum diproses
    $get_aktual = mysqli_query($conn, "SELECT DISTINCT sap_code FROM bom_check WHERE bom_id = '$bid' AND status_proses = 'pending'");
    
    mysqli_begin_transaction($conn);
    try {
        while ($row = mysqli_fetch_array($get_aktual)) {
            $sap = $row['sap_code'];
            // Update status di Master BOM List
            mysqli_query($conn, "UPDATE bom_items SET status_verifikasi = 'verified' WHERE bom_id = '$bid' AND sap_code = '$sap'");
        }
        mysqli_query($conn, "UPDATE bom_check SET status_proses = 'done' WHERE bom_id = '$bid'");
        mysqli_commit($conn);
        echo "<script>alert('Sync Berhasil!'); window.location='bom_check.php';</script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal!');</script>";
    }
}

// Hitung Antrian untuk Notifikasi di Sidebar & Navbar
$q_notif = mysqli_query($conn, "SELECT COUNT(id) as total FROM bom_check WHERE status_proses = 'pending'");
$notif_count = mysqli_fetch_assoc($q_notif)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Check | SIIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --sidebar-text: #475569;
            --sidebar-hover: #f1f5f9;
            --nav-border: #e2e8f0;
            --accent: #f97316;
            --sidebar-header-text: #1e293b;
        }

        [data-bs-theme="dark"] {
            --bg-body: #020617;
            --bg-sidebar: #0f172a;
            --bg-card: #1e293b;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --sidebar-text: #94a3b8;
            --sidebar-hover: rgba(255, 255, 255, 0.05);
            --nav-border: #334155;
            --sidebar-header-text: #ffffff;
        }

        body { 
            background-color: var(--bg-body); 
            color: var(--text-main); 
            font-family: 'Inter', sans-serif; 
            transition: all 0.3s ease; 
            overflow-x: hidden; 
        }
        
        /* --- SIDEBAR REFINED (SYNCED) --- */
        #sidebar {
            width: 260px; height: 100vh; position: fixed;
            background: var(--bg-sidebar); display: flex; flex-direction: column; 
            z-index: 1000; transition: 0.3s ease; 
            border-right: 1px solid var(--nav-border);
        }
        .sidebar-header { padding: 30px 25px; text-align: center; border-bottom: 1px solid var(--nav-border); }
        .sidebar-header h4 { color: var(--sidebar-header-text); }
        .sidebar-menu { flex: 1; padding: 15px 0; overflow-y: auto; }
        .sidebar-menu .menu-label { padding: 15px 25px 5px; font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-menu a { padding: 12px 20px; margin: 4px 15px; display: flex; align-items: center; color: var(--sidebar-text); text-decoration: none; transition: 0.2s; font-size: 14px; border-radius: 10px; font-weight: 500; }
        .sidebar-menu a i { width: 25px; font-size: 17px; }
        .sidebar-menu a:hover { color: var(--accent); background: var(--sidebar-hover); }
        .sidebar-menu a.active { color: #fff; background: var(--accent); box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3); }
        .sidebar-footer { padding: 20px; border-top: 1px solid var(--nav-border); }

        /* --- MAIN CONTENT (SYNCED) --- */
        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; transition: 0.3s; }
        .top-nav { background: var(--bg-card); padding: 15px 30px; border-bottom: 1px solid var(--nav-border); position: sticky; top: 0; z-index: 999; }
        
        .card { background-color: var(--bg-card); border: 1px solid var(--nav-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        
        .theme-toggle { cursor: pointer; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--bg-body); color: var(--text-main); border: 1px solid var(--nav-border); }
        .theme-toggle:hover { background: var(--accent); color: white; border-color: var(--accent); }

        .pulse-red { animation: pulse-red 1.5s infinite; }
        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        @media (max-width: 768px) { #sidebar { margin-left: -260px; } .main-content { margin-left: 0; width: 100%; } }
    </style>
</head>
<body data-bs-theme="light">

<nav id="sidebar">
    <div class="sidebar-header">
        <h4 class="fw-bold mb-0" style="letter-spacing: 2px;">SIIX</h4>
        <div style="height: 2px; width: 30px; background: var(--accent); margin: 8px auto;"></div>
        <small style="font-size: 9px; color: var(--text-muted); font-weight: 700;">SCANNER SYSTEM</small>
    </div>
    <div class="sidebar-menu">
        <div class="menu-label">Utama</div>
        <a href="dashboard.php"><i class="fas fa-th-large me-3"></i> Dashboard</a>
        <div class="menu-label">Manajemen Data</div>
        <a href="users.php"><i class="fas fa-user-shield me-3"></i> Data User</a>
        <a href="master_line.php"><i class="fas fa-industry me-3"></i> Master Line</a>
        <a href="master_data.php"><i class="fas fa-database me-3"></i> Master Model</a>
        <div class="menu-label">Operasional</div>
        <a href="csv_mounter.php"><i class="fas fa-file-csv me-3"></i> CSV Mounter</a>
        <a href="bom_check.php" class="active"><i class="fas fa-check-double me-3"></i> Verification</a>
        <a href="scanner_check.php"><i class="fas fa-barcode me-3"></i> Scanner Check</a>
        <a href="history_scanner.php"><i class="fas fa-clock-rotate-left me-3"></i> History</a>
    </div>
    <div class="sidebar-footer">
        <a href="../logout.php" style="width: 100%; display: flex; align-items: center; justify-content: center; padding: 12px; color: #ef4444; text-decoration: none; background: rgba(239, 68, 68, 0.1); border-radius: 10px; font-weight: 700; font-size: 13px;">
            <i class="fas fa-power-off me-2"></i> LOGOUT
        </a>
    </div>
</nav>

<div class="main-content">
    <div class="top-nav d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <h6 class="mb-0 fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Verification Check</h6>
            <?php if($notif_count > 0): ?>
                <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold pulse-red" style="font-size: 10px;">
                    <i class="fas fa-bell me-1"></i> <?= $notif_count ?> ANTRIAN
                </span>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="theme-toggle" id="darkModeToggle">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            <div class="d-flex align-items-center gap-2 px-3 py-1 rounded-3" style="background: var(--bg-body); border: 1px solid var(--nav-border);">
                <div class="text-end">
                    <p class="mb-0 fw-bold" style="font-size: 12px;"><?= $data_user['nama_karyawan'] ?></p>
                    <p class="mb-0 text-primary fw-bold" style="font-size: 9px; text-transform: uppercase;"><?= $_SESSION['role'] ?></p>
                </div>
                <img src="../assets/img/profile/<?= $data_user['foto'] ?: 'default.png' ?>" style="width: 35px; height: 35px; border-radius: 8px; object-fit: cover;">
            </div>
        </div>
    </div>

    <div class="p-4">
        <div class="card rounded-4 p-4 mb-4" style="border-left: 5px solid var(--accent) !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Verifikasi Data Aktual</h5>
                    <p class="text-muted mb-0 small">Menunggu sinkronisasi dari data scan operator ke Master BOM.</p>
                </div>
                <button class="btn btn-light border btn-sm px-3 fw-bold" onclick="location.reload()" style="font-size: 12px;">
                    <i class="fas fa-sync-alt me-1"></i> REFRESH
                </button>
            </div>
        </div>

        <div class="card rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr style="font-size: 11px;" class="text-uppercase text-muted fw-800">
                            <th class="ps-4 py-3">Line</th>
                            <th>Model Name</th>
                            <th>Customer</th>
                            <th class="text-center">Actual Scan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q_antrian = mysqli_query($conn, "SELECT bl.id as bid, bl.nama_line, bl.model_name, bl.customer, COUNT(bc.id) as jml 
                                                          FROM bom_check bc 
                                                          JOIN bom_list bl ON bc.bom_id = bl.id 
                                                          WHERE bc.status_proses = 'pending' 
                                                          GROUP BY bc.bom_id");
                        
                        if(mysqli_num_rows($q_antrian) == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-clipboard-check fa-3x text-muted opacity-20 mb-3"></i>
                                    <p class="text-muted small fw-bold">Belum ada antrian verifikasi saat ini.</p>
                                </td>
                            </tr>
                        <?php endif;

                        while($row = mysqli_fetch_array($q_antrian)): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-3 fw-bold">
                                    <?= $row['nama_line'] ?>
                                </span>
                            </td>
                            <td><div class="fw-bold"><?= $row['model_name'] ?></div></td>
                            <td class="text-muted small"><?= $row['customer'] ?></td>
                            <td class="text-center">
                                <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold" style="font-size: 10px;">
                                    <?= $row['jml'] ?> ITEMS
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="" method="POST" onsubmit="return confirm('Update data ini ke Master?')">
                                    <input type="hidden" name="bom_id" value="<?= $row['bid'] ?>">
                                    <button type="submit" name="sync_data" class="btn btn-dark btn-sm px-4 rounded-pill fw-bold shadow-sm" style="font-size: 11px;">
                                        <i class="fas fa-save me-1"></i> UPDATE MASTER
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // --- THEME LOGIC (SYNCED) ---
    const toggleBtn = document.getElementById('darkModeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const body = document.body;

    if (localStorage.getItem('theme') === 'dark') {
        body.setAttribute('data-bs-theme', 'dark');
        themeIcon.classList.replace('fa-moon', 'fa-sun');
    }

    toggleBtn.addEventListener('click', () => {
        if (body.getAttribute('data-bs-theme') === 'light') {
            body.setAttribute('data-bs-theme', 'dark');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('theme', 'dark');
        } else {
            body.setAttribute('data-bs-theme', 'light');
            themeIcon.classList.replace('fa-sun', 'fa-moon');
            localStorage.setItem('theme', 'light');
        }
    });
</script>
</body>
</html>