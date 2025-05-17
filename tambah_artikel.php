<?php
include("koneksi.php");

 if (!isset($_SESSION['user_id'])) {
     header("Location: login.php");
    exit;
 }
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Artikel Dakwah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.ckeditor.com/4.25.1/standard/ckeditor.js"></script>
</head>
<body class="container py-5">

  <h2>Tambah Artikel Dakwah</h2>
  <form action="simpan_artikel.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="judul" class="form-label">Judul Artikel</label>
      <input type="text" class="form-control" id="judul" name="judul" required>
    </div>

    <div class="mb-3">
      <label for="gambar" class="form-label">Gambar</label>
      <input type="file" class="form-control" id="gambar" name="gambar" required>
    </div>

    <div class="mb-3">
      <label for="isi" class="form-label">Isi Artikel</label>
      <textarea name="isi" id="isi" rows="10" class="form-control" required></textarea>
      <script>
        CKEDITOR.replace('isi');
      </script>
    </div>

    <div class="mb-3">
      <label for="penulis" class="form-label">Penulis</label>
      <input type="text" class="form-control" id="penulis" name="penulis" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Artikel</button>
    <script>
  // Sinkronkan data dari CKEditor ke textarea saat form disubmit
  document.querySelector('form').addEventListener('submit', function(e) {
    for (instance in CKEDITOR.instances) {
      CKEDITOR.instances[instance].updateElement();
    }
  });
</script>

  </form>

</body>
</html>
