<?php
include 'koneksi.php';

// Ambil ID artikel dari parameter URL
$id = $_GET['id'] ?? 0;

// Ambil data artikel dari database
$stmt = $conn->prepare("SELECT * FROM artikel_dakwah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h3>Artikel tidak ditemukan.</h3>";
    exit;
}

$artikel = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($artikel['judul']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .artikel-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .artikel-gambar {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            margin-bottom: 20px;
        }
        .artikel-isi {
            line-height: 1.8;
            font-size: 1.1rem;
        }
        .artikel-penulis {
            font-style: italic;
            color: #6c757d;
        }
    </style>
</head>
<body class="container py-5">

    <a href="dashboard.php" class="btn btn-secondary mb-4">← Kembali ke Beranda</a>

    <div class="artikel-container">
        <h2 class="mb-3"><?php echo htmlspecialchars($artikel['judul']); ?></h2>
        <p class="artikel-penulis mb-4">
            Ditulis oleh <strong><?php echo htmlspecialchars($artikel['penulis']); ?></strong> pada <?php echo date('d M Y H:i', strtotime($artikel['tanggal_dibuat'])); ?>
        </p>

        <?php if (!empty($artikel['gambar'])): ?>
            <img src="<?php echo htmlspecialchars($artikel['gambar']); ?>" class="img-fluid artikel-gambar" alt="Gambar Artikel">
        <?php endif; ?>

        <div class="artikel-isi">
    <?php
    // Pisahkan isi berdasarkan baris kosong jadi paragraf
    $paragraf = preg_split('/\r\n|\r|\n/', $artikel['isi']);
    foreach ($paragraf as $p) {
        if (trim($p) !== '') {
            echo '<p>' . nl2br(htmlspecialchars($p)) . '</p>';
        }
    }
    ?>
</div>

    </div>

</body>
</html>
