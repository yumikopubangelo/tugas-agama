<?php
require_once 'koneksi.php';
require_once 'ZakatModel.php';

// Initialize model and get data
$zakatModel = new ZakatModel($conn);

// Initialize default data structure
$zakatData = [
    'total' => 0,
    'transactions' => 0,
    'types' => [],
    'distribution' => [],
    'locations' => []
];

try {
    // Get all data
    $zakatData = $zakatModel->getZakatSummary();
    $zakatTypes = $zakatModel->getZakatTypes();
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    // You might want to show this to admins
    if (isAdmin()) {
        echo "Error loading data: " . $e->getMessage();
    }
}


// Get data for locations table
$locations = [];
try {
    $stmt = $conn->prepare("SELECT tanggal, acara, penceramah, lokasi FROM lokasi_penyaluran ORDER BY tanggal DESC");
    $stmt->execute();
    $locations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log("Locations DB error: " . $e->getMessage());
    if (isAdmin()) {
        echo "Error loading locations: " . $e->getMessage();
    }
}

// Helper functions
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function formatPercentage($number) {
    return number_format($number, 1) . '%';
}

function formatDate($dateString) {
    return date('d M Y', strtotime($dateString));
}
?>

<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <?php include 'header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan Zakat Masjid Suhada</title>
 
    <link rel="stylesheet" href="zakat_table.css">
    <link rel="stylesheet" href="css/toast.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .zakat-card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .zakat-card:hover {
            transform: translateY(-5px);
        }
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        .progress-bar {
            background-color: #2ecc71;
        }
        .zakat-type-item {
            border-left: 4px solid #2ecc71;
            transition: all 0.3s ease;
        }
        .zakat-type-item:hover {
            background-color: rgba(46, 204, 113, 0.1);
        }
        .form-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-title {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .form-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .form-button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .table-responsive {
            overflow-x: auto;
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr !important;
            }
        }
        .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-column {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
    </style>
</head>
<body>
    <?php include 'preloader.php'; ?>
    <div class="main-content container py-4">

        <h1 class="text-center mb-4 fw-bold">Sistem Manajemen Zakat Masjid Suhada</h1>

        <!-- Information Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h4">Apa itu Zakat?</h2>
                <p class="card-text">
                    Zakat adalah salah satu rukun Islam yang wajib ditunaikan oleh setiap Muslim yang mampu. Zakat berarti menyucikan harta dengan cara memberikan sebagian harta tersebut kepada mereka yang berhak menerima, seperti fakir, miskin, dan golongan lainnya yang tercantum dalam Al-Qur'an Surah At-Taubah ayat 60. 
                </p>
                <p class="card-text">
                    Jenis-jenis zakat yang umum dikenal adalah zakat fitrah dan zakat mal. Zakat fitrah ditunaikan menjelang Idul Fitri sebagai bentuk penyucian diri selama bulan Ramadan, sedangkan zakat mal adalah zakat atas harta yang telah mencapai nisab dan haul, seperti emas, perak, penghasilan, hasil pertanian, dan lain-lain.
                </p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Zakat Terkumpul Card -->
         <!-- Zakat Terkumpul Card -->
<div class="col-lg-6">
    <div class="card zakat-card text-white bg-success h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title h5 mb-0">
                    <i class="bi bi-coin me-2" aria-hidden="true"></i>Zakat Terkumpul
                </h2>
                <span class="badge bg-light text-dark fs-6">
                    <?= htmlspecialchars($zakatData['transactions']) ?> Transaksi
                </span>
            </div>
            
            <h3 class="display-5 fw-bold mb-4"><?= formatRupiah($zakatData['total']) ?></h3>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small>Terkumpul</small>
                    <small><?= formatPercentage(array_reduce($zakatData['types'], fn($carry, $item) => $carry + ($item['percentage'] ?? 0), 0)) ?></small>
                </div>
                <div class="progress" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
            </div>
            
            <h4 class="h6 mt-4 mb-3">
                <i class="bi bi-pie-chart me-2" aria-hidden="true"></i>Distribusi Per Jenis
            </h4>
            
            <div class="list-group">
                <?php foreach ($zakatData['types'] as $type): ?>
                    <?php if (($type['amount'] ?? 0) > 0): ?>
                    <div class="list-group-item zakat-type-item bg-transparent text-white border-0 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><?= htmlspecialchars($type['name'] ?? '') ?></span>
                            <strong><?= formatRupiah($type['amount'] ?? 0) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="opacity-75"><?= formatPercentage($type['percentage'] ?? 0) ?> dari total</small>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer bg-success bg-opacity-75 border-0">
            <small class="opacity-75">Update terakhir: <?= date('d M Y H:i') ?></small>
        </div>
    </div>
</div>
            
            <!-- Zakat Tersalurkan Card -->
            <div class="col-lg-6">
                <div class="card zakat-card text-white bg-info h-100">
                    <div class="card-body">
                        <h2 class="card-title h5">
                            <i class="bi bi-arrow-up-right-circle me-2" aria-hidden="true"></i>Zakat Tersalurkan
                        </h2>
                        <h3 class="display-5 fw-bold my-4">
                            <?= formatRupiah($zakatSummary['distributed'] ?? 0) ?>
                        </h3>
                        <div class="alert alert-light text-dark">
                            <i class="bi bi-info-circle me-2" aria-hidden="true"></i>
                            Zakat telah disalurkan ke mustahik
                        </div>
                    </div>
                    <div class="card-footer bg-info bg-opacity-75 border-0">
                        <small class="opacity-75">Proses verifikasi mustahik sedang berlangsung</small>
                    </div>
                </div>
            </div>
        </div>



  <h2 class="h4 mb-3">Lokasi Penyaluran Zakat</h2>
        <div class="table-responsive mb-4">
            <table class="table table-striped" aria-label="Daftar lokasi penyaluran zakat">
                <thead>
                    <tr>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Acara</th>
                        <th scope="col">Penceramah</th>
                        <th scope="col">Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($locations)): ?>
                        <?php foreach ($locations as $location): ?>
                            <tr>
                                <td><?= formatDate($location['tanggal']) ?></td>
                                <td><?= htmlspecialchars($location['acara']) ?></td>
                                <td><?= htmlspecialchars($location['penceramah']) ?></td>
                                <td><?= htmlspecialchars($location['lokasi']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data lokasi penyaluran</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isAdmin()): ?>
        <div class="text-end mb-4">
            <a href="pemberian_zakat.php" class="text-decoration-none small">
                <i class="bi bi-plus-circle"></i> Tambahkan
            </a>
        </div>
        <?php endif; ?>
        <!-- Zakat Submission Form -->
        <form action="proses_zakat.php" method="POST" class="colorful-form" id="zakatForm" novalidate>
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $_SESSION[CSRF_TOKEN_NAME] ?>">

            <!-- Personal Information -->
            <div class="form-section">
                <h2 class="form-title">Informasi Pribadi</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required 
                                   pattern="[A-Za-z\s]{3,}" title="Minimal 3 karakter huruf">
                        </div>
                        
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="telepon" name="telepon" required
                                   pattern="[0-9]{10,15}" title="10-15 digit angka">
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="tanggal_pembayaran" name="tanggal_pembayaran" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jumlah_tanggungan" class="form-label">Jumlah Tanggungan</label>
                            <input type="number" class="form-control" id="jumlah_tanggungan" name="jumlah_tanggungan" min="1" required>
                        </div>
                    </div>
                </div>
            </div>

          <!-- Zakat Type -->
<!-- JENIS ZAKAT -->
<div class="form-group">
    <label for="jenis_zakat">Jenis Zakat:</label>
    <select name="jenis_zakat" id="jenis_zakat" required>
        <option value="" selected disabled>-- Pilih Jenis Zakat --</option>
        <?php
        $query = mysqli_query($conn, "SELECT id, nama FROM jenis_zakat");
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<option value='{$row['id']}' data-nama='{$row['nama']}'>{$row['nama']}</option>";
        }
        ?>
    </select>
</div>

<!-- ZAKAT FITRAH -->
<div id="jumlah-zakat-group" class="form-section" style="display: none;">
    <div class="form-grid">
        <div class="form-column">
            <div class="form-group">
                <label for="jumlah_individu">Jumlah Individu:</label>
                <input type="number" id="jumlah_individu" name="jumlah_individu" min="1">
            </div>
            <div class="form-group">
                <label for="harga_beras">Harga Beras per Kg:</label>
                <input type="number" id="harga_beras" name="harga_beras" step="0.01">
            </div>
        </div>
        <div class="form-column">
            <div class="form-group">
                <label for="jumlah_zakat2">Jumlah Zakat:</label>
                <input type="text" id="jumlah_zakat2" name="jumlah_zakat" readonly>
            </div>
        </div>
    </div>
</div>

<!-- ZAKAT PENGHASILAN -->
<div id="penghasilan-group" class="form-section" style="display: none;">
    <div class="form-grid">
        <div class="form-column">
            <div class="form-group">
                <label for="Penghasilan">Jumlah Penghasilan:</label>
                <input type="number" id="Penghasilan" name="Penghasilan">
            </div>
            <div class="form-group">
                <label for="persentase_zakat">Persentase Zakat (%):</label>
                <input type="number" id="persentase_zakat" name="persentase_zakat" step="0.01" value="2.5">
            </div>
        </div>
        <div class="form-column">
            <div class="form-group">
                <label for="Jumlah_Zakat_penghasilan">Jumlah Zakat:</label>
                <input type="text" id="Jumlah_Zakat_penghasilan" name="jumlah_zakat" readonly>
            </div>
        </div>
    </div>
</div>

<!-- ZAKAT TERNAK -->
<div id="ternak-group" class="form-section" style="display: none;">
    <div class="form-grid">
        <div class="form-column">
            <div class="form-group">
                <label for="jenis_ternak">Jenis Ternak:</label>
                <select id="jenis_ternak" name="jenis_ternak">
                    <option value="kambing">Kambing</option>
                    <option value="sapi">Sapi</option>
                    <option value="unta">Unta</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah_ternak">Jumlah Ternak:</label>
                <input type="number" id="jumlah_ternak" name="jumlah_ternak" min="1">
            </div>
        </div>
        <div class="form-column">
            <div class="form-group">
                <label for="Jumlah_Zakat_ternak">Jumlah Zakat:</label>
                <input type="text" id="Jumlah_Zakat_ternak" name="jumlah_zakat" readonly>
            </div>
        </div>
    </div>
</div>

<!-- TOMBOL SIMPAN -->
<div class="form-section text-center">
    <button class="form-button" type="submit">Simpan Data</button>
</div>

    <?php include 'footer.php'; ?>
    
    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    
  
  <<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form immediately
    toggleJenisZakat();
    
    // Set up event listener
    const jenisZakatSelect = document.getElementById('jenis_zakat');
    if (jenisZakatSelect) {
        jenisZakatSelect.addEventListener('change', toggleJenisZakat);
    }
});

function toggleJenisZakat() {
    const select = document.getElementById('jenis_zakat');
    const selectedId = select.value; // Get the ID value
    
    const jumlahZakatGroup = document.getElementById('jumlah-zakat-group');
    const penghasilanGroup = document.getElementById('penghasilan-group');
    const ternakGroup = document.getElementById('ternak-group');

    // Hide all groups first
    [jumlahZakatGroup, penghasilanGroup, ternakGroup].forEach(group => {
        if (group) {
            group.style.display = 'none';
            group.querySelectorAll('input, select').forEach(el => el.disabled = true);
        }
    });

    // Show the relevant group based on ID
    if (selectedId === "1") { // Fitrah
        if (jumlahZakatGroup) {
            jumlahZakatGroup.style.display = 'block';
            jumlahZakatGroup.querySelectorAll('input, select').forEach(el => {
                el.disabled = false;
                // Reattach event listeners
                if (el.id === 'jumlah_individu' || el.id === 'harga_beras') {
                    el.addEventListener('input', hitungZakatFitrah);
                }
            });
            hitungZakatFitrah();
        }
    } else if (selectedId === "2") { // Penghasilan
        if (penghasilanGroup) {
            penghasilanGroup.style.display = 'block';
            penghasilanGroup.querySelectorAll('input, select').forEach(el => {
                el.disabled = false;
                // Reattach event listeners
                if (el.id === 'Penghasilan' || el.id === 'persentase_zakat') {
                    el.addEventListener('input', hitungZakatPenghasilan);
                }
            });
            hitungZakatPenghasilan();
        }
    } else if (selectedId === "3") { // Ternak
        if (ternakGroup) {
            ternakGroup.style.display = 'block';
            ternakGroup.querySelectorAll('input, select').forEach(el => {
                el.disabled = false;
                // Reattach event listeners
                if (el.id === 'jumlah_ternak' || el.id === 'jenis_ternak') {
                    el.addEventListener('change', hitungZakatTernak);
                    el.addEventListener('input', hitungZakatTernak);
                }
            });
            hitungZakatTernak();
        }
    }
}

function hitungZakatFitrah() {
    const jumlahIndividu = parseFloat(document.getElementById('jumlah_individu')?.value) || 0;
    const hargaBeras = parseFloat(document.getElementById('harga_beras')?.value) || 0;
    const zakat = jumlahIndividu * 2.5 * hargaBeras; // 2.5 kg per person
    const outputField = document.getElementById('jumlah_zakat2');
    if (outputField) {
        outputField.value = zakat.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
}

function hitungZakatPenghasilan() {
    const penghasilan = parseFloat(document.getElementById('Penghasilan')?.value) || 0;
    const persen = parseFloat(document.getElementById('persentase_zakat')?.value) || 2.5;
    const zakat = (penghasilan * persen) / 100;
    const outputField = document.getElementById('Jumlah_Zakat_penghasilan');
    if (outputField) {
        outputField.value = zakat.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
}

function hitungZakatTernak() {
    const jenis = document.getElementById('jenis_ternak')?.value;
    const jumlah = parseInt(document.getElementById('jumlah_ternak')?.value) || 0;
    let zakat = 0;

    if (jenis === "kambing") {
        if (jumlah >= 40 && jumlah < 121) zakat = 1;
        else if (jumlah >= 121 && jumlah < 201) zakat = 2;
        else if (jumlah >= 201) zakat = Math.floor(jumlah / 100);
    } else if (jenis === "sapi") {
        if (jumlah >= 30 && jumlah < 40) zakat = 1;
        else if (jumlah >= 40 && jumlah < 60) zakat = 1;
        else if (jumlah >= 60) zakat = Math.floor(jumlah / 30);
    } else if (jenis === "unta") {
        if (jumlah >= 30 && jumlah < 40) zakat = 1;
        else if (jumlah >= 40 && jumlah < 60) zakat = 2;
        else if (jumlah >= 60) zakat = Math.floor(jumlah / 40);
    }

    const outputField = document.getElementById('Jumlah_Zakat_ternak');
    if (outputField) {
        outputField.value = zakat + (zakat === 1 ? " ekor" : " ekor") + " " + jenis;
    }
}
</script>
    <script src="js/preloader.js"></script>
</body>
</html>