<?php
include 'koneksi.php';

// CSRF protection
if (!isset($_POST[CSRF_TOKEN_NAME]) || $_POST[CSRF_TOKEN_NAME] !== $_SESSION[CSRF_TOKEN_NAME]) {
    header('Location: zakat.php?status=error&message=CSRF%20validation%20failed');
    exit();
}

// Input validation
$requiredFields = [
    'nama', 'telepon', 'alamat', 'tanggal_pembayaran', 
    'email', 'jumlah_tanggungan', 'jenis_zakat'
];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        header('Location: zakat.php?status=error&message=Missing%20required%20fields');
        exit();
    }
}

// Sanitize inputs
$nama = htmlspecialchars(trim($_POST['nama']));
$telepon = preg_replace('/[^0-9]/', '', $_POST['telepon']);
$alamat = htmlspecialchars(trim($_POST['alamat']));
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$tanggal_pembayaran = $_POST['tanggal_pembayaran'];
$jumlah_tanggungan = (int)$_POST['jumlah_tanggungan'];
$jenis_zakat_id = (int)$_POST['jenis_zakat'];

// Additional validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: zakat.php?status=error&message=Invalid%20email');
    exit();
}

if ($jumlah_tanggungan < 1) {
    header('Location: zakat.php?status=error&message=Invalid%20dependents');
    exit();
}

try {
    // Begin transaction
    $conn->begin_transaction();

    // 1. Insert into muzzaki table
    $stmt_muzzaki = $conn->prepare("
        INSERT INTO muzzaki 
        (Nama_Muzzaki, nomor_hp, email, alamat, tanggal_pembayaran, jumlah_tanggungan) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt_muzzaki->bind_param(
        "sssssi", 
        $nama, $telepon, $email, $alamat, $tanggal_pembayaran, $jumlah_tanggungan
    );
    $stmt_muzzaki->execute();
    $muzzaki_id = $stmt_muzzaki->insert_id;
    $stmt_muzzaki->close();

    // 2. Insert into zakat table
    $stmt_zakat = $conn->prepare("
        INSERT INTO zakat 
        (No_Muzzaki, tanggal, jenis_zakat_id) 
        VALUES (?, NOW(), ?)
    ");
    $stmt_zakat->bind_param("ii", $muzzaki_id, $jenis_zakat_id);
    $stmt_zakat->execute();
    $zakat_id = $stmt_zakat->insert_id;
    $stmt_zakat->close();

    // 3. Insert into specific zakat type table
    switch ($jenis_zakat_id) {
        case 1: // Fitrah
            $jumlah_individu = (int)$_POST['jumlah_individu'];
            $harga_beras = (float)$_POST['harga_beras'];
            $jumlah_zakat = $jumlah_individu * 2.5 * $harga_beras;
            
            $stmt_fitrah = $conn->prepare("
                INSERT INTO zakat_fitrah 
                (zakat_id, jumlah_individu, harga_beras, jumlah_zakat) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt_fitrah->bind_param(
                "iidd", 
                $zakat_id, $jumlah_individu, $harga_beras, $jumlah_zakat
            );
            $stmt_fitrah->execute();
            $stmt_fitrah->close();
            break;
            
        case 2: // Mal
            $penghasilan = (float)$_POST['penghasilan'];
            $persentase_zakat = (float)$_POST['persentase_zakat'];
            $jumlah_zakat = $penghasilan * ($persentase_zakat / 100);
            
            $stmt_mal = $conn->prepare("
                INSERT INTO zakat_mal 
                (zakat_id, penghasilan, persentase_zakat, jumlah_zakat) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt_mal->bind_param(
                "iddd", 
                $zakat_id, $penghasilan, $persentase_zakat, $jumlah_zakat
            );
            $stmt_mal->execute();
            $stmt_mal->close();
            break;
            
        case 3: // Peternakan
            $jenis_ternak = $_POST['jenis_ternak'];
            $jumlah_ternak = (int)$_POST['jumlah_ternak'];
            $jumlah_zakat = 0;
            
            // Simplified calculation - adjust according to Islamic rules
            if ($jenis_ternak === 'kambing') {
                $jumlah_zakat = floor($jumlah_ternak / 40);
            } elseif ($jenis_ternak === 'sapi') {
                $jumlah_zakat = floor($jumlah_ternak / 30);
            } elseif ($jenis_ternak === 'unta') {
                $jumlah_zakat = floor($jumlah_ternak / 5);
            }
            
            $stmt_peternakan = $conn->prepare("
                INSERT INTO zakat_peternakan 
                (zakat_id, jenis_ternak, jumlah_ternak, jumlah_zakat) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt_peternakan->bind_param(
                "isdi", 
                $zakat_id, $jenis_ternak, $jumlah_ternak, $jumlah_zakat
            );
            $stmt_peternakan->execute();
            $stmt_peternakan->close();
            break;
    }

    // Commit transaction
    $conn->commit();
    
    header('Location: zakat.php?status=success');
    exit();

} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    error_log("Error processing zakat: " . $e->getMessage());
    header('Location: zakat.php?status=error&message=Database%20error');
    exit();
}
?>