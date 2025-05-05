<?php
session_start();
include 'koneksi.php'; // Pastikan file ini menghubungkan ke database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $No_Muzzaki = $_POST["No_Muzzaki"];
    $tanggal = $_POST["tanggal"];
    $Jenis_Zakat = $_POST["Jenis_Zakat"];
    $Penghasilan = $_POST["Penghasilan"];
    $persentase_zakat = $_POST["persentase_zakat"];
    $Jumlah_Zakat = $_POST["Jumlah_Zakat"];

    $query = "INSERT INTO zakat (No_Muzzaki, tanggal, Jenis_Zakat, Penghasilan, persentase_zakat, Jumlah_Zakat) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issddd", $No_Muzzaki, $tanggal, $Jenis_Zakat, $Penghasilan, $persentase_zakat, $Jumlah_Zakat);

    if ($stmt->execute()) {
        header("Location: zakat.php?status=success");
    } else {
        header("Location: zakat.php?status=error");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: zakat_input.php");
    exit();
}
