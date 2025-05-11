<?php
// Tampilkan error PHP di layar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cek metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'koneksi.php';

    // Validasi field wajib
    if (!isset($_POST['nama'], $_POST['telepon'], $_POST['alamat'], $_POST['email'], $_POST['jumlah_tanggungan'], $_POST['jenis_zakat'])) {
        echo "<h3>Data tidak lengkap. Silakan isi semua data wajib.</h3>";
        exit;
    }

    // Ambil data pribadi
    $nama               = $_POST['nama'];
    $telepon            = $_POST['telepon'];
    $alamat             = $_POST['alamat'];
    $email              = $_POST['email'];
    $tanggal_pembayaran = $_POST['tanggal_pembayaran'];
    $tanggungan         = $_POST['jumlah_tanggungan'];
    $tanggal            = date('Y-m-d H:i:s');
    $jenis_zakat_id     = (int) $_POST['jenis_zakat'];

    // Ambil nama jenis zakat (untuk validasi)
    $stmt = $conn->prepare("SELECT nama FROM jenis_zakat WHERE id = ?");
    $stmt->bind_param("i", $jenis_zakat_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $jenis_nama = $data['nama'] ?? null;

    if (!$jenis_nama) {
        echo "<h3>Jenis zakat tidak ditemukan!</h3>";
        exit;
    }

    // Simpan data muzzaki
    $stmt = $conn->prepare("INSERT INTO muzzaki (Nama_Muzzaki, nomor_hp, alamat, tanggal_pembayaran, email, jumlah_tanggungan) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "<h3>Error persiapan query muzzaki: " . $conn->error . "</h3>";
        exit;
    }
    $stmt->bind_param("sssssi", $nama, $telepon, $alamat, $tanggal_pembayaran, $email, $tanggungan);
    $stmt->execute();
    $no_muzzaki = $conn->insert_id;

    // Simpan ke tabel zakat
    $stmt = $conn->prepare("INSERT INTO zakat (No_Muzzaki, tanggal, jenis_zakat_id) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "<h3>Error saat prepare tabel zakat: " . $conn->error . "</h3>";
        exit;
    }
    $stmt->bind_param("isi", $no_muzzaki, $tanggal, $jenis_zakat_id);
    if (!$stmt->execute()) {
        echo "<h3>Error saat menyimpan ke tabel zakat: " . $stmt->error . "</h3>";
        exit;
    }
    $zakat_id = $conn->insert_id;

    // Simpan ke tabel sesuai jenis zakat
    if ($jenis_zakat_id == 1) { // Zakat Fitrah
        $jumlah_individu = $_POST['jumlah_individu'] ?? 0;
        $harga_beras     = $_POST['harga_beras'] ?? 0;
        $jumlah_zakat    = $jumlah_individu * $harga_beras;

       $stmt = $conn->prepare("INSERT INTO zakat_fitrah (zakat_id, jumlah_individu, harga_beras, jumlah_zakat) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            echo "<h3>❌ Gagal prepare zakat_fitrah: " . $conn->error . "</h3>";
            exit;
        }
        $stmt->bind_param("iidd", $zakat_id, $jumlah_individu, $harga_beras, $jumlah_zakat);
        $stmt->execute();


    } elseif ($jenis_zakat_id == 2) { // Zakat Mal
    $penghasilan      = $_POST['Penghasilan'] ?? 0;
    $persentase_zakat = $_POST['persentase_zakat'] ?? 0;
    $jumlah_zakat     = ($penghasilan * $persentase_zakat) / 100;

    $stmt = $conn->prepare("INSERT INTO zakat_mal (zakat_id, penghasilan, persentase_zakat, jumlah_zakat) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iddd", $zakat_id, $penghasilan, $persentase_zakat, $jumlah_zakat);
    $stmt->execute();



} elseif ($jenis_zakat_id == 3) { // Zakat Peternakan
    $jenis_ternak  = $_POST['jenis_ternak'] ?? '';
    $jumlah_ternak = (float)($_POST['jumlah_ternak'] ?? 0);
    $jumlah_zakat  = (float)($_POST['Jumlah_Zakat_ternak'] ?? 0);
 // Ambil dari input hidden JS

    $stmt = $conn->prepare("INSERT INTO zakat_peternakan (zakat_id, jenis_ternak, jumlah_ternak, jumlah_zakat) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("❌ Prepare failed (insert zakat_peternakan): " . $conn->error);
    }

    $stmt->bind_param("isdd", $zakat_id, $jenis_ternak, $jumlah_ternak, $jumlah_zakat);
    $stmt->execute();
}

} 


// Jika tidak error sampai sini, berarti sukses
header("Location: zakat.php?status=success");
exit;

?>
