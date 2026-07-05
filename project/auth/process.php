<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
    $user  = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id']   = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            echo json_encode(['status' => 'success', 'role' => 'admin']);
        } else {
            echo json_encode(['status' => 'success', 'role' => 'user']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email atau password salah.']);
    }
    exit();
}

if ($action === 'register') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email sudah terdaftar.']);
    } else {
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'user')";
        if (mysqli_query($koneksi, $query)) {
            echo json_encode(['status' => 'success', 'message' => 'Akun berhasil dibuat. Silakan login.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal membuat akun.']);
        }
    }
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenal.']);