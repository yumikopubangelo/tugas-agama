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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    <?php
    if ($result_jadwal && $result_jadwal->num_rows > 0) {
        while ($row = $result_jadwal->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . date("d M Y", strtotime($row["tanggal"])) . "</td>";
            echo "<td>" . htmlspecialchars($row["acara"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["penceramah"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["lokasi"]) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Belum ada jadwal pengajian.</td></tr>";
    }
    ?>
  </tbody>
</table>

<br><br>
<div class="container">
  <h3 class="text-left">Jadwal Sholat dan Petugas</h3>
  <div class="table-container" style="display: flex; justify-content: space-between;">
    
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

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
<!-- Add this before </body> -->
<div class="admin-panel">
  <div class="panel-tab" onclick="togglePanel()">
    <i class="fas fa-cog fa-spin"></i>
    <span class="notification-badge">6</span>
  </div>
  
  <div class="panel-content">
    <div class="panel-header">
      <h4><i class="fas fa-user-shield"></i> Admin Panel</h4>
      <button class="close-panel" onclick="togglePanel()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="search-box">
      <input type="text" placeholder="Cari fitur...">
      <i class="fas fa-search"></i>
    </div>
    
    <!-- Scrollable Content Area -->
    <div class="panel-scrollable">
      <div class="panel-group">
        <div class="group-header" onclick="toggleGroup(this)">
          <i class="fas fa-newspaper"></i>
          <h5>Manajemen Konten</h5>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="group-links">
          <a href="tambah_artikel.php" class="panel-link">
            <div class="link-icon" style="background: #4CAF50;">
              <i class="fas fa-pen"></i>
            </div>
            <div class="link-text">
              <span>Buat Artikel</span>
              <small>Publikasi konten baru</small>
            </div>
          </a>
        </div>
      </div>
      
      <div class="panel-group">
        <div class="group-header" onclick="toggleGroup(this)">
          <i class="fas fa-users-cog"></i>
          <h5>Administrator</h5>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="group-links">
          <a href="tambah_admin.php" class="panel-link">
            <div class="link-icon" style="background: #2196F3;">
              <i class="fas fa-user-plus"></i>
            </div>
            <div class="link-text">
              <span>Tambah Admin</span>
              <small>Buat akun baru</small>
            </div>
          </a>
          <a href="lihat_admin.php" class="panel-link">
            <div class="link-icon" style="background: #673AB7;">
              <i class="fas fa-users"></i>
            </div>
            <div class="link-text">
              <span>Lihat Admin</span>
              <small>Kelola pengguna</small>
            </div>
          </a>
        </div>
      </div>
      
      <div class="panel-group">
        <div class="group-header" onclick="toggleGroup(this)">
          <i class="fas fa-calendar-alt"></i>
          <h5>Jadwal Kegiatan</h5>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="group-links">
          <a href="tambah_pengajian.php" class="panel-link">
            <div class="link-icon" style="background: #FF9800;">
              <i class="fas fa-mosque"></i>
            </div>
            <div class="link-text">
              <span>Pengajian</span>
              <small>Atur jadwal kajian</small>
            </div>
          </a>
          <a href="tambah_jadwal_sholat.php" class="panel-link">
            <div class="link-icon" style="background: #F44336;">
              <i class="fas fa-clock"></i>
            </div>
             <div class="link-text">
              <span>Jadwal Sholat</span>
              <small>Atur waktu ibadah</small>
            </div>
          </a>
          <a href="tambah_petugas.php" class="panel-link">
            <div class="link-icon" style="background: #009688;">
              <i class="fas fa-user-cog"></i>
            </div>
            <div class="link-text">
              <span>Petugas</span>
              <small>Kelura pembagian tugas</small>
            </div>
          </a>
        </div>
      </div>
    </div> < <!-- End of scrollable content -->
    
    <div class="panel-footer">
      <small>DKM Masjid Suhada</small>
      <a href="logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </div>
</div>

<style>

/* Fixed positioning for the panel */
.admin-panel {
  position: fixed;
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  z-index: 1000;
}

/* Panel content with proper height calculation */
.panel-content {
  position: absolute;
  right: -300px;
  width: 300px;
  max-height: calc(100vh - 40px); /* Accounts for potential browser chrome */
  background: white;
  box-shadow: -5px 5px 15px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  transition: right 0.3s ease;
}

/* Scrollable area with proper constraints */
.panel-scrollable {
  flex: 1;
  overflow-y: auto;
  padding: 0 15px;
  -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
}

/* Ensure content never exceeds viewport */
@media (max-height: 700px) {
  .panel-content {
    max-height: 95vh;
    top: 10px;
    bottom: 10px;
    transform: none;
  }
}

/* ===== Base Styles ===== */
.admin-panel {
  position: fixed;
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  z-index: 1000;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Panel Content with Scroll */
.panel-content {
  position: absolute;
  right: -300px;
  top: 0;
  width: 300px;
  height: 80vh;
  max-height: 700px;
  background: white;
  border-radius: 15px 0 0 15px;
  box-shadow: -10px 5px 25px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  transition: right 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
}

.admin-panel.open .panel-content {
  right: 0;
}

/* Scrollable Area */
.panel-scrollable {
  flex-grow: 1;
  overflow-y: auto;
  padding: 0 15px 15px;
  -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
}

/* Scrollbar Styling */
.panel-scrollable::-webkit-scrollbar {
  width: 6px;
}
.panel-scrollable::-webkit-scrollbar-thumb {
  background: rgba(0,0,0,0.2);
  border-radius: 3px;
}
.admin-panel {
  position: fixed;
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  z-index: 1000;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ===== Tab Button ===== */
.panel-tab {
  position: absolute;
  left: -40px;
  background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
  color: white;
  width: 40px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px 0 0 8px;
  cursor: pointer;
  box-shadow: -5px 5px 15px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  z-index: 2;
}

.panel-tab:hover {
  width: 45px;
  left: -45px;
}

.panel-tab i {
  font-size: 1.2rem;
}

.notification-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #FF5722;
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ===== Panel Content ===== */
.panel-content {
  position: absolute;
  right: -300px;
  top: 0;
  width: 300px;
  height: 80vh;
  max-height: 600px;
  background: white;
  border-radius: 15px 0 0 15px;
  box-shadow: -10px 5px 25px rgba(0,0,0,0.1);
  transition: right 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.admin-panel.open .panel-content {
  right: 0;
}

/* ===== Header ===== */
.panel-header {
  padding: 15px 20px;
  background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.panel-header h4 {
  margin: 0;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
}

.close-panel {
  background: transparent;
  border: none;
  color: white;
  font-size: 1.2rem;
  cursor: pointer;
  opacity: 0.8;
  transition: opacity 0.2s;
}

.close-panel:hover {
  opacity: 1;
}

/* ===== Search Box ===== */
.search-box {
  padding: 15px 20px;
  position: relative;
  border-bottom: 1px solid #eee;
}

.search-box input {
  width: 100%;
  padding: 8px 15px 8px 35px;
  border: 1px solid #ddd;
  border-radius: 20px;
  outline: none;
  transition: border 0.3s;
}

.search-box input:focus {
  border-color: #4CAF50;
}

.search-box i {
  position: absolute;
  left: 30px;
  top: 50%;
  transform: translateY(-50%);
  color: #777;
}

/* ===== Panel Groups ===== */
.panel-group {
  border-bottom: 1px solid #f0f0f0;
}

.group-header {
  padding: 12px 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  transition: background 0.2s;
}

.group-header:hover {
  background: #f9f9f9;
}

.group-header h5 {
  margin: 0;
  flex-grow: 1;
  font-size: 0.95rem;
  color: #444;
}

.group-header i:first-child {
  color: #4CAF50;
}

.group-header i:last-child {
  transition: transform 0.3s;
}

.panel-group.open .group-header i:last-child {
  transform: rotate(180deg);
}

.group-links {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease-out;
}

.panel-group.open .group-links {
  max-height: 500px;
}

/* ===== Panel Links ===== */
.panel-link {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  text-decoration: none;
  color: #333;
  transition: background 0.2s;
}

.panel-link:hover {
  background: #f5fff5;
}

.link-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  margin-right: 12px;
  flex-shrink: 0;
}

.link-text {
  flex-grow: 1;
}

.link-text span {
  display: block;
  font-size: 0.9rem;
  font-weight: 500;
}

.link-text small {
  display: block;
  font-size: 0.75rem;
  color: #777;
  margin-top: 2px;
}

/* ===== Footer ===== */
.panel-footer {
  margin-top: auto;
  padding: 12px 20px;
  background: #f9f9f9;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.8rem;
  color: #666;
}

.logout-btn {
  color: #F44336;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 5px;
  font-weight: 500;
}

/* ===== Scrollbar ===== */
.panel-scrollable {
  flex-grow: 1;
  overflow-y: auto;
  padding-bottom: 10px;
}

.panel-scrollable::-webkit-scrollbar {
  width: 6px;
}

.panel-scrollable::-webkit-scrollbar-thumb {
  background: #ccc;
  border-radius: 3px;
}
/* Add these to your existing styles */
.group-links {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease-out;
}

.panel-content {
  transition: right 0.3s ease, max-height 0.3s ease;
}

.search-box input {
  transition: all 0.3s ease;
}
/* ===== Animation ===== */
@keyframes fadeIn {
  from { opacity: 0; transform: translateX(10px); }
  to { opacity: 1; transform: translateX(0); }
}

.panel-link {
  animation: fadeIn 0.3s ease-out forwards;
  opacity: 0;
}
@media (max-height: 600px) {
  .panel-content {
    height: 90vh;
  }}

.panel-link:nth-child(1) { animation-delay: 0.1s; }
.panel-link:nth-child(2) { animation-delay: 0.2s; }
.panel-link:nth-child(3) { animation-delay: 0.3s; }
</style>

<script>
// Toggle main panel with viewport boundary checking
function togglePanel() {
  const panel = document.querySelector('.admin-panel');
  const content = panel.querySelector('.panel-content');
  
  panel.classList.toggle('open');
  
  if (panel.classList.contains('open')) {
    // Reset styles first
    content.style.maxHeight = '';
    content.style.top = '';
    content.style.bottom = '';
    
    // Calculate available space
    const viewportHeight = window.innerHeight;
    const panelHeight = content.scrollHeight;
    const spaceAbove = content.getBoundingClientRect().top;
    const spaceBelow = viewportHeight - spaceAbove;
    
    // Adjust if panel would go off-screen
    if (panelHeight > spaceBelow) {
      const newHeight = Math.min(panelHeight, viewportHeight - 20);
      content.style.maxHeight = `${newHeight}px`;
      content.style.overflowY = 'auto';
      
      // If panel is too tall even after adjustment
      if (newHeight >= viewportHeight - 20) {
        content.style.top = '10px';
        content.style.bottom = '10px';
      }
    }
  }
}

// Enhanced close-panel when clicking outside
document.addEventListener('click', function(e) {
  const panel = document.querySelector('.admin-panel');
  const tab = document.querySelector('.panel-tab');
  
  if (!panel.contains(e.target) && e.target !== tab && !tab.contains(e.target)) {
    panel.classList.remove('open');
  }
});

// Responsive resize handler
let resizeTimer;
window.addEventListener('resize', function() {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(function() {
    const panel = document.querySelector('.admin-panel.open');
    if (panel) {
      const content = panel.querySelector('.panel-content');
      const viewportHeight = window.innerHeight;
      content.style.maxHeight = `${viewportHeight - 20}px`;
      
      // Re-check position
      togglePanel();
    }
  }, 250);
});

// Toggle individual groups with smooth animation
function toggleGroup(header) {
  const group = header.parentElement;
  const links = group.querySelector('.group-links');
  
  group.classList.toggle('open');
  
  if (group.classList.contains('open')) {
    links.style.maxHeight = `${links.scrollHeight}px`;
  } else {
    links.style.maxHeight = '0';
  }
}

// Debounced search functionality
let searchTimer;
document.querySelector('.search-box input').addEventListener('input', function(e) {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(function() {
    const searchTerm = e.target.value.toLowerCase().trim();
    
    document.querySelectorAll('.panel-link').forEach(link => {
      const text = link.textContent.toLowerCase();
      link.style.display = text.includes(searchTerm) ? 'flex' : 'none';
    });
    
    // Auto-expand groups with matches
    document.querySelectorAll('.panel-group').forEach(group => {
      const hasVisibleLinks = group.querySelector('.panel-link[style="display: flex;"]');
      if (hasVisibleLinks) {
        group.classList.add('open');
        group.querySelector('.group-links').style.maxHeight = `${group.querySelector('.group-links').scrollHeight}px`;
      }
    });
  }, 300);
});

// Initialize groups with proper max-height
document.querySelectorAll('.panel-group').forEach(group => {
  const links = group.querySelector('.group-links');
  if (group.classList.contains('open')) {
    links.style.maxHeight = `${links.scrollHeight}px`;
  } else {
    links.style.maxHeight = '0';
  }
});
</script>
<?php endif; ?>

<?php include 'footer.php'; ?>
<script src="js/preloader.js"></script>
</body>
</html>