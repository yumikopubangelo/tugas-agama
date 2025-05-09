<?php
session_start();
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true || $_SESSION["role"] !== 'Admin') {
    header("Location: halaman_login.php");
    exit();
}
include 'koneksi.php';

$tipe_id    = (int) $_POST['tipe_id'];
$tanggal    = $_POST['tanggal'];
$jumlah     = $_POST['jumlah'];
$keterangan = $_POST['keterangan'];

// Hanya ambil sumber_id kalau tipe_id = 1 (pemasukan)
if ($tipe_id === 1) {
    if (empty($_POST['sumber_id'])) {
        // validasi sederhana: pemasukan harus punya sumber
        header("Location: keuangan.php?status=error");
        exit();
    }
    $sumber_id = (int) $_POST['sumber_id'];
} else {
    // tipe_id = 2 => pengeluaran, set sumber_id jadi NULL
    $sumber_id = null;
}

// Siapkan statement dengan placeholder
$stmt = $conn->prepare(
    "INSERT INTO keuangan
      (tipe_id, sumber_id, tanggal, Jumlah, keterangan)
     VALUES (?, ?, ?, ?, ?)"
);

// Bind param: gunakan "i" untuk integer, "s" untuk string.
// Karena sumber_id bisa NULL, kita pakai tipe "i" dan PHP akan mengirim null dengan benar.
$stmt->bind_param(
    "iisss",
    $tipe_id,
    $sumber_id,
    $tanggal,
    $jumlah,
    $keterangan
);

if ($stmt->execute()) {
    header("Location: keuangan.php?status=success");
} else {
    header("Location: keuangan.php?status=error");
}


$stmt->close();
$conn->close();
