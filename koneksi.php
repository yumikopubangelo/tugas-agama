<?php
// =============================================
// DATABASE CONFIGURATION
// =============================================
$servername = "localhost";
$username = "root";
$password = "";
$database = "Agama";

// =============================================
// SECURITY CONSTANTS
// =============================================
if (!defined('CSRF_TOKEN_NAME')) {
    define('CSRF_TOKEN_NAME', 'csrf_token');
}

// =============================================
// SESSION HANDLING (with check)
// =============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token only once
if (empty($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

// =============================================
// DATABASE CONNECTION
// =============================================
if (!isset($conn)) {
    $conn = mysqli_connect($servername, $username, $password, $database);
    
    if (!$conn) {
        error_log("Database connection failed: " . mysqli_connect_error());
        die("Terjadi kesalahan sistem. Silakan coba lagi nanti.");
    }

    mysqli_set_charset($conn, "utf8mb4");
}

// =============================================
// CSRF VALIDATION FUNCTION (with check)
// =============================================
if (!function_exists('validate_csrf_token')) {
    function validate_csrf_token($token) {
        if (!isset($_SESSION[CSRF_TOKEN_NAME]) || $token !== $_SESSION[CSRF_TOKEN_NAME]) {
            die("CSRF token validation failed.");
        }
        return true;
    }
}

// =============================================
// SECURITY HEADERS (sent only once)
// =============================================
if (!headers_sent()) {
    header("Content-Security-Policy: "
        . "default-src 'self'; "
        . "frame-src 'self' https://www.google.com; "
        . "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; "
        . "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
        . "font-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com data:; "
        . "img-src 'self' data: https://cdn.jsdelivr.net; "
        . "connect-src 'self'; "
        . "form-action 'self';"
    );
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
}
?>