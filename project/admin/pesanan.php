<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// UPDATE STATUS
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id     = (int)$_GET['id'];
    $status = mysqli_real_escape_string($koneksi, $_GET['status']);
    $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
    if (in_array($status, $allowed)) {
        mysqli_query($koneksi, "UPDATE orders SET status='$status' WHERE id=$id");
    }
    header('Location: pesanan.php');
    exit();
}

// FILTER STATUS
$filter = isset($_GET['filter']) ? mysqli_real_escape_string($koneksi, $_GET['filter']) : 'all';
$where  = $filter !== 'all' ? "WHERE status = '$filter'" : '';
$pesanan = mysqli_query($koneksi, "SELECT * FROM orders $where ORDER BY created_at DESC");

// Hitung per status
$count_all       = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM orders"))['t'];
$count_pending   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM orders WHERE status='pending'"))['t'];
$count_confirmed = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM orders WHERE status='confirmed'"))['t'];
$count_completed = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM orders WHERE status='completed'"))['t'];
$count_cancelled = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM orders WHERE status='cancelled'"))['t'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Pesanan - Kedai Himajas</title>
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet"/>
   <link rel="stylesheet" href="../assets/css/admin.css"/>
   <link rel="stylesheet" href="../assets/css/all.min.css"/>
</head>
<body>

<aside class="sidebar">
   <div class="sidebar-logo">
      <div class="logo-name">Kedai<span>Himajas</span></div>
      <small>Admin Panel</small>
   </div>
   <nav class="sidebar-nav">
      <div class="nav-label">Menu Utama</div>
      <a href="dashboard.php" class="nav-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="menu.php" class="nav-item"><i class="fas fa-utensils"></i> Kelola Menu</a>
      <a href="pesanan.php" class="nav-item active">
         <i class="fas fa-receipt"></i> Pesanan
         <?php if ($count_pending > 0): ?>
            <span class="badge-new"><?= $count_pending ?></span>
         <?php endif; ?>
      </a>
      <div class="nav-label">Lainnya</div>
      <a href="../index.php" class="nav-item"><i class="fas fa-store"></i> Lihat Landing Page</a>
   </nav>
   <div class="sidebar-footer">
      <div class="admin-info">
         <div class="admin-avatar"><i class="fas fa-user"></i></div>
         <div>
            <div class="admin-name"><?= htmlspecialchars($_SESSION['name']) ?></div>
            <div class="admin-role">Administrator</div>
         </div>
      </div>
      <a href="../auth/logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
   </div>
</aside>

<main class="main">
   <div class="topbar">
      <h1><i class="fas fa-receipt me-2" style="color:var(--secondary);"></i>Daftar Pesanan</h1>
   </div>
   <div class="content">

      <!-- FILTER TABS -->
      <div class="filter-tabs">
         <a href="pesanan.php" class="ftab <?= $filter === 'all' ? 'active' : '' ?>">Semua <span class="cnt"><?= $count_all ?></span></a>
         <a href="pesanan.php?filter=pending"   class="ftab <?= $filter === 'pending'   ? 'active' : '' ?>">Pending   <span class="cnt"><?= $count_pending ?></span></a>
         <a href="pesanan.php?filter=confirmed" class="ftab <?= $filter === 'confirmed' ? 'active' : '' ?>">Dikonfirmasi <span class="cnt"><?= $count_confirmed ?></span></a>
         <a href="pesanan.php?filter=completed" class="ftab <?= $filter === 'completed' ? 'active' : '' ?>">Selesai   <span class="cnt"><?= $count_completed ?></span></a>
         <a href="pesanan.php?filter=cancelled" class="ftab <?= $filter === 'cancelled' ? 'active' : '' ?>">Dibatalkan <span class="cnt"><?= $count_cancelled ?></span></a>
      </div>

      <!-- TABEL PESANAN -->
      <div class="card">
         <div class="card-header">
            <h5>Pesanan (<?= mysqli_num_rows($pesanan) ?>)</h5>
         </div>
         <table>
            <thead>
               <tr>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>No. Meja</th>
                  <th>No. Telp</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Waktu</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tbody>
               <?php if (mysqli_num_rows($pesanan) > 0): ?>
                  <?php while ($p = mysqli_fetch_assoc($pesanan)): ?>
                  <tr>
                     <td><strong>#<?= htmlspecialchars($p['order_code']) ?></strong></td>
                     <td><?= htmlspecialchars($p['customer_name']) ?></td>
                     <td>Meja <?= htmlspecialchars($p['table_number']) ?></td>
                     <td><?= htmlspecialchars($p['customer_phone']) ?></td>
                     <td>Rp <?= number_format($p['total_price'], 0, ',', '.') ?></td>
                     <td><span class="status-badge <?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                     <td><?= date('d/m H:i', strtotime($p['created_at'])) ?></td>
                     <td>
                        <div class="dropdown">
                           <button class="dropdown-btn"><i class="fas fa-ellipsis-v"></i> Ubah</button>
                           <div class="dropdown-menu">
                              <?php if ($p['status'] !== 'confirmed'  && $p['status'] !== 'completed' && $p['status'] !== 'cancelled'): ?>
                                 <a href="pesanan.php?id=<?= $p['id'] ?>&status=confirmed" class="confirm"><i class="fas fa-check me-2"></i>Konfirmasi</a>
                              <?php endif; ?>
                              <?php if ($p['status'] === 'confirmed'): ?>
                                 <a href="pesanan.php?id=<?= $p['id'] ?>&status=completed" class="complete"><i class="fas fa-flag-checkered me-2"></i>Selesai</a>
                              <?php endif; ?>
                              <?php if ($p['status'] !== 'cancelled' && $p['status'] !== 'completed'): ?>
                                 <div class="divider"></div>
                                 <a href="pesanan.php?id=<?= $p['id'] ?>&status=cancelled" class="cancel" onclick="return confirm('Batalkan pesanan ini?')"><i class="fas fa-times me-2"></i>Batalkan</a>
                              <?php endif; ?>
                           </div>
                        </div>
                     </td>
                  </tr>
                  <?php endwhile; ?>
               <?php else: ?>
                  <tr><td colspan="8" class="empty-state">Belum ada pesanan masuk.</td></tr>
               <?php endif; ?>
            </tbody>
         </table>
      </div>

   </div>
</main>

</body>
</html>
