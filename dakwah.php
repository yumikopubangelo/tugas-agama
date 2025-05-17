<?php
session_start();   
include 'header.php';
include_once 'koneksi.php';  // Will only be included once even if called multiple times


// Tangani logout jika form logout ada di file ini
if (isset($_POST['logout'])) {
    session_destroy();
    header("location:dashboard.php");
    exit();
}

// Ambil keyword pencarian jika ada
$keyword = $_GET['q'] ?? '';

// Konfigurasi pagination
$per_page = 6;
$page     = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset   = ($page - 1) * $per_page;

// Total data (untuk pagination)
if (!empty($keyword)) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM artikel_dakwah WHERE judul LIKE ? OR deskripsi LIKE ?");
    $search = "%" . $keyword . "%";
    $stmt->bind_param("ss", $search, $search);
} else {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM artikel_dakwah");
}
$stmt->execute();
$stmt->bind_result($total_data);
$stmt->fetch();
$stmt->close();

$total_pages = ceil($total_data / $per_page);

// Ambil data artikel sesuai halaman & pencarian
if (!empty($keyword)) {
    $sql = "SELECT * FROM artikel_dakwah WHERE judul LIKE ? OR deskripsi LIKE ? ORDER BY tanggal_dibuat DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $search, $search, $per_page, $offset);
} else {
    $sql = "SELECT * FROM artikel_dakwah ORDER BY tanggal_dibuat DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $per_page, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>


<div class="container">

  <h3 align="center">Cari Dakwah</h3>
  <div class="d-flex justify-content-center my-3">
    <form class="d-flex" role="search" method="GET" style="max-width: 600px; width: 100%;">
      <input class="form-control me-2" type="search" name="q" placeholder="Cari dakwah..." aria-label="Search" value="<?php echo htmlspecialchars($keyword); ?>">
      <button class="btn btn-outline-success" type="submit">Cari</button>
    </form>
  </div>

  <h3 class="text-left mb-4">Artikel Dakwah</h3>

  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100">
            <img src="<?php echo htmlspecialchars($row['gambar']); ?>" class="card-img-top" alt="Gambar Artikel">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($row['judul']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
              <a href="detail_artikel.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Baca Artikel</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">Tidak ada artikel ditemukan.</p>
    <?php endif; ?>
  </div>

  <!-- PAGINATION -->
  <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
            <a class="page-link" href="?page=<?php echo $i; ?><?php echo $keyword ? '&q=' . urlencode($keyword) : ''; ?>">
              <?php echo $i; ?>
            </a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>

</div>

<?php include 'footer.php'; ?>
</body>

     