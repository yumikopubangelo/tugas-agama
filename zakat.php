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
    <title>Keuangan </title>
    <link rel="stylesheet" href="css/style_preloader.css">
    <link rel="stylesheet" href="zakat_table.css">
    <link rel="stylesheet" href="css/toast.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

</head>
<body>
<body>
  <div class="main-content container">


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

<form action="proses_zakat.php" method="POST" class="colorful-form container-fluid px-5">

  <!-- INFORMASI PRIBADI -->
  <div class="form-section">
    <h2 class="form-title">Informasi Pribadi</h2>
    <div class="form-grid">
      <!-- Kolom Kiri -->
      <div class="form-column">
        <div class="form-group">
          <label for="nama">Nama:</label>
          <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
          <label for="telepon">Nomor Telepon:</label>
          <input type="text" id="telepon" name="telepon" required>
        </div>
        <div class="form-group">
          <label for="alamat">Alamat:</label>
          <textarea id="alamat" name="alamat" rows="5" required></textarea>
        </div>
      </div>

      <!-- Kolom Kanan -->
      <div class="form-column">
        <div class="form-group">
          <label for="tanggal_pembayaran">Tanggal Pembayaran:</label>
          <input type="date" id="tanggal_pembayaran" name="tanggal_pembayaran" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="jumlah_tanggungan">Jumlah Tanggungan:</label>
          <input type="number" id="jumlah_tanggungan" name="jumlah_tanggungan" required>
        </div>
      </div>
    </div>
  </div>

  <!-- JENIS ZAKAT -->
  <div class="form-section">
    <h2 class="form-title">Jenis Zakat</h2>
    <div class="form-group">
      <label for="jenis_zakat">Jenis Zakat:</label>
      <select name="jenis_zakat" id="jenis_zakat" required onchange="toggleJenisZakat()">
        <?php
        include 'koneksi.php';
        $query = mysqli_query($conn, "SELECT id, nama FROM jenis_zakat");
        while ($row = mysqli_fetch_assoc($query)) {
          echo "<option value='{$row['id']}' data-nama='{$row['nama']}'>{$row['nama']}</option>";
        }
        ?>
      </select>
    </div>
  </div>

  <!-- ZAKAT FITRAH -->
  <div id="jumlah-zakat-group" class="form-section" style="display: none;">
    <div class="form-grid">
      <div class="form-column">
        <div class="form-group">
          <label for="jumlah_individu">Jumlah Individu:</label>
          <input type="number" id="jumlah_individu" name="jumlah_individu" min="1">
        </div>
        <div class="form-group">
          <label for="harga_beras">Harga Beras per Kg:</label>
          <input type="number" id="harga_beras" name="harga_beras" step="0.01">
        </div>
      </div>
      <div class="form-column">
        <div class="form-group">
          <label for="jumlah_zakat2">Jumlah Zakat:</label>
          <input type="text" id="jumlah_zakat2" readonly>
        </div>
      </div>
    </div>
  </div>

  <!-- ZAKAT PENGHASILAN -->
  <div id="penghasilan-group" class="form-section" style="display: none;">
    <div class="form-grid">
      <div class="form-column">
        <div class="form-group">
          <label for="Penghasilan">Jumlah Penghasilan:</label>
          <input type="number" id="Penghasilan" name="Penghasilan">
        </div>
        <div class="form-group">
          <label for="persentase_zakat">Persentase Zakat (%):</label>
          <input type="number" id="persentase_zakat" name="persentase_zakat" step="0.01">
        </div>
      </div>
      <div class="form-column">
        <div class="form-group">
          <label for="Jumlah_Zakat_penghasilan">Jumlah Zakat:</label>
          <input type="text" id="Jumlah_Zakat_penghasilan" readonly>
        </div>
      </div>
    </div>
  </div>

  <!-- ZAKAT TERNAK -->
  <div id="ternak-group" class="form-section" style="display: none;">
    <div class="form-grid">
      <div class="form-column">
        <div class="form-group">
          <label for="jenis_ternak">Jenis Ternak:</label>
          <select id="jenis_ternak" name="jenis_ternak">
            <option value="kambing">Kambing</option>
            <option value="sapi">Sapi</option>
            <option value="unta">Unta</option>
          </select>
        </div>
        <div class="form-group">
          <label for="jumlah_ternak">Jumlah Ternak:</label>
          <input type="number" id="jumlah_ternak" name="jumlah_ternak" min="1">
        </div>
      </div>
      <div class="form-column">
        <div class="form-group">
          <label for="Jumlah_Zakat_ternak">Jumlah Zakat:</label>
          <input type="text" id="Jumlah_Zakat_ternak" name="Jumlah_Zakat_ternak" readonly>
        </div>
      </div>
    </div>
  </div>

  <!-- TOMBOL SIMPAN -->
  <div class="form-section text-center">
    <button class="form-button" type="submit">Simpan Data</button>
  </div>

</form>
</div>
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
<script src="script.js" defer></script>
</body>
</html>
