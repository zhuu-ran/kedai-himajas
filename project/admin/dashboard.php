<?php
session_start();
require_once '../config/database.php';

// Proteksi: hanya admin yang boleh akses
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Statistik
$total_menu    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM menus"))['total'];
$total_pesanan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM orders"))['total'];
$pesanan_baru  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM orders WHERE status = 'pending'"))['total'];
$total_user    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users"))['total'];

// Pesanan terbaru (5)
$pesanan_terbaru = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Dashboard - Kedai Himajas</title>
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet"/>
   <link rel="stylesheet" href="../assets/css/admin.css"/>
   <link rel="stylesheet" href="../assets/css/all.min.css"/>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
   <div class="sidebar-logo">
      <div class="logo-name">Kedai<span>Himajas</span></div>
      <small>Admin Panel</small>
   </div>
   <nav class="sidebar-nav">
      <div class="nav-label">Menu Utama</div>
      <a href="dashboard.php" class="nav-item active">
         <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
      <a href="menu.php" class="nav-item">
         <i class="fas fa-utensils"></i> Kelola Menu
      </a>
      <a href="pesanan.php" class="nav-item">
         <i class="fas fa-receipt"></i> Pesanan
         <?php if ($pesanan_baru > 0): ?>
            <span class="badge-new ms-auto"><?= $pesanan_baru ?></span>
         <?php endif; ?>
      </a>
      <div class="nav-label">Lainnya</div>
      <a href="../index.php" class="nav-item">
         <i class="fas fa-store"></i> Lihat Landing Page
      </a>
   </nav>
   <div class="sidebar-footer">
      <div class="admin-info">
         <div class="admin-avatar"><i class="fas fa-user"></i></div>
         <div>
            <div class="admin-name"><?= htmlspecialchars($_SESSION['name']) ?></div>
            <div class="admin-role">Administrator</div>
         </div>
      </div>
      <a href="../auth/logout.php" class="btn-logout">
         <i class="fas fa-sign-out-alt"></i> Keluar
      </a>
   </div>
</aside>

<!-- MAIN CONTENT -->
<main class="main">
   <div class="topbar">
      <div>
         <h1>Dashboard</h1>
         <small>Selamat datang, <?= htmlspecialchars($_SESSION['name']) ?>!</small>
      </div>
   </div>
   <div class="content">

      <!-- STATISTIK -->
      <div class="stats-grid">
         <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-utensils"></i></div>
            <div>
               <div class="stat-num"><?= $total_menu ?></div>
               <div class="stat-label">Total Menu</div>
            </div>
         </div>
         <div class="stat-card">
            <div class="stat-icon gold"><i class="fas fa-receipt"></i></div>
            <div>
               <div class="stat-num"><?= $total_pesanan ?></div>
               <div class="stat-label">Total Pesanan</div>
            </div>
         </div>
         <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-clock"></i></div>
            <div>
               <div class="stat-num"><?= $pesanan_baru ?></div>
               <div class="stat-label">Pesanan Pending</div>
            </div>
         </div>
         <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div>
               <div class="stat-num"><?= $total_user ?></div>
               <div class="stat-label">Total Pengguna</div>
            </div>
         </div>
      </div>

      <!-- PESANAN TERBARU -->
      <div class="card">
         <div class="card-header">
            <h5><i class="fas fa-receipt me-2" style="color:var(--secondary);"></i>Pesanan Terbaru</h5>
            <a href="pesanan.php" class="btn-sm-green">Lihat Semua</a>
         </div>
         <table>
            <thead>
               <tr>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>No. Meja</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Waktu</th>
               </tr>
            </thead>
            <tbody>
               <?php if (mysqli_num_rows($pesanan_terbaru) > 0): ?>
                  <?php while ($p = mysqli_fetch_assoc($pesanan_terbaru)): ?>
                  <tr>
                     <td><strong>#<?= htmlspecialchars($p['order_code']) ?></strong></td>
                     <td><?= htmlspecialchars($p['customer_name']) ?></td>
                     <td>Meja <?= htmlspecialchars($p['table_number']) ?></td>
                     <td>Rp <?= number_format($p['total_price'], 0, ',', '.') ?></td>
                     <td><span class="status-badge <?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                     <td><?= date('d/m H:i', strtotime($p['created_at'])) ?></td>
                  </tr>
                  <?php endwhile; ?>
               <?php else: ?>
                  <tr><td colspan="6" class="empty-state">Belum ada pesanan masuk.</td></tr>
               <?php endif; ?>
            </tbody>
         </table>
      </div>

   </div>
</main>

</body>
</html>
