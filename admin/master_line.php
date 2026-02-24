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

// --- LOGIC CRUD ---
if(isset($_POST['simpan'])){
    $nama_line = mysqli_real_escape_string($conn, $_POST['nama_line']);
    $customer  = mysqli_real_escape_string($conn, $_POST['customer']);
    $model     = mysqli_real_escape_string($conn, $_POST['model']);
    
    $insert = mysqli_query($conn, "INSERT INTO master_line (nama_line, customer, model) VALUES ('$nama_line', '$customer', '$model')");
    if($insert) header("location:master_line.php?pesan=tambah_berhasil");
}

// Logic Update
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $nama_line = mysqli_real_escape_string($conn, $_POST['nama_line']);
    $customer  = mysqli_real_escape_string($conn, $_POST['customer']);
    $model     = mysqli_real_escape_string($conn, $_POST['model']);
    
    $update = mysqli_query($conn, "UPDATE master_line SET nama_line='$nama_line', customer='$customer', model='$model' WHERE id='$id'");
    if($update) header("location:master_line.php?pesan=update_berhasil");
}

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM master_line WHERE id='$id'");
    header("location:master_line.php?pesan=hapus_berhasil");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Line | SIIX Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
        
        .form-control { 
            background-color: var(--bg-body); border: 1px solid var(--nav-border); color: var(--text-main); border-radius: 10px; padding: 10px 15px;
        }
        .form-control:focus { background-color: var(--bg-body); color: var(--text-main); border-color: var(--accent); box-shadow: none; }

        @media (max-width: 768px) { #sidebar { margin-left: -260px; } .main-content { margin-left: 0; width: 100%; } }
    </style>
</head>
<body data-bs-theme="light">

<?php if(isset($_GET['pesan'])): ?>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const pesan = urlParams.get('pesan');
        if(pesan === 'tambah_berhasil'){
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data Line baru telah ditambahkan.', timer: 2000, showConfirmButton: false });
        } else if(pesan === 'update_berhasil'){
            Swal.fire({ icon: 'success', title: 'Terupdate!', text: 'Data Line berhasil diperbarui.', timer: 2000, showConfirmButton: false });
        } else if(pesan === 'hapus_berhasil'){
            Swal.fire({ icon: 'success', title: 'Dihapus!', text: 'Data Line telah dihapus dari sistem.', timer: 2000, showConfirmButton: false });
        }
        // Bersihkan URL dari parameter pesan tanpa reload
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
<?php endif; ?>

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
        <a href="master_line.php" class="active"><i class="fas fa-industry me-3"></i> Master Line</a>
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
        <h6 class="mb-0 fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Production Line Setup</h6>
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
            <h6 class="fw-bold mb-3 text-muted"><i class="fas fa-plus-circle me-2 text-primary"></i>Registrasi Production Line</h6>
            <form action="" method="POST" class="row g-3">
                <div class="col-md-3">
                    <label class="small fw-bold mb-1">NAMA LINE</label>
                    <input type="text" name="nama_line" class="form-control" placeholder="Contoh: LINE 01" required>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold mb-1">CUSTOMER</label>
                    <input type="text" name="customer" class="form-control" placeholder="Contoh: TOYOTA" required>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold mb-1">MODEL UNIT</label>
                    <input type="text" name="model" class="form-control" placeholder="Contoh: HEAD UNIT A1" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="simpan" class="btn btn-primary w-100 fw-bold" style="background: var(--accent); border:none; height: 45px;">
                        <i class="fas fa-save me-2"></i>SIMPAN
                    </button>
                </div>
            </form>
        </div>

        <div class="card overflow-hidden">
            <div class="p-3 border-bottom bg-light bg-opacity-10">
                <h6 class="mb-0 fw-bold text-muted" style="font-size: 12px;">DATABASE PRODUCTION LINE</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="70">No</th>
                            <th>Nama Line</th>
                            <th>Customer Name</th>
                            <th>Current Model</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = mysqli_query($conn, "SELECT * FROM master_line ORDER BY nama_line ASC");
                        if (mysqli_num_rows($sql) > 0) {
                            while($row = mysqli_fetch_array($sql)){
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-muted"><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-2 me-3">
                                        <i class="fas fa-industry shadow-sm"></i>
                                    </div>
                                    <span class="fw-bold"><?= $row['nama_line'] ?></span>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 rounded-pill"><?= $row['customer'] ?></span></td>
                            <td class="fw-medium text-muted"><?= $row['model'] ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border" onclick="konfirmasiHapus(<?= $row['id'] ?>)">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--nav-border); border-radius: 16px;">
                                    <div class="modal-header border-bottom">
                                        <h6 class="modal-title fw-bold"><i class="fas fa-edit me-2 text-primary"></i>Edit Data Line</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="" method="POST">
                                        <div class="modal-body p-4">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <div class="mb-3">
                                                <label class="small fw-bold mb-1">NAMA LINE</label>
                                                <input type="text" name="nama_line" class="form-control" value="<?= $row['nama_line'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="small fw-bold mb-1">CUSTOMER</label>
                                                <input type="text" name="customer" class="form-control" value="<?= $row['customer'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="small fw-bold mb-1">MODEL UNIT</label>
                                                <input type="text" name="model" class="form-control" value="<?= $row['model'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top p-3">
                                            <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">BATAL</button>
                                            <button type="submit" name="update" class="btn btn-sm btn-primary fw-bold" style="background: var(--accent); border:none;">SIMPAN PERUBAHAN</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='5' class='text-center p-5 text-muted'>Belum ada data line terdaftar.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --- KONFIRMASI HAPUS ---
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data Line ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "master_line.php?hapus=" + id;
            }
        })
    }

    // --- DARK MODE LOGIC ---
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