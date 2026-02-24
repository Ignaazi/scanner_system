<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit();
}
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header("location:master_data.php");
    exit();
}

$bom_id = mysqli_real_escape_string($conn, $_GET['id']);
$query_header = mysqli_query($conn, "SELECT * FROM bom_list WHERE id = '$bom_id'");
$header = mysqli_fetch_assoc($query_header);

if (!$header) {
    echo "<script>alert('Data Model tidak ditemukan!'); window.location='master_data.php';</script>";
    exit();
}

$user_login = $_SESSION['username'];
$data_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE username = '$user_login'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repository Detail | <?= $header['model_name'] ?></title>
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

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
        
        #sidebar {
            width: 260px; height: 100vh; position: fixed;
            background: var(--bg-sidebar); display: flex; flex-direction: column; 
            z-index: 1000; transition: 0.3s ease; 
            border-right: 1px solid var(--nav-border);
        }
        .sidebar-header { 
            padding: 30px 25px; text-align: center; 
            border-bottom: 1px solid var(--nav-border); 
        }
        .sidebar-header h4 { color: var(--sidebar-header-text); letter-spacing: 2px; }
        
        .sidebar-menu { flex: 1; padding: 15px 0; overflow-y: auto; }
        .sidebar-menu .menu-label { 
            padding: 15px 25px 5px; font-size: 10px; font-weight: 800; 
            color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; 
        }
        .sidebar-menu a {
            padding: 12px 20px; margin: 4px 15px; display: flex; align-items: center;
            color: var(--sidebar-text); text-decoration: none; transition: 0.2s; 
            font-size: 14px; border-radius: 10px; font-weight: 500;
        }
        .sidebar-menu a i { width: 25px; font-size: 17px; }
        .sidebar-menu a:hover { color: var(--accent); background: var(--sidebar-hover); }
        .sidebar-menu a.active { 
            color: #fff; background: var(--accent); 
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3); 
        }
        .sidebar-footer { padding: 20px; border-top: 1px solid var(--nav-border); }

        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; transition: 0.3s; }
        .top-nav { 
            background: var(--bg-card); padding: 15px 30px; 
            border-bottom: 1px solid var(--nav-border); position: sticky; top: 0; z-index: 999; 
        }

        .model-banner-3d { 
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 16px; padding: 25px 30px; margin: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-bottom: 5px solid var(--accent);
        }
        .text-3d { color: #fff; font-weight: 900; letter-spacing: 1px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); margin: 0; text-transform: uppercase; }

        .dashboard-container { display: flex; gap: 20px; padding: 0 25px 25px 25px; align-items: flex-start; }
        .table-side { flex: 3; }
        .card-custom { background: var(--bg-card); border: 1px solid var(--nav-border); border-radius: 12px; overflow: hidden; }

        .table-excel { width: 100%; border-collapse: collapse; background: var(--bg-card); }
        .table-excel thead th { 
            background: var(--bg-body); border: 1px solid var(--nav-border); 
            padding: 12px; font-size: 11px; text-transform: uppercase; color: var(--text-main); font-weight: 800; text-align: center;
        }
        .table-excel tbody td { border: 1px solid var(--nav-border); padding: 12px; font-size: 13px; color: var(--text-main); text-align: center; vertical-align: middle; }
        
        /* ADJUSTMENT UNTUK PLACEMENT BANYAK */
        .placement-scroll {
            max-height: 60px; 
            max-width: 200px;
            overflow-y: auto;
            font-size: 11px;
            padding: 5px;
            background: rgba(0,0,0,0.03);
            border-radius: 4px;
            line-height: 1.4;
            text-align: left;
            word-wrap: break-word;
            margin: 0 auto;
        }
        [data-bs-theme="dark"] .placement-scroll {
            background: rgba(255,255,255,0.05);
        }
        /* Custom Scrollbar Mini */
        .placement-scroll::-webkit-scrollbar { width: 4px; }
        .placement-scroll::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 10px; }

        .history-side { 
            flex: 1; min-width: 330px; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--nav-border);
            height: calc(100vh - 180px); display: flex; flex-direction: column; position: sticky; top: 100px;
        }
        .chat-box { flex: 1; overflow-y: auto; padding: 15px; }
        .chat-item { margin-bottom: 15px; padding-left: 12px; border-left: 3px solid var(--nav-border); }
        .chat-item.system { border-left-color: var(--accent); }
        .chat-user { font-size: 11px; font-weight: 800; color: var(--accent); display: block; }
        .chat-msg { font-size: 12px; color: var(--text-main); display: block; margin-top: 2px; line-height: 1.4; }

        .theme-toggle {
            cursor: pointer; width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: var(--bg-body); color: var(--text-main); border: 1px solid var(--nav-border);
        }
        .theme-toggle:hover { background: var(--accent); color: white; border-color: var(--accent); }

        .sap-tag { background: #0f172a; color: #fff; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-size: 12px; }

        @media (max-width: 768px) { #sidebar { margin-left: -260px; } .main-content { margin-left: 0; width: 100%; } }
        @media print { .no-print { display: none !important; } .main-content { margin-left: 0; width: 100%; } .placement-scroll { max-height: none; overflow: visible; } }
    </style>
</head>
<body>

<nav id="sidebar" class="no-print">
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
        <a href="master_data.php" class="active"><i class="fas fa-database me-3"></i> Master Model</a>
        <div class="menu-label">Operasional</div>
        <a href="csv_mounter.php"><i class="fas fa-file-csv me-3"></i> CSV Mounter</a>
        <a href="bom_check.php"><i class="fas fa-check-double me-3"></i> Verification</a>
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
    <div class="top-nav d-flex justify-content-between align-items-center no-print">
        <div class="d-flex align-items-center gap-3">
            <a href="master_data.php" class="theme-toggle" style="text-decoration:none;"><i class="fas fa-arrow-left"></i></a>
            <h6 class="mb-0 fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Repository Detail</h6>
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

    <div class="model-banner-3d">
        <span class="badge bg-warning text-dark mb-1" style="font-size: 10px; font-weight: 800;"><?= $header['customer'] ?></span>
        <h2 class="text-3d"><?= $header['model_name'] ?></h2>
        <div class="small text-light opacity-75 mt-1">
            <i class="fas fa-industry me-1"></i> Line: <?= $header['nama_line'] ?> <span class="mx-2">|</span> 
            <i class="fas fa-microchip me-1"></i> Machine: <?= $header['tipe_mesin'] ?>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="table-side">
            <div class="card-custom shadow-sm">
                <div class="p-3 border-bottom d-flex gap-2 align-items-center no-print">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari Part Number..." onkeyup="filterTable()">
                    </div>
                    <div class="ms-auto d-flex gap-2">
                        <button onclick="window.print()" class="btn btn-light btn-sm border fw-bold" style="font-size:11px; background: var(--bg-body); color: var(--text-main); border-color: var(--nav-border)!important;"><i class="fas fa-print me-1"></i>PRINT</button>
                        <a href="export_csv.php?id=<?= $bom_id ?>" class="btn btn-success btn-sm fw-bold" style="font-size:11px;"><i class="fas fa-file-csv me-1"></i>EXPORT CSV</a>
                        <a href="csv_mounter.php?manage=<?= $bom_id ?>" class="btn btn-primary btn-sm fw-bold" style="font-size:11px;"><i class="fas fa-cog me-1"></i>MANAGE</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-excel mb-0" id="mainTable">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Machine</th>
                                <th>Table</th>
                                <th>Slot</th>
                                <th onclick="sortTable(4)" style="cursor:pointer">PART NUMBER <i class="fas fa-sort ms-1"></i></th>
                                <th onclick="sortTable(5)" style="cursor:pointer">Description <i class="fas fa-sort ms-1"></i></th>
                                <th width="220">Placement</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Pitch</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php 
                            $n = 1;
                            $items = mysqli_query($conn, "SELECT * FROM master_model WHERE bom_id = '$bom_id' ORDER BY machine ASC, table_no ASC, slot ASC");
                            while($it = mysqli_fetch_array($items)): 
                            ?>
                            <tr>
                                <td class="text-muted small"><?= $n++ ?></td>
                                <td class="fw-bold"><?= $it['machine'] ?: '-' ?></td>
                                <td><?= $it['table_no'] ?: '-' ?></td>
                                <td class="text-primary fw-bold"><?= $it['slot'] ?: '-' ?></td>
                                <td><span class="sap-tag"><?= $it['sap_code'] ?></span></td>
                                <td class="text-start px-3 fw-bold"><?= $it['feeder_name'] ?></td>
                                <td>
                                    <div class="placement-scroll">
                                        <?= $it['placement'] ?: '-' ?>
                                    </div>
                                </td>
                                <td><span class="badge border text-dark bg-white" style="font-size: 9px;"><?= strtoupper($it['feeder_type']) ?></span></td>
                                <td class="fw-bold"><?= $it['feeder_size'] ?></td>
                                <td><span class="badge bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 border"><?= $it['pitch'] ?>mm</span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="history-side no-print shadow-sm">
            <div class="p-3 border-bottom fw-bold text-muted small text-uppercase" style="letter-spacing: 1px;">
                <i class="fas fa-history me-2 text-primary"></i> Activity History
            </div>
            <div class="chat-box">
                <div class="chat-item system">
                    <span class="chat-user">SYSTEM MONITOR</span>
                    <span class="chat-msg">Model <strong><?= $header['model_name'] ?></strong> has been accessed successfully.</span>
                </div>
                <div class="chat-item">
                    <span class="chat-user"><?= strtoupper($data_user['username']) ?></span>
                    <span class="chat-msg">Viewing details for <?= $header['nama_line'] ?> repository.</span>
                </div>
            </div>
            <div class="p-3 bg-opacity-10 bg-primary border-top text-center">
                <small class="text-muted fw-bold" style="font-size: 8px; letter-spacing: 1px;">DATABASE CONNECTED</small>
            </div>
        </div>
    </div>
</div>

<script>
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

    function filterTable() {
        let input = document.getElementById("searchInput").value.toUpperCase();
        let rows = document.querySelectorAll("#tableBody tr");
        rows.forEach(row => {
            let text = row.innerText.toUpperCase();
            row.style.display = (text.includes(input)) ? "" : "none";
        });
    }

    function sortTable(n) {
        let table = document.getElementById("mainTable");
        let switching = true, i, x, y, shouldSwitch, dir = "asc", switchcount = 0;
        while (switching) {
            switching = false;
            let rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) { shouldSwitch = true; break; }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) { shouldSwitch = true; break; }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;
            } else {
                if (switchcount == 0 && dir == "asc") { dir = "desc"; switching = true; }
            }
        }
    }
</script>
</body>
</html>