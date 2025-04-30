    <?php
    session_start();

    // Cek login
    if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true) {
        header("Location: halaman_login.html");
        exit();
    }
    ?>

    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard | <?php echo htmlspecialchars($_SESSION['username']); ?></title>
        <link rel="stylesheet" href="css/style_dashboard.css">
        <link rel="stylesheet" href="css/style_preloader.css">
        <script>
            function confirmLogout() {
                return confirm('Apakah Anda yakin ingin logout?');
            }
        </script>
    </head>
    <body>
    <?php include 'preloader.php'; ?>
        <!-- Navbar -->
        <div class="navbar">
            <div class="logo">Dashboard</div>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="Zakat.php">Zakat</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                    <li><a href="keuangan.php">Keuangan</a></li>
                <?php endif; ?>
            </ul>
            <form class="logout-form" action="halaman_login.php" method="POST" onsubmit="return confirmLogout();">
                <button class="btn-logout" type="submit" name="logout">Logout</button>
            </form>
        </div>

        <!-- Konten -->
        <div class="dashboard-container">
            <h3>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
            <p>Gunakan menu navigasi di atas untuk mengakses fitur-fitur dashboard Anda.</p>
        </div>

    <script src="js/preloader.js"></script>
    </body>
    </html>
