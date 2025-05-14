<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Suhadah.my.id</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<link rel="stylesheet" href="css/header.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="asset/logo-masjid-suhada.png" alt="Logo" width="60" height="40" class="me-2" />
      Syuhada.com
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarContent">
    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
    <li class="nav-item"><a class="nav-link" href="dashboard.php">Beranda</a></li>
    <li class="nav-item">  <a class="nav-link" href="dakwah.php">Dakwah</a></li>    
      <li class="nav-item"><a class="nav-link" href="zakat.php">Zakat</a></li>
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
      <li class="nav-item"><a class="nav-link" href="keuangan.php">Keuangan</a></li>
      <?php endif; ?>
    <li class="nav-item">
            <a class="nav-link" href="tentang.php">Tentang</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="kontak.php">Kontak</a>
          </li>
  </ul>

  <!-- Login/Logout Button -->
  <div class="login-wrapper">
    <?php if (isset($_SESSION['username']) && $_SESSION['username']): ?>
      <form action="logout.php" method="POST" onsubmit="return confirm('Yakin ingin logout?');">
        <button type="submit" class="login-btn">Logout</button>
      </form>
    <?php else: ?>
      <a href="halaman_login.php" class="login-btn">Login</a>
    <?php endif; ?>
  </div>
</div>
</nav>


<script>
  const toggler = document.querySelector('.navbar-toggler');
  toggler?.addEventListener('click', () => {
    console.log('Toggler clicked');
    const content = document.getElementById('navbarContent');
    console.log('Collapsed:', content?.classList.contains('show'));
  });
</script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>