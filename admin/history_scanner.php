<?php
session_start();
if (!isset($_SESSION['status'])) {
    header("location:../login.php?pesan=belum_login");
    exit();
}
include '../config/koneksi.php';

$user_login = $_SESSION['username'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$user_login'");
$data_user = mysqli_fetch_assoc($query_user);

// --- LOGIKA FILTER ASLI ANDA (TIDAK DIUBAH) ---
$where_clauses = [];
if(isset($_GET['filter_date']) && !empty($_GET['filter_date'])) {
    $f_date = mysqli_real_escape_string($conn, $_GET['filter_date']);
    $where_clauses[] = "DATE(tanggal) = '$f_date'";
}
if(isset($_GET['time_start']) && !empty($_GET['time_start'])) {
    $t_start = mysqli_real_escape_string($conn, $_GET['time_start']);
    $where_clauses[] = "TIME(tanggal) >= '$t_start'";
}
if(isset($_GET['time_end']) && !empty($_GET['time_end'])) {
    $t_end = mysqli_real_escape_string($conn, $_GET['time_end']);
    $where_clauses[] = "TIME(tanggal) <= '$t_end'";
}
if(isset($_GET['search']) && !empty($_GET['search'])) {
    $s = mysqli_real_escape_string($conn, $_GET['search']);
    $where_clauses[] = "(nama_karyawan LIKE '%$s%' OR sap_code LIKE '%$s%' OR model_name LIKE '%$s%')";
}
$where_sql = count($where_clauses) > 0 ? " WHERE " . implode(" AND ", $where_clauses) : "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Scanner | SIIX System</title>
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
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

        body { background-color: var(--bg-body); color: var(--text-main); font-family: 'Inter', sans-serif; transition: background-color 0.3s; }
        
        /* SIDEBAR (MENGIKUTI DASHBOARD TERBARU) */
        #sidebar { width: 260px; height: 100vh; position: fixed; background: var(--bg-sidebar); border-right: 1px solid var(--nav-border); z-index: 1000; display: flex; flex-direction: column; }
        .sidebar-header { padding: 30px 25px; text-align: center; border-bottom: 1px solid var(--nav-border); }
        .sidebar-header h4 { color: var(--sidebar-header-text); font-weight: 800; letter-spacing: 2px; }
        .sidebar-menu { flex: 1; padding: 15px 0; overflow-y: auto; }
        .sidebar-menu .menu-label { padding: 15px 25px 5px; font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-menu a { padding: 12px 20px; margin: 4px 15px; display: flex; align-items: center; color: var(--sidebar-text); text-decoration: none; font-size: 14px; border-radius: 10px; font-weight: 500; transition: 0.2s; }
        .sidebar-menu a i { width: 25px; font-size: 17px; }
        .sidebar-menu a:hover { color: var(--accent); background: var(--sidebar-hover); }
        .sidebar-menu a.active { color: #fff; background: var(--accent); box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3); }
        .sidebar-footer { padding: 20px; border-top: 1px solid var(--nav-border); }

        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; }
        .top-nav { background: var(--bg-card); padding: 15px 30px; border-bottom: 1px solid var(--nav-border); position: sticky; top: 0; z-index: 999; }
        .theme-toggle { cursor: pointer; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--bg-body); color: var(--text-main); border: 1px solid var(--nav-border); }

        /* --- KONTEN ASLI ANDA (STYLE TIDAK DIUBAH) --- */
        .unified-card { background: var(--bg-card); border-radius: 12px; border: 1px solid var(--nav-border); box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; }
        .unified-header { padding: 25px; border-bottom: 2px solid var(--nav-border); }
        .btn-3d-csv { background: #10b981; color: white; border: none; border-bottom: 4px solid #059669; transition: 0.1s; border-radius: 6px; }
        .btn-3d-print { background: #334155; color: white; border: none; border-bottom: 4px solid #1e293b; transition: 0.1s; border-radius: 6px; }

        .table-3d thead th { 
            background: var(--bg-body); color: var(--text-main); font-size: 11px; font-weight: 800; text-transform: uppercase; 
            padding: 15px 10px; border: 1px solid var(--nav-border) !important; text-align: center !important; vertical-align: middle;
        }
        .table-3d td { padding: 12px 10px; border: 1px solid var(--nav-border) !important; text-align: center !important; vertical-align: middle; color: var(--text-main); }

        .data-box-3d { display: inline-block; padding: 5px 12px; border-radius: 6px; border: 1px solid var(--nav-border); box-shadow: 0 3px 0 var(--nav-border); font-size: 12.5px; font-weight: 700; background: var(--bg-body); }
        .box-sap { font-family: 'Consolas', monospace; color: #f97316; }
        .box-cll { color: #10b981; }

        .text-data { font-size: 13px; font-weight: 600; }
        .text-date-top { font-size: 14px; font-weight: 800; display: block; }
        .text-time { font-family: 'Consolas', monospace; font-size: 11px; font-weight: 600; color: var(--text-muted); display: block; }
        .text-model { color: #2563eb; font-weight: 700; }

        .cycle-badge { font-weight: 800; font-size: 10px; padding: 6px 14px; border-radius: 4px; border: 1px solid; display: inline-block; min-width: 90px; }
        .c-1 { background: rgba(254, 252, 232, 0.2); color: #854d0e; border-color: #fde047; } 
        .c-completed { background: var(--text-main); color: var(--bg-card); }

        @media print { #sidebar, .top-nav, .no-print { display: none !important; } .main-content { margin-left: 0 !important; width: 100% !important; } }
    </style>
</head>
<body>

<nav id="sidebar">
    <div class="sidebar-header">
        <h4 class="fw-bold mb-0">SIIX</h4>
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
        <a href="bom_check.php"><i class="fas fa-check-double me-3"></i> Verification</a>
        <a href="scanner_check.php"><i class="fas fa-barcode me-3"></i> Scanner Check</a>
        <a href="history_scanner.php" class="active"><i class="fas fa-clock-rotate-left me-3"></i> History</a>
    </div>
    <div class="sidebar-footer">
        <a href="../logout.php" style="width: 100%; display: flex; align-items: center; justify-content: center; padding: 12px; color: #ef4444; text-decoration: none; background: rgba(239, 68, 68, 0.1); border-radius: 10px; font-weight: 700;">
            <i class="fas fa-power-off me-2"></i> LOGOUT
        </a>
    </div>
</nav>

<div class="main-content">
    <div class="top-nav d-flex justify-content-between align-items-center no-print">
        <h6 class="mb-0 fw-bold text-muted text-uppercase" style="font-size: 11px;">Activity History Log</h6>
        <div class="d-flex align-items-center gap-3">
            <button class="theme-toggle" id="darkModeToggle"><i class="fas fa-moon" id="themeIcon"></i></button>
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
        <div class="unified-card shadow-lg">
            <div class="unified-header no-print">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Riwayat Scanner</h4>
                        <p class="text-muted small mb-0">Laporan aktivitas sinkronisasi data produksi.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="?export=csv" class="btn btn-3d-csv btn-sm fw-bold px-3"><i class="fas fa-file-excel me-2"></i>EXPORT CSV</a>
                        <button onclick="window.print()" class="btn btn-3d-print btn-sm fw-bold px-3"><i class="fas fa-print me-2"></i>PRINT PDF</button>
                    </div>
                </div>

                <form action="" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label style="color: #ea580c; font-size: 11px; font-weight: 800;">QUICK SEARCH</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari data..." value="<?= $_GET['search'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label style="color: #0891b2; font-size: 11px; font-weight: 800;">DATE</label>
                        <input type="date" name="filter_date" class="form-control" value="<?= $_GET['filter_date'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label style="color: #7c3aed; font-size: 11px; font-weight: 800;">START TIME</label>
                        <input type="time" name="time_start" class="form-control" value="<?= $_GET['time_start'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label style="color: #7c3aed; font-size: 11px; font-weight: 800;">END TIME</label>
                        <input type="time" name="time_end" class="form-control" value="<?= $_GET['time_end'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold shadow-sm" style="height: 38px; border-bottom: 4px solid #1d4ed8;">
                            <i class="fas fa-filter me-2"></i>FILTER
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table id="historyTable" class="table table-3d table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Model Name</th>
                            <th>SAP Code</th>
                            <th>CLL</th>
                            <th>Size</th>
                            <th>Type</th>
                            <th>Pitch</th>
                            <th>Cycle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM scanner_history $where_sql ORDER BY tanggal DESC LIMIT 500";
                        $history = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($history)): 
                            $c_val = $row['cycle'] ?? 0;
                            if($c_val >= 4) { $c_class = "c-completed"; $label = "FINISHED"; }
                            else { $c_class = "c-1"; $label = "CHECK " . $c_val; }
                        ?>
                        <tr>
                            <td>
                                <span class="text-date-top"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></span>
                                <span class="text-time"><?= date('H:i:s', strtotime($row['tanggal'])) ?></span>
                            </td>
                            <td>
                                <div class="text-data" style="font-weight: 700;"><?= $row['nama_karyawan'] ?></div>
                                <small class="text-muted" style="font-size: 10px;">ID: <?= $row['username'] ?></small>
                            </td>
                            <td><span class="text-data text-model"><?= $row['model_name'] ?></span></td>
                            <td><div class="data-box-3d box-sap"><?= $row['sap_code'] ?></div></td>
                            <td><div class="data-box-3d box-cll"><?= $row['cll'] ?></div></td>
                            <td><span class="text-data"><?= $row['size'] ?></span></td>
                            <td><span class="text-data text-uppercase"><?= $row['type'] ?></span></td>
                            <td><span class="text-data"><?= $row['pitch'] ?></span></td>
                            <td><span class="cycle-badge <?= $c_class ?>"><?= $label ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    // THEME TOGGLE SCRIPT
    const toggleBtn = document.getElementById('darkModeToggle');
    const themeIcon = document.getElementById('themeIcon');
    
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        if(theme === 'dark') {
            themeIcon.classList.replace('fa-moon', 'fa-sun');
        } else {
            themeIcon.classList.replace('fa-sun', 'fa-moon');
        }
    }

    toggleBtn.addEventListener('click', () => {
        let theme = document.documentElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', theme);
        applyTheme(theme);
    });

    $(document).ready(function() {
        $('#historyTable').DataTable({
            "order": [[ 0, "desc" ]],
            "pageLength": 50,
            "dom": '<"p-3 d-flex justify-content-between align-items-center"l>t<"p-3 d-flex justify-content-between align-items-center"ip>',
        });
    });
</script>
</body>
</html>