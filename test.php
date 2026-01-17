<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit();
}
include '../config/koneksi.php';

$user_login = $_SESSION['username'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$user_login'");
$data_user = mysqli_fetch_assoc($query_user);

// --- LOGIKA HITUNG DATA SEKOLAH ---
$total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='siswa'"))['total'];
$total_guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='guru'"))['total'];
$total_kelas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM master_kelas"))['total'];
$total_mapel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM master_mapel"))['total'];

// Data untuk Chart (Siswa per Kelas)
$chart_labels = []; $chart_data = [];
$sql_chart = mysqli_query($conn, "SELECT k.nama_kelas, COUNT(u.id_user) as jml FROM master_kelas k LEFT JOIN users u ON u.role='siswa' GROUP BY k.nama_kelas");
while($row = mysqli_fetch_assoc($sql_chart)){
    $chart_labels[] = $row['nama_kelas'];
    $chart_data[] = $row['jml'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-body: #f4f7fe;
            --bg-sidebar: #ffffff;
            --accent: #4361ee; /* School Blue */
            --nav-border: #e2e8f0;
        }
        [data-bs-theme="dark"] {
            --bg-body: #0b1137;
            --bg-sidebar: #111c44;
            --accent: #758bff;
            --nav-border: #1b254b;
        }
        body { background: var(--bg-body); font-family: 'Plus Jakarta Sans', sans-serif; transition: 0.3s; }
        #sidebar { width: 260px; height: 100vh; position: fixed; background: var(--bg-sidebar); border-right: 1px solid var(--nav-border); z-index: 1000; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; }
        .sidebar-menu a { padding: 12px 20px; margin: 4px 15px; display: flex; align-items: center; color: #8b95b7; text-decoration: none; border-radius: 12px; font-weight: 600; transition: 0.2s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { color: var(--accent); background: rgba(67, 97, 238, 0.1); }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        @media (max-width: 768px) { #sidebar { display: none; } .main-content { margin-left: 0; width: 100%; } }
    </style>
</head>
<body data-bs-theme="light">

<nav id="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold text-primary">E-SCHOOL</h4>
        <p class="text-muted small">Academic System</p>
    </div>
    <div class="sidebar-menu">
        <a href="dashboard.php" class="active"><i class="fas fa-home me-3"></i> Dashboard</a>
        <div class="px-4 mt-4 mb-2 small fw-bold text-muted text-uppercase">Data Master</div>
        <a href="users.php"><i class="fas fa-users-cog me-3"></i> Manajemen User</a>
        <a href="kelas.php"><i class="fas fa-school me-3"></i> Data Kelas</a>
        <a href="mapel.php"><i class="fas fa-book me-3"></i> Mata Pelajaran</a>
        <div class="px-4 mt-4 mb-2 small fw-bold text-muted text-uppercase">Laporan</div>
        <a href="absensi.php"><i class="fas fa-calendar-check me-3"></i> Absensi</a>
        <a href="../logout.php" class="text-danger mt-5"><i class="fas fa-sign-out-alt me-3"></i> Logout</a>
    </div>
</nav>

<div class="main-content">
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Dashboard Overview</h3>
                <p class="text-muted">Halo, <?= $data_user['nama_lengkap'] ?>. Berikut statistik sekolah hari ini.</p>
            </div>
            <button class="btn btn-white shadow-sm rounded-3" id="darkModeToggle"><i class="fas fa-moon"></i></button>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-4 text-primary me-3"><i class="fas fa-user-graduate fs-4"></i></div>
                        <div><p class="text-muted small mb-0">Total Siswa</p><h4 class="fw-bold mb-0"><?= $total_siswa ?></h4></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-success bg-opacity-10 rounded-4 text-success me-3"><i class="fas fa-chalkboard-teacher fs-4"></i></div>
                        <div><p class="text-muted small mb-0">Total Guru</p><h4 class="fw-bold mb-0"><?= $total_guru ?></h4></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-4 text-warning me-3"><i class="fas fa-door-open fs-4"></i></div>
                        <div><p class="text-muted small mb-0">Kelas</p><h4 class="fw-bold mb-0"><?= $total_kelas ?></h4></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-info bg-opacity-10 rounded-4 text-info me-3"><i class="fas fa-book-open fs-4"></i></div>
                        <div><p class="text-muted small mb-0">Mapel</p><h4 class="fw-bold mb-0"><?= $total_mapel ?></h4></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card p-4">
                    <h6 class="fw-bold mb-4">Grafik Siswa per Kelas</h6>
                    <canvas id="schoolChart" height="300"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <h6 class="fw-bold mb-4">Log Aktivitas Terbaru</h6>
                    <div class="activity-list">
                        <?php 
                        $recent_logs = mysqli_query($conn, "SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 5");
                        while($log = mysqli_fetch_assoc($recent_logs)): ?>
                        <div class="d-flex mb-3 align-items-center">
                            <div class="badge bg-light text-dark me-3"><?= date('H:i', strtotime($log['created_at'])) ?></div>
                            <div>
                                <p class="mb-0 small fw-bold"><?= $log['username'] ?></p>
                                <p class="mb-0 text-muted extra-small" style="font-size: 11px;"><?= $log['action_text'] ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Theme Toggle
    const btn = document.getElementById('darkModeToggle');
    btn.onclick = () => {
        const theme = document.body.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
        document.body.setAttribute('data-bs-theme', theme);
    };

    // Chart
    const ctx = document.getElementById('schoolChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Jumlah Siswa',
                data: <?= json_encode($chart_data) ?>,
                backgroundColor: '#4361ee',
                borderRadius: 10
            }]
        }
    });
</script>
</body>
</html>