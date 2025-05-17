<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
<div class="admin-panel">
  <div class="panel-tab" onclick="togglePanel()">
    <i class="fas fa-hand-holding-usd"></i>
    <span class="notification-badge" id="zakat-notifications">0</span>
  </div>
  
  <div class="panel-content">
    <div class="panel-header">
      <h4><i class="fas fa-user-shield"></i> Admin Panel</h4>
      <button class="close-panel" onclick="togglePanel()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="search-box">
      <input type="text" placeholder="Cari fitur...">
      <i class="fas fa-search"></i>
    </div>
    
    <div class="panel-scrollable">
      <!-- Zakat Management Section - Added as first item -->
      <div class="panel-group open">
        <div class="group-header" onclick="toggleGroup(this)">
          <i class="fas fa-hand-holding-usd"></i>
          <h5>Manajemen Zakat</h5>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="group-links">
          <a href="mustahiq.php" class="panel-link">
            <div class="link-icon" style="background: #4CAF50;">
              <i class="fas fa-users"></i>
            </div>
            <div class="link-text">
              <span>Data Mustahiq</span>
              <small>Kelola penerima zakat</small>
            </div>
          </a>
          <a href="distribusi_zakat.php" class="panel-link">
            <div class="link-icon" style="background: #2196F3;">
              <i class="fas fa-share-square"></i>
            </div>
            <div class="link-text">
              <span>Distribusi Zakat</span>
              <small>Proses penyaluran</small>
            </div>
          </a>
          <a href="laporan_zakat.php" class="panel-link">
            <div class="link-icon" style="background: #9C27B0;">
              <i class="fas fa-chart-pie"></i>
            </div>
            <div class="link-text">
              <span>Laporan Zakat</span>
              <small>Statistik penyaluran</small>
            </div>
          </a>
        </div>
      </div>
      
      <!-- Original Content Management Section -->
      <div class="panel-group">
        <div class="group-header" onclick="toggleGroup(this)">
          <i class="fas fa-newspaper"></i>
          <h5>Manajemen Konten</h5>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="group-links">
          <a href="tambah_artikel.php" class="panel-link">
            <div class="link-icon" style="background: #4CAF50;">
              <i class="fas fa-pen"></i>
            </div>
            <div class="link-text">
              <span>Buat Artikel</span>
              <small>Publikasi konten baru</small>
            </div>
          </a>
        </div>
      </div>
      
      <!-- Original Administrator Section -->
      <div class="panel-group">
        <div class="group-header" onclick="toggleGroup(this)">
          <i class="fas fa-users-cog"></i>
          <h5>Administrator</h5>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="group-links">
          <a href="tambah_admin.php" class="panel-link">
            <div class="link-icon" style="background: #2196F3;">
              <i class="fas fa-user-plus"></i>
            </div>
            <div class="link-text">
              <span>Tambah Admin</span>
              <small>Buat akun baru</small>
            </div>
          </a>
          <a href="lihat_admin.php" class="panel-link">
            <div class="link-icon" style="background: #673AB7;">
              <i class="fas fa-users"></i>
            </div>
            <div class="link-text">
              <span>Lihat Admin</span>
              <small>Kelola pengguna</small>
            </div>
          </a>
        </div>
      </div>
      
      <!-- Original Schedule Section -->
      <div class="panel-group">
        <div class="group-header" onclick="toggleGroup(this)">
          <i class="fas fa-calendar-alt"></i>
          <h5>Jadwal Kegiatan</h5>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="group-links">
          <a href="tambah_pengajian.php" class="panel-link">
            <div class="link-icon" style="background: #FF9800;">
              <i class="fas fa-mosque"></i>
            </div>
            <div class="link-text">
              <span>Pengajian</span>
              <small>Atur jadwal kajian</small>
            </div>
          </a>
          <a href="tambah_jadwal_sholat.php" class="panel-link">
            <div class="link-icon" style="background: #F44336;">
              <i class="fas fa-clock"></i>
            </div>
             <div class="link-text">
              <span>Jadwal Sholat</span>
              <small>Atur waktu ibadah</small>
            </div>
          </a>
          <a href="tambah_petugas.php" class="panel-link">
            <div class="link-icon" style="background: #009688;">
              <i class="fas fa-user-cog"></i>
            </div>
            <div class="link-text">
              <span>Petugas</span>
              <small>Kelura pembagian tugas</small>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* ===== Core Panel Styles ===== */
.admin-panel {
  position: fixed;
  top: 30%;
  right: 0;
  transform: translateY(-50%);
  z-index: 1000;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ===== Tab Button ===== */
.panel-tab {
  position: absolute;
  left: -40px;
  background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
  color: white;
  width: 40px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px 0 0 8px;
  cursor: pointer;
  box-shadow: -5px 5px 15px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  z-index: 2;
}

.panel-tab:hover {
  width: 45px;
  left: -45px;
}

.panel-tab i {
  font-size: 1.2rem;
}

.notification-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #FF5722;
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: pulse 2s infinite;
}

/* ===== Panel Content ===== */
.panel-content {
  position: absolute;
  right: -300px;
  top: 0;
  width: 300px;
  height: 80vh;
  max-height: 600px;
  background: white;
  border-radius: 15px 0 0 15px;
  box-shadow: -10px 5px 25px rgba(0,0,0,0.1);
  transition: right 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.admin-panel.open .panel-content {
  right: 0;
}

/* ===== Header ===== */
.panel-header {
  padding: 15px 20px;
  background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.panel-header h4 {
  margin: 0;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
}

.close-panel {
  background: transparent;
  border: none;
  color: white;
  font-size: 1.2rem;
  cursor: pointer;
  opacity: 0.8;
  transition: opacity 0.2s;
}

.close-panel:hover {
  opacity: 1;
}

/* ===== Search Box ===== */
.search-box {
  padding: 15px 20px;
  position: relative;
  border-bottom: 1px solid #eee;
}

.search-box input {
  width: 100%;
  padding: 8px 15px 8px 35px;
  border: 1px solid #ddd;
  border-radius: 20px;
  outline: none;
  transition: all 0.3s ease;
}

.search-box input:focus {
  border-color: #4CAF50;
}

.search-box i {
  position: absolute;
  left: 30px;
  top: 50%;
  transform: translateY(-50%);
  color: #777;
}

/* ===== Panel Groups ===== */
.panel-group {
  border-bottom: 1px solid #f0f0f0;
}

/* Highlight Zakat Management Section */
.panel-group:nth-of-type(1) .group-header {
  background-color: rgba(76, 175, 80, 0.1);
  border-left: 3px solid #4CAF50;
}

.group-header {
  padding: 12px 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  transition: background 0.2s;
}

.group-header:hover {
  background: #f9f9f9;
}

.group-header h5 {
  margin: 0;
  flex-grow: 1;
  font-size: 0.95rem;
  color: #444;
}

.group-header i:first-child {
  color: #4CAF50;
}

.group-header i:last-child {
  transition: transform 0.3s;
}

.panel-group.open .group-header i:last-child {
  transform: rotate(180deg);
}

.group-links {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease-out;
}

.panel-group.open .group-links {
  max-height: 500px;
}

/* ===== Panel Links ===== */
.panel-link {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  text-decoration: none;
  color: #333;
  transition: background 0.2s;
  animation: fadeIn 0.3s ease-out forwards;
  opacity: 0;
}

.panel-link:hover {
  background: #f5fff5;
}

.link-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  margin-right: 12px;
  flex-shrink: 0;
}

.link-text {
  flex-grow: 1;
}

.link-text span {
  display: block;
  font-size: 0.9rem;
  font-weight: 500;
}

.link-text small {
  display: block;
  font-size: 0.75rem;
  color: #777;
  margin-top: 2px;
}

/* ===== Scrollable Area ===== */
.panel-scrollable {
  flex-grow: 1;
  overflow-y: auto;
  padding: 0 15px 15px;
  -webkit-overflow-scrolling: touch;
  max-height: calc(80vh - 120px);
}

/* ===== Animation ===== */
@keyframes fadeIn {
  from { opacity: 0; transform: translateX(10px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}

.panel-link:nth-child(1) { animation-delay: 0.1s; }
.panel-link:nth-child(2) { animation-delay: 0.2s; }
.panel-link:nth-child(3) { animation-delay: 0.3s; }

/* ===== Responsive Adjustments ===== */
@media (max-height: 600px) {
  .panel-content {
    height: 90vh;
    max-height: 90vh;
  }
  
  .panel-scrollable {
    max-height: calc(90vh - 120px);
  }
}
</style>

<script>
// Initialize panel
document.addEventListener('DOMContentLoaded', function() {
  // Load pending distributions count
  updateZakatNotifications();
  
  // Set interval to check every 5 minutes
  setInterval(updateZakatNotifications, 300000);
  
  // Initialize all groups
  document.querySelectorAll('.panel-group').forEach(group => {
    const links = group.querySelector('.group-links');
    if (group.classList.contains('open')) {
      links.style.maxHeight = `${links.scrollHeight}px`;
    }
  });
});

// Fetch pending zakat distributions
function updateZakatNotifications() {
  fetch('api/get_pending_distributions.php')
    .then(response => response.json())
    .then(data => {
      const badge = document.getElementById('zakat-notifications');
      if (data.pending > 0) {
        badge.textContent = data.pending;
        badge.style.display = 'flex';
      } else {
        badge.style.display = 'none';
      }
    })
    .catch(error => console.error('Error:', error));
}

// Toggle main panel
function togglePanel() {
  const panel = document.querySelector('.admin-panel');
  panel.classList.toggle('open');
  
  if (panel.classList.contains('open')) {
    panel.querySelector('.panel-scrollable').scrollTop = 0;
  }
}

// Toggle individual groups
function toggleGroup(header) {
  const group = header.parentElement;
  const links = group.querySelector('.group-links');
  
  group.classList.toggle('open');
  
  if (group.classList.contains('open')) {
    links.style.maxHeight = `${links.scrollHeight}px`;
  } else {
    links.style.maxHeight = '0';
  }
}

// Close panel when clicking outside
document.addEventListener('click', function(e) {
  const panel = document.querySelector('.admin-panel');
  const tab = document.querySelector('.panel-tab');
  
  if (!panel.contains(e.target) && e.target !== tab && !tab.contains(e.target)) {
    panel.classList.remove('open');
  }
});

// Search functionality
let searchTimer;
document.querySelector('.search-box input').addEventListener('input', function(e) {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(function() {
    const searchTerm = e.target.value.toLowerCase().trim();
    
    document.querySelectorAll('.panel-link').forEach(link => {
      const text = link.textContent.toLowerCase();
      link.style.display = text.includes(searchTerm) ? 'flex' : 'none';
    });
    
    // Auto-expand groups with matches
    document.querySelectorAll('.panel-group').forEach(group => {
      const hasVisibleLinks = group.querySelector('.panel-link[style="display: flex;"]');
      if (hasVisibleLinks) {
        group.classList.add('open');
        group.querySelector('.group-links').style.maxHeight = `${group.querySelector('.group-links').scrollHeight}px`;
      }
    });
  }, 300);
});
</script>
<?php endif; ?>