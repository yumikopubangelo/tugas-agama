<?php 
include "koneksi.php";
session_start();
var_dump($_SESSION); // Cek apakah "is_login" muncul
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: halaman_login.php");
    exit(); // Ensure script stops executing after redirect
}


if (isset($_POST['logout'])){
    session_destroy();
    header("location:index.php");
    exit(); // Ensure script stops executing after redirect
}

// Periksa apakah pengguna memiliki akses admin
$admin_akses = isset($_SESSION['admin_akses']) ? $_SESSION['admin_akses'] : null;
$admin = false; // Set default value for admin access

if ($admin_akses !== null) {
    // Ensure that $admin_akses is an array
    $admin_akses = (array) $admin_akses;
    $admin = in_array("admin", $admin_akses);
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
            <form action="logout.php" method="POST" onsubmit="return confirmLogout();">
    <button type="submit" class="btn-logout">Logout</button>
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
