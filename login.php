<?php
ob_start();
session_start();
include 'config/koneksi.php';

// Cek jika sudah login
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    if ($_SESSION['role'] == "admin") {
        header("location: admin/dashboard.php");
    } else {
        header("location: user/scan_check.php");
    }
    exit();
}

$login_error = false;
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama']     = $data['nama_karyawan'];
        $_SESSION['role']     = $data['role'];
        $_SESSION['status']   = "login";

        header("Location: " . ($data['role'] == "admin" ? "admin/dashboard.php" : "user/scan_check.php"));
        exit();
    } else {
        $login_error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIIX Scanner System</title>
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

        :root {
            --bg-body: #f8fafc;
            --bg-card: rgba(255, 255, 255, 0.9);
            --text-main: #0f172a;
            --nav-border: #e2e8f0;
            --accent: #f97316;
            --input-bg: #ffffff;
        }

        [data-bs-theme="dark"] {
            --bg-body: #020617;
            --bg-card: rgba(15, 23, 42, 0.8);
            --text-main: #f1f5f9;
            --nav-border: #1e293b;
            --input-bg: #0f172a;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            background-image: 
                radial-gradient(at 0% 0%, rgba(249, 115, 22, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.05) 0px, transparent 50%);
        }

        .login-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--nav-border);
            border-radius: 32px;
            padding: 50px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .brand-logo { text-align: center; margin-bottom: 40px; }
        .brand-logo h2 { 
            font-weight: 800; 
            font-size: 2rem;
            letter-spacing: -1px;
            margin: 0; 
            background: linear-gradient(45deg, var(--text-main), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-label { 
            font-size: 12px; 
            font-weight: 700; 
            color: var(--text-main); 
            opacity: 0.7;
            margin-left: 5px;
            margin-bottom: 8px;
        }

        .input-group-custom {
            background: var(--input-bg);
            border: 1px solid var(--nav-border);
            border-radius: 16px;
            padding: 4px 12px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .input-group-custom:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
            transform: translateY(-1px);
        }

        .input-group-custom i { color: var(--accent); width: 30px; font-size: 18px; }

        .input-group-custom input {
            background: transparent;
            border: none;
            color: var(--text-main);
            padding: 12px 8px;
            width: 100%;
            outline: none;
            font-size: 15px;
            font-weight: 600;
        }

        .btn-login {
            background: var(--accent);
            border: none;
            color: white;
            font-weight: 700;
            padding: 16px;
            border-radius: 16px;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s;
            font-size: 14px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn-login:hover {
            background: #ea580c;
            box-shadow: 0 12px 20px -5px rgba(249, 115, 22, 0.4);
            transform: translateY(-2px);
        }

        .theme-switch {
            position: absolute;
            top: 40px;
            right: 40px;
            cursor: pointer;
            width: 48px;
            height: 48px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-card);
            border: 1px solid var(--nav-border);
            color: var(--text-main);
            transition: 0.3s;
        }

        .theme-switch:hover { background: var(--accent); color: white; border-color: var(--accent); }

        .footer-text { margin-top: 40px; text-align: center; }
        .footer-text p { font-size: 12px; font-weight: 600; opacity: 0.5; margin: 0; }
    </style>
</head>
<body>

    <div class="theme-switch" id="themeToggle">
        <i class="fas fa-moon" id="themeIcon"></i>
    </div>

    <div class="login-card">
        <div class="brand-logo">
            <h2>SIIX</h2>
            <p class="mb-0" style="font-size: 10px; font-weight: 800; letter-spacing: 3px; opacity: 0.6; color: var(--text-main);">SCANNER ECOSYSTEM</p>
        </div>

        <form method="POST" id="loginForm">
            <div class="mb-4">
                <label class="form-label">NIK / USERNAME</label>
                <div class="input-group-custom">
                    <i class="fas fa-fingerprint"></i>
                    <input type="text" name="username" placeholder="Masukkan NIK" required autofocus autocomplete="off">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">PASSWORD</label>
                <div class="input-group-custom">
                    <i class="fas fa-shield-halved"></i>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-login" id="btnLogin">
                SIGN IN <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="footer-text">
            <p>PT SIIX ELECTRONICS INDONESIA</p>
            <p style="font-size: 10px; margin-top: 4px;">&copy; 2026 Process Engineering</p>
        </div>
    </div>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;

        function applyTheme(theme) {
            html.setAttribute('data-bs-theme', theme);
            themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            localStorage.setItem('theme', theme);
        }

        themeToggle.addEventListener('click', () => {
            const newTheme = html.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
            applyTheme(newTheme);
        });

        applyTheme(localStorage.getItem('theme') || 'light');

        // NOTIFIKASI TANPA REFRESH POSISI
        <?php if($login_error): ?>
        Swal.fire({
            icon: 'error',
            title: '<span style="font-family: Jakarta Sans; font-weight: 800;">Akses Ditolak</span>',
            html: '<span style="font-family: Jakarta Sans; font-size: 14px;">Kombinasi NIK dan Password tidak ditemukan.</span>',
            confirmButtonText: 'COBA LAGI',
            confirmButtonColor: '#f97316',
            background: html.getAttribute('data-bs-theme') === 'dark' ? '#0f172a' : '#ffffff',
            color: html.getAttribute('data-bs-theme') === 'dark' ? '#f1f5f9' : '#0f172a',
            customClass: {
                popup: 'rounded-5',
                confirmButton: 'rounded-4 px-4 py-2'
            }
        });
        <?php endif; ?>

        document.getElementById('loginForm').onsubmit = function() {
            const btn = document.getElementById('btnLogin');
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i> VERIFYING...';
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
        };
    </script>
</body>
</html>