<?php
/**
 * =====================================================================
 * FILE: login.php
 * FUNGSI: Autentikasi admin/operator rental motor.
 *
 * MODE: LOGIN BYPASS - kredensial admin/admin123 di-hardcode langsung di
 * sini, TIDAK dicek ke database sama sekali. Cocok untuk development/
 * testing/demo cepat tanpa perlu setup tabel users atau generate hash
 * password.
 *
 * PERINGATAN: JANGAN pakai mode ini untuk rental yang dipasang di server
 * publik / komputer kasir yang bisa diakses banyak orang, karena siapa
 * pun yang membaca source code ini otomatis tahu passwordnya. Untuk versi
 * aman (cek ke database dengan password_hash/password_verify), lihat blok
 * "MODE DATABASE (aman)" yang di-comment di bawah - tinggal uncomment dan
 * buat tabel users sendiri jika suatu saat ingin upgrade.
 * =====================================================================
 */
require_once __DIR__ . '/functions.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token'] ?? null);

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $errors[] = 'Username dan password wajib diisi.';

    // ---------------------------------------------------------------
    // MODE BYPASS: cek langsung ke kredensial hardcode, tanpa database.
    // ---------------------------------------------------------------
    } elseif ($username === 'admin' && $password === 'admin123') {
        session_regenerate_id(true);
        $_SESSION['user_id']      = 1; // id dummy (bukan 0, karena isLoggedIn() memakai empty())
        $_SESSION['username']     = 'admin';
        $_SESSION['nama_lengkap'] = 'Administrator Rental Motor';

        setFlash('success', 'Selamat datang kembali, Administrator Rental Motor!');
        redirect('dashboard.php');
    } else {
        $errors[] = 'Username atau password yang Anda masukkan salah.';

        /**
         * -----------------------------------------------------------
         * MODE DATABASE (aman, untuk production): hapus blok "elseif"
         * bypass di atas, lalu uncomment kode di bawah ini. Anda perlu
         * membuat tabel users sendiri (kolom: id, username, password,
         * nama_lengkap) dan mengisi password-nya dengan hasil
         * password_hash() di PHP, bukan teks biasa.
         * -----------------------------------------------------------
         *
         * $pdo = getConnection();
         * $stmt = $pdo->prepare('SELECT id, username, password, nama_lengkap FROM users WHERE username = :username LIMIT 1');
         * $stmt->execute(['username' => $username]);
         * $user = $stmt->fetch();
         *
         * if ($user && password_verify($password, $user['password'])) {
         *     session_regenerate_id(true);
         *     $_SESSION['user_id']      = $user['id'];
         *     $_SESSION['username']     = $user['username'];
         *     $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
         *
         *     setFlash('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');
         *     redirect('dashboard.php');
         * } else {
         *     $errors[] = 'Username atau password yang Anda masukkan salah.';
         * }
         */
    }
}

$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rental Motor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, #3a181c, #120f10 60%);
        }
        .login-card {
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            background: #1e1416;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            color: #f0e9e9;
        }
        .brand-title { background: linear-gradient(90deg, #ff8c42, #e63946); -webkit-background-clip: text; background-clip: text; color: transparent; font-weight: 700; }
        .form-control { background-color: #170f11; border: 1px solid rgba(255,255,255,0.12); color: #f0e9e9; }
        .form-control:focus { background-color: #170f11; color: #fff; border-color: #e63946; box-shadow: 0 0 0 .2rem rgba(230,57,70,.25); }
        .input-group-text { background-color: #170f11; border: 1px solid rgba(255,255,255,0.12); color: #b39a9a; }
        .btn-motor { background: linear-gradient(90deg, #e63946, #ff8c42); border: none; color: #1a0f10; font-weight: 700; }
        .btn-motor:hover { opacity: .9; color: #1a0f10; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-scooter" style="font-size: 2.5rem; color: #ff8c42;"></i>
                        <h4 class="mt-2 mb-0 brand-title">Rental Motor</h4>
                        <small style="color:#b39a9a;">Silakan login untuk melanjutkan</small>
                    </div>

                    <div class="alert alert-info small py-2">
                        <i class="bi bi-info-circle"></i> Mode testing: login dengan <strong>admin</strong> / <strong>admin123</strong>
                    </div>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?= clean($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <input type="hidden" name="csrf_token" value="<?= clean($csrfToken) ?>">

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="username" required autofocus
                                       value="<?= clean($_POST['username'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-motor w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-center mt-3 small" style="color:#7a5f5f;">&copy; <?= date('Y') ?> Sistem Rental Motor</p>
        </div>
    </div>
</div>
</body>
</html>
