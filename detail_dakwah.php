<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Detail Dakwah</title>
</head>
<style>
    .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card-body {
        flex-grow: 1; /* agar mengisi ruang vertikal yang tersedia */
        display: flex;
        flex-direction: column;
    }

    .card-text {
        flex-grow: 1; /* membuat teks mendorong tombol ke bawah */
    }

    .card .btn {
        margin-top: auto; /* tombol akan selalu di bawah */
    }
</style>

<body>
    <div class="container my-5">
        <img src="assest/dkwh1.jpg" class="img-fluid rounded mb-4" alt="Kajian Dakwah">

        <h1 class="mb-3">Menjaga Hati di Era Digital</h1>
        <p><em>Oleh: Ust. Ahmad Saefullah</em></p>
        <hr>

        <p>
            Di tengah derasnya arus informasi dan kemudahan teknologi saat ini, kita sebagai umat Islam harus mampu menjaga hati agar tetap bersih dan tidak lalai dari mengingat Allah. Dunia digital bisa menjadi ladang pahala, namun juga bisa menjadi sumber maksiat jika tidak digunakan dengan bijak.
        </p>

        <p>
            Allah SWT berfirman dalam Al-Qur’an:
        </p>

        <blockquote class="blockquote">
            <p class="mb-0">"Sesungguhnya beruntunglah orang yang membersihkan jiwa itu." (QS. Asy-Syams: 9)</p>
        </blockquote>

        <p>
            Ayat ini mengingatkan kita bahwa kebersihan hati dan jiwa adalah kunci keberuntungan di dunia dan akhirat. Maka dari itu, penting bagi kita untuk menjaga niat, perkataan, dan tindakan, baik di dunia nyata maupun di dunia maya.
        </p>

        <p>
            Salah satu cara menjaga hati adalah dengan memperbanyak dzikir, membaca Al-Qur'an, serta menyaring informasi yang kita terima dan bagikan. Jadikan media sosial sebagai sarana dakwah dan kebaikan, bukan untuk menyebarkan kebencian atau gosip.
        </p>

        <p>
            Mari kita jadikan era digital ini sebagai jalan menuju keberkahan, bukan malah menjauhkan kita dari Allah SWT. Mulailah dari diri sendiri, dan terus perbaiki niat serta amal sehari-hari.
        </p>

        <br><br>
        <h3>Dakwah Terkait</h3>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card">
                    <img src="assest/dkwh1.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Menjadi Pemuda Islam yang Tangguh</h5>
                        <p class="card-text">Refleksi dakwah untuk membentuk generasi muda Islam yang kuat iman dan karakter.</p>
                        <a href="detail_dakwah.php" class="btn btn-success">Baca Artikel</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="assest/dkwh3.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Keutamaan Shalat Berjamaah</h5>
                        <p class="card-text">Pentingnya menjaga shalat berjamaah sebagai bentuk ketaatan dan persatuan umat.</p>
                        <a href="#" class="btn btn-success">Baca Artikel</a>
                    </div>
                </div>
            </div>
            <div class="col">
  <div class="card h-100 d-flex flex-column">
    <img src="assest/dkwh4.jpg" class="card-img-top" alt="...">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">Judul Artikel 3</h5>
      <p class="card-text flex-grow-1">Konten artikel dakwah 3 yang memuat nilai-nilai moral.</p>
      <a href="#" class="btn btn-success mt-auto">Baca Artikel</a>
    </div>
  </div>
</div>

        </div>
    </div>

<?php include 'footer.php'; ?>
</body>
</html>
