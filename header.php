<?php
/**
 * =====================================================================
 * FILE: header.php
 * FUNGSI: Template bagian atas (head, navbar, sidebar) bertema motor.
 * =====================================================================
 */
if (!isLoggedIn()) {
    requireLogin();
}
$pageTitle = $pageTitle ?? 'Rental Motor';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= clean($pageTitle) ?> - Rental Motor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #120f10;
            font-family: 'Inter', sans-serif;
            color: #f0e9e9;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #241417 0%, #120f10 100%);
            border-right: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar a { color: #b39a9a; text-decoration: none; }
        .sidebar a.active, .sidebar a:hover { background: linear-gradient(90deg, #e63946, #ff8c42); color: #1a0f10; font-weight: 700; }
        .sidebar .nav-link { padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 6px; font-weight: 500; transition: all .15s; }
        .brand-title { background: linear-gradient(90deg, #ff8c42, #e63946); -webkit-background-clip: text; background-clip: text; color: transparent; font-weight: 700; }
        .card-stat, .card-glass {
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            background: #1e1416;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            color: #f0e9e9;
        }
        .card-glass .card-header { background: transparent; border-bottom: 1px solid rgba(255,255,255,0.07); color: #d9c6c6; font-weight: 600; }
        .table { color: #f0e9e9; }
        .table > :not(caption) > * > * { background-color: transparent; color: #f0e9e9; }
        .table-hover > tbody > tr:hover > * { background-color: rgba(255,255,255,0.04); }
        .table-light, thead.table-light th { background-color: #2b1c1f !important; color: #d9c6c6 !important; border-color: rgba(255,255,255,0.07); }
        .form-control, .form-select {
            background-color: #170f11; border: 1px solid rgba(255,255,255,0.12); color: #f0e9e9;
        }
        .form-control:focus, .form-select:focus {
            background-color: #170f11; color: #fff; border-color: #e63946; box-shadow: 0 0 0 .2rem rgba(230,57,70,.25);
        }
        .modal-content { background-color: #1e1416; color: #f0e9e9; }
        .btn-primary-motor {
            background: linear-gradient(90deg, #e63946, #ff8c42);
            border: none; color: #1a0f10; font-weight: 700;
        }
        .btn-primary-motor:hover { opacity: .9; color: #1a0f10; }
        .unit-card {
            border-radius: 18px;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.08);
            transition: transform .15s;
        }
        .unit-card:hover { transform: translateY(-3px); }
        .unit-tersedia { background: linear-gradient(135deg, #0f5132, #14532d); }
        .unit-disewa   { background: linear-gradient(135deg, #7f1d1d, #991b1b); }
        .unit-maintenance { background: linear-gradient(135deg, #78350f, #92400e); }
        .text-muted-light { color: #b39a9a; }
    </style>
</head>
<body>
<div class="d-flex">
    <nav class="sidebar p-3" style="width: 250px;">
        <h5 class="brand-title mb-4"><i class="bi bi-scooter"></i> Rental Motor</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($activeMenu ?? '') === 'sewa' ? 'active' : '' ?>" href="sewa.php">
                    <i class="bi bi-key-fill me-2"></i> Sewa Berlangsung
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($activeMenu ?? '') === 'riwayat' ? 'active' : '' ?>" href="riwayat.php">
                    <i class="bi bi-clock-history me-2"></i> Riwayat Transaksi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($activeMenu ?? '') === 'motor' ? 'active' : '' ?>" href="motor.php">
                    <i class="bi bi-scooter me-2"></i> Data Motor
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($activeMenu ?? '') === 'penyewa' ? 'active' : '' ?>" href="penyewa.php">
                    <i class="bi bi-people-fill me-2"></i> Data Penyewa
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="bi bi-power me-2"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <main class="flex-fill p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><?= clean($pageTitle) ?></h4>
            <span class="text-muted-light">
                <i class="bi bi-person-circle"></i> <?= clean($_SESSION['nama_lengkap'] ?? 'Admin') ?>
            </span>
        </div>
        <?php showFlash(); ?>
