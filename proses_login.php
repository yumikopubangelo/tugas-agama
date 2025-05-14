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

// Ambil user berdasarkan username
$query = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $storedPassword = $user['password'];
    $userId = $user['user_id'];

    $isHashed = strlen($storedPassword) === 60 && substr($storedPassword, 0, 4) === '$2y$';

    if (
        // Kasus: sudah hashed dan password_verify cocok
        ($isHashed && password_verify($password, $storedPassword)) ||
        // Kasus: belum di-hash dan cocok secara langsung
        (!$isHashed && $password === $storedPassword)
    ) {
        // Jika belum di-hash, hash sekarang dan update
        if (!$isHashed) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE user SET password = ? WHERE user_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $hashedPassword, $userId);
            $updateStmt->execute();
        }

        $_SESSION['is_login'] = true;
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $user['username'];

        // Ambil role dari tabel relasi
        $role_query = "SELECT r.role FROM user_assigment ua
                       JOIN role r ON ua.role_id = r.role_id
                       WHERE ua.user_id = ?";
        $role_stmt = $conn->prepare($role_query);
        $role_stmt->bind_param("i", $userId);
        $role_stmt->execute();
        $role_result = $role_stmt->get_result();

        if ($role_result && $role_result->num_rows > 0) {
            $role = $role_result->fetch_assoc();
            $_SESSION['role'] = $role['role'];
        }

        // ✅ Redirect ke halaman sukses login
        header("Location: dashboard.php");
        exit();
    }
}

// ❌ Jika username tidak ditemukan atau password salah
$_SESSION['login_error'] = "Username atau Password salah!";
header("Location: halaman_login.php");
exit();
?>
