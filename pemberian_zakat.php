<?php
require_once 'koneksi.php';
if (!isAdmin()) {
    header('Location: zakat.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lokasi Penyaluran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .form-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .card-header {
            background-color: #2c3e50;
            color: white;
            border-radius: 8px 8px 0 0 !important;
            padding: 1.25rem;
        }
        .form-control {
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            border-radius: 6px;
        }
        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.1);
        }
        .btn-primary {
            background-color: #2c3e50;
            border-color: #2c3e50;
            padding: 8px 20px;
        }
        .btn-primary:hover {
            background-color: #1a252f;
            border-color: #1a252f;
        }
        .form-label {
            font-weight: 500;
            color: #34495e;
        }
        .input-hint {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-top: 4px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card form-card">
                    <div class="card-header">
                        <h3 class="h5 mb-0"><i class="bi bi-geo-alt me-2"></i>Tambah Lokasi Penyaluran</h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="proses_pemberian_zakat.php" method="post">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $_SESSION[CSRF_TOKEN_NAME] ?>">
                            
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                <div class="input-hint">Format: DD/MM/YYYY</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="acara" class="form-label">Nama Acara</label>
                                <input type="text" class="form-control" id="acara" name="acara" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="penceramah" class="form-label">Nama Penceramah</label>
                                <input type="text" class="form-control" id="penceramah" name="penceramah" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                                <div class="input-hint">Masukkan alamat lengkap</div>
                            </div>
                            
                            <div class="d-flex justify-content-between pt-2">
                                <a href="zakat.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>