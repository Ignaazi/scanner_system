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

// --- LOGIKA HITUNG DATA AKTUAL ---
$total_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_line = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM master_line"))['total'];
$total_model = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bom_list"))['total'];
$total_items = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bom_items"))['total'];

// Data untuk Chart
$chart_labels = [];
$chart_data = [];
$sql_chart = mysqli_query($conn, "SELECT nama_line, COUNT(*) as jml FROM bom_list GROUP BY nama_line");
while($row = mysqli_fetch_assoc($sql_chart)){
    $chart_labels[] = $row['nama_line'];
    $chart_data[] = $row['jml'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | SIIX Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            /* Light Mode Variables */
            --bg-body: #f8fafc;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --sidebar-text: #475569;
            --sidebar-hover: #f1f5f9;
            --nav-border: #e2e8f0;
            --accent: #f97316; /* SIIX Orange */
            --sidebar-header-text: #1e293b;
        }

        [data-bs-theme="dark"] {
            /* Dark Mode Variables */
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
        
        /* --- SIDEBAR REFINED --- */
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
        .sidebar-header h4 { color: var(--sidebar-header-text); }
        
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

        /* --- MAIN CONTENT --- */
        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; transition: 0.3s; }
        .top-nav { 
            background: var(--bg-card); padding: 15px 30px; 
            border-bottom: 1px solid var(--nav-border); position: sticky; top: 0; z-index: 999; 
        }
        .card { 
            background-color: var(--bg-card); border: 1px solid var(--nav-border); 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s; 
        }
        
        .theme-toggle {
            cursor: pointer; width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: var(--bg-body); color: var(--text-main); border: 1px solid var(--nav-border);
        }
        .theme-toggle:hover { background: var(--accent); color: white; border-color: var(--accent); }

        .activity-list .badge { background: var(--bg-body) !important; color: var(--text-main) !important; border: 1px solid var(--nav-border); }

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
        <a href="dashboard.php" class="active"><i class="fas fa-th-large me-3"></i> Dashboard</a>
        
        <div class="menu-label">Manajemen Data</div>
        <a href="users.php"><i class="fas fa-user-shield me-3"></i> Data User</a>
        <a href="master_line.php"><i class="fas fa-industry me-3"></i> Master Line</a>
        <a href="master_data.php"><i class="fas fa-database me-3"></i> Master Model</a>
        
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
    <div class="top-nav d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Overview Dashboard</h6>
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
        <div class="card shadow-sm rounded-4 p-4 mb-4" style="border-left: 5px solid var(--accent) !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1">Selamat Datang, <?= $data_user['nama_karyawan'] ?>!</h4>
                    <p class="text-muted mb-0 small">Sistem mendeteksi <strong><?= $total_user ?></strong> akun terdaftar hari ini.</p>
                </div>
                <div class="text-end">
                    <h5 class="fw-bold mb-0 text-primary" id="clock" style="letter-spacing: 2px;">00:00:00</h5>
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 10px;"><?= date('d F Y') ?></small>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card rounded-4 p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-primary me-3"><i class="fas fa-users fs-4"></i></div>
                        <div><p class="text-muted small mb-0 fw-bold">TOTAL USER</p><h3 class="fw-bold mb-0"><?= $total_user ?></h3></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-4 p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-success bg-opacity-10 rounded-3 text-success me-3"><i class="fas fa-industry fs-4"></i></div>
                        <div><p class="text-muted small mb-0 fw-bold">ACTIVE LINE</p><h3 class="fw-bold mb-0"><?= $total_line ?></h3></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-4 p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-warning me-3"><i class="fas fa-box fs-4"></i></div>
                        <div><p class="text-muted small mb-0 fw-bold">MASTER MODEL</p><h3 class="fw-bold mb-0"><?= $total_model ?></h3></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-4 p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-info bg-opacity-10 rounded-3 text-info me-3"><i class="fas fa-microchip fs-4"></i></div>
                        <div><p class="text-muted small mb-0 fw-bold">COMPONENTS</p><h3 class="fw-bold mb-0"><?= $total_items ?></h3></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card rounded-4 p-4 h-100">
                    <h6 class="fw-bold mb-4 text-muted"><i class="fas fa-chart-bar me-2"></i>Produksi Model per Line</h6>
                    <div style="position: relative; height:300px;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card rounded-4 p-4 h-100">
                    <h6 class="fw-bold mb-4 text-muted"><i class="fas fa-history me-2"></i>Log Aktivitas</h6>
                    <div class="activity-list">
                        <?php 
                        $recent_logs = mysqli_query($conn, "SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 5");
                        while($log = mysqli_fetch_assoc($recent_logs)): ?>
                        <div class="d-flex mb-3 border-bottom border-secondary border-opacity-10 pb-2">
                            <div class="me-3 text-center">
                                <small class="badge bg-light text-dark d-block" style="font-size: 9px; min-width: 45px;"><?= date('H:i', strtotime($log['created_at'])) ?></small>
                            </div>
                            <div>
                                <p class="mb-0 fw-bold small" style="font-size: 11px;"><?= strtoupper($log['username']) ?></p>
                                <p class="mb-0 text-muted" style="font-size: 10px; line-height: 1.3;"><?= $log['action_text'] ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <a href="history_scanner.php" class="btn btn-outline-secondary btn-sm w-100 mt-2 fw-bold" style="font-size: 11px;">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- THEME LOGIC ---
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

    // --- CLOCK ---
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent = now.getHours().toString().padStart(2, '0') + ":" + 
                        now.getMinutes().toString().padStart(2, '0') + ":" + 
                        now.getSeconds().toString().padStart(2, '0');
    }
    setInterval(updateClock, 1000); updateClock();

    // --- CHART DENGAN ANIMASI FIX ---
    const ctx = document.getElementById('lineChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Jumlah Model',
                data: <?= json_encode($chart_data) ?>,
                backgroundColor: 'rgba(249, 115, 22, 0.7)',
                borderColor: '#f97316',
                borderWidth: 1,
                borderRadius: 8,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            // Perbaikan Animasi di sini
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            },
            animations: {
                y: {
                    from: 500, // Mulai meluncur dari bawah
                    duration: 2000
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.1)', drawBorder: false }, 
                    ticks: { color: '#94a3b8' } 
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#94a3b8' } 
                }
            },
            plugins: { 
                legend: { display: false } 
            }
        }
    });
</script>
</body>
</html>