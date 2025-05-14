<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Form Registrasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Opsional: Haluskan transisi tombol daftar */
    .btn-outline-success {
      transition: all 0.2s ease-in-out;
    }

    .btn-outline-success:hover {
      color: #fff !important;
      background-color: #198754 !important; /* Bootstrap green */
      border-color: #198754 !important;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm rounded-4 p-4" style="max-width: 500px; width: 100%;">
      <h2 class="mb-4 text-center">Buat Akun</h2>
      <form action="proses_register.php" method="POST">
        <div class="mb-3">
          <label for="nama" class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Kata Sandi</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
          <label for="konfirmasi_password" class="form-label">Konfirmasi Kata Sandi</label>
          <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
        </div>
        <button type="submit" class="btn btn-outline-success w-100 mb-2">Daftar</button>
        <a href="login.php" class="btn btn-outline-secondary w-100">Kembali ke Login</a>
      </form>
    </div>
  </div>
</body>
</html>
