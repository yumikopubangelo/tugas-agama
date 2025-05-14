<?php
session_start();
include 'koneksi.php';

// Cek apakah yang login adalah admin
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true || $_SESSION["role"] !== 'Admin') {
    header("Location: halaman_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Simpan user baru
    $stmt = $conn->prepare("INSERT INTO user (nama, email, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $email, $username, $password);
    if ($stmt->execute()) {
        $new_user_id = $stmt->insert_id;

        // Ambil role_id untuk "admin"
        $role_query = $conn->prepare("SELECT role_id FROM role WHERE role = 'admin'");
        $role_query->execute();
        $role_result = $role_query->get_result()->fetch_assoc();
        $admin_role_id = $role_result['role_id'];

        // Tambahkan ke user_assigment
        $assign_stmt = $conn->prepare("INSERT INTO user_assigment (user_id, role_id) VALUES (?, ?)");
        $assign_stmt->bind_param("ii", $new_user_id, $admin_role_id);
        $assign_stmt->execute();

        echo "<script>alert('Admin berhasil ditambahkan!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "Gagal menambahkan admin.";
    }
}
?>

<!-- Form Tambah Admin -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
  <h3 class="mb-4">Tambah Admin Baru</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Nama Lengkap</label>
      <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-outline-success">Tambah Admin</button>
  </form>
</div>
</body>
</html>
