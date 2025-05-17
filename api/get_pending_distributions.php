<?php
// Strict error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers first to ensure proper content type
header('Content-Type: application/json');

// Include database connection with proper path handling
require_once __DIR__ . '/../includes/koneksi.php'; // Adjusted path

// Start secure session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Verify admin session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access',
        'code' => 401
    ]);
    exit;
}

// Initialize response array
$response = [
    'status' => 'success',
    'data' => [],
    'timestamp' => time()
];

try {
    // Validate database connection
    if (!$conn || !$conn->ping()) {
        throw new Exception('Database connection failed');
    }

    // Query 1: Count pending distributions (parameterized query)
    $query = "SELECT COUNT(*) as pending FROM penyaluran_zakat WHERE status = ?";
    $stmt = $conn->prepare($query);
    $status = 'belum diterima';
    $stmt->bind_param('s', $status);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        throw new Exception('Failed to count pending distributions');
    }
    
    $row = $result->fetch_assoc();
    $response['data']['pending'] = (int)$row['pending'];
    $stmt->close();

    // Query 2: Get recent pending distributions (parameterized query)
    $recentQuery = "SELECT m.Nama_Mustahiq, pz.jumlah, pz.tanggal 
                   FROM penyaluran_zakat pz
                   JOIN mustahiq m ON pz.mustahiq_id = m.id
                   WHERE pz.status = ?
                   ORDER BY pz.tanggal DESC
                   LIMIT 3";
    
    $stmt = $conn->prepare($recentQuery);
    $stmt->bind_param('s', $status);
    $stmt->execute();
    $recentResult = $stmt->get_result();
    
    $response['data']['recent'] = [];
    while ($recentRow = $recentResult->fetch_assoc()) {
        $response['data']['recent'][] = [
            'name' => htmlspecialchars($recentRow['Nama_Mustahiq']),
            'amount' => (float)$recentRow['jumlah'],
            'date' => $recentRow['tanggal'],
            'formatted_date' => date('d M Y H:i', strtotime($recentRow['tanggal']))
        ];
    }
    $stmt->close();

    // Add metadata
    $response['data']['last_checked'] = date('Y-m-d H:i:s');
    $response['data']['server'] = gethostname();

} catch (Exception $e) {
    http_response_code(500);
    $response = [
        'status' => 'error',
        'message' => 'Database operation failed',
        'error' => $e->getMessage(),
        'code' => 500
    ];
} finally {
    // Close connection if it exists
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    
    // Ensure proper JSON encoding
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}
?>