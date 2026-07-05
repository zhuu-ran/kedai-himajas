<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$noMeja  = mysqli_real_escape_string($koneksi, $data['noMeja'] ?? '');
$nama    = mysqli_real_escape_string($koneksi, $data['nama'] ?? '');
$noTelp  = mysqli_real_escape_string($koneksi, $data['noTelp'] ?? '');
$items   = $data['items'] ?? [];
$userId  = isset($_SESSION['id']) ? (int)$_SESSION['id'] : null;

if (empty($noMeja) || empty($nama) || empty($noTelp) || empty($items)) {
    echo json_encode(['status' => 'error', 'message' => 'Data pesanan tidak lengkap.']);
    exit();
}

// Hitung total & validasi harga langsung dari database (jangan percaya harga dari client)
$total = 0;
$validatedItems = [];

foreach ($items as $item) {
    $menuId = (int)$item['id'];
    $qty    = (int)$item['qty'];

    $res = mysqli_query($koneksi, "SELECT id, price FROM menus WHERE id = $menuId AND status = 'available'");
    $menu = mysqli_fetch_assoc($res);

    if (!$menu) {
        echo json_encode(['status' => 'error', 'message' => 'Salah satu menu tidak tersedia lagi.']);
        exit();
    }

    $subtotal = $menu['price'] * $qty;
    $total += $subtotal;

    $validatedItems[] = [
        'menu_id'  => $menu['id'],
        'qty'      => $qty,
        'price'    => $menu['price'],
        'subtotal' => $subtotal
    ];
}

// Generate kode order unik
$orderCode = 'ORD' . date('ymd') . strtoupper(substr(uniqid(), -5));

mysqli_begin_transaction($koneksi);

try {
    $userIdSql = $userId ? $userId : 'NULL';

    mysqli_query($koneksi, "
        INSERT INTO orders (user_id, table_number, customer_name, customer_phone, order_code, total_price, status)
        VALUES ($userIdSql, '$noMeja', '$nama', '$noTelp', '$orderCode', $total, 'pending')
    ");

    $orderId = mysqli_insert_id($koneksi);

    foreach ($validatedItems as $vi) {
        mysqli_query($koneksi, "
            INSERT INTO order_items (order_id, menu_id, quantity, price, subtotal)
            VALUES ($orderId, {$vi['menu_id']}, {$vi['qty']}, {$vi['price']}, {$vi['subtotal']})
        ");
    }

    mysqli_commit($koneksi);

    echo json_encode([
        'status' => 'success',
        'order_code' => $orderCode,
        'total' => $total
    ]);

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pesanan.']);
}