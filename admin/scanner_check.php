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

$bom_options = mysqli_query($conn, "SELECT * FROM bom_list ORDER BY model_name ASC");

$selected_bom = null;
$items = [];
if (isset($_GET['bom_id'])) {
    $bom_id = mysqli_real_escape_string($conn, $_GET['bom_id']);
    $selected_bom = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bom_list WHERE id = '$bom_id'"));
    
    if ($selected_bom) {
        $items_query = mysqli_query($conn, "SELECT * FROM bom_items WHERE bom_id = '$bom_id'");
        while ($row = mysqli_fetch_assoc($items_query)) {
            $items[] = $row['sap_code'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Check | SIIX</title>
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
        .theme-toggle { cursor: pointer; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--bg-body); color: var(--text-main); border: 1px solid var(--nav-border); }
        
        /* --- SCANNER SPECIFIC UI --- */
        .card-custom { background-color: var(--bg-card); border: 1px solid var(--nav-border); border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
        
        .status-banner { padding: 30px; text-align: center; border-bottom: 1px solid var(--nav-border); transition: 0.3s; }
        .status-banner h1 { font-weight: 900; letter-spacing: -1px; margin-bottom: 0; }
        
        .step-progress { display: flex; justify-content: space-around; padding: 15px; background: var(--bg-body); border-bottom: 1px solid var(--nav-border); }
        .step-item { text-align: center; flex: 1; opacity: 0.3; font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
        .step-item.active { opacity: 1; color: var(--accent); position: relative; }
        .step-item.active::after { content: ''; position: absolute; bottom: -15px; left: 20%; right: 20%; height: 3px; background: var(--accent); border-radius: 10px; }

        .large-input { 
            font-size: 32px !important; font-weight: 800 !important; padding: 20px !important; 
            border: 2px solid var(--nav-border) !important; text-align: center; border-radius: 16px !important; 
            background-color: var(--bg-body) !important; color: var(--text-main) !important;
        }
        .large-input:focus { border-color: var(--accent) !important; box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1) !important; }

        .info-board { background: #0f172a; color: #fff; border-radius: 20px; padding: 25px; border: 1px solid #334155; }
        .info-label { color: #64748b; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 4px; }
        .info-value { font-size: 1.2rem; font-weight: 800; color: #f8fafc; }

        .comp-card { 
            background: var(--bg-card); border-radius: 12px; padding: 16px; margin-bottom: 12px; 
            border: 1px solid var(--nav-border); display: flex; justify-content: space-between; 
            align-items: center; cursor: pointer; transition: 0.2s; 
        }
        .comp-card:hover { border-color: var(--accent); transform: translateX(5px); }
        .comp-card.active { border: 2px solid var(--accent) !important; background: rgba(249, 115, 22, 0.05); }
        
        .dot-check { width: 12px; height: 12px; border-radius: 50%; background: var(--bg-body); border: 1px solid var(--nav-border); }
        
        /* SCANNER STATES */
        .state-1 { background: #fefce8 !important; color: #854d0e !important; } 
        .state-2 { background: #eff6ff !important; color: #1e40af !important; } 
        .state-3 { background: #f0fdf4 !important; color: #166534 !important; } 
        .bg-error { background: #fef2f2 !important; color: #991b1b !important; }

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
        <a href="bom_check.php"><i class="fas fa-check-double me-3"></i> Verification</a>
        <a href="scanner_check.php" class="active"><i class="fas fa-barcode me-3"></i> Scanner Check</a>
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
        <h6 class="mb-0 fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Scanner Verification</h6>
        <div class="d-flex align-items-center gap-3">
            <button class="theme-toggle" id="darkModeToggle"><i class="fas fa-moon" id="themeIcon"></i></button>
            <div class="d-flex align-items-center gap-2 px-3 py-1 rounded-3" style="background: var(--bg-body); border: 1px solid var(--nav-border);">
                <div class="text-end text-dark">
                    <p class="mb-0 fw-bold" style="font-size: 12px;"><?= $data_user['nama_karyawan'] ?? 'Admin' ?></p>
                    <p class="mb-0 text-primary fw-bold" style="font-size: 9px; text-transform: uppercase;"><?= $_SESSION['role'] ?></p>
                </div>
                <img src="../assets/img/profile/<?= (!empty($data_user['foto'])) ? $data_user['foto'] : 'default.png' ?>" style="width: 35px; height: 35px; border-radius: 8px; object-fit: cover;">
            </div>
        </div>
    </div>

    <div class="p-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 border-start border-4 border-primary" style="background: var(--bg-card);">
            <form action="" method="GET">
                <select name="bom_id" class="form-select border-0 bg-transparent fw-bold text-main" onchange="this.form.submit()" style="height: 40px; cursor: pointer;">
                    <option value="">-- PILIH MODEL PRODUKSI --</option>
                    <?php while($b = mysqli_fetch_array($bom_options)): ?>
                        <option value="<?= $b['id'] ?>" <?= (isset($_GET['bom_id']) && $_GET['bom_id'] == $b['id']) ? 'selected' : '' ?>>
                            [<?= $b['nama_line'] ?>] <?= $b['customer'] ?> - <?= $b['model_name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>
        </div>

        <?php if ($selected_bom): ?>
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card-custom">
                    <div id="statusBanner" class="status-banner bg-light">
                        <p id="statusSub" class="small fw-bold text-muted mb-1">READY TO START</p>
                        <h1 id="statusMain">PILIH PART</h1>
                    </div>
                    
                    <div class="step-progress">
                        <div class="step-item" id="step-PART"><i class="fas fa-barcode d-block mb-1"></i>SAP</div>
                        <div class="step-item" id="step-CLL"><i class="fas fa-link d-block mb-1"></i>CLL</div>
                        <div class="step-item" id="step-SIZE"><i class="fas fa-ruler d-block mb-1"></i>SIZE</div>
                        <div class="step-item" id="step-TYPE"><i class="fas fa-tag d-block mb-1"></i>TYPE</div>
                        <div class="step-item" id="step-PITCH"><i class="fas fa-arrows-h d-block mb-1"></i>PITCH</div>
                    </div>

                    <div class="p-4 text-center">
                        <span class="badge bg-dark rounded-pill mb-4 px-4 py-2" id="cycleIndicator" style="letter-spacing: 1px;">CYCLE 0/3</span>
                        <input type="text" id="mainScanner" class="form-control large-input mb-4" placeholder="Select item from list..." disabled autocomplete="off">
                        
                        <div class="info-board">
                            <div class="row g-4 text-center">
                                <div class="col-6 border-end border-secondary border-opacity-25">
                                    <span class="info-label">PART TARGET</span>
                                    <span class="info-value text-warning" id="dispPn">-</span>
                                </div>
                                <div class="col-6">
                                    <span class="info-label">LAST CLL</span>
                                    <span class="info-value" id="dispCll">-</span>
                                </div>
                                <div class="col-12"><hr class="my-0 border-secondary border-opacity-25"></div>
                                <div class="col-4 border-end border-secondary border-opacity-25">
                                    <span class="info-label">SIZE</span><span class="info-value" id="dispSize">-</span>
                                </div>
                                <div class="col-4 border-end border-secondary border-opacity-25">
                                    <span class="info-label">TYPE</span><span class="info-value" id="dispType">-</span>
                                </div>
                                <div class="col-4">
                                    <span class="info-label">PITCH</span><span class="info-value" id="dispPitch">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-muted mb-0"><i class="fas fa-list-ul me-2 text-primary"></i>CHECKLIST KOMPONEN</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 rounded-pill"><?= count($items) ?> TOTAL</span>
                </div>
                <div style="max-height: 600px; overflow-y: auto; padding-right: 10px;">
                    <?php foreach($items as $sap_full): 
                        $p_v = array_values(array_filter(explode('@', $sap_full)));
                        $pn_v = $p_v[0] ?? 'N/A';
                        $mid = md5($sap_full);
                    ?>
                        <div class="comp-card" id="card-<?= $mid ?>" onclick="initiateScan('<?= $mid ?>', '<?= $pn_v ?>')">
                            <div>
                                <div class="fw-bold text-main" style="font-size: 15px;"><?= $pn_v ?></div>
                                <small class="text-muted" style="font-size: 10px; font-family: monospace;"><?= $sap_full ?></small>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="dot-check" id="dot-1-<?= $mid ?>"></div>
                                <div class="dot-check" id="dot-2-<?= $mid ?>"></div>
                                <div class="dot-check" id="dot-3-<?= $mid ?>"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-barcode fa-4x text-muted opacity-20 mb-3"></i>
                <h5 class="text-muted">Silahkan pilih Model Produksi untuk memulai scan.</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    /* --- LOGIC TETAP SAMA --- */
    let componentMemory = {}; 
    let targetPn = "";
    let targetId = "";
    let currentCycle = 1;
    let currentStep = 'PART';

    const mainScanner = document.getElementById('mainScanner');
    const banner = document.getElementById('statusBanner');
    const statusMain = document.getElementById('statusMain');
    const statusSub = document.getElementById('statusSub');

    const cycleColors = ['#facc15', '#3b82f6', '#22c55e']; 
    const cycleBgs = ['#fef9c3', '#dbeafe', '#dcfce7'];

    function initiateScan(id, pn) {
        targetId = id;
        targetPn = pn;
        if (!componentMemory[id]) {
            componentMemory[id] = {
                cycle: 1,
                step: 'PART',
                data: { cll: "-", size: "-", type: "-", pitch: "-" }
            };
        }
        currentCycle = componentMemory[id].cycle;
        currentStep = componentMemory[id].step;
        let memData = componentMemory[id].data;

        document.querySelectorAll('.comp-card').forEach(c => {
            c.classList.remove('active');
            const mid = c.id.replace('card-', '');
            if (componentMemory[mid]) {
                const prog = componentMemory[mid].cycle;
                if (prog > 1) {
                    c.style.backgroundColor = (prog > 3) ? cycleBgs[2] : cycleBgs[prog - 2];
                }
            }
        });

        const activeCard = document.getElementById('card-' + id);
        activeCard.classList.add('active');
        mainScanner.disabled = (currentCycle > 3);
        mainScanner.placeholder = (currentCycle > 3) ? "COMPLETED" : "Scan SAP Barcode...";
        mainScanner.value = "";
        mainScanner.focus();

        document.getElementById('dispPn').innerText = pn;
        document.getElementById('dispCll').innerText = memData.cll;
        document.getElementById('dispSize').innerText = memData.size;
        document.getElementById('dispType').innerText = memData.type;
        document.getElementById('dispPitch').innerText = memData.pitch;
        document.getElementById('cycleIndicator').innerText = currentCycle > 3 ? `COMPLETED` : `CHECK CYCLE ${currentCycle}/3`;
        updateStepUI(currentStep, currentStep === 'PART' ? 'SCAN SAP' : currentStep, 'RESUME PROGRESS', `state-${currentCycle}`);
    }

    mainScanner.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); 
            processInput();
        }
    });

    function processInput() {
        const val = mainScanner.value.trim();
        if (val !== "") {
            handleInput(val);
            mainScanner.value = ''; 
        }
    }

    function handleInput(val) {
        if (!val || !targetPn || currentCycle > 3) return;
        let mem = componentMemory[targetId];

        if (currentStep === 'PART') {
            const parts = val.split('@');
            const scannedPn = parts.length > 1 ? (parts[3] ? parts[3].trim() : "") : val;
            
            if (scannedPn === targetPn) {
                mem.step = 'CLL';
                updateStepUI('CLL', 'SCAN CLL', 'VERIFIKASI CLL MATCH', `state-${currentCycle}`);
            } else { showError("PN TIDAK MATCH! Terdeteksi: " + scannedPn); }
        } 
        else if (currentStep === 'CLL') {
            if (val === targetPn) {
                mem.data.cll = val;
                document.getElementById('dispCll').innerText = val;
                mem.step = 'SIZE';
                updateStepUI('SIZE', 'SIZE', 'INPUT FEEDER SIZE', `state-${currentCycle}`);
            } else { showError("CLL TIDAK MATCH!"); }
        }
        else if (currentStep === 'SIZE') {
            if (!isNaN(val) && val !== "") {
                mem.data.size = val;
                document.getElementById('dispSize').innerText = val;
                mem.step = 'TYPE';
                updateStepUI('TYPE', 'TYPE', 'INPUT P / E (TYPE)', `state-${currentCycle}`);
            } else { showError("HARUS ANGKA!"); }
        }
        else if (currentStep === 'TYPE') {
            const up = val.toUpperCase();
            if (up === "PAPER" || up === "P" || up === "EMBOSS" || up === "E") {
                const shortType = (up === "PAPER" || up === "P") ? "P" : "E";
                mem.data.type = shortType;
                document.getElementById('dispType').innerText = shortType;
                mem.step = 'PITCH';
                updateStepUI('PITCH', 'PITCH', 'INPUT PITCH VALUE', `state-${currentCycle}`);
            } else { showError("TYPE SALAH (P/E)!"); }
        }
        else if (currentStep === 'PITCH') {
            if (!isNaN(val) && val !== "") {
                mem.data.pitch = val;
                document.getElementById('dispPitch').innerText = val;
                processCycleEnd();
            } else { showError("HARUS ANGKA!"); }
        }
    }

    function saveToHistory(cycle) {
        let mem = componentMemory[targetId];
        const formData = new FormData();
        formData.append('action', 'simpan');
        formData.append('sap', targetPn);
        formData.append('model', "<?= $selected_bom['model_name'] ?? '' ?>");
        formData.append('cll', mem.data.cll);
        formData.append('size', mem.data.size);
        formData.append('type', mem.data.type);
        formData.append('pitch', mem.data.pitch);
        formData.append('cycle', cycle);

        fetch('simpan_history.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .catch(err => console.error("Gagal simpan:", err));
    }

    function syncToBomCheck() {
        const formData = new FormData();
        formData.append('action', 'sync_to_bom_check');
        formData.append('bom_id', "<?= $_GET['bom_id'] ?? '' ?>");
        formData.append('sap_code', targetPn);
        fetch('simpan_bom_check.php', { method: 'POST', body: formData })
        .catch(err => console.error("Gagal sinkron BOM:", err));
    }

    function processCycleEnd() {
        saveToHistory(currentCycle);
        document.getElementById(`dot-${currentCycle}-${targetId}`).style.background = cycleColors[currentCycle-1];
        const card = document.getElementById('card-' + targetId);
        card.style.backgroundColor = cycleBgs[currentCycle-1];
        let mem = componentMemory[targetId];

        if (currentCycle < 3) {
            currentCycle++;
            mem.cycle = currentCycle;
            mem.step = 'PART';
            mem.data = { cll: "-", size: "-", type: "-", pitch: "-" }; 
            document.getElementById('cycleIndicator').innerText = `CHECK CYCLE ${currentCycle}/3`;
            resetFieldTexts();
            updateStepUI('PART', 'NEXT CYCLE', `MULAI CYCLE ${currentCycle}`, `state-${currentCycle}`);
        } else {
            syncToBomCheck();
            mem.cycle = 4; 
            card.classList.add('state-3');
            document.getElementById('cycleIndicator').innerText = `COMPLETED`;
            mainScanner.disabled = true;
            alert("VERIFIKASI SELESAI");
        }
    }

    function updateStepUI(step, main, sub, colorClass) {
        currentStep = step;
        statusMain.innerText = main;
        statusSub.innerText = sub;
        banner.className = `status-banner ${colorClass}`;
        document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active'));
        const targetStep = document.getElementById(`step-${step}`);
        if(targetStep) targetStep.classList.add('active');
    }

    function showError(msg) {
        const currentClass = banner.className;
        banner.className = 'status-banner bg-error';
        statusMain.innerText = "ERROR";
        statusSub.innerText = msg;
        setTimeout(() => {
            banner.className = currentClass;
            statusMain.innerText = (currentStep === 'PART') ? "SCAN SAP" : currentStep;
        }, 1500);
    }

    function resetFieldTexts() {
        ['dispCll', 'dispSize', 'dispType', 'dispPitch'].forEach(id => {
            document.getElementById(id).innerText = "-";
        });
    }

    /* --- THEME TOGGLE LOGIC --- */
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

    document.addEventListener('click', () => { if(!mainScanner.disabled) mainScanner.focus(); });
</script>
</body>
</html>