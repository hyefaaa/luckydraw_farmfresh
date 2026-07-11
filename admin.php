<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include 'db_config.php';

// LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// PADAM ENTRI
if (isset($_GET['padam']) && is_numeric($_GET['padam'])) {
    $id_padam = (int)$_GET['padam'];
    $res = $conn->query("SELECT receipt_img FROM entries WHERE id = $id_padam");
    if ($row = $res->fetch_assoc()) {
        $fail_gambar = 'uploads/' . $row['receipt_img'];
        if (file_exists($fail_gambar)) unlink($fail_gambar);
    }
    $conn->query("DELETE FROM entries WHERE id = $id_padam");
    header('Location: admin.php?msg=padam');
    exit();
}

// AMBIL DATA
$carian = isset($_GET['cari']) ? $conn->real_escape_string(trim($_GET['cari'])) : '';
$where  = '';
if ($carian !== '') {
    $where = "WHERE name LIKE '%$carian%' OR phone LIKE '%$carian%' OR receipt_no LIKE '%$carian%' OR ic_no LIKE '%$carian%'";
}

$result     = $conn->query("SELECT * FROM entries $where ORDER BY created_at DESC");
$semua_data = $result->fetch_all(MYSQLI_ASSOC);
$jumlah     = count($semua_data);

$jumlah_semua = $conn->query("SELECT COUNT(*) as jumlah FROM entries")->fetch_assoc()['jumlah'];
$hari_ini     = $conn->query("SELECT COUNT(*) as j FROM entries WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['j'];

// Jumlah pemenang lepas (untuk tolak dari sedia cabutan)
// Tambah table winners kalau belum ada
$conn->query("CREATE TABLE IF NOT EXISTS `winners` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `entry_id` int(11) NOT NULL,
    `name` varchar(100),
    `phone` varchar(20),
    `ic_no` varchar(20),
    `receipt_no` varchar(50),
    `won_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$jumlah_pemenang = $conn->query("SELECT COUNT(*) as j FROM winners")->fetch_assoc()['j'];
$sedia_cabutan   = max(0, $jumlah_semua - $jumlah_pemenang);

// CABUTAN BERTUAH — exclude previous winners
$pemenang = null;
if (isset($_GET['cabut'])) {
    // Ambil ID semua pemenang lepas
    $res_winners = $conn->query("SELECT entry_id FROM winners");
    $id_pemenang_lepas = [];
    while ($w = $res_winners->fetch_assoc()) {
        $id_pemenang_lepas[] = $w['entry_id'];
    }

    $exclude_sql = '';
    if (!empty($id_pemenang_lepas)) {
        $ids = implode(',', $id_pemenang_lepas);
        $exclude_sql = "WHERE id NOT IN ($ids)";
    }

    $res_cabut = $conn->query("SELECT * FROM entries $exclude_sql ORDER BY RAND() LIMIT 1");
    if ($res_cabut && $res_cabut->num_rows > 0) {
        $pemenang = $res_cabut->fetch_assoc();
        // Simpan pemenang dalam table winners
        $conn->query("INSERT INTO winners (entry_id, name, phone, ic_no, receipt_no)
                      VALUES ({$pemenang['id']}, '{$conn->real_escape_string($pemenang['name'])}',
                      '{$conn->real_escape_string($pemenang['phone'])}',
                      '{$conn->real_escape_string($pemenang['ic_no'])}',
                      '{$conn->real_escape_string($pemenang['receipt_no'])}')");
        // Kira semula
        $jumlah_pemenang = $conn->query("SELECT COUNT(*) as j FROM winners")->fetch_assoc()['j'];
        $sedia_cabutan   = max(0, $jumlah_semua - $jumlah_pemenang);
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | Farm Fresh Terengganu</title>
    <link rel="icon" type="image/x-icon" href="logo.png.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #f68b20;
            --primary-dark: #e05500;
            --bg: #f1f5f9;
            --white: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --danger: #ef4444;
            --success: #22c55e;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* GIFTBOX SPIN */
        @keyframes giftSpin {
            0%   { transform: rotate(0deg) scale(1); }
            25%  { transform: rotate(15deg) scale(1.1); }
            50%  { transform: rotate(-15deg) scale(1.15); }
            75%  { transform: rotate(10deg) scale(1.1); }
            100% { transform: rotate(0deg) scale(1); }
        }

        @keyframes giftBounce {
            0%, 100% { transform: translateY(0) scale(1); }
            30%       { transform: translateY(-20px) scale(1.08); }
            60%       { transform: translateY(-8px) scale(1.04); }
        }

        @keyframes giftShake {
            0%,100% { transform: rotate(0deg); }
            10%     { transform: rotate(-12deg); }
            20%     { transform: rotate(12deg); }
            30%     { transform: rotate(-10deg); }
            40%     { transform: rotate(10deg); }
            50%     { transform: rotate(-8deg); }
            60%     { transform: rotate(8deg); }
            70%     { transform: rotate(-5deg); }
            80%     { transform: rotate(5deg); }
            90%     { transform: rotate(-2deg); }
        }

        @keyframes giftExplode {
            0%   { transform: scale(1); opacity: 1; }
            50%  { transform: scale(1.4); opacity: 0.7; }
            100% { transform: scale(0); opacity: 0; }
        }

        @keyframes winnerReveal {
            0%   { transform: scale(0.3) translateY(60px); opacity: 0; }
            60%  { transform: scale(1.06) translateY(-8px); opacity: 1; }
            80%  { transform: scale(0.97) translateY(2px); }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }

        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position: 200% center; }
        }

        @keyframes pulse-ring {
            0%   { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        @keyframes confettiFall {
            0%   { transform: translateY(-20px) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }

        @keyframes starBurst {
            0%   { transform: scale(0) rotate(0deg); opacity: 1; }
            100% { transform: scale(3) rotate(180deg); opacity: 0; }
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
        }

        /* ---- TOPBAR ---- */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 64px;
            background: var(--white);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 100;
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-left img { height: 36px; width: auto; }

        .topbar-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px; font-weight: 800; color: var(--text-dark);
        }
        .topbar-title span { color: var(--primary); }

        .topbar-right { display: flex; align-items: center; gap: 14px; }

        .badge-user {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12px; font-weight: 700; color: var(--text-muted);
            background: #f1f5f9; padding: 6px 14px; border-radius: 20px;
        }

        .btn-logout {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fef2f2; color: var(--danger);
            border: 1.5px solid #fecaca;
            padding: 7px 16px; border-radius: 10px;
            font-size: 12px; font-weight: 700;
            cursor: pointer; text-decoration: none; transition: all 0.2s;
        }
        .btn-logout:hover { background: var(--danger); color: white; border-color: var(--danger); }

        .main {
            margin-top: 64px; padding: 28px;
            animation: fadeIn 0.5s ease forwards;
        }

        /* ---- STATS ---- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px; margin-bottom: 28px;
        }

        .stat-card {
            background: var(--white); border-radius: 18px;
            padding: 22px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border-left: 5px solid var(--primary);
            display: flex; align-items: center; gap: 16px;
        }
        .stat-card .icon { font-size: 32px; line-height: 1; }
        .stat-card .info .label {
            font-size: 12px; font-weight: 600; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.6px;
        }
        .stat-card .info .value {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 28px; font-weight: 800; color: var(--text-dark); line-height: 1.1;
        }

        /* ---- PANEL ---- */
        .panel {
            background: var(--white); border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden; margin-bottom: 24px;
        }

        .panel-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center;
            justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }

        .panel-header h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px; font-weight: 800; color: var(--text-dark);
        }

        .panel-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

        /* ---- CARIAN ---- */
        .carian-wrap { position: relative; }
        .carian-wrap .icon {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%); font-size: 14px;
        }
        .input-cari {
            font-family: 'Inter', sans-serif;
            padding: 9px 14px 9px 36px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 13px; background: #f8fafc; color: var(--text-dark);
            width: 220px; transition: all 0.2s;
        }
        .input-cari:focus {
            outline: none; border-color: var(--primary);
            background: #fff; box-shadow: 0 0 0 3px rgba(246,139,32,0.1);
        }

        /* ---- BUTANG ---- */
        .btn {
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 9px 18px; border-radius: 10px;
            font-size: 13px; font-weight: 700;
            cursor: pointer; border: none; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f68b20, #e05500);
            color: white; box-shadow: 0 4px 12px rgba(246,139,32,0.3);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(246,139,32,0.4); }

        /* BUTANG CABUTAN BERTUAH — lebih besar & meriah */
        .btn-cabut {
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 14px 28px; border-radius: 14px;
            font-size: 15px; font-weight: 800;
            cursor: pointer; border: none; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #f68b20 0%, #ff4500 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(246,139,32,0.4);
            transition: all 0.2s;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }
        .btn-cabut::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        .btn-cabut:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 10px 28px rgba(246,139,32,0.5); }

        .btn-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white; box-shadow: 0 4px 12px rgba(34,197,94,0.3);
        }
        .btn-success:hover { transform: translateY(-1px); }

        .btn-danger {
            background: #fef2f2; color: var(--danger);
            border: 1.5px solid #fecaca;
        }
        .btn-danger:hover { background: var(--danger); color: white; }

        .btn-ghost { background: #f1f5f9; color: var(--text-muted); }
        .btn-ghost:hover { background: #e2e8f0; color: var(--text-dark); }

        /* ---- JADUAL ---- */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }

        thead th {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc; padding: 13px 18px;
            text-align: left; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.7px;
            color: var(--text-muted); border-bottom: 1px solid #f1f5f9;
            white-space: nowrap;
        }

        tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; }
        tbody tr:hover { background: #fffaf7; }
        tbody tr:last-child { border-bottom: none; }
        tbody td { padding: 14px 18px; color: var(--text-dark); vertical-align: middle; }

        .td-nama { font-weight: 600; }
        .td-ic, .td-resit { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; }
        .td-tarikh { color: var(--text-muted); font-size: 12px; white-space: nowrap; }

        .badge-no {
            display: inline-block;
            background: rgba(246,139,32,0.1); color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11px; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
        }

        .thumb {
            width: 50px; height: 50px; object-fit: cover;
            border-radius: 10px; border: 2px solid #e2e8f0;
            cursor: pointer; transition: all 0.2s;
        }
        .thumb:hover { border-color: var(--primary); transform: scale(1.08); }

        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
        .empty-state .emoji { font-size: 48px; margin-bottom: 12px; }

        /* ---- NOTIFIKASI ---- */
        .notif {
            display: flex; align-items: center; gap: 10px;
            padding: 14px 20px; border-radius: 14px;
            margin-bottom: 20px; font-size: 13.5px; font-weight: 500;
        }
        .notif-success { background: #f0fdf4; border: 1.5px solid #bbf7d0; color: #166534; }
        .notif-info    { background: #fffbeb; border: 1.5px solid #fde68a; color: #92400e; }

        /* ---- MODAL GAMBAR RESIT ---- */
        .img-modal {
            display: none; position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15,23,42,0.7); backdrop-filter: blur(8px);
            z-index: 999; justify-content: center; align-items: center;
        }
        .img-modal.show { display: flex; }
        .img-modal-content {
            max-width: 90%; max-height: 85vh;
            border-radius: 16px; box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        }
        .img-modal-close {
            position: absolute; top: 20px; right: 20px;
            background: white; border: none; border-radius: 50%;
            width: 40px; height: 40px; font-size: 18px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: all 0.2s;
        }
        .img-modal-close:hover { transform: scale(1.1); }

        /* ============================================
           GIFTBOX OVERLAY — SEBELUM POPUP PEMENANG
        ============================================ */
        .giftbox-overlay {
            display: none; position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(ellipse at center, rgba(20,10,40,0.92) 0%, rgba(5,0,20,0.97) 100%);
            z-index: 1001;
            justify-content: center; align-items: center;
            flex-direction: column;
        }
        .giftbox-overlay.show { display: flex; }

        .giftbox-wrap {
            display: flex; flex-direction: column;
            align-items: center; gap: 20px;
            cursor: pointer; user-select: none;
        }

        .giftbox-emoji {
            font-size: 120px; line-height: 1;
            filter: drop-shadow(0 0 30px rgba(246,139,32,0.8));
            animation: giftBounce 1.5s ease-in-out infinite;
            transition: all 0.3s;
        }

        .giftbox-emoji.spinning {
            animation: giftShake 0.5s ease-in-out infinite;
        }

        .giftbox-emoji.exploding {
            animation: giftExplode 0.5s ease-out forwards;
        }

        /* Cincin denyut di sekeliling giftbox */
        .pulse-ring {
            position: absolute;
            width: 160px; height: 160px;
            border-radius: 50%;
            border: 3px solid rgba(246,139,32,0.6);
            animation: pulse-ring 1.8s ease-out infinite;
        }
        .pulse-ring:nth-child(2) { animation-delay: 0.6s; }
        .pulse-ring:nth-child(3) { animation-delay: 1.2s; }

        .giftbox-hint {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: rgba(255,255,255,0.8);
            font-size: 16px; font-weight: 700;
            letter-spacing: 1px;
            text-align: center;
            animation: fadeIn 1s ease 0.5s both;
        }

        .giftbox-hint span {
            color: var(--primary);
            font-size: 20px;
        }

        /* Countdown bulatan */
        .countdown-circle {
            width: 70px; height: 70px;
            border-radius: 50%;
            background: rgba(246,139,32,0.15);
            border: 3px solid var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 28px; font-weight: 800; color: var(--primary);
            animation: fadeIn 0.5s ease;
        }

        /* ============================================
           CONFETTI CANVAS
        ============================================ */
        #confettiCanvas {
            position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none; z-index: 1003;
            display: none;
        }

        /* ============================================
           MODAL PEMENANG — MERIAH
        ============================================ */
        .winner-modal {
            display: none; position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15,23,42,0.75); backdrop-filter: blur(10px);
            z-index: 1002; justify-content: center; align-items: center;
        }
        .winner-modal.show { display: flex; }

        .winner-box {
            background: var(--white); border-radius: 28px;
            padding: 40px 35px; max-width: 440px; width: 90%;
            text-align: center;
            border-top: 10px solid var(--primary);
            box-shadow: 0 30px 80px rgba(0,0,0,0.3), 0 0 0 1px rgba(246,139,32,0.2);
            animation: winnerReveal 0.7s cubic-bezier(0.16,1,0.3,1) forwards;
            position: relative; overflow: hidden;
        }

        /* Latar belakang kilauan dalam box */
        .winner-box::before {
            content: '';
            position: absolute; top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(ellipse at center, rgba(246,139,32,0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .winner-trophy {
            font-size: 70px; line-height: 1;
            margin-bottom: 10px;
            filter: drop-shadow(0 4px 16px rgba(246,139,32,0.5));
            display: block;
        }

        .winner-box h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px; font-weight: 800;
            background: linear-gradient(135deg, #f68b20, #ff4500);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            margin-bottom: 4px; letter-spacing: -0.5px;
        }

        .winner-box .tagline {
            font-size: 13px; color: var(--text-muted); margin-bottom: 24px;
        }

        .winner-info {
            background: linear-gradient(135deg, #fffaf7, #fff8f0);
            border: 2px solid rgba(246,139,32,0.2);
            border-radius: 18px; padding: 20px; text-align: left; margin-bottom: 24px;
            box-shadow: inset 0 2px 8px rgba(246,139,32,0.05);
        }

        .winner-info .row {
            display: flex; align-items: flex-start;
            margin-bottom: 12px; gap: 10px; font-size: 13.5px;
        }
        .winner-info .row:last-child { margin-bottom: 0; }

        .winner-info .row .key {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700; color: var(--text-muted);
            min-width: 100px; font-size: 11px;
            text-transform: uppercase; letter-spacing: 0.5px; padding-top: 2px;
        }
        .winner-info .row .val { font-weight: 700; color: var(--text-dark); font-size: 15px; }

        .winner-confetti-row { font-size: 28px; letter-spacing: 4px; margin-bottom: 20px; }

        .btn-tutup-winner {
            font-family: 'Plus Jakarta Sans', sans-serif;
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, #f68b20, #e05500);
            color: white; border: none; border-radius: 14px;
            font-weight: 800; font-size: 15px; cursor: pointer;
            box-shadow: 0 6px 20px rgba(246,139,32,0.35);
            transition: all 0.2s; letter-spacing: 0.5px;
        }
        .btn-tutup-winner:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(246,139,32,0.45);
        }

        /* ---- RESPONSIVE ---- */
        @media (max-width: 600px) {
            .main { padding: 16px; }
            .topbar { padding: 0 16px; }
            .topbar-title { font-size: 14px; }
            .badge-user { display: none; }
            .input-cari { width: 160px; }
            .giftbox-emoji { font-size: 90px; }
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <img src="logo.png.png" alt="Logo">
        <div class="topbar-title">Farm Fresh <span>Admin</span></div>
    </div>
    <div class="topbar-right">
        <div class="badge-user">👤 <?= htmlspecialchars($_SESSION['admin_user']) ?></div>
        <a href="admin.php?logout=1" class="btn-logout" onclick="return confirm('Anda pasti mahu log keluar?')">🚪 Log Keluar</a>
    </div>
</div>

<!-- CONFETTI CANVAS -->
<canvas id="confettiCanvas"></canvas>

<!-- MAIN CONTENT -->
<div class="main">

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'padam'): ?>
    <div class="notif notif-success">✅ Entri berjaya dipadamkan.</div>
    <?php endif; ?>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon">📋</div>
            <div class="info">
                <div class="label">Jumlah Pendaftaran</div>
                <div class="value"><?= $jumlah_semua ?></div>
            </div>
        </div>
        <div class="stat-card" style="border-left-color: #22c55e;">
            <div class="icon">📅</div>
            <div class="info">
                <div class="label">Hari Ini</div>
                <div class="value"><?= $hari_ini ?></div>
            </div>
        </div>
        <div class="stat-card" style="border-left-color: #6366f1;">
            <div class="icon">🏆</div>
            <div class="info">
                <div class="label">Sedia Untuk Cabutan</div>
                <div class="value" id="statSediaCabutan"><?= $sedia_cabutan ?></div>
            </div>
        </div>
    </div>

    <!-- PANEL CABUTAN BERTUAH -->
    <div class="panel" style="border-top: 4px solid var(--primary);">
        <div class="panel-header">
            <div>
                <h2>🎯 Cabutan Bertuah</h2>
                <p style="font-size:13px; color:var(--text-muted); margin-top:4px;">
                    <?= $sedia_cabutan ?> peserta layak | <?= $jumlah_pemenang ?> pemenang lepas
                </p>
            </div>
            <div class="panel-actions">
                <?php if ($sedia_cabutan > 0): ?>
                <button class="btn-cabut" onclick="mulakanCabutan()">
                    🎰 Buat Cabutan Sekarang!
                </button>
                <?php else: ?>
                <span class="btn" style="background:#f1f5f9; color:var(--text-muted); cursor:default;">
                    ✅ Semua peserta telah menang
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- PANEL SENARAI PESERTA -->
    <div class="panel">
        <div class="panel-header">
            <h2>📋 Senarai Peserta
                <?php if ($carian !== ''): ?>
                <span style="font-size:13px; color:var(--text-muted); font-weight:500;">
                    — Hasil carian: "<?= htmlspecialchars($carian) ?>" (<?= $jumlah ?> rekod)
                </span>
                <?php endif; ?>
            </h2>
            <div class="panel-actions">
                <form method="GET" action="admin.php" style="display:flex; gap:8px; align-items:center;">
                    <div class="carian-wrap">
                        <span class="icon">🔍</span>
                        <input type="text" name="cari" class="input-cari"
                               placeholder="Cari nama, IC, resit..."
                               value="<?= htmlspecialchars($carian) ?>">
                    </div>
                    <button type="submit" class="btn btn-ghost">Cari</button>
                    <?php if ($carian !== ''): ?>
                    <a href="admin.php" class="btn btn-ghost">✕ Padam Carian</a>
                    <?php endif; ?>
                </form>
                <a href="export.php" class="btn btn-success">📥 Export CSV</a>
            </div>
        </div>

        <div class="table-wrap">
            <?php if (empty($semua_data)): ?>
            <div class="empty-state">
                <div class="emoji">📭</div>
                <p><?= $carian !== '' ? 'Tiada rekod yang sepadan.' : 'Belum ada peserta.' ?></p>
            </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>No. Telefon</th>
                        <th>Nombor IC</th>
                        <th>No. Resit</th>
                        <th>Gambar Resit</th>
                        <th>Tarikh Daftar</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($semua_data as $i => $baris): ?>
                    <tr>
                        <td><span class="badge-no"><?= $i + 1 ?></span></td>
                        <td class="td-nama"><?= htmlspecialchars($baris['name']) ?></td>
                        <td><?= htmlspecialchars($baris['phone']) ?></td>
                        <td class="td-ic"><?= htmlspecialchars($baris['ic_no']) ?></td>
                        <td class="td-resit"><?= htmlspecialchars($baris['receipt_no']) ?></td>
                        <td>
                            <?php if ($baris['receipt_img']): ?>
                            <img src="uploads/<?= htmlspecialchars($baris['receipt_img']) ?>"
                                 alt="Resit" class="thumb"
                                 onclick="lihatGambar(this.src)">
                            <?php else: ?>
                            <span style="color:#94a3b8; font-size:12px;">Tiada</span>
                            <?php endif; ?>
                        </td>
                        <td class="td-tarikh">
                            <?= date('d/m/Y', strtotime($baris['created_at'])) ?><br>
                            <span style="font-size:11px;"><?= date('h:i A', strtotime($baris['created_at'])) ?></span>
                        </td>
                        <td>
                            <a href="admin.php?padam=<?= $baris['id'] ?>"
                               class="btn btn-danger"
                               style="font-size:12px; padding:6px 12px;"
                               onclick="return confirm('Padam rekod ini? Tindakan ini tidak boleh diundur.')">
                               🗑️ Padam
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <div style="font-family:'Plus Jakarta Sans',sans-serif; text-align:center; font-size:11px; color:var(--text-muted); letter-spacing:1.2px; font-weight:700; opacity:0.8; padding-bottom:20px;">
        TWIN MATRIX ENTERPRISE | FARM FRESH TERENGGANU
    </div>
</div>

<!-- MODAL LIHAT GAMBAR RESIT -->
<div class="img-modal" id="imgModal" onclick="tutupGambar()">
    <button class="img-modal-close" onclick="tutupGambar()">✕</button>
    <img src="" id="imgModalSrc" class="img-modal-content" onclick="event.stopPropagation()">
</div>

<!-- GIFTBOX OVERLAY -->
<div class="giftbox-overlay" id="giftboxOverlay">
    <div class="giftbox-wrap" id="giftboxWrap" onclick="klikGiftbox()">
        <div style="position:relative; display:flex; justify-content:center; align-items:center;">
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>
            <div class="giftbox-emoji" id="giftboxEmoji">🎁</div>
        </div>
        <div class="giftbox-hint">
            Klik kotak hadiah untuk<br>
            <span>dedahkan pemenang!</span>
        </div>
    </div>
    <div class="countdown-circle" id="countdownCircle" style="display:none;"></div>
</div>

<!-- MODAL PEMENANG -->
<div class="winner-modal" id="winnerModal">
    <div class="winner-box">
        <span class="winner-trophy">🏆</span>
        <h2>Tahniah Pemenang!</h2>
        <p class="tagline">✨ Hasil cabutan bertuah secara rawak ✨</p>
        <div class="winner-confetti-row">🎉🥳🎊🎈🎆</div>
        <div class="winner-info" id="winnerInfo">
            <!-- Diisi oleh JavaScript -->
        </div>
        <button class="btn-tutup-winner" onclick="tutupPemenang()">🎊 Tutup & Sambung</button>
    </div>
</div>

<script>
// ============================================================
// DATA PEMENANG DARI PHP
// ============================================================
<?php if ($pemenang): ?>
const dataPemenang = {
    nama    : <?= json_encode($pemenang['name']) ?>,
    phone   : <?= json_encode($pemenang['phone']) ?>,
    ic_no   : <?= json_encode($pemenang['ic_no']) ?>,
    receipt : <?= json_encode($pemenang['receipt_no']) ?>
};
<?php else: ?>
const dataPemenang = null;
<?php endif; ?>

// ============================================================
// GAMBAR RESIT
// ============================================================
function lihatGambar(src) {
    document.getElementById('imgModalSrc').src = src;
    document.getElementById('imgModal').classList.add('show');
}
function tutupGambar() {
    document.getElementById('imgModal').classList.remove('show');
}

// ============================================================
// AUDIO ENGINE — Web Audio API (tiada fail upload diperlukan)
// ============================================================
let audioCtx = null;

function getAudioCtx() {
    if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    return audioCtx;
}

// Bunyi tick countdown (beep pendek)
function bunyiTick(frekuensi = 440, tempoh = 0.08) {
    try {
        const ctx = getAudioCtx();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.type = 'sine';
        osc.frequency.setValueAtTime(frekuensi, ctx.currentTime);
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + tempoh);
        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + tempoh);
    } catch(e) {}
}

// Bunyi tick akhir (lebih kuat & tinggi)
function bunyiTickAkhir() {
    try {
        const ctx = getAudioCtx();
        // Nota tinggi pertama
        for (let i = 0; i < 3; i++) {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'square';
            osc.frequency.setValueAtTime(880 + (i * 220), ctx.currentTime + i * 0.12);
            gain.gain.setValueAtTime(0.2, ctx.currentTime + i * 0.12);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.12 + 0.15);
            osc.start(ctx.currentTime + i * 0.12);
            osc.stop(ctx.currentTime + i * 0.12 + 0.15);
        }
    } catch(e) {}
}

// Muzik suspense latar (drone + tremolo)
let suspenseNodes = [];
function mulakanMuzikSuspense() {
    try {
        const ctx = getAudioCtx();

        // Bass drone
        const bass = ctx.createOscillator();
        const bassGain = ctx.createGain();
        bass.connect(bassGain);
        bassGain.connect(ctx.destination);
        bass.type = 'sawtooth';
        bass.frequency.setValueAtTime(55, ctx.currentTime);
        bassGain.gain.setValueAtTime(0, ctx.currentTime);
        bassGain.gain.linearRampToValueAtTime(0.08, ctx.currentTime + 1);
        bass.start();

        // Tremolo mid tone
        const mid = ctx.createOscillator();
        const midGain = ctx.createGain();
        const lfo = ctx.createOscillator();
        const lfoGain = ctx.createGain();
        lfo.connect(lfoGain);
        lfoGain.connect(midGain.gain);
        mid.connect(midGain);
        midGain.connect(ctx.destination);
        mid.type = 'triangle';
        mid.frequency.setValueAtTime(110, ctx.currentTime);
        lfo.type = 'sine';
        lfo.frequency.setValueAtTime(6, ctx.currentTime);
        lfoGain.gain.setValueAtTime(0.05, ctx.currentTime);
        midGain.gain.setValueAtTime(0.05, ctx.currentTime);
        mid.start();
        lfo.start();

        // High tension string effect
        const high = ctx.createOscillator();
        const highGain = ctx.createGain();
        high.connect(highGain);
        highGain.connect(ctx.destination);
        high.type = 'sawtooth';
        high.frequency.setValueAtTime(220, ctx.currentTime);
        // Naik pitch perlahan-lahan untuk buat suspense
        high.frequency.linearRampToValueAtTime(440, ctx.currentTime + 10);
        highGain.gain.setValueAtTime(0, ctx.currentTime);
        highGain.gain.linearRampToValueAtTime(0.04, ctx.currentTime + 2);
        high.start();

        suspenseNodes = [bass, mid, lfo, high];
    } catch(e) {}
}

function hentikanMuzikSuspense() {
    try {
        suspenseNodes.forEach(n => {
            try { n.stop(); } catch(e) {}
        });
        suspenseNodes = [];
    } catch(e) {}
}

// Bunyi menang — fanfare trumpet gaya
function bunyiFanfare() {
    try {
        const ctx = getAudioCtx();
        // Nota fanfare: C E G C (naik)
        const nota = [261.63, 329.63, 392.00, 523.25, 659.25, 783.99];
        nota.forEach((freq, i) => {
            const osc  = ctx.createOscillator();
            const gain = ctx.createGain();
            const masa = ctx.currentTime + i * 0.18;
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'square';
            osc.frequency.setValueAtTime(freq, masa);
            gain.gain.setValueAtTime(0.0, masa);
            gain.gain.linearRampToValueAtTime(0.25, masa + 0.05);
            gain.gain.exponentialRampToValueAtTime(0.001, masa + 0.4);
            osc.start(masa);
            osc.stop(masa + 0.4);
        });

        // Chord akhir yang meriah
        [523.25, 659.25, 783.99].forEach((freq, i) => {
            const osc  = ctx.createOscillator();
            const gain = ctx.createGain();
            const masa = ctx.currentTime + nota.length * 0.18 + 0.1;
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(freq, masa);
            gain.gain.setValueAtTime(0.15, masa);
            gain.gain.exponentialRampToValueAtTime(0.001, masa + 1.5);
            osc.start(masa);
            osc.stop(masa + 1.5);
        });
    } catch(e) {}
}

// Bunyi meletup giftbox
function bunyiLetup() {
    try {
        const ctx = getAudioCtx();
        const bufferSize = ctx.sampleRate * 0.3;
        const buffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
        const data = buffer.getChannelData(0);
        for (let i = 0; i < bufferSize; i++) {
            data[i] = (Math.random() * 2 - 1) * Math.pow(1 - i / bufferSize, 2);
        }
        const source = ctx.createBufferSource();
        const gain   = ctx.createGain();
        const filter = ctx.createBiquadFilter();
        source.buffer = buffer;
        filter.type = 'lowpass';
        filter.frequency.setValueAtTime(800, ctx.currentTime);
        source.connect(filter);
        filter.connect(gain);
        gain.connect(ctx.destination);
        gain.gain.setValueAtTime(0.8, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
        source.start();
    } catch(e) {}
}

// ============================================================
// MULAKAN CABUTAN — redirect ke server untuk pilih pemenang
// ============================================================
function mulakanCabutan() {
    document.getElementById('giftboxOverlay').classList.add('show');
    // Mula muzik suspense terus bila overlay muncul
    mulakanMuzikSuspense();
}

// ============================================================
// KLIK GIFTBOX — animate + audio + countdown 10 saat
// ============================================================
let sudahKlik = false;
function klikGiftbox() {
    if (sudahKlik) return;
    sudahKlik = true;

    const emoji     = document.getElementById('giftboxEmoji');
    const hint      = document.querySelector('.giftbox-hint');
    const countdown = document.getElementById('countdownCircle');

    // Fasa 1: Shake
    emoji.classList.add('spinning');
    hint.innerHTML = '<span>Sedang memilih pemenang...</span>';

    // Fasa 2: Countdown 10 saat
    let kiraan = 10;
    countdown.style.display = 'flex';
    countdown.textContent = kiraan;

    // Tick bunyi mula
    bunyiTick(330, 0.1);

    const timerKiraan = setInterval(() => {
        kiraan--;

        if (kiraan > 0) {
            countdown.textContent = kiraan;

            // Tick makin laju & tinggi bila hampir 0
            const freq = 330 + (10 - kiraan) * 40;
            bunyiTick(freq, 0.08);

            // Giftbox makin kuat bergegar bila hampir 0
            if (kiraan <= 3) {
                emoji.style.fontSize = (120 + (3 - kiraan) * 15) + 'px';
            }
        } else {
            clearInterval(timerKiraan);
            countdown.textContent = '🎯';

            // Bunyi tick akhir yang kuat
            bunyiTickAkhir();
            hentikanMuzikSuspense();

            // Fasa 3: Explode
            emoji.classList.remove('spinning');
            emoji.classList.add('exploding');
            bunyiLetup();

            // Fasa 4: Redirect
            setTimeout(() => {
                window.location.href = 'admin.php?cabut=1';
            }, 500);
        }
    }, 1000);
}

// ============================================================
// TUNJUK PEMENANG — dipanggil bila ada dataPemenang dari PHP
// ============================================================
function tunjukPemenang(data) {
    const info = document.getElementById('winnerInfo');
    info.innerHTML = `
        <div class="row">
            <span class="key">🏅 Nama</span>
            <span class="val">${data.nama}</span>
        </div>
        <div class="row">
            <span class="key">📱 No. Telefon</span>
            <span class="val">${data.phone}</span>
        </div>
        <div class="row">
            <span class="key">🪪 Nombor IC</span>
            <span class="val">${data.ic_no}</span>
        </div>
        <div class="row">
            <span class="key">🧾 No. Resit</span>
            <span class="val">${data.receipt}</span>
        </div>
    `;
    document.getElementById('winnerModal').classList.add('show');

    // Bunyi fanfare menang + confetti firework!
    bunyiFanfare();
    mulakanConfetti();
}

function tutupPemenang() {
    document.getElementById('winnerModal').classList.remove('show');
    hentikanConfetti();
    history.replaceState(null, '', 'admin.php');
}

// ============================================================
// CONFETTI / FIREWORK ENGINE
// ============================================================
const canvas  = document.getElementById('confettiCanvas');
const ctx     = canvas.getContext('2d');
let confettiParticles = [];
let animFrameId = null;
let confettiAktif = false;

const warna = ['#f68b20','#ff4500','#ffd700','#ff69b4','#00cfff','#7cfc00','#ff1493','#ffffff','#ff6347'];

class Partikel {
    constructor() { this.reset(); }

    reset() {
        this.x    = Math.random() * canvas.width;
        this.y    = Math.random() * canvas.height - canvas.height;
        this.w    = Math.random() * 12 + 5;
        this.h    = Math.random() * 6 + 3;
        this.r    = Math.random() * Math.PI * 2;
        this.dr   = (Math.random() - 0.5) * 0.3;
        this.dx   = (Math.random() - 0.5) * 4;
        this.dy   = Math.random() * 5 + 2;
        this.warna = warna[Math.floor(Math.random() * warna.length)];
        this.opasiti = 1;
    }

    update() {
        this.x  += this.dx;
        this.y  += this.dy;
        this.r  += this.dr;
        this.dy += 0.05; // graviti
        if (this.y > canvas.height) this.reset();
    }

    draw() {
        ctx.save();
        ctx.translate(this.x, this.y);
        ctx.rotate(this.r);
        ctx.fillStyle = this.warna;
        ctx.globalAlpha = this.opasiti;
        ctx.fillRect(-this.w/2, -this.h/2, this.w, this.h);
        ctx.restore();
    }
}

// Firework burst
class Firework {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height * 0.5;
        this.partikel = [];
        const jumlah = 60 + Math.floor(Math.random() * 40);
        const w = warna[Math.floor(Math.random() * warna.length)];
        for (let i = 0; i < jumlah; i++) {
            const sudut = (Math.PI * 2 / jumlah) * i;
            const laju  = Math.random() * 6 + 2;
            this.partikel.push({
                x: this.x, y: this.y,
                dx: Math.cos(sudut) * laju,
                dy: Math.sin(sudut) * laju,
                warna: w,
                opasiti: 1,
                saiz: Math.random() * 5 + 2
            });
        }
    }

    update() {
        this.partikel.forEach(p => {
            p.x  += p.dx;
            p.y  += p.dy;
            p.dy += 0.12;
            p.dx *= 0.97;
            p.opasiti -= 0.015;
        });
        this.partikel = this.partikel.filter(p => p.opasiti > 0);
    }

    draw() {
        this.partikel.forEach(p => {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.saiz, 0, Math.PI * 2);
            ctx.fillStyle = p.warna;
            ctx.globalAlpha = p.opasiti;
            ctx.fill();
        });
    }

    selesai() { return this.partikel.length === 0; }
}

let fireworks = [];
let timerFirework;

function mulakanConfetti() {
    canvas.style.display = 'block';
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;
    confettiAktif = true;

    // Buat 150 confetti
    confettiParticles = Array.from({length: 150}, () => new Partikel());

    // Letupkan firework setiap 800ms
    timerFirework = setInterval(() => {
        if (confettiAktif) fireworks.push(new Firework());
    }, 800);

    // Terus letupkan beberapa sekarang
    for (let i = 0; i < 5; i++) fireworks.push(new Firework());

    animasiLoop();
}

function animasiLoop() {
    if (!confettiAktif) return;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    confettiParticles.forEach(p => { p.update(); p.draw(); });

    fireworks.forEach(f => { f.update(); f.draw(); });
    fireworks = fireworks.filter(f => !f.selesai());

    ctx.globalAlpha = 1;
    animFrameId = requestAnimationFrame(animasiLoop);
}

function hentikanConfetti() {
    confettiAktif = false;
    clearInterval(timerFirework);
    if (animFrameId) cancelAnimationFrame(animFrameId);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    canvas.style.display = 'none';
    fireworks = [];
}

window.addEventListener('resize', () => {
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;
});

// ============================================================
// AUTO-TUNJUK PEMENANG BILA ADA DATA DARI PHP
// ============================================================
if (dataPemenang) {
    // Tunjuk giftbox sebentar kemudian terus reveal pemenang
    // (sebab redirect dah selesai, terus ke peringkat reveal)
    const overlay = document.getElementById('giftboxOverlay');
    overlay.classList.add('show');

    const emoji = document.getElementById('giftboxEmoji');
    const hint  = document.querySelector('.giftbox-hint');
    const countdown = document.getElementById('countdownCircle');

    hint.innerHTML = '<span>Pemenang telah dipilih! 🎯</span>';
    emoji.style.animation = 'giftShake 0.3s ease-in-out 3';

    countdown.style.display = 'flex';
    countdown.textContent = '🎯';

    setTimeout(() => {
        emoji.classList.add('exploding');
        setTimeout(() => {
            overlay.classList.remove('show');
            tunjukPemenang(dataPemenang);
        }, 500);
    }, 1200);
}

// Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        tutupGambar();
        tutupPemenang();
    }
});
</script>

</body>
</html>
