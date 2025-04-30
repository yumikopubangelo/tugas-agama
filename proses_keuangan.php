<?php
session_start();
include 'koneksi.php'; // file ini harus berisi koneksi ke database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipe = $_POST["tipe"];
    $tanggal = $_POST["tanggal"];
    $jumlah = $_POST["jumlah"];
    $keterangan = $_POST["keterangan"];

    // Simpan ke database
    $query = "INSERT INTO keuangan (tipe, tanggal, Jumlah, Keterangan) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssds", $tipe, $tanggal, $jumlah, $keterangan);

    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    if ($success) {
        header("Location: keuangan.php?status=success");
    } else {
        header("Location: keuangan.php?status=error");
    }
    exit();
    
    
}
?>
