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

// --- FUNGSI LOGGER UNTUK ACTIVITY HISTORY ---
function write_log($bom_id, $action_text) {
    global $conn;
    $username = $_SESSION['username'];
    $action_text = mysqli_real_escape_string($conn, $action_text);
    mysqli_query($conn, "INSERT INTO activity_logs (bom_id, username, action_text) VALUES ('$bom_id', '$username', '$action_text')");
}

// --- LOGIC TAMBAH MODEL BARU ---
if(isset($_POST['add_model'])){
    $line     = mysqli_real_escape_string($conn, $_POST['nama_line']);
    $customer = mysqli_real_escape_string($conn, $_POST['customer']);
    $model    = mysqli_real_escape_string($conn, $_POST['model_name']);
    $mesin    = mysqli_real_escape_string($conn, $_POST['tipe_mesin']);

    if(mysqli_query($conn, "INSERT INTO bom_list (nama_line, customer, model_name, tipe_mesin) VALUES ('$line', '$customer', '$model', '$mesin')")){
        $new_id = mysqli_insert_id($conn);
        write_log($new_id, "Membuat model baru: $model untuk line $line");
    }
    echo "<script>window.location='csv_mounter.php';</script>";
}

// --- LOGIC HAPUS DATA ITEM ---
if(isset($_POST['truncate_items'])){
    $bom_id = $_POST['bom_id'];
    mysqli_query($conn, "DELETE FROM bom_items WHERE bom_id = '$bom_id'");
    write_log($bom_id, "Isi tabel dikosongkan (semua item dihapus dari daftar mounter).");
    echo "<script>alert('Data berhasil dikosongkan!'); window.location='csv_mounter.php?manage=$bom_id';</script>";
}

// --- LOGIC IMPORT CSV ---
if(isset($_POST['import_csv'])){
    $bom_id = $_POST['bom_id'];
    $file = $_FILES['file_csv']['tmp_name'];
    if (($handle = fopen($file, "r")) !== FALSE) {
        $count = 0; $row_num = 0;
        mysqli_begin_transaction($conn);
        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $row_num++;
                if ($row_num <= 1) continue; 
                $sap = isset($data[4]) ? trim($data[4]) : '';
                $fn  = isset($data[5]) ? trim($data[5]) : '';
                $t   = isset($data[9]) ? trim($data[9]) : ''; 
                $sz  = isset($data[10]) ? trim($data[10]) : '';
                $p   = isset($data[11]) ? trim($data[11]) : '';
                
                if (!empty($sap)) {
                    $s_v = mysqli_real_escape_string($conn, $sap);
                    $f_v = mysqli_real_escape_string($conn, $fn);
                    $t_v = mysqli_real_escape_string($conn, $t); 
                    $z_v = mysqli_real_escape_string($conn, $sz);
                    $p_v = mysqli_real_escape_string($conn, $p);
                    mysqli_query($conn, "INSERT INTO bom_items (bom_id, sap_code, feeder_name, feeder_type, feeder_size, pitch) VALUES ('$bom_id', '$s_v', '$f_v', '$t_v', '$z_v', '$p_v')");
                    $count++;
                }
            }
            mysqli_commit($conn);
            fclose($handle);
            write_log($bom_id, "Perubahan isi tabel: Berhasil mengimport $count item baru via CSV.");
            echo "<script>alert('Berhasil! $count data terimport.'); window.location='csv_mounter.php?manage=$bom_id';</script>";
        } catch (Exception $e) { mysqli_rollback($conn); echo "<script>alert('Gagal!');</script>"; }
    }
}

// --- LOGIC SYNC KE MASTER DATA ---
if(isset($_POST['sync_master'])){
    $bom_id = $_POST['bom_id'];
    $res_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM bom_items WHERE bom_id = '$bom_id'");
    $data_count = mysqli_fetch_assoc($res_count);
    $total_item = $data_count['total'];

    mysqli_query($conn, "DELETE FROM master_model WHERE bom_id = '$bom_id'");
    
    $items = mysqli_query($conn, "SELECT * FROM bom_items WHERE bom_id = '$bom_id'");
    while($row = mysqli_fetch_assoc($items)){
        $sap = mysqli_real_escape_string($conn, $row['sap_code']);
        $fn  = mysqli_real_escape_string($conn, $row['feeder_name']);
        $t   = mysqli_real_escape_string($conn, $row['feeder_type']);
        $sz  = mysqli_real_escape_string($conn, $row['feeder_size']);
        $p   = mysqli_real_escape_string($conn, $row['pitch']);
        
        mysqli_query($conn, "INSERT INTO master_model (bom_id, sap_code, feeder_name, feeder_type, feeder_size, pitch) 
                             VALUES ('$bom_id', '$sap', '$fn', '$t', '$sz', '$p')");
    }
    write_log($bom_id, "Data Master diperbarui: Mengunci $total_item item sebagai data referensi utama.");
    echo "<script>alert('Sukses! Master Data telah diperbarui.'); window.location='csv_mounter.php?manage=$bom_id';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Mounter | SIIX</title>
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

        body { background-color: var(--bg-body); color: var(--text-main); font-family: 'Inter', sans-serif; transition: all 0.3s ease; overflow-x: hidden; }
        
        /* --- SIDEBAR (Sama dengan Dashboard) --- */
        #sidebar { width: 260px; height: 100vh; position: fixed; background: var(--bg-sidebar); display: flex; flex-direction: column; z-index: 1000; transition: 0.3s ease; border-right: 1px solid var(--nav-border); }
        .sidebar-header { padding: 30px 25px; text-align: center; border-bottom: 1px solid var(--nav-border); }
        .sidebar-menu { flex: 1; padding: 15px 0; overflow-y: auto; }
        .sidebar-menu .menu-label { padding: 15px 25px 5px; font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-menu a { padding: 12px 20px; margin: 4px 15px; display: flex; align-items: center; color: var(--sidebar-text); text-decoration: none; transition: 0.2s; font-size: 14px; border-radius: 10px; font-weight: 500; }
        .sidebar-menu a i { width: 25px; font-size: 17px; }
        .sidebar-menu a:hover { color: var(--accent); background: var(--sidebar-hover); }
        .sidebar-menu a.active { color: #fff; background: var(--accent); box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3); }
        .sidebar-footer { padding: 20px; border-top: 1px solid var(--nav-border); }

        /* --- MAIN CONTENT (Sama dengan Dashboard) --- */
        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; transition: 0.3s; }
        .top-nav { background: var(--bg-card); padding: 15px 30px; border-bottom: 1px solid var(--nav-border); position: sticky; top: 0; z-index: 999; }
        
        .card-custom { background-color: var(--bg-card); border: 1px solid var(--nav-border); border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }

        /* --- INPUT (Garis Dipertebal) --- */
        .form-label-custom { color: var(--text-main); font-weight: 700; font-size: 13px; margin-bottom: 8px; display: block; }
        .form-control-custom { 
            background-color: var(--bg-body); 
            border: 2px solid var(--nav-border) !important; 
            color: var(--text-main); border-radius: 10px; padding: 10px 15px; transition: 0.2s; font-weight: 500;
        }
        .form-control-custom:focus { border-color: var(--accent) !important; box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1); outline: none; }

        .theme-toggle { cursor: pointer; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--bg-body); color: var(--text-main); border: 1px solid var(--nav-border); }
        
        .box-sap { background: rgba(37, 99, 235, 0.1); color: #2563eb; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-family: 'JetBrains Mono', monospace; border: 1px solid rgba(37, 99, 235, 0.1); }
        
        .table-custom thead th { border: none; padding: 15px 20px; color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; }
        .table-custom tbody td { padding: 15px 20px; border-top: 1px solid var(--nav-border); vertical-align: middle; }

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
        <a href="csv_mounter.php" class="active"><i class="fas fa-file-csv me-3"></i> CSV Mounter</a>
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
        <h6 class="mb-0 fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">BOM Mounter Management</h6>
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
        <?php if(isset($_GET['manage'])): 
            $bom_id = $_GET['manage'];
            $header = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bom_list WHERE id='$bom_id'"));
            $check_master = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM master_model WHERE bom_id='$bom_id'"));
        ?>
            <div class="card-custom p-4 mb-4" style="border-left: 5px solid var(--accent);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <h4 class="fw-bold mb-0 text-uppercase"><?= $header['model_name'] ?></h4>
                            <?= ($check_master > 0) ? '<span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fw-bold" style="font-size:10px;">MASTER READY</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill fw-bold" style="font-size:10px;">NO MASTER</span>' ?>
                        </div>
                        <p class="text-muted mb-0 small text-uppercase fw-bold"><?= $header['nama_line'] ?> | <?= $header['customer'] ?> | <?= $header['tipe_mesin'] ?></p>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="" method="POST" onsubmit="return confirm('Update Master Data?')">
                            <input type="hidden" name="bom_id" value="<?= $bom_id ?>">
                            <button type="submit" name="sync_master" class="btn btn-warning fw-bold px-3 rounded-3 shadow-sm text-white"><i class="fas fa-sync-alt me-2"></i>SYNC MASTER</button>
                        </form>
                        <a href="csv_mounter.php" class="btn btn-light border fw-bold px-3 rounded-3 shadow-sm">KEMBALI</a>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-8">
                    <div class="card-custom p-4">
                        <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size: 11px;"><i class="fas fa-file-import me-2"></i>Import File CSV</h6>
                        <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
                            <input type="hidden" name="bom_id" value="<?= $bom_id ?>">
                            <div class="col-md-9">
                                <input type="file" name="file_csv" class="form-control-custom w-100" accept=".csv" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="import_csv" class="btn btn-primary w-100 fw-bold py-2 rounded-3">IMPORT</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-custom p-4 d-flex flex-column justify-content-center">
                        <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size: 11px;"><i class="fas fa-trash-alt me-2"></i>Clear Data</h6>
                        <form action="" method="POST" onsubmit="return confirm('Hapus semua item? Data ini akan hilang selamanya.')">
                            <input type="hidden" name="bom_id" value="<?= $bom_id ?>">
                            <button type="submit" name="truncate_items" class="btn btn-outline-danger w-100 fw-bold py-2 rounded-3">KOSONGKAN TABEL</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-custom overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr><th class="ps-4">No</th><th>Part Number</th><th>Feeder Name</th><th class="text-center">Type</th><th>Size</th><th>Pitch</th></tr>
                        </thead>
                        <tbody>
                            <?php $n=1; $items = mysqli_query($conn, "SELECT * FROM bom_items WHERE bom_id='$bom_id' ORDER BY id ASC");
                            if(mysqli_num_rows($items) > 0){
                                while($it = mysqli_fetch_array($items)): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted"><?= $n++ ?></td>
                                    <td><span class="box-sap"><?= $it['sap_code'] ?></span></td>
                                    <td class="fw-bold"><?= $it['feeder_name'] ?></td>
                                    <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><?= strtoupper($it['feeder_type']) ?></span></td>
                                    <td class="fw-bold"><?= $it['feeder_size'] ?></td>
                                    <td class="fw-bold" style="color: var(--accent);"><?= $it['pitch'] ?></td>
                                </tr>
                            <?php endwhile; } else { echo "<tr><td colspan='6' class='text-center py-5 text-muted fw-bold'>Belum ada item terdaftar.</td></tr>"; } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else: ?>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-custom p-4">
                        <h5 class="fw-bold mb-4"><i class="fas fa-plus-circle text-accent me-2" style="color: var(--accent);"></i>Tambah Model Baru</h5>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label-custom">Nama Line</label>
                                <select name="nama_line" class="form-control-custom w-100" required>
                                    <option value="">- Pilih Line -</option>
                                    <?php $l = mysqli_query($conn, "SELECT * FROM master_line"); while($dl = mysqli_fetch_array($l)) echo "<option value='".$dl['nama_line']."'>".$dl['nama_line']."</option>"; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-custom">Customer</label>
                                <input type="text" name="customer" class="form-control-custom w-100" placeholder="Input Customer" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-custom">Model Name</label>
                                <input type="text" name="model_name" class="form-control-custom w-100" placeholder="Input Model" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-custom">Machine Type</label>
                                <input type="text" name="tipe_mesin" class="form-control-custom w-100" placeholder="Input Tipe" required>
                            </div>
                            <button type="submit" name="add_model" class="btn btn-accent w-100 fw-bold py-3 rounded-3 mt-3 text-white" style="background: var(--accent); box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2);">
                                <i class="fas fa-save me-2"></i>SIMPAN MODEL
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-custom p-4 h-100">
                        <h5 class="fw-bold mb-4">Daftar Model Mounter</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr class="small text-muted"><th>LINE</th><th>CUSTOMER</th><th>MODEL NAME</th><th class="text-center">AKSI</th></tr>
                                </thead>
                                <tbody>
                                    <?php $res = mysqli_query($conn, "SELECT * FROM bom_list ORDER BY id DESC");
                                    while($row = mysqli_fetch_array($res)): ?>
                                    <tr>
                                        <td><span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-3"><?= $row['nama_line'] ?></span></td>
                                        <td class="fw-bold"><?= $row['customer'] ?></td>
                                        <td><div class="fw-bold"><?= $row['model_name'] ?></div><small class="text-muted text-uppercase"><?= $row['tipe_mesin'] ?></small></td>
                                        <td class="text-center"><a href="csv_mounter.php?manage=<?= $row['id'] ?>" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm fw-bold">Manage</a></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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
</body>
</html>