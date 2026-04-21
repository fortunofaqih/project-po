<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $p = md5($_POST['password']);
    
    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$u' AND password='$p'");
    $d = mysqli_fetch_assoc($q);
    
    if ($d) {
        // Simpan data ke session
        $_SESSION['login'] = true;
        $_SESSION['nama']  = $d['nama'];
        $_SESSION['role']  = $d['username']; // Simpan username untuk cek role di halaman lain

        // LOGIKA REDIRECT:
        if ($d['username'] == 'admin') {
            // Jika yang login adalah 'admin', arahkan ke manajemen user
            header("location: admin_user.php");
        } else {
            // Jika user lain, arahkan ke dashboard input PO
            header("location: dashboard.php");
        }
        exit(); 
    } else {
        $error = "Login gagal! Username atau Password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MCP Marketing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" type="image/png" href="assets/img/logo_mcp.png">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        }
        
        .login-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
        }
        
        .login-header h2 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 2rem;
            background: white;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 0.5rem;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-login {
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
            margin-top: 1rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
        }
        
        .login-footer {
            text-align: center;
            color: white;
            font-size: 0.9rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body class="login-container">

<div class="col-md-4 col-lg-4 col-sm-8 col-xs-12">
    <div class="login-card">
        <div class="login-header">
            <h2>🏢 MCP Marketing</h2>
            <p>Internal System Login</p>
        </div>
        
        <div class="login-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> <?= htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" autofocus required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                
                <button type="submit" name="login" class="btn btn-primary btn-login w-100">
                    <strong>Login Sekarang</strong>
                </button>
            </form>
        </div>
    </div>
    
    <div class="login-footer">
        <p>&copy; 2026 PT Mutiara Cahaya Plastindo</p>
        <small>Semua hak dilindungi</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>