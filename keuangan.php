<?php
session_start();

// Cek login dan role Admin
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true || $_SESSION["role"] !== 'Admin') {
    header("Location: halaman_login.html");
    exit();
}
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
    <link rel="stylesheet" href="css/toast.css"
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
            <li><a href="kegiatan.php">Kegiatan</a></li>
            <li><a href="keuangan.php" style="text-decoration: underline;">Keuangan</a></li>
        </ul>
        <form class="logout-form" action="home.php" method="POST" onsubmit="return confirmLogout();">
            <button class="btn-logout" type="submit" name="logout">Logout</button>
        </form>
    </div>

    <!-- Konten -->
    <div class="dashboard-container">
        <h3>Halaman Keuangan</h3>
        <p>Selamat datang di modul keuangan. Di sini Anda bisa mengelola data transaksi, anggaran, dan laporan keuangan.</p>
    </div>

    <form action="proses_keuangan.php" method="POST" class="colorful-form">
  <div class="form-group">
      <label class="form-label" for="tipe">Tipe</label>
      <select name="tipe" id="tipe" required class="form-input">
        <option value="Pemasukan">Pemasukan</option>
        <option value="Pengeluaran">Pengeluaran</option>
        </select>
  </div>
  <div class="form-group">
  <label class="form-label" for="tanggal">Tanggal:</label>
  <input type="datetime-local" name="tanggal" id="tanggal" required class="form-input">
  </div>
  <div class="form-group">
  <label class="form-label" for="jumlah">Jumlah:</label>
  <input type="number" name="jumlah" id="jumlah" step="0.01" required class="form-input"><br><br>
  </div>
  <label class="form-label" for="keterangan">keterangan:</label>
  <textarea required="" type="text" placeholder="Masukan Keterangan" class="form-input" name="keterangan" id="keterangan"></textarea>
  <button class="form-button" type="submit">Simpan Data</button>
</form>
<?php if (isset($_GET['status']) && $_GET['status'] === 'success') : ?>
<div class="toast success-toast">✅ Data berhasil disimpan!</div>
<?php elseif (isset($_GET['status']) && $_GET['status'] === 'error') : ?>
<div class="toast error-toast">❌ Gagal menyimpan data!</div>
<?php endif; ?>

<script>
  // Hapus toast setelah 3 detik
  setTimeout(function() {
    const toast = document.querySelector('.toast');
    if (toast) {
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 500); // Hapus elemen dari DOM
    }
  }, 3000);
</script>
  

    <script src="js/preloader.js"></script>
</body>