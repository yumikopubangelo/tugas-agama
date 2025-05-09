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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include 'header.php'; ?>
  <title>Tentang Masjid & DKM</title>
  <style>
    iframe {
      width: 100%;
      height: 400px;
      border: 0;
    }
  </style>
</head>
<body>
<div class="container">
  <br><br>

  <h3>Sejarah Masjid</h3>
  <hr> 
  <img src="assest/masjid.jpg" class="img-fluid mb-3" alt="Foto Masjid">
  <p>Masjid Al-Hikmah didirikan pada tahun 1995 sebagai tempat ibadah utama bagi warga sekitar. Seiring waktu, masjid ini berkembang menjadi pusat dakwah dan kegiatan sosial keislaman, termasuk pengajian rutin, kajian kitab, dan pelatihan keagamaan untuk generasi muda. Renovasi besar dilakukan pada tahun 2015 untuk memperluas kapasitas jamaah dan memperindah fasilitas masjid.</p>

  <br> 
  <h3>Kepengurusan DKM</h3>
  <hr>
  <ul>
    <li><strong>Ketua DKM:</strong> Ust. Ahmad Hidayat</li>
    <li><strong>Sekretaris:</strong> H. Ridwan Maulana</li>
    <li><strong>Bendahara:</strong> Ibu Nani Rahmawati</li>
    <li><strong>Koordinator Pengajian:</strong> Ust. Salim Asy'ari</li>
    <li><strong>Koordinator Sosial:</strong> Bapak Dedi Mulyana</li>
  </ul>

  <br>
  <h3>Lokasi Masjid</h3>
  <hr>
  <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3957.3461148746323!2d108.218936!3d-7.314959999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zN8KwMTgnNTMuOSJTIDEwOMKwMTMnMDguMiJF!5e0!3m2!1sen!2sid!4v1746796991709!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

  <br><br> 
  <h3>Kegiatan Masjid</h3>
  <hr> 
  <p>Masjid Al-Hikmah menyelenggarakan berbagai kegiatan rutin, seperti:</p>
  <ul>
    <li>Pengajian malam Jumat</li>
    <li>Khutbah Jumat</li>
    <li>Pelatihan Tahsin Al-Qur'an</li>
    <li>Buka bersama saat Ramadhan</li>
    <li>Bakti sosial dan pembagian sembako</li>
  </ul>
  <br>

  <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="assest/crsl1.jpg" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="assest/crsl2.jpg" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="assest/crsl3.jpg" class="d-block w-100" alt="...">
      </div>
    </div>
  </div>

  <br><br>
  <h3>FAQ</h3>
  <hr>
  <div class="accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
          Apakah masjid terbuka untuk umum?
        </button>
      </h2>
      <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">Ya, Masjid Al-Hikmah terbuka untuk semua kalangan dan usia, baik untuk sholat maupun kegiatan sosial.</div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
          Bagaimana cara mendaftar kegiatan masjid?
        </button>
      </h2>
      <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">Silakan menghubungi sekretariat DKM secara langsung atau melalui kontak WhatsApp yang tertera di papan informasi masjid.</div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
          Apakah tersedia fasilitas untuk difabel?
        </button>
      </h2>
      <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">Ya, kami menyediakan jalur kursi roda dan toilet khusus difabel demi kenyamanan semua jamaah.</div>
      </div>
    </div>
  </div>

</div>
<?php include 'footer.php'; ?>
</body>
</html>
