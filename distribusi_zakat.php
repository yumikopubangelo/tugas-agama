<?php
session_start();
require_once 'koneksi.php';

if (!isAdmin()) {
    header("Location: halaman_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['distribusi'])) {
    $mustahiq_id = mysqli_real_escape_string($conn, $_POST['mustahiq_id']);
    $jumlah = mysqli_real_escape_string($conn, str_replace('.', '', $_POST['jumlah']));
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    // Insert distribution record
    $query = "INSERT INTO penyaluran_zakat (mustahiq_id, jumlah, keterangan) 
              VALUES ('$mustahiq_id', '$jumlah', '$keterangan')";
    mysqli_query($conn, $query);
    
    // Update mustahiq's received amount
    $update_query = "UPDATE mustahiq SET total_diterima = total_diterima + $jumlah WHERE id = $mustahiq_id";
    mysqli_query($conn, $update_query);
    
    header("Location: distribusi_zakat.php?status=success");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include 'header.php'; ?>
    <title>Distribusi Zakat</title>
    <style>
        .currency-input { position: relative; }
        .currency-input span { 
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: bold;
        }
        .currency-input input { padding-left: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Distribusi Zakat</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4>Form Distribusi</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Penerima (Mustahiq)</label>
                                <select name="mustahiq_id" class="form-select" required>
                                    <?php
                                    $query = "SELECT id, nama, kategori FROM mustahiq ORDER BY nama ASC";
                                    $result = mysqli_query($conn, $query);
                                    
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['id']}'>
                                                {$row['nama']} ({$row['kategori']})
                                              </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jumlah Zakat</label>
                                <div class="currency-input">
                                    <span>Rp</span>
                                    <input type="text" name="jumlah" class="form-control" 
                                           oninput="formatCurrency(this)" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" required></textarea>
                            </div>
                            
                            <button type="submit" name="distribusi" class="btn btn-primary w-100">
                                Catat Distribusi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4>Riwayat Distribusi Terakhir</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Penerima</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT pz.*, m.nama 
                                              FROM penyaluran_zakat pz
                                              JOIN mustahiq m ON pz.mustahiq_id = m.id
                                              ORDER BY pz.tanggal DESC LIMIT 10";
                                    $result = mysqli_query($conn, $query);
                                    
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                                <td>" . date('d/m/Y', strtotime($row['tanggal'])) . "</td>
                                                <td>{$row['nama']}</td>
                                                <td>Rp " . number_format($row['jumlah'], 0, ',', '.') . "</td>
                                                <td>{$row['keterangan']}</td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="laporan_zakat.php" class="btn btn-secondary mt-3 w-100">Lihat Laporan Lengkap</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function formatCurrency(input) {
        // Remove non-digit characters
        let value = input.value.replace(/\D/g, '');
        
        // Format with thousand separators
        if (value.length > 0) {
            value = parseInt(value, 10).toLocaleString('id-ID');
        }
        
        // Update visible field
        input.value = value;
    }
    
    // Convert back to number before form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const jumlahInput = document.querySelector('input[name="jumlah"]');
        jumlahInput.value = jumlahInput.value.replace(/\D/g, '');
    });
    </script>
    
    <?php include 'footer.php'; ?>
</body>
</html>