<?php
session_start();


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
    <link rel="stylesheet" href="css/toast.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container">

<h2 class="text-center my-4">Informasi Zakat</h2>

<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Apa itu Zakat?</h5>
    <p class="card-text">
      Zakat adalah salah satu rukun Islam yang wajib ditunaikan oleh setiap Muslim yang mampu. Zakat berarti menyucikan harta dengan cara memberikan sebagian harta tersebut kepada mereka yang berhak menerima, seperti fakir, miskin, dan golongan lainnya yang tercantum dalam Al-Qur’an Surah At-Taubah ayat 60. 
      <br><br>
      Jenis-jenis zakat yang umum dikenal adalah zakat fitrah dan zakat mal. Zakat fitrah ditunaikan menjelang Idul Fitri sebagai bentuk penyucian diri selama bulan Ramadan, sedangkan zakat mal adalah zakat atas harta yang telah mencapai nisab dan haul, seperti emas, perak, penghasilan, hasil pertanian, dan lain-lain.
    </p>
  </div>
</div>

<br><br>

<h2 class="text-center my-4">Informasi Zakat Masjid Suhada</h2>
<div class="row text-center my-4">
  <div class="col-md-6">
    <div class="card text-white bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Zakat Terkumpul</h5>
        <p class="card-text display-6">Rp 12.500.000</p>
        <p class="card-text">Jumlah total zakat yang telah terkumpul dari para jamaah dan donatur hingga bulan Mei 2025.</p>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card text-white bg-info mb-3">
      <div class="card-body">
        <h5 class="card-title">Zakat Tersalurkan</h5>
        <p class="card-text display-6">Rp 9.000.000</p>
        <p class="card-text">Zakat telah disalurkan ke beberapa mustahik di berbagai lokasi dalam bentuk sembako dan bantuan langsung.</p>
      </div>
    </div>
  </div>
</div>

<br><br>

<h3 class="text-left">Lokasi Penyaluran Zakat</h3>
<hr>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Tanggal</th>
      <th>Acara</th>
      <th>Penceramah</th>
      <th>Lokasi</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>3 Mei 2025</td>
      <td>Pembagian Sembako</td>
      <td>Ust. Farhan L.</td>
      <td>Kampung Cibatu</td>
    </tr>
    <tr>
      <td>5 Mei 2025</td>
      <td>Khutbah Jumat</td>
      <td>Ust. Ahmad H.</td>
      <td>Masjid Al-Hikmah</td>
    </tr>
    <tr>
      <td>6 Mei 2025</td>
      <td>Pengajian Ibu-Ibu</td>
      <td>Ustazah Laila M.</td>
      <td>Majelis Taklim Al-Mubarok</td>
    </tr>
    <tr>
      <td>7 Mei 2025</td>
      <td>Bakti Sosial</td>
      <td>Ust. Salim A.</td>
      <td>Desa Sukamaju</td>
    </tr>
    <tr>
      <td>9 Mei 2025</td>
      <td>Khutbah Jumat</td>
      <td>Ust. Ahmad H.</td>
      <td>Masjid Al-Hikmah</td>
    </tr>
    <tr>
      <td>10 Mei 2025</td>
      <td>Pengajian Umum</td>
      <td>Ust. Salim A.</td>
      <td>Majelis Taklim An-Nur</td>
    </tr>
  </tbody>
</table>

</div>
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
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
        <label class="form-label" for="jenis_zakat">Jenis Zakat</label>
        <select name="jenis_zakat" id="jenis_zakat" required class="form-input" onchange="toggleJenisZakat()">
            <?php
            include 'koneksi.php';
            $query = mysqli_query($conn, "SELECT id, nama FROM jenis_zakat");
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<option value='{$row['id']}' data-nama='{$row['nama']}'>{$row['nama']}</option>";
            }
            ?>
        </select>
    </div>

    <!-- Input untuk zakat Fitrah -->
    <div class="form-group" id="jumlah-zakat-group">
        <label class="form-label" for="Jumlah_Zakat">Jumlah Zakat:</label>
        <input type="number" step="0.01" name="Jumlah_Zakat" id="Jumlah_Zakat" class="form-input">
    </div>

    <!-- Input untuk zakat Mal atau lainnya -->
    <div class="form-group" id="penghasilan-group" style="display: none;">
        <label class="form-label" for="Penghasilan">Penghasilan:</label>
        <input type="number" step="0.01" name="Penghasilan" id="Penghasilan" class="form-input">
    </div>
    <div class="form-group" id="ternak-group" style="display: none;">
        <label>Jenis Ternak:</label>
        <select name="jenis_ternak" class="form-input">
            <option value="kambing">Kambing</option>
            <option value="sapi">Sapi</option>
            <option value="unta">Unta</option>
        </select>
    </div>

    <div class="form-group" id="jumlah-ternak-group" style="display: none;">
        <label>Jumlah Ternak:</label>
        <input type="number" name="jumlah_ternak" class="form-input" min="1">
    </div>
    <div class="form-group" id="persentase-group" style="display: none;">
        <label class="form-label" for="persentase_zakat">Persentase Zakat (%):</label>
        <input type="number" step="0.01" name="persentase_zakat" id="persentase_zakat" class="form-input">
    </div>

    <button class="form-button" type="submit">Simpan Data</button>
</form>
</div>
<?php endif; ?>
<script>
function toggleJenisZakat() {
    const select = document.getElementById("jenis_zakat");
    const selectedOption = select.options[select.selectedIndex];
    const namaZakat = selectedOption.getAttribute("data-nama").toLowerCase();

    // Reset tampilan semua form-group
    document.getElementById("penghasilan-group").style.display = "none";
    document.getElementById("persentase-group").style.display = "none";
    document.getElementById("jumlah-zakat-group").style.display = "none";
    document.getElementById("ternak-group").style.display = "none";
    document.getElementById("jumlah-ternak-group").style.display = "none";

    if (namaZakat === "fitrah") {
        document.getElementById("jumlah-zakat-group").style.display = "block";
    } else if (namaZakat === "mal") {
        document.getElementById("penghasilan-group").style.display = "block";
        document.getElementById("persentase-group").style.display = "block";
    } else if (namaZakat === "peternakan") {
        document.getElementById("ternak-group").style.display = "block";
        document.getElementById("jumlah-ternak-group").style.display = "block";
    }
}

// Memanggil fungsi saat halaman pertama kali dimuat
window.onload = toggleJenisZakat;
</script>

<?php include 'footer.php'; ?>
<script src="js/preloader.js"></script>
</body>
</html>
