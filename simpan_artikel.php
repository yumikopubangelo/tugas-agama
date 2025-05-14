<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul   = $_POST['judul'] ?? '';
    $isi     = $_POST['isi'] ?? '';
    $penulis = $_POST['penulis'] ?? '';

    // Buat deskripsi otomatis dari isi (150 karakter pertama, tanpa HTML)
    $deskripsi = substr(strip_tags($isi), 0, 150) . '...';

    // Upload gambar
    $gambar = '';
    $target_dir = "uploads/";

    // Pastikan folder upload ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($_FILES['gambar']['name'])) {
        $file_tmp  = $_FILES["gambar"]["tmp_name"];
        $file_ext  = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $file_name = uniqid("img_", true) . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;

        // Validasi ekstensi file
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($file_ext, $allowed_ext)) {
            echo "<script>alert('❌ Format file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF, WEBP.'); history.back();</script>";
            exit;
        }

        // Simpan gambar
        if (move_uploaded_file($file_tmp, $target_file)) {
            $gambar = $target_file;
        } else {
            echo "<script>alert('❌ Gagal mengupload gambar.'); history.back();</script>";
            exit;
        }
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO artikel_dakwah (judul, deskripsi, isi, gambar, penulis, tanggal_dibuat) VALUES (?, ?, ?, ?, ?, NOW())");
    if (!$stmt) {
        echo "❌ Gagal prepare statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sssss", $judul, $deskripsi, $isi, $gambar, $penulis);

    if ($stmt->execute()) {
        header("Location: dashboard.php?status=success");
        exit;
    } else {
        echo "❌ Gagal menyimpan artikel: " . $stmt->error;
    }
} else {
    echo "❌ Akses tidak valid.";
}
?>
