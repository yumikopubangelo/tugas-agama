<?php
require 'koneksi.php'; // pastikan koneksi tersambung

$nama     = $_POST['nama'];
$email    = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// Validasi password cocok
if ($password !== $konfirmasi_password) {
  die("Password dan konfirmasi tidak cocok.");
}

// Enkripsi password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Cek apakah username atau email sudah ada
$cek = $conn->prepare("SELECT user_id FROM user WHERE username = ? OR email = ?");
if (!$cek) {
  die("Query error (cek): " . $conn->error);
}
$cek->bind_param("ss", $username, $email);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
  die("Username atau Email sudah digunakan.");
}

// Lakukan INSERT data baru
$stmt = $conn->prepare("INSERT INTO user (username, password, nama, email) VALUES (?, ?, ?, ?)");
if (!$stmt) {
  die("Query error (insert): " . $conn->error);
}
$stmt->bind_param("ssss", $username, $hashed_password, $nama, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo "Registrasi berhasil. <a href='halaman_login.php'>Login sekarang</a>";
} else {
  echo "Registrasi gagal. Silakan coba lagi.";
}
?>
