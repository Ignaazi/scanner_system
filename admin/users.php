<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit();
}
include '../config/koneksi.php';

// Data User Login untuk Topbar
$user_login = $_SESSION['username'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$user_login'");
$data_user = mysqli_fetch_assoc($query_user);

// 1. FUNGSI CREATE (Update: Menggunakan Session Notifikasi)
if(isset($_POST['simpan'])){
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_karyawan']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = $_POST['role'];
    
    $foto_name = $_FILES['foto']['name'];
    if($foto_name != ""){
        $ekstensi = pathinfo($foto_name, PATHINFO_EXTENSION);
        $foto_baru = $username . "_" . time() . "." . $ekstensi;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/img/profile/" . $foto_baru);
    } else { $foto_baru = "default.png"; }

    $query = mysqli_query($conn, "INSERT INTO users (username, nama_karyawan, password, role, foto) VALUES ('$username', '$nama', '$password', '$role', '$foto_baru')");
    if($query) {
        $_SESSION['sukses'] = "Data Karyawan berhasil ditambahkan!";
        header("location:users.php");
        exit();
    }
}

// 2. FUNGSI UPDATE (Update: Menggunakan Session Notifikasi)
if(isset($_POST['update'])){
    $id          = $_POST['id'];
    $nama        = mysqli_real_escape_string($conn, $_POST['nama_karyawan']);
    $username_baru = mysqli_real_escape_string($conn, $_POST['username']);
    $role        = $_POST['role'];
    $password    = $_POST['password'];

    if($_FILES['foto_edit']['name'] != ""){
        $foto_name = $_FILES['foto_edit']['name'];
        $ekstensi = pathinfo($foto_name, PATHINFO_EXTENSION);
        $foto_baru = "upd_" . time() . "." . $ekstensi;
        move_uploaded_file($_FILES['foto_edit']['tmp_name'], "../assets/img/profile/" . $foto_baru);
        mysqli_query($conn, "UPDATE users SET foto='$foto_baru' WHERE id='$id'");
    }

    if(!empty($password)){
        $q = mysqli_query($conn, "UPDATE users SET username='$username_baru', nama_karyawan='$nama', role='$role', password='$password' WHERE id='$id'");
    } else {
        $q = mysqli_query($conn, "UPDATE users SET username='$username_baru', nama_karyawan='$nama', role='$role' WHERE id='$id'");
    }
    
    if($q) {
        $_SESSION['sukses'] = "Profil User berhasil diperbarui!";
        header("location:users.php");
        exit();
    }
}

// 3. FUNGSI DELETE (Update: Menggunakan Session Notifikasi)
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $delete = mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    if($delete) {
        $_SESSION['sukses'] = "User telah dihapus dari sistem.";
    } else {
        $_SESSION['error'] = "Gagal menghapus user!";
    }
    header("location:users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User | SIIX Scanner</title>
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

        body { background-color: var(--bg-body); color: var(--text-main); font-family: 'Inter', sans-serif; transition: 0.3s ease; overflow-x: hidden; }
        
        /* --- SIDEBAR --- */
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

        /* --- CONTENT --- */
        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; }
        .top-nav { background: var(--bg-card); padding: 15px 30px; border-bottom: 1px solid var(--nav-border); position: sticky; top: 0; z-index: 999; }
        .card { background-color: var(--bg-card); border: 1px solid var(--nav-border); border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        
        .theme-toggle {
            cursor: pointer; width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: var(--bg-body); color: var(--text-main); border: 1px solid var(--nav-border);
        }

        .table thead th { background: var(--bg-body); color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; border: none; padding: 15px; }
        .table tbody td { padding: 15px; border-bottom: 1px solid var(--nav-border); color: var(--text-main); }
        
        .form-control, .form-select { 
            background-color: var(--bg-body); border: 1px solid var(--nav-border); color: var(--text-main); border-radius: 10px; padding: 10px 15px;
        }
        .form-control:focus { background-color: var(--bg-body); color: var(--text-main); border-color: var(--accent); box-shadow: none; }

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
        <a href="users.php" class="active"><i class="fas fa-user-shield me-3"></i> Data User</a>
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
        <h6 class="mb-0 fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">User Management</h6>
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
        <div class="card p-4 mb-4" style="border-left: 5px solid var(--accent) !important;">
            <h6 class="fw-bold mb-3 text-muted"><i class="fas fa-user-plus me-2 text-primary"></i>Registrasi Karyawan Baru</h6>
            <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-3">
                    <label class="small fw-bold mb-1">NAMA LENGKAP</label>
                    <input type="text" name="nama_karyawan" class="form-control" placeholder="Input Nama..." required>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold mb-1">USERNAME</label>
                    <input type="text" name="username" class="form-control" placeholder="NIK/Inisial..." required>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold mb-1">PASSWORD</label>
                    <input type="password" name="password" class="form-control" placeholder="******" required>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold mb-1">LEVEL</label>
                    <select name="role" class="form-select">
                        <option value="user">Operator</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold mb-1">FOTO PROFIL</label>
                    <input type="file" name="foto" class="form-control">
                </div>
                <div class="col-12 text-end">
                    <button type="submit" name="simpan" class="btn btn-primary px-4 fw-bold" style="background: var(--accent); border:none;">
                        <i class="fas fa-save me-2"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>

        <div class="card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Profil</th>
                            <th>Nama Karyawan</th>
                            <th>Username</th>
                            <th>Role Access</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                        while($row = mysqli_fetch_array($sql)){
                        ?>
                        <tr>
                            <td class="ps-4">
                                <img src="../assets/img/profile/<?= $row['foto'] ?>" class="rounded-3 shadow-sm" width="40" height="40" style="object-fit:cover; border: 1px solid var(--nav-border);">
                            </td>
                            <td><div class="fw-bold"><?= $row['nama_karyawan'] ?></div><small class="text-muted">ID: #<?= $row['id'] ?></small></td>
                            <td><code class="text-primary fw-bold"><?= $row['username'] ?></code></td>
                            <td>
                                <span class="badge rounded-pill px-3 <?= $row['role'] == 'admin' ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' ?>">
                                    <?= strtoupper($row['role']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                    <i class="fas fa-edit text-warning"></i>
                                </button>
                                <a href="users.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-light border" onclick="return confirm('Hapus user ini?')">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content card shadow-lg">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <div class="modal-header border-bottom border-secondary border-opacity-10">
                                            <h5 class="modal-title fw-bold">Update User Data</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <div class="mb-3">
                                                <label class="fw-bold small mb-1">NAMA LENGKAP</label>
                                                <input type="text" name="nama_karyawan" class="form-control" value="<?= $row['nama_karyawan'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold small mb-1">USERNAME</label>
                                                <input type="text" name="username" class="form-control" value="<?= $row['username'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold small mb-1">ROLE</label>
                                                <select name="role" class="form-select">
                                                    <option value="user" <?= $row['role']=='user'?'selected':'' ?>>Operator</option>
                                                    <option value="admin" <?= $row['role']=='admin'?'selected':'' ?>>Admin</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold small mb-1">GANTI FOTO</label>
                                                <input type="file" name="foto_edit" class="form-control">
                                            </div>
                                            <div class="mb-0">
                                                <label class="fw-bold small mb-1">PASSWORD (Kosongkan jika tidak ganti)</label>
                                                <input type="password" name="password" class="form-control" placeholder="••••••••">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="update" class="btn btn-primary fw-bold px-4">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</script>

<?php 
    if (file_exists('../notifikasi.php')) {
        include '../notifikasi.php'; 
    }
?>
</body>
</html>