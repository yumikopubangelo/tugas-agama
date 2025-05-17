<?php
include "koneksi.php";

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
  <title>Kontak</title>
</head>
<body>

<div class="container my-5">
  <h3 class="text-center mb-4">Hubungi Kami</h3>
  <hr>
  
  <div class="row">
    <!-- Informasi Kontak -->
    <div class="col-md-6 mb-4">
      <h5>Informasi Kontak Masjid Suhada</h5>
      <p><strong>Alamat:</strong> Jl. Raya Suhada No. 123, Tasikmalaya</p>
      <p><strong>Email:</strong> dkm.suhada@gmail.com</p>
      <p><strong>Telepon/WA:</strong> 0821-1234-5678</p>
      <div class="map-responsive">
        <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3957.3461148746323!2d108.218936!3d-7.314959999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zN8KwMTgnNTMuOSJTIDEwOMKwMTMnMDguMiJF!5e0!3m2!1sen!2sid!4v1746796991709!5m2!1sen!2sid"
                width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>

    <!-- Form Kontak -->
    <div class="col-md-6">
      <h5>Kirim Pesan</h5>
      <form>
        <div class="mb-3">
          <label for="inputNama" class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" id="inputNama" required>
        </div>
        <div class="mb-3">
          <label for="inputEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="inputEmail" required>
        </div>
        <div class="mb-3">
          <label for="inputPesan" class="form-label">Pesan</label>
          <textarea class="form-control" id="inputPesan" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Kirim</button>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
