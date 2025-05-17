<?php
class ZakatModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getZakatSummary() {
        $data = [
            'total' => 0,
            'transactions' => 0,
            'types' => [],
            'distribution' => [],
            'locations' => []
        ];

        try {
            // Get all zakat types and their amounts
            $sql = "SELECT jz.id, jz.nama, 
                    COALESCE(SUM(
                        CASE 
                            WHEN jz.id = 1 THEN zf.jumlah_zakat 
                            WHEN jz.id = 2 THEN zm.jumlah_zakat 
                            WHEN jz.id = 3 THEN zp.jumlah_zakat 
                        END
                    ), 0) as amount
                    FROM jenis_zakat jz
                    LEFT JOIN zakat z ON 1=1
                    LEFT JOIN zakat_fitrah zf ON jz.id = 1 AND zf.zakat_id = z.No_zakat
                    LEFT JOIN zakat_mal zm ON jz.id = 2 AND zm.zakat_id = z.No_zakat
                    LEFT JOIN zakat_peternakan zp ON jz.id = 3 AND zp.zakat_id = z.No_zakat
                    GROUP BY jz.id, jz.nama";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $data['types'][] = [
                    'id' => $row['id'],
                    'name' => $row['nama'],
                    'amount' => (float)$row['amount'],
                    'percentage' => 0
                ];
                $data['total'] += (float)$row['amount'];
            }
            $stmt->close();

            // Calculate percentages
            if ($data['total'] > 0) {
                foreach ($data['types'] as &$type) {
                    $type['percentage'] = round(($type['amount'] / $data['total']) * 100, 1);
                }
            }

            // Get total transactions
            $stmt_count = $this->conn->prepare("SELECT COUNT(*) AS total FROM zakat");
            if ($stmt_count) {
                $stmt_count->execute();
                $result_count = $stmt_count->get_result();
                $row = $result_count->fetch_assoc();
                $data['transactions'] = (int)$row['total'];
                $stmt_count->close();
            }

            // Get distribution data
            $stmt_dist = $this->conn->prepare("
                SELECT jenis_bantuan, COUNT(*) as jumlah 
                FROM penyaluran_zakat 
                GROUP BY jenis_bantuan
            ");
            if ($stmt_dist) {
                $stmt_dist->execute();
                $result_dist = $stmt_dist->get_result();
                while ($row = $result_dist->fetch_assoc()) {
                    $data['distribution'][] = $row;
                }
                $stmt_dist->close();
            }

            // Get location data
            $stmt_loc = $this->conn->prepare("
                SELECT tanggal, acara, penceramah, lokasi 
                FROM lokasi_penyaluran 
                ORDER BY tanggal DESC 
                LIMIT 6
            ");
            if ($stmt_loc) {
                $stmt_loc->execute();
                $result_loc = $stmt_loc->get_result();
                $data['locations'] = $result_loc->fetch_all(MYSQLI_ASSOC);
                $stmt_loc->close();
            }

            return $data;

        } catch (Exception $e) {
            error_log("ZakatModel Error: " . $e->getMessage());
            return $data;
        }
    }

    public function getZakatTypes() {
        $types = [];
        $stmt = $this->conn->prepare("SELECT id, nama FROM jenis_zakat");
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $types[] = $row;
            }
            $stmt->close();
        }
        return $types;
    }
}
?>