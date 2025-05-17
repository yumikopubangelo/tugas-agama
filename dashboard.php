<?php
session_start();   
include "koneksi.php";

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


// === 2. Ambil data jadwal sholat ===
$stmt_sholat = $conn->prepare("SELECT * FROM jadwal_sholat ORDER BY tanggal ASC");
$stmt_sholat->execute();
$result_sholat = $stmt_sholat->get_result();

// === 3. Ambil data jadwal petugas ===
$stmt_petugas = $conn->prepare("SELECT * FROM jadwal_petugas ORDER BY tanggal ASC");
$stmt_petugas->execute();
$result_petugas = $stmt_petugas->get_result();

// === 4. Ambil data jadwal pengajian (JOIN dengan penceramah & lokasi) ===
$stmt_jadwal = $conn->prepare("
    SELECT jp.tanggal, jp.acara, p.nama AS penceramah, l.nama_lokasi AS lokasi
    FROM jadwal_pengajian jp
    LEFT JOIN penceramah p ON jp.id_penceramah = p.id_penceramah
    LEFT JOIN lokasi l ON jp.id_lokasi = l.id_lokasi
    ORDER BY jp.tanggal ASC
");
$stmt_jadwal->execute();
$result_jadwal = $stmt_jadwal->get_result();

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

// Set timezone ke WIB (Waktu Indonesia Barat)
date_default_timezone_set('Asia/Jakarta');

function timeAgo($datetime) {
    $time = strtotime($datetime);
    if (!$time) return 'Tanggal tidak valid';

    $diff = time() - $time;

    if ($diff < 60) {
        return "Baru saja";
    } elseif ($diff < 3600) {
        return floor($diff / 60) . " menit yang lalu";
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . " jam yang lalu";
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . " hari yang lalu";
    } else {
        return date('d M Y', $time);
    }
}

// Ambil artikel terbaru (1 artikel terbaru)
$stmt_artikel_utama = $conn->prepare("SELECT * FROM artikel_dakwah ORDER BY tanggal_dibuat DESC LIMIT 1");
$stmt_artikel_utama->execute();
$result_artikel_utama = $stmt_artikel_utama->get_result();

// Ambil 4 artikel terbaru untuk bagian "Dakwah Hari Ini"
$stmt_artikel_terbaru = $conn->prepare("SELECT * FROM artikel_dakwah ORDER BY tanggal_dibuat DESC LIMIT 3");
$stmt_artikel_terbaru->execute();
$query_artikel = $stmt_artikel_terbaru->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php include 'header.php'; ?>
<?php include 'preloader.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.0/css/all.min.css">
    <title>Dashboard | <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengunjung'; ?></title> 
     <link rel="stylesheet" href="css/style_preloader.css">
     <link rel="stylesheet" href="css/button.css">
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
  <?php if ($result_artikel_utama && $result_artikel_utama->num_rows > 0): ?>
    <?php while ($row = $result_artikel_utama->fetch_assoc()): ?>
      <div class="card mb-3">
        <div class="row g-0">
          <div class="col-md-4">
            <img src="<?php echo htmlspecialchars($row['gambar']); ?>" class="img-fluid rounded-start" alt="Gambar Artikel">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($row['judul']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
              <p class="card-text">
                <small class="text-body-secondary">
                  Diposting oleh <?php echo htmlspecialchars($row['penulis']); ?> — <?php echo timeAgo($row['tanggal_dibuat']); ?>
                </small>
              </p>
              <a href="detail_artikel.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Baca Artikel</a>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>Belum ada artikel dakwah yang tersedia.</p>
  <?php endif; ?>
</div>

<br>

<!-- Dakwah Hari Ini -->
<div class="row row-cols-1 row-cols-md-3 g-4">
  <?php while ($artikel = $query_artikel->fetch_assoc()) : ?>
    <div class="col">
      <div class="card h-100">
        <?php if (!empty($artikel['gambar']) && file_exists($artikel['gambar'])): ?>
          <img src="<?php echo htmlspecialchars($artikel['gambar']); ?>" class="card-img-top" alt="Gambar Artikel">
        <?php else: ?>
          <img src="asset/default-thumbnail.jpg" class="card-img-top" alt="Gambar Default">
        <?php endif; ?>
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($artikel['judul']); ?></h5>
          <p class="card-text">
            <?php echo substr(strip_tags($artikel['isi']), 0, 100); ?>...
          </p>
          <a href="detail_artikel.php?id=<?php echo $artikel['id']; ?>" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>



<div class="pengajian-container">
  <h3 class="text-middle">Jadwal Pengajian</h3>
  
  <!-- Desktop Table View -->
  <div class="desktop-view">
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
        <?php if ($result_jadwal && $result_jadwal->num_rows > 0): ?>
          <?php while ($row = $result_jadwal->fetch_assoc()): ?>
            <tr>
              <td><?= date("d M Y", strtotime($row["tanggal"])) ?></td>
              <td><?= htmlspecialchars($row["acara"]) ?></td>
              <td><?= htmlspecialchars($row["penceramah"]) ?></td>
              <td><?= htmlspecialchars($row["lokasi"]) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4">Belum ada jadwal pengajian.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  
  <!-- Mobile Card View -->
  <div class="mobile-cards">
    <?php if ($result_jadwal && $result_jadwal->num_rows > 0): ?>
      <?php while ($row = $result_jadwal->fetch_assoc()): ?>
        <div class="pengajian-card">
          <h4>Jadwal Pengajian</h4>
          <div class="card-row">
            <span class="card-label">Tanggal:</span>
            <span><?= date("d M Y", strtotime($row["tanggal"])) ?></span>
          </div>
          <div class="card-row">
            <span class="card-label">Acara:</span>
            <span><?= htmlspecialchars($row["acara"]) ?></span>
          </div>
          <div class="card-row">
            <span class="card-label">Penceramah:</span>
            <span><?= htmlspecialchars($row["penceramah"]) ?></span>
          </div>
          <div class="card-row">
            <span class="card-label">Lokasi:</span>
            <span><?= htmlspecialchars($row["lokasi"]) ?></span>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="pengajian-card">Belum ada jadwal pengajian.</div>
    <?php endif; ?>
  </div>
</div>

<style>
/* Base Styles */
.pengajian-container {
  margin: 20px 0;
}

.text-middle {
  text-align: center;
  margin-bottom: 20px;
}

/* Desktop Table Styles */
.desktop-view {
  display: block;
  overflow-x: auto;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th, .table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.table th {
  background-color: #f8f9fa;
  font-weight: 600;
}

/* Mobile Card Styles */
.mobile-cards {
  display: none;
}

.pengajian-card {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.pengajian-card h4 {
  margin: 0 0 10px 0;
  color: #2c3e50;
  font-size: 1.1rem;
  padding-bottom: 8px;
  border-bottom: 1px solid #eee;
}

.card-row {
  display: flex;
  margin-bottom: 8px;
  font-size: 0.95rem;
}

.card-label {
  font-weight: 600;
  min-width: 100px;
  color: #555;
}

/* Responsive Behavior */
@media (max-width: 767px) {
  .desktop-view {
    display: none;
  }
  
  .mobile-cards {
    display: block;
  }
}

@media (min-width: 768px) {
  .mobile-cards {
    display: none;
  }
  
  .desktop-view {
    display: block;
  }
}
</style>

<script>
// Responsive view switcher with error handling
document.addEventListener('DOMContentLoaded', function() {
  function checkView() {
    const isMobile = window.innerWidth <= 767;
    const desktopView = document.querySelector('.desktop-view');
    const mobileCards = document.querySelector('.mobile-cards');
    
    if (desktopView && mobileCards) {
      desktopView.style.display = isMobile ? 'none' : 'block';
      mobileCards.style.display = isMobile ? 'block' : 'none';
    }
  }
  
  // Initial check
  checkView();
  
  // Debounced resize handler
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(checkView, 250);
  });
});
</script>

<!-- Jadwal Sholat dan Petugas Section -->
<div class="container">
  <h3 class="text-middle">Jadwal Sholat dan Petugas</h3>
  
  <!-- Desktop Table View -->
  <div class="mobile-card-view">
    <div class="table-container">
      <!-- Tabel Jadwal Sholat -->
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
          <?php if ($result_sholat && $result_sholat->num_rows > 0): ?>
            <?php while ($row = $result_sholat->fetch_assoc()): ?>
              <tr>
                <td><?= date("d M Y", strtotime($row["tanggal"])) ?></td>
                <td><?= htmlspecialchars($row["jenis_sholat"]) ?></td>
                <td><?= htmlspecialchars($row["imam"]) ?></td>
                <td><?= htmlspecialchars($row["lokasi"]) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4">Belum ada data jadwal sholat.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Tabel Jadwal Petugas -->
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
          <?php if ($result_petugas && $result_petugas->num_rows > 0): ?>
            <?php while ($row = $result_petugas->fetch_assoc()): ?>
              <tr>
                <td><?= date("d M Y", strtotime($row["tanggal"])) ?></td>
                <td><?= htmlspecialchars($row["petugas"]) ?></td>
                <td><?= htmlspecialchars($row["posisi"]) ?></td>
                <td><?= htmlspecialchars($row["lokasi"]) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4">Belum ada data petugas.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Mobile Card View (hidden by default) -->
  <div class="card-container" style="display: none;">
    <?php if ($result_sholat && $result_sholat->num_rows > 0): ?>
      <?php while ($row = $result_sholat->fetch_assoc()): ?>
        <div class="schedule-card">
          <h4>Jadwal Sholat</h4>
          <div class="card-row">
            <span class="card-label">Tanggal:</span>
            <span><?= date("d M Y", strtotime($row["tanggal"])) ?></span>
          </div>
          <div class="card-row">
            <span class="card-label">Sholat:</span>
            <span><?= htmlspecialchars($row["jenis_sholat"]) ?></span>
          </div>
          <div class="card-row">
            <span class="card-label">Imam:</span>
            <span><?= htmlspecialchars($row["imam"]) ?></span>
          </div>
          <div class="card-row">
            <span class="card-label">Lokasi:</span>
            <span><?= htmlspecialchars($row["lokasi"]) ?></span>
          </div>
        </div>
      <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Belum ada data jadwal sholat.</td></tr>
    <?php endif; ?>
    
    <?php if ($result_petugas && $result_petugas->num_rows > 0): ?>
      <?php while ($row = $result_petugas->fetch_assoc()): ?>
        <div class="schedule-card">
          <h4>Jadwal Petugas</h4>
          <div class="card-row">
            <span class="card-label">Tanggal:</span>
            <span><?= date("d M Y", strtotime($row["tanggal"])) ?></span>
          </div>
          <div class="card-row">
            <span class="card-label">Petugas:</span>
            <span><?= htmlspecialchars($row["petugas"]) ?></span>
          </div>
          <div class="card-row">
            <span class="card-label">Posisi:</span>
            <span><?= htmlspecialchars($row["posisi"]) ?></span>
          </div>
          <div class="card-row">
            <span class="card-label">Lokasi:</span>
            <span><?= htmlspecialchars($row["lokasi"]) ?></span>
          </div>
        </div>
      <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Belum ada data petugas.</td></tr>
    <?php endif; ?>
  </div>
</div>

<style>
/* Base Styles */
.container {
  margin: 20px 0;
}

/* Table Container */
.table-container {
  display: flex;
  justify-content: space-between;
}

/* Card Styles */
.schedule-card {
  border: 1px solid #dee2e6;
  border-radius: 5px;
  padding: 15px;
  margin-bottom: 15px;
  background-color: white;
}

.card-row {
  display: flex;
  margin-bottom: 8px;
}

.card-label {
  font-weight: bold;
  min-width: 100px;
  color: #555;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .table-container {
    flex-direction: column;
  }
  
  .table-container table {
    width: 100%;
    margin-right: 0;
    margin-bottom: 15px;
  }
  
  .mobile-card-view {
    display: none;
  }
  
  .card-container {
    display: block !important;
  }
}

@media (min-width: 769px) {
  .card-container {
    display: none !important;
  }
  
  .mobile-card-view {
    display: block !important;
  }
}
</style>

<script>
// Enhanced responsive view switcher
function checkView() {
  const isMobile = window.innerWidth <= 768;
  const tableView = document.querySelector('.mobile-card-view');
  const cardContainer = document.querySelector('.card-container');
  
  if (!tableView || !cardContainer) {
    console.error("Required elements for responsive view not found");
    return;
  }
  
  tableView.style.display = isMobile ? 'none' : 'block';
  cardContainer.style.display = isMobile ? 'block' : 'none';
}

// Debounce function for resize events
function debounce(func, wait) {
  let timeout;
  return function() {
    clearTimeout(timeout);
    timeout = setTimeout(func, wait);
  };
}

// Initialize on load and resize
window.addEventListener('load', checkView);
window.addEventListener('resize', debounce(checkView, 250));
</script>



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