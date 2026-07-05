<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

$msg = '';
$edit_data = null;

// HAPUS
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT image FROM menus WHERE id = $id"));
    if ($row && $row['image'] && file_exists('../' . $row['image'])) {
        unlink('../' . $row['image']);
    }
    mysqli_query($koneksi, "DELETE FROM menus WHERE id = $id");
    header('Location: menu.php?msg=hapus');
    exit();
}

// AMBIL DATA EDIT
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menus WHERE id = $id"));
}

// SIMPAN (tambah / update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['id'] ?? 0);
    $name        = mysqli_real_escape_string($koneksi, $_POST['name']);
    $category    = mysqli_real_escape_string($koneksi, $_POST['category']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $price       = (int)$_POST['price'];
    $status      = mysqli_real_escape_string($koneksi, $_POST['status']);
    $image_path  = $_POST['old_image'] ?? '';

    // Upload gambar kalau ada
    if (!empty($_FILES['image']['name'])) {
        $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'menu_' . time() . '.' . $ext;
        $dest     = '../assets/img/menu/' . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            // hapus gambar lama kalau edit
            if ($image_path && file_exists('../' . $image_path)) unlink('../' . $image_path);
            $image_path = 'assets/img/menu/' . $filename;
        }
    }

    if ($id > 0) {
        mysqli_query($koneksi, "UPDATE menus SET name='$name', category='$category', description='$description', price=$price, image='$image_path', status='$status' WHERE id=$id");
        header('Location: menu.php?msg=update');
    } else {
        mysqli_query($koneksi, "INSERT INTO menus (name, category, description, price, image, status) VALUES ('$name','$category','$description',$price,'$image_path','$status')");
        header('Location: menu.php?msg=tambah');
    }
    exit();
}

// AMBIL SEMUA MENU
$menus = mysqli_query($koneksi, "SELECT * FROM menus ORDER BY category, name");

// Pesan notif
$notif = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'tambah')  $notif = 'Menu berhasil ditambahkan.';
    if ($_GET['msg'] === 'update')  $notif = 'Menu berhasil diperbarui.';
    if ($_GET['msg'] === 'hapus')   $notif = 'Menu berhasil dihapus.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Kelola Menu - Kedai Himajas</title>
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
      <a href="dashboard.php" class="nav-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="menu.php" class="nav-item active"><i class="fas fa-utensils"></i> Kelola Menu</a>
      <a href="pesanan.php" class="nav-item"><i class="fas fa-receipt"></i> Pesanan</a>
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

<!-- MAIN -->
<main class="main">
   <div class="topbar">
      <h1><i class="fas fa-utensils me-2" style="color:var(--secondary);"></i>Kelola Menu</h1>
   </div>
   <div class="content">

      <?php if ($notif): ?>
         <div class="notif"><i class="fas fa-check-circle me-2"></i><?= $notif ?></div>
      <?php endif; ?>

      <!-- FORM TAMBAH / EDIT -->
      <div class="card">
         <div class="card-header">
            <h5><?= $edit_data ? '<i class="fas fa-edit me-2"></i>Edit Menu' : '<i class="fas fa-plus me-2"></i>Tambah Menu Baru' ?></h5>
         </div>
         <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
               <input type="hidden" name="id" value="<?= $edit_data['id'] ?? 0 ?>">
               <input type="hidden" name="old_image" value="<?= $edit_data['image'] ?? '' ?>">
               <div class="form-grid">
                  <div class="form-group">
                     <label>Nama Menu *</label>
                     <input type="text" name="name" value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>" placeholder="Contoh: Ayam Geprek Sambal" required>
                  </div>
                  <div class="form-group">
                     <label>Kategori *</label>
                     <select name="category" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach (['Ayam'=>'ayam','Mie'=>'mie','Cemilan/Dessert'=>'cemilan','Minuman'=>'minuman'] as $label => $slug): ?>
                           <option value="<?= $label ?>" <?= ($edit_data['category'] ?? '') === $label ? 'selected' : '' ?>>
                              <?= $label ?>
                           </option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Harga (Rp) *</label>
                     <input type="number" name="price" value="<?= $edit_data['price'] ?? '' ?>" placeholder="15000" required min="0">
                  </div>
                  <div class="form-group">
                     <label>Status</label>
                     <select name="status">
                        <option value="available"   <?= ($edit_data['status'] ?? 'available') === 'available'   ? 'selected' : '' ?>>Aktif</option>
                        <option value="unavailable" <?= ($edit_data['status'] ?? '') === 'unavailable' ? 'selected' : '' ?>>Nonaktif</option>
                     </select>
                  </div>
                  <div class="form-group full">
                     <label>Deskripsi</label>
                     <textarea name="description" placeholder="Deskripsi singkat menu..."><?= htmlspecialchars($edit_data['description'] ?? '') ?></textarea>
                  </div>
                  <div class="form-group full">
                     <label>Gambar Menu <?= $edit_data ? '(kosongkan jika tidak diganti)' : '' ?></label>
                     <input type="file" name="image" accept="image/*" <?= $edit_data ? '' : 'required' ?>>
                     <?php if (!empty($edit_data['image'])): ?>
                        <img src="../<?= htmlspecialchars($edit_data['image']) ?>" class="img-preview" alt="Preview">
                     <?php endif; ?>
                  </div>
               </div>
               <div class="form-actions">
                  <button type="submit" class="btn-save">
                     <i class="fas fa-save me-1"></i><?= $edit_data ? 'Simpan Perubahan' : 'Tambah Menu' ?>
                  </button>
                  <?php if ($edit_data): ?>
                     <a href="menu.php" class="btn-cancel">Batal</a>
                  <?php endif; ?>
               </div>
            </form>
         </div>
      </div>

      <!-- TABEL MENU -->
      <div class="card">
         <div class="card-header">
            <h5><i class="fas fa-list me-2" style="color:var(--secondary);"></i>Daftar Menu (<?= mysqli_num_rows($menus) ?>)</h5>
         </div>
         <table>
            <thead>
               <tr>
                  <th>Gambar</th>
                  <th>Nama</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Status</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tbody>
               <?php if (mysqli_num_rows($menus) > 0): ?>
                  <?php while ($m = mysqli_fetch_assoc($menus)): ?>
                  <tr>
                     <td>
                        <?php if ($m['image']): ?>
                           <img src="../<?= htmlspecialchars($m['image']) ?>" class="menu-img" alt="<?= htmlspecialchars($m['name']) ?>">
                        <?php else: ?>
                           <div style="width:52px;height:52px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#ccc;"><i class="fas fa-image"></i></div>
                        <?php endif; ?>
                     </td>
                     <td><strong><?= htmlspecialchars($m['name']) ?></strong><br><small style="color:#888;"><?= htmlspecialchars($m['description'] ?? '') ?></small></td>
                     <td><?= ucfirst($m['category']) ?></td>
                     <td>Rp <?= number_format($m['price'], 0, ',', '.') ?></td>
                     <td><span class="status-badge <?= $m['status'] ?>"><?= $m['status'] === 'available' ? 'Aktif' : 'Nonaktif' ?></span></td>
                     <td>
                        <a href="menu.php?edit=<?= $m['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <button class="btn-hapus" onclick="if(confirm('Hapus menu ini?')) window.location.href='menu.php?hapus=<?= $m['id'] ?>'">
                           <i class="fas fa-trash"></i> Hapus
                        </button>
                     </td>
                  </tr>
                  <?php endwhile; ?>
               <?php else: ?>
                  <tr><td colspan="6" class="empty-state">Belum ada menu. Tambahkan menu pertama!</td></tr>
               <?php endif; ?>
            </tbody>
         </table>
      </div>

   </div>
</main>

</body>
</html>
