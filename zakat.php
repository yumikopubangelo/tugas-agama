<?php
session_start();

// Cek login dan role Admin
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true || $_SESSION["role"] !== 'Admin') {
    header("Location: halaman_login.php");
    exit();
}
?>
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php include 'preloader.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan | <?php echo htmlspecialchars($_SESSION['username']); ?></title>
    <link rel="stylesheet" href="css/style_dashboard.css">
    <link rel="stylesheet" href="css/style_preloader.css">
    <link rel="stylesheet" href="css/tabel.css">
    <link rel="stylesheet" href="css/toast.css">
    <script>
        function confirmLogout() {
            return confirm('Apakah Anda yakin ingin logout?');
        }
    </script>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">Dashboard</div>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                <li><a href="Zakat.php">Zakat</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                    <li><a href="keuangan.php">Keuangan</a></li>
                <?php endif; ?>
        </ul>
        <form class="logout-form" action="halaman_login.php" method="POST" onsubmit="return confirmLogout();">
            <button class="btn-logout" type="submit" name="logout">Logout</button>
        </form>
    </div>
    <title>Input Data Zakat</title>
    <div class="dashboard-container">
        <h3>Halaman Keuangan</h3>
        <p>Selamat datang di modul keuangan. Di sini Anda bisa mengelola data transaksi, anggaran, dan laporan keuangan.</p>
    </div>
</head>
<body>
<div class="container">
    <form action="proses_zakat.php" method="POST" class="colorful-form">
        <div class="form-group">
        <label class="form-label" for="no_muzzaki">No Muzzaki:</label>
            <input type="number" name="no_muzzaki" id="no_muzzaki" required class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label" for="tanggal">Tanggal:</label>
            <input type="datetime-local" name="tanggal" id="tanggal" required class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label" for="Jenis_Zakat">Jenis Zakat:</label>
            <select name="Jenis_Zakat" id="Jenis_Zakat" required class="form-input">
                <option value="Fitrah">Fitrah</option>
                <option value="Mal">Mal</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="Penghasilan">Penghasilan:</label>
            <input type="number" step="0.01" name="Penghasilan" id="Penghasilan" required class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label" for="persentase_zakat">Persentase Zakat (%):</label>
            <input type="number" step="0.01" name="persentase_zakat" id="persentase_zakat" required class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label" for="Jumlah_Zakat">Jumlah Zakat:</label>
            <input type="number" step="0.01" name="Jumlah_Zakat" id="Jumlah_Zakat" required class="form-input">
        </div>

        <button class="form-button" type="submit">Simpan Data</button>
    </form>
</div>
<script src="js/preloader.js"></script>
</body>
</html>
