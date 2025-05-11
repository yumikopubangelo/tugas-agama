<?php 
include "koneksi.php";
session_start();

// Nonaktifkan cek login agar tidak redirect
// if (!isset($_SESSION['username'])) {
//     header("Location: halaman_login.php");
//     exit();
// }

// Tangani logout jika form logout ada di file ini
if (isset($_POST['logout'])) {
    session_destroy();
    header("location:dashboard.php");
    exit();
}

// Periksa akses admin
$admin_akses = isset($_SESSION['admin_akses']) ? (array)$_SESSION['admin_akses'] : [];
$admin = in_array("admin", $admin_akses);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php include 'header.php'; ?>
<?php include 'preloader.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengunjung'; ?></title>
    <link rel="stylesheet" href="css/style_preloader.css">
    <script>
        function confirmLogout() {
            return confirm('Apakah Anda yakin ingin logout?');
        }
    </script>
</head>

<body>
<div class="container position-relative" style="max-width: 100%; padding: 0;">
  <img src="asset/Sign-Masjid.jpg" class="img-fluid w-100" alt="">
  <div class="position-absolute top-50 start-50 translate-middle text-center text-white" style="background-color: rgba(0, 0, 0, 0.5); padding: 20px; border-radius: 10px;">
    <h3 class="fw-bold">Selamat Datang <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengunjung'; ?> di Website Resmi Masjid Suhada</h3>
    <p class="mb-0">Pusat Informasi Dakwah, Kegiatan & Zakat</p>
    <small class="blockquote-footer text-white">DKM Masjid Suhada — Tasikmalaya, Jawa Barat</small>
  </div>
</div>
<div class="container">
  

  
<br><br>

<!-- Card Artikel Utama -->
<div class="container">
  <div class="card mb-3">
    <div class="row g-0">
      <div class="col-md-4">
        <img src="asset/dkwh1.jpg" class="img-fluid rounded-start" alt="...">
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h5 class="card-title">Keutamaan Berzakat: Bersihkan Harta, Tenangkan Jiwa</h5>
          <p class="card-text">Zakat adalah rukun Islam yang wajib dilaksanakan bagi yang mampu. Dengan zakat, kita membersihkan harta dan menyalurkan berkah pada yang membutuhkan.</p>
          <p class="card-text"><small class="text-body-secondary">Last updated 5 menit lalu</small></p>
          <a href="#" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
  </div>
</div>
<br>

<!-- Dakwah Hari Ini -->
<div class="container">
  <h3 class="text-left">Dakwah Hari Ini</h3>
  <br>
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col">
      <div class="card">
        <img src="asset/dkwh2.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Meningkatkan Keimanan di Era Digital</h5>
          <p class="card-text">Menjaga nilai-nilai Islam di tengah perkembangan teknologi digital yang cepat.</p>
          <a href="#" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="asset/dkwh3.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Adab dan Etika Muslim Sehari-hari</h5>
          <p class="card-text">Panduan adab Islami dalam kehidupan sehari-hari sebagai bentuk dakwah akhlak.</p>
          <a href="#" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="asset/dkwh4.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Tafsir Ringkas Surat Al-Ikhlas</h5>
          <p class="card-text">Menggali makna tauhid melalui tafsir singkat Surat Al-Ikhlas dalam kajian Jumat pagi.</p>
          <a href="#" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
      <div class="d-flex justify-content-end mt-3">
        <a href="#" class="btn btn-underline">Baca Artikel Lainnya</a>
      </div>
    </div>
  </div>
</div>

<!-- Jadwal Pengajian -->
<br><br>
<h3 class="text-left">Jadwal Pengajian</h3>
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
    <tr>
      <td>11 Mei 2025</td>
      <td>Kajian Remaja</td>
      <td>Ust. Budi S.</td>
      <td>Musholla Al-Furqan</td>
    </tr>
    <tr>
      <td>12 Mei 2025</td>
      <td>Maulid Nabi</td>
      <td>Ust. H. Yusuf M.</td>
      <td>Masjid Raya Al-Ikhlas</td>
    </tr>
  </tbody>
</table>

<!-- Jadwal Sholat dan Petugas -->
<br><br>
<div class="container">
  <h3 class="text-left">Jadwal Sholat dan Petugas</h3>
  <div class="table-container" style="display: flex; justify-content: space-between;">
    <table class="table table-striped" style="width: 48%; margin-right: 10px;">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Sholat</th>
          <th>Imam</th>
          <th>Lokasi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>9 Mei 2025</td>
          <td>Sholat Jumat</td>
          <td>Ust. Ahmad H.</td>
          <td>Masjid Al-Hikmah</td>
        </tr>
        <tr>
          <td>10 Mei 2025</td>
          <td>Sholat Dhuhr</td>
          <td>Ust. Salim A.</td>
          <td>Masjid Al-Hikmah</td>
        </tr>
      </tbody>
    </table>

    <table class="table table-striped" style="width: 48%;">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Petugas</th>
          <th>Posisi</th>
          <th>Lokasi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>9 Mei 2025</td>
          <td>Ahmad Y.</td>
          <td>Muadzin</td>
          <td>Masjid Al-Hikmah</td>
        </tr>
        <tr>
          <td>10 Mei 2025</td>
          <td>Rizky F.</td>
          <td>Khotib</td>
          <td>Masjid Al-Hikmah</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Carousel Kegiatan DKM -->
<br><br>
<h3 class="text-left">Galeri Suhada</h3>
<br>
<div id="carouselExampleCaptions" class="carousel carousel-dark slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="asset/Interior-Masjid.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Sholat Berjamaah</h5>
      </div>
    </div>
    <div class="carousel-item">
      <img src="asset/Selasar-Madrasah-Masjid.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Madrasah</h5>
      </div>
    </div>
    <div class="carousel-item">
      <img src="asset/Sign-Toilet--Masjid.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Toilet</h5>
      </div>
    </div>
    <div class="carousel-item">
      <img src="asset/Tampak-Depan-Masjid-2.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Masjid Suhada</h5>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
</div>


<?php include 'footer.php'; ?>
<script src="js/preloader.js"></script>
</body>
</html>
