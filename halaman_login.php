<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Kas Masjid</title>
  <link rel="stylesheet" href="css/style_preloader.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(120deg, #f0f2f5, #e2e6ea);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-container {
      background: #fff;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }
    .login-container h3 {
      text-align: center;
      margin-bottom: 28px;
      color: #333;
      font-weight: bold;
    }
    .login-container label {
      font-weight: 500;
      color: #444;
    }
    .login-container input {
      width: 100%;
      padding: 12px;
      margin-top: 8px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      transition: border-color 0.3s;
    }
    .login-container input:focus {
      border-color: #4CAF50;
      outline: none;
    }
    .login-container button {
      width: 100%;
      padding: 12px;
      border: 2px solid #4CAF50;
      background-color: transparent;
      color: #4CAF50;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }
    .login-container button:hover {
      background-color: #4CAF50;
      color: white;
    }
    .alert {
      background-color: #f8d7da;
      color: #842029;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: center;
    }
    .kembali {
      display: block;
      text-align: center;
      margin-top: 16px;
      color: #555;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.3s;
    }
    .kembali:hover {
      color: #000;
    }
  </style>
</head>
<body>

<?php include 'preloader.php'; ?>
<div class="login-container">
  <h3>Login</h3>

  <?php
  session_start();
  if (isset($_SESSION['login_error'])): ?>
    <div class="alert">
      <?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
    </div>
  <?php endif; ?>

  <form action="proses_login.php" method="POST">
    <label for="username">Username</label>
    <input type="text" name="username" required>

    <label for="password">Kata Sandi</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
  </form>

  <a class="kembali" href="register.php">Belum punya akun? Daftar di sini</a>
</div>

<script src="js/preloader.js"></script>
</body>
</html>
