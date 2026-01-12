<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit();
}
include '../config/koneksi.php';

// Data User Login
$user_login = $_SESSION['username'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$user_login'");
$data_user = mysqli_fetch_assoc($query_user);

// Data untuk Filter Line
$list_line = mysqli_query($conn, "SELECT * FROM master_line ORDER BY nama_line ASC");

// Query Master Model
$query_master = mysqli_query($conn, "SELECT b.nama_line, b.customer, b.model_name, b.id as bom_id, b.tipe_mesin,
                                     COUNT(m.id) as total_item, MAX(m.created_at) as last_update
                                     FROM bom_list b 
                                     LEFT JOIN master_model m ON b.id = m.bom_id 
                                     GROUP BY b.id 
                                     ORDER BY b.nama_line ASC, b.customer ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Repository | SIIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* SINKRONISASI TEMA DENGAN DASHBOARD */
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

        body { background-color: var(--bg-body); color: var(--text-main); font-family: 'Inter', sans-serif; transition: 0.3s ease; overflow-x: hidden; }
        
        /* SIDEBAR */
        #sidebar {
            width: 260px; height: 100vh; position: fixed;
            background: var(--bg-sidebar); display: flex; flex-direction: column; 
            z-index: 1000; transition: 0.3s ease; border-right: 1px solid var(--nav-border);
        }
        .sidebar-header { padding: 30px 25px; text-align: center; border-bottom: 1px solid var(--nav-border); }
        .sidebar-menu { flex: 1; padding: 15px 0; overflow-y: auto; }
        .sidebar-menu .menu-label { padding: 15px 25px 5px; font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-menu a {
            padding: 12px 20px; margin: 4px 15px; display: flex; align-items: center;
            color: var(--sidebar-text); text-decoration: none; transition: 0.2s; font-size: 14px; border-radius: 10px;
        }
        .sidebar-menu a i { width: 25px; font-size: 17px; }
        .sidebar-menu a:hover { color: var(--accent); background: var(--sidebar-hover); }
        .sidebar-menu a.active { color: #fff; background: var(--accent); box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3); }
        .sidebar-footer { padding: 20px; border-top: 1px solid var(--nav-border); }

        /* CONTENT */
        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; }
        .top-nav { background: var(--bg-card); padding: 15px 30px; border-bottom: 1px solid var(--nav-border); position: sticky; top: 0; z-index: 999; }
        
        .theme-toggle {
            cursor: pointer; width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: var(--bg-body); color: var(--text-main); border: 1px solid var(--nav-border);
        }

        /* SEARCH & FILTER AREA (Themed) */
        .filter-card { background: var(--bg-card); border-radius: 15px; padding: 20px; border: 1px solid var(--nav-border); }
        .search-box { background: var(--bg-body); border: 1px solid var(--nav-border); border-radius: 10px; padding: 5px 15px; display: flex; align-items: center; transition: 0.3s; }
        .search-box:focus-within { border-color: var(--accent); background: var(--bg-card); }
        .search-box input { border: none; outline: none; padding: 8px; width: 100%; font-size: 14px; background: transparent; color: var(--text-main); font-weight: 500; }
        
        /* 3D ROW TABLE (Themed) */
        .table-custom { border-collapse: separate; border-spacing: 0 10px; width: 100%; }
        .table-custom thead th { border: none; padding: 10px 20px; color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; }
        
        .table-custom tbody tr { 
            background: var(--bg-card); 
            transition: all 0.25s ease; 
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        
        .table-custom tbody tr:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .table-custom tbody td { padding: 18px 20px; border: none; vertical-align: middle; color: var(--text-main); }
        
        .table-custom tbody td:first-child { 
            border-radius: 12px 0 0 12px; 
            border-left: 5px solid var(--nav-border); 
        }
        .table-custom tr:hover td:first-child { border-left-color: var(--accent); }
        .table-custom tbody td:last-child { border-radius: 0 12px 12px 0; }

        /* Badges & Typography */
        .text-model { font-weight: 800; color: var(--text-main); font-size: 16px; margin-bottom: 0; }
        .text-customer { color: var(--accent); font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
        .badge-line { background: var(--text-main); color: var(--bg-card); padding: 7px 15px; border-radius: 8px; font-weight: 800; font-size: 13px; }
        .badge-count { background: rgba(37, 99, 235, 0.1); color: #2563eb; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px; border: 1px solid rgba(37, 99, 235, 0.2); }
        
        .btn-view { background: var(--bg-body); color: var(--text-main); border: 2px solid var(--nav-border); padding: 10px 20px; border-radius: 10px; font-weight: 800; font-size: 12px; transition: 0.3s; }
        .btn-view:hover { background: var(--accent); color: #fff; border-color: var(--accent); }
        
        @media (max-width: 768px) { #sidebar { margin-left: -260px; } .main-content { margin-left: 0; width: 100%; } }
    </style>
</head>
<body data-bs-theme="light">

<nav id="sidebar">
    <div class="sidebar-header">
        <h4 class="fw-bold mb-0" style="letter-spacing: 2px; color: var(--sidebar-header-text);">SIIX</h4>
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
        <a href="bom_Check.php"><i class="fas fa-check-double me-3"></i> Verification</a>
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
        <h6 class="mb-0 fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Master Repository</h6>
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
        <div class="filter-card mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="search-box">
                        <i class="fas fa-search text-muted"></i>
                        <input type="text" id="searchInput" placeholder="Cari Model, Customer, atau Spesifikasi..." onkeyup="filterTable()">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select border-1 fw-bold" id="lineFilter" onchange="filterTable()" style="height: 45px; border-radius: 10px; font-size: 14px; background: var(--bg-body); color: var(--text-main); border-color: var(--nav-border);">
                        <option value="">Semua Line</option>
                        <?php while($line = mysqli_fetch_assoc($list_line)): ?>
                            <option value="<?= $line['nama_line'] ?>"><?= $line['nama_line'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-block px-4 py-2 rounded-3" style="background: var(--bg-body); border: 1px solid var(--nav-border);">
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 10px;">Total Record:</small>
                        <span class="fw-bold ms-1" id="countDisplay" style="font-size: 16px; color: var(--accent);"><?= mysqli_num_rows($query_master) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive" style="overflow: visible;">
            <table class="table-custom align-middle" id="masterTable">
                <thead>
                    <tr>
                        <th>Line</th>
                        <th>Model Information</th>
                        <th>Machine</th>
                        <th class="text-center">Components</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($query_master) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($query_master)): ?>
                        <tr class="model-row" data-line="<?= $row['nama_line'] ?>">
                            <td><span class="badge-line"><?= $row['nama_line'] ?></span></td>
                            <td>
                                <div class="text-customer"><?= $row['customer'] ?></div>
                                <div class="text-model"><?= $row['model_name'] ?></div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 text-main border p-2 px-3 rounded-pill fw-bold" style="font-size: 10px; background: var(--accent);">
                                    <i class="fas fa-gear fa-spin me-1"></i><?= $row['tipe_mesin'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-count"><i class="fas fa-list-ol me-2"></i><?= $row['total_item'] ?> Items</span>
                            </td>
                            <td class="small text-muted">
                                <span class="d-flex align-items-center text-success fw-bold mb-1" style="font-size: 11px;">
                                    <i class="fas fa-lock me-1"></i> LOCKED
                                </span>
                                <i class="far fa-calendar-alt me-1"></i> <?= $row['last_update'] ? date('d/m/y', strtotime($row['last_update'])) : '-' ?>
                            </td>
                            <td class="text-center">
                                <a href="view_master_detail.php?id=<?= $row['bom_id'] ?>" class="btn btn-view shadow-sm">
                                    DETAILS <i class="fas fa-arrow-right-long ms-2"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Data Master belum tersedia.</h6>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // DARK MODE LOGIC
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

    // FILTER LOGIC
    function filterTable() {
        let input = document.getElementById('searchInput').value.toUpperCase();
        let filter = document.getElementById('lineFilter').value.toUpperCase();
        let rows = document.querySelectorAll('.model-row');
        let count = 0;

        rows.forEach(row => {
            let text = row.innerText.toUpperCase();
            let line = row.getAttribute('data-line').toUpperCase();
            
            let showSearch = text.includes(input);
            let showLine = filter === "" || line === filter;

            if (showSearch && showLine) {
                row.style.display = "";
                count++;
            } else {
                row.style.display = "none";
            }
        });
        document.getElementById('countDisplay').innerText = count;
    }
</script>

</body>
</html>