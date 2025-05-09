<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dakwah</title>
   
</head>
<body>
  

<div class="container">

<h3 align="center">Cari Dakwah</h3>
<div class="d-flex justify-content-center my-3">
  <form class="d-flex" role="search" style="max-width: 600px; width: 100%;">
    <input class="form-control me-2 flex-grow-1" type="search" placeholder="Cari dakwah..." aria-label="Search">
    <button class="btn btn-outline-success" type="submit">Search</button>
  </form>
</div>


<br><br>
  <h3 class="text-left">Dakwah Hari Ini</h3>
  <br>
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col">
      <div class="card">
        <img src="../assest/dkwh1.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Judul Artikel 1</h5>
          <p class="card-text">Konten artikel dakwah 1 yang menjelaskan isi penting dakwah hari ini.</p>
          <a href="detail_dakwah.php" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="../assest/dkwh3.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Judul Artikel 2</h5>
          <p class="card-text">Konten artikel dakwah 2 sebagai lanjutan dari misi keislaman masa kini.</p>
          <a href="#" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="../assest/dkwh4.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Judul Artikel 3</h5>
          <p class="card-text">Konten artikel dakwah 3 yang memuat nilai-nilai moral dalam kehidupan.</p>
          <a href="#" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
    <!-- Menambahkan 3 card tambahan -->
    <div class="col">
      <div class="card">
        <img src="../assest/dkwh1.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Judul Artikel 4</h5>
          <p class="card-text">Konten artikel dakwah 4 yang menjelaskan lebih dalam tentang nilai-nilai keislaman.</p>
          <a href="#" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="../assest/dkwh2.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Judul Artikel 5</h5>
          <p class="card-text">Konten artikel dakwah 5 yang membahas tentang pentingnya dakwah di era modern.</p>
          <a href="#" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="../assest/dkwh3.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Judul Artikel 6</h5>
          <p class="card-text">Konten artikel dakwah 6 yang memberikan inspirasi bagi umat.</p>
          <a href="#" class="btn btn-success">Baca Artikel</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
</body>

     