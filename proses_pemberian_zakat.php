<?php
require_once 'koneksi.php';

// === Security Checks ===
// 1. Ensure the user is logged in as admin
if (!isAdmin()) {  // ← FIXED: Now properly checks for non-admin
    header('Location: dashboard.php?status=error&message=Unauthorized access');
    exit();
}

// 2. CSRF Protection
define('CSRF_TOKEN_NAME', 'csrf_token');
if (!isset($_POST[CSRF_TOKEN_NAME]) || empty($_SESSION[CSRF_TOKEN_NAME]) || 
    !hash_equals($_SESSION[CSRF_TOKEN_NAME], $_POST[CSRF_TOKEN_NAME])) {
    header('Location: zakat.php?status=error&message=Invalid CSRF Token');
    exit();
}

// === Input Validation ===
$required = ['tanggal', 'acara', 'penceramah', 'lokasi'];
foreach ($required as $field) {
    if (empty(trim($_POST[$field]))) {
        header('Location: zakat.php?status=error&message=Semua field harus diisi');
        exit();
    }
}

// Validate date format (YYYY-MM-DD)
$tanggal = $_POST['tanggal'];
if (!DateTime::createFromFormat('Y-m-d', $tanggal)) {
    header('Location: zakat.php?status=error&message=Format tanggal tidak valid (harus YYYY-MM-DD)');
    exit();
}

// Sanitize inputs
$acara = htmlspecialchars(trim($_POST['acara']), ENT_QUOTES, 'UTF-8');
$penceramah = htmlspecialchars(trim($_POST['penceramah']), ENT_QUOTES, 'UTF-8');
$lokasi = htmlspecialchars(trim($_POST['lokasi']), ENT_QUOTES, 'UTF-8');

// === Database Insertion ===
try {
    $stmt = $conn->prepare("INSERT INTO lokasi_penyaluran (tanggal, acara, penceramah, lokasi) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $tanggal, $acara, $penceramah, $lokasi);
    $stmt->execute();

    header('Location: zakat.php?status=success&message=Lokasi berhasil ditambahkan');
    exit();
} catch (Exception $e) {
    error_log("Error adding location: " . $e->getMessage());
    header('Location: zakat.php?status=error&message=Gagal menambahkan lokasi');
    exit();
}
?>