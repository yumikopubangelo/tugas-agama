<?php
session_start();
require_once 'koneksi.php';

if (!isAdmin()) {
    header("Location: halaman_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_mustahiq'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    
    $query = "INSERT INTO mustahiq (nama, alamat, no_hp, kategori) 
              VALUES ('$nama', '$alamat', '$no_hp', '$kategori')";
    mysqli_query($conn, $query);
    
    header("Location: mustahiq.php?status=success");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include 'header.php'; ?>
    <title>Data Mustahiq</title>
    <style>
        .kategori-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .fakir { background-color: #ffcccc; color: #cc0000; }
        .miskin { background-color: #ffebcc; color: #cc7a00; }
        .amil { background-color: #ccffcc; color: #006600; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Data Mustahiq</h2>
        
        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nama Mustahiq</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="fakir">Fakir</option>
                        <option value="miskin">Miskin</option>
                        <option value="amil">Amil</option>
                        <option value="muallaf">Muallaf</option>
                        <option value="riqab">Riqab</option>
                        <option value="gharim">Gharim</option>
                        <option value="fisabilillah">Fisabilillah</option>
                        <option value="ibnu sabil">Ibnu Sabil</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="tambah_mustahiq" class="btn btn-primary">Tambah</button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" required></textarea>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Kategori</th>
                        <th>Total Diterima</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM mustahiq ORDER BY nama ASC";
                    $result = mysqli_query($conn, $query);
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        $badge_class = '';
                        switch ($row['kategori']) {
                            case 'fakir': $badge_class = 'fakir'; break;
                            case 'miskin': $badge_class = 'miskin'; break;
                            case 'amil': $badge_class = 'amil'; break;
                            default: $badge_class = 'secondary';
                        }
                        
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['alamat']}</td>
                                <td>{$row['no_hp']}</td>
                                <td><span class='kategori-badge $badge_class'>{$row['kategori']}</span></td>
                                <td>Rp " . number_format($row['total_diterima'], 0, ',', '.') . "</td>
                                <td>
                                    <a href='edit_mustahiq.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                                    <a href='hapus_mustahiq.php?id={$row['id']}' class='btn btn-sm btn-danger'>Hapus</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>