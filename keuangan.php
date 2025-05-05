<?php
session_start();

// Cek login dan role Admin
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true || $_SESSION["role"] !== 'Admin') {
    header("Location: halaman_login.php");
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

    <!-- Konten -->
    <div class="dashboard-container">
        <h3>Halaman Keuangan</h3>
        <p>Selamat datang di modul keuangan. Di sini Anda bisa mengelola data transaksi, anggaran, dan laporan keuangan.</p>
    </div>

    <form action="proses_keuangan.php" method="POST" class="colorful-form">
  <div class="form-group">
    <label class="form-label" for="tipe_id">Tipe</label>
    <select name="tipe_id" id="tipe_id" required class="form-input" onchange="toggleSumber()">
      <?php
        include 'koneksi.php';
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
    <label class="form-label" for="tanggal">Tanggal:</label>
    <input type="datetime-local" name="tanggal" id="tanggal" required class="form-input">
  </div>

  <div class="form-group">
    <label class="form-label" for="jumlah">Jumlah:</label>
    <input type="number" name="jumlah" id="jumlah" step="0.01" required class="form-input">
  </div>

  <label class="form-label" for="keterangan">Keterangan:</label>
  <textarea name="keterangan" id="keterangan" required placeholder="Masukkan Keterangan" class="form-input"></textarea>

  <button class="form-button" type="submit">Simpan Data</button>
</form>

<script>
function toggleSumber() {
  const tipeSelect = document.getElementById('tipe_id');
  const sumberGroup = document.getElementById('sumber-group');

  // Asumsikan ID 1 adalah "Pemasukan"
  if (tipeSelect.value === '1') {
    sumberGroup.style.display = 'block';
  } else {
    sumberGroup.style.display = 'none';
    document.getElementById('sumber_id').value = ''; // reset nilai jika disembunyikan
  }
}

// Inisialisasi saat halaman dimuat
window.addEventListener('DOMContentLoaded', toggleSumber);
</script>


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