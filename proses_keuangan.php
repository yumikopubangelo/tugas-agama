<?php
session_start();

// 1. SECURITY CHECKS
// Verify admin login
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true || $_SESSION["role"] !== 'Admin') {
    header("Location: halaman_login.php");
    exit();
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed");
}

include 'koneksi.php';

// 2. INPUT VALIDATION
$tipe_id = (int) ($_POST['tipe_id'] ?? 0);
if (!in_array($tipe_id, [1, 2])) { // Only allow 1 (income) or 2 (expense)
    header("Location: keuangan.php?status=error&message=Invalid+type");
    exit();
}

// Validate amount
$jumlah = str_replace(['.', ','], '', $_POST['jumlah']); // Remove thousand separators
if (!is_numeric($jumlah) || $jumlah <= 0) {
    header("Location: keuangan.php?status=error&message=Invalid+amount");
    exit();
}

// Validate date
$tanggal = $_POST['tanggal'] ?? '';
if (!DateTime::createFromFormat('Y-m-d\TH:i', $tanggal)) {
    header("Location: keuangan.php?status=error&message=Invalid+date");
    exit();
}

$keterangan = trim($_POST['keterangan'] ?? '');
if (empty($keterangan)) {
    header("Location: keuangan.php?status=error&message=Description+required");
    exit();
}

// 3. SOURCE VALIDATION (only for income)
if ($tipe_id === 1) {
    $sumber_id = (int) ($_POST['sumber_id'] ?? 0);
    // Verify source exists in database
    $check = $conn->prepare("SELECT id FROM sumber_keuangan WHERE id = ?");
    $check->bind_param("i", $sumber_id);
    $check->execute();
    if (!$check->get_result()->num_rows) {
        header("Location: keuangan.php?status=error&message=Invalid+source");
        exit();
    }
} else {
    $sumber_id = null;
}

// 4. DATABASE OPERATION WITH TRANSACTION
mysqli_begin_transaction($conn);

try {
    $stmt = $conn->prepare(
        "INSERT INTO keuangan 
        (tipe_id, sumber_id, tanggal, Jumlah, keterangan) 
        VALUES (?, ?, ?, ?, ?)"
    );
    
    $stmt->bind_param(
        "iisds", // 'd' for decimal
        $tipe_id,
        $sumber_id,
        $tanggal,
        $jumlah,
        $keterangan
    );

    if ($stmt->execute()) {
        mysqli_commit($conn);
        header("Location: keuangan.php?status=success");
    } else {
        throw new Exception("Database error: ".$stmt->error);
    }
} catch (Exception $e) {
    mysqli_rollback($conn);
    error_log($e->getMessage());
    header("Location: keuangan.php?status=error&message=Database+error");
} finally {
    $stmt->close();
    $conn->close();
}