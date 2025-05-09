<?php
session_start();

// Cek login dan role Admin
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true || $_SESSION["role"] !== 'Admin') {
    header("Location: halaman_login.php");
    exit();
}

// ✅ Tambahkan koneksi ke database DI SINI:
include 'koneksi.php';

// Query untuk hitung saldo
$query = "SELECT 
  SUM(CASE WHEN tipe_id = 1 THEN Jumlah ELSE 0 END) -
  SUM(CASE WHEN tipe_id = 2 THEN Jumlah ELSE 0 END) AS saldo 
FROM keuangan";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
$saldo = $data['saldo'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php include 'header.php'; ?>
<?php include 'preloader.php'; ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan | <?php echo htmlspecialchars($_SESSION['username']); ?></title>
    <link rel="stylesheet" href="css/style_preloader.css">
    <link rel="stylesheet" href="css/tabel.css">
    <link rel="stylesheet" href="css/toast.css"> <!-- Pastikan toast.css dimuat terakhir -->

   

  
</head>
<body>

    <figure class="text-center">
  <blockquote class="blockquote">
  <h3>Halaman Keuangan</h3>
  <h2>Saldo Saat Ini: Rp <?php echo number_format($saldo, 0, ',', '.'); ?></h2>
  </blockquote>
</figure>

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
 window.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const status = urlParams.get('status');
  if (status === 'success' || status === 'error') {
    const toastMessage = document.createElement('div');
    toastMessage.classList.add('toast');
    toastMessage.classList.add(status === 'success' ? 'success-toast' : 'error-toast');
    toastMessage.textContent = status === 'success' ? '✅ Data berhasil disimpan!' : '❌ Gagal menyimpan data!';

    document.body.appendChild(toastMessage);
    
    // Tampilkan toast dengan animasi
    setTimeout(() => {
      toastMessage.style.display = 'block';  // Menampilkan toast

      // Hapus toast setelah 3 detik
      setTimeout(() => {
        toastMessage.classList.add('hide');
        setTimeout(() => toastMessage.remove(), 500); // Hapus dari DOM setelah fade out
      }, 3000);
    }, 0);
  }
});
</script>

  
<?php include 'footer.php'; ?>
<script src="js/preloader.js"></script>
</body>