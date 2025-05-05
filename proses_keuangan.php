<?php
session_start();
include 'koneksi.php';

// Cek apakah form dikirim dengan method POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $tipe_id = $_POST["tipe_id"];
    $sumber_id = $_POST["sumber_id"] ? $_POST["sumber_id"] : NULL;
    $tanggal = $_POST["tanggal"];
    $jumlah = $_POST["jumlah"];
    $keterangan = $_POST["keterangan"];
    $user_id = $_SESSION['user_id']; // ID pengguna yang login

    // Tentukan apakah ini insert baru atau update
    if (isset($_POST['no_keuangan']) && !empty($_POST['no_keuangan'])) {
        // Update data
        $no_keuangan = $_POST['no_keuangan'];
        
        // Ambil data lama untuk audit trail (old_value)
        $queryOld = "SELECT * FROM keuangan WHERE no_keuangan = ?";
        $stmtOld = $conn->prepare($queryOld);
        $stmtOld->bind_param("i", $no_keuangan);
        $stmtOld->execute();
        $result = $stmtOld->get_result();
        $oldData = $result->fetch_assoc();
        $old_value = json_encode($oldData); // Data lama yang akan disimpan ke audit trail
        
        // Query Update
        $queryUpdate = "UPDATE keuangan SET tipe_id = ?, sumber_id = ?, tanggal = ?, jumlah = ?, keterangan = ?, updated_by = ?, updated_at = NOW() WHERE no_keuangan = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param("iisdsi", $tipe_id, $sumber_id, $tanggal, $jumlah, $keterangan, $user_id, $no_keuangan);
        $stmtUpdate->execute();

        // Catat audit trail untuk update
        $new_value = json_encode([
            "tipe_id" => $tipe_id,
            "sumber_id" => $sumber_id,
            "tanggal" => $tanggal,
            "jumlah" => $jumlah,
            "keterangan" => $keterangan
        ]);

        $action = "UPDATE";
        
        // Masukkan ke audit_trail
        $queryAudit = "INSERT INTO audit_trail (table_name, record_id, action, old_value, new_value, user_id) VALUES ('keuangan', ?, ?, ?, ?, ?)";
        $stmtAudit = $conn->prepare($queryAudit);
        $stmtAudit->bind_param("issssi", $no_keuangan, $action, $old_value, $new_value, $user_id);
        $stmtAudit->execute();

    } else {
        // Insert Data Baru
        // Query Insert
        $queryInsert = "INSERT INTO keuangan (tipe_id, sumber_id, tanggal, jumlah, keterangan, created_by) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($queryInsert);
        $stmtInsert->bind_param("iisdsi", $tipe_id, $sumber_id, $tanggal, $jumlah, $keterangan, $user_id);
        $stmtInsert->execute();

        // Ambil no_keuangan yang baru diinsert
        $no_keuangan = $stmtInsert->insert_id; // ID yang baru dimasukkan

        // Catat audit trail untuk insert
        $new_value = json_encode([
            "tipe_id" => $tipe_id,
            "sumber_id" => $sumber_id,
            "tanggal" => $tanggal,
            "jumlah" => $jumlah,
            "keterangan" => $keterangan
        ]);
        $action = "INSERT";

        // Masukkan ke audit_trail
        $queryAudit = "INSERT INTO audit_trail (table_name, record_id, action, new_value, user_id) 
                       VALUES ('keuangan', ?, ?, ?, ?)";
        $stmtAudit = $conn->prepare($queryAudit);
        $stmtAudit->bind_param("isssi", $no_keuangan, $action, $new_value, $user_id);
        $stmtAudit->execute();
    }

    // Redirect atau pesan sukses
    header("Location: keuangan.php?status=success");
    exit();
}
?>
