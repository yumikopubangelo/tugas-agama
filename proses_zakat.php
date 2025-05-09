<?php
include 'koneksi.php';

// Validasi input POST
$jenis_id = $_POST['jenis_zakat'];
$no_muzzaki = $_POST['no_muzzaki'];
$tanggal = $_POST['tanggal'];

// Ambil nama zakat dengan prepared statement untuk menghindari SQL injection
$stmt = $conn->prepare("SELECT nama FROM jenis_zakat WHERE id = ?");
$stmt->bind_param("i", $jenis_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$jenis_nama = strtolower($row['nama']);

// Masukkan ke tabel utama zakat
$stmt = $conn->prepare("INSERT INTO zakat (no_muzzaki, tanggal, jenis_zakat) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $no_muzzaki, $tanggal, $jenis_nama);
$stmt->execute();
$zakat_id = $conn->insert_id;

// Lanjut ke tabel khusus
if ($jenis_nama === 'mal') {
    $penghasilan = $_POST['Penghasilan'];
    $persen = $_POST['persentase_zakat'];
    $stmt = $conn->prepare("INSERT INTO zakat_mal (zakat_id, penghasilan, persentase_zakat) VALUES (?, ?, ?)");
    $stmt->bind_param("idd", $zakat_id, $penghasilan, $persen);
    $stmt->execute();
} elseif ($jenis_nama === 'peternakan') {
    $jenis_ternak = $_POST['jenis_ternak'];
    $jumlah_ternak = $_POST['jumlah_ternak'];

    // Ambil aturan zakat dari zakat_peternakan menggunakan prepared statement
    $stmt = $conn->prepare("SELECT zakat_output FROM zakat_peternakan WHERE jenis_ternak = ? AND batas_bawah <= ? AND batas_atas >= ? LIMIT 1");
    $stmt->bind_param("sii", $jenis_ternak, $jumlah_ternak, $jumlah_ternak);
    $stmt->execute();
    $query = $stmt->get_result();
    $data = $query->fetch_assoc();
    $output = $data ? $data['zakat_output'] : 'Tidak wajib zakat';

    // Simpan ke transaksi
    $stmt = $conn->prepare("INSERT INTO transaksi_zakat_peternakan (zakat_id, jenis_ternak, jumlah_ternak, hasil_zakat) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $zakat_id, $jenis_ternak, $jumlah_ternak, $output);
    $stmt->execute();
} elseif ($jenis_nama === 'fitrah') {
    $jumlah = $_POST['Jumlah_Zakat'];
    $stmt = $conn->prepare("UPDATE zakat SET jumlah_zakat = ? WHERE id = ?");
    $stmt->bind_param("di", $jumlah, $zakat_id);
    $stmt->execute();
}

echo "<script>alert('Zakat berhasil disimpan'); window.location.href='form_zakat.php';</script>";
?>
