<?php
// 1. INITIALIZATION - MUST BE AT VERY TOP
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. DATABASE & SECURITY (use require_once to prevent duplicate includes)
require_once 'koneksi.php';

// 3. CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 4. AUTH CHECK
if (!isAdmin()) {
    header("Location: halaman_login.php");
    exit();
}

// 5. SALDO CALCULATION
$saldo = 0;
$saldoQuery = "SELECT SUM(CASE WHEN tipe_id = 1 THEN jumlah ELSE 0 END) - 
               SUM(CASE WHEN tipe_id = 2 THEN jumlah ELSE 0 END) AS saldo 
               FROM keuangan";
$saldoResult = mysqli_query($conn, $saldoQuery);

if ($saldoResult && mysqli_num_rows($saldoResult) > 0) {
    $saldoData = mysqli_fetch_assoc($saldoResult);
    $saldo = $saldoData['saldo'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include 'header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan | <?php echo htmlspecialchars($_SESSION['username']); ?></title>
    <link rel="stylesheet" href="css/tabel.css">
    <link rel="stylesheet" href="css/toast.css">
</head>
<body>
    <figure class="text-center">
        <blockquote class="blockquote">
            <h3>Halaman Keuangan</h3>
            <h2>Saldo Saat Ini: Rp <?php echo number_format($saldo, 0, ',', '.'); ?></h2>
        </blockquote>
    </figure>

    <div class="form-container">
        <form action="proses_keuangan.php" method="POST" class="colorful-form">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label class="form-label" for="tipe_id">Tipe</label>
                <select name="tipe_id" id="tipe_id" required class="form-input">
                    <?php
                    $query = mysqli_query($conn, "SELECT id, nama FROM tipe_keuangan");
                    while ($row = mysqli_fetch_assoc($query)) {
                        echo "<option value='{$row['id']}'>{$row['nama']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group" id="sumber-group" style="display: none;">
                <label class="form-label" for="sumber_id">Sumber Pemasukan</label>
                <select name="sumber_id" id="sumber_id" class="form-input">
                    <option value="">-- Pilih Sumber --</option>
                    <?php
                    $sumberQuery = mysqli_query($conn, "SELECT id, nama_sumber FROM sumber_keuangan");
                    while ($sumber = mysqli_fetch_assoc($sumberQuery)) {
                        echo "<option value='{$sumber['id']}'>{$sumber['nama_sumber']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="tanggal">Tanggal</label>
                <input type="datetime-local" name="tanggal" id="tanggal" required class="form-input" value="<?php echo date('Y-m-d\TH:i'); ?>">
            </div>

            <div class="form-group">
        <label class="form-label" for="jumlah">Jumlah</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="text" name="jumlah" id="jumlah" class="form-input" required 
                   placeholder="0" oninput="formatCurrency(this)">
        </div>
        <input type="hidden" id="jumlah_raw" name="jumlah_raw">
    </div>

            <div class="form-group">
                <label class="form-label" for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" required placeholder="Masukkan Keterangan" class="form-input" rows="3"></textarea>
            </div>

            <div class="form-group text-center">
                <button class="form-button" type="submit">Simpan Data</button>
            </div>
        </form>
    </div>

    <div class="table-wrapper">
        <table class="table-keuangan">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Sumber</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $queryData = "SELECT k.tanggal, t.nama AS tipe, s.nama_sumber, k.jumlah, k.keterangan 
                              FROM keuangan k
                              LEFT JOIN tipe_keuangan t ON k.tipe_id = t.id
                              LEFT JOIN sumber_keuangan s ON k.sumber_id = s.id
                              ORDER BY k.tanggal DESC";
                $resultData = mysqli_query($conn, $queryData);

                if (!$resultData) {
                    echo "<tr><td colspan='5'>Error: " . mysqli_error($conn) . "</td></tr>";
                } elseif (mysqli_num_rows($resultData) == 0) {
                    echo "<tr><td colspan='5'>Tidak ada data ditemukan</td></tr>";
                } else {
                    while ($row = mysqli_fetch_assoc($resultData)) {
                        echo "<tr>
                                <td>" . date('d-m-Y H:i', strtotime($row['tanggal'])) . "</td>
                                <td>{$row['tipe']}</td>
                                <td>" . ($row['nama_sumber'] ?? '-') . "</td>
                                <td>Rp " . number_format($row['jumlah'], 0, ',', '.') . "</td>
                                <td>{$row['keterangan']}</td>
                              </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
       function formatCurrency(input) {
        // Remove all non-digit characters
        let value = input.value.replace(/\D/g, '');
        
        // Store raw value in hidden field
        document.getElementById('jumlah_raw').value = value;
        
        // Format with thousand separators
        if (value.length > 0) {
            value = parseInt(value, 10).toLocaleString('id-ID');
        }
        
        // Update visible field
        input.value = value;
    }

    // Convert back to number before form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const rawInput = document.getElementById('jumlah_raw');
        const formattedInput = document.getElementById('jumlah');
        
        if (rawInput.value) {
            formattedInput.value = rawInput.value;
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const tipeSelect = document.getElementById('tipe_id');
        const sumberGroup = document.getElementById('sumber-group');

        function toggleSumber() {
            sumberGroup.style.display = (tipeSelect.value === '1') ? 'block' : 'none';
            if (tipeSelect.value !== '1') {
                document.getElementById('sumber_id').value = '';
            }
        }

        // Initialize on load
        toggleSumber();
        
        // Add event listener
        tipeSelect.addEventListener('change', toggleSumber);
    });

    // Toast notification handling
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        
        if (status) {
            const toast = document.createElement('div');
            toast.className = `toast ${status}-toast`;
            toast.textContent = status === 'success' 
                ? '✅ Data berhasil disimpan!' 
                : '❌ Gagal menyimpan data!';
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }, 100);
        }
    });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>