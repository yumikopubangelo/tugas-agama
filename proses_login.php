<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

// Cek user
$query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Set session
    $_SESSION['is_login'] = true;
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];

    // Cek role dan set ke session
    $role_query = "SELECT role FROM user_assigment ua
                   JOIN role r ON ua.role_id = r.role_id
                   WHERE ua.user_id = " . $user['user_id'];
    $role_result = mysqli_query($conn, $role_query);
    $role = mysqli_fetch_assoc($role_result);

    $_SESSION['role'] = $role['role']; // Simpan role (Admin atau User)

    header("Location: dashboard.php"); // Setelah login berhasil
    exit();
} else {
    echo "<script>
        alert('Username atau Password salah!');
        window.location.href = 'halaman_login.html';
    </script>";
}
?>
