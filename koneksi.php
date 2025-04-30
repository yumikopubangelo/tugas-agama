<?php
$servername = "localhost";
$username = "root";    // Ganti kalau beda
$password = "";        // Password MySQL kamu
$database = "Agama";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>