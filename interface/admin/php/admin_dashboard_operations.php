<?php
require_once '../../../db/db_connection.php';

class AdminDashboardOperations {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get dashboard statistics
    public function getDashboardStats() {
        try {
            // Get active users count (users with status = 1)
            $stmt = $this->pdo->query("SELECT COUNT(*) as active_users FROM users WHERE status = 1");
            $activeUsers = $stmt->fetch()['active_users'];

            // Get pending verifications count
            $stmt = $this->pdo->query("SELECT COUNT(*) as pending_verifications FROM users WHERE status = 0 AND role IN ('influencer', 'business')");
            $pendingVerifications = $stmt->fetch()['pending_verifications'];

            // Get active disputes count
            $stmt = $this->pdo->query("SELECT COUNT(*) as active_disputes FROM disputes WHERE status = 'open'");
            $activeDisputes = $stmt->fetch()['active_disputes'];

            // Get total revenue (sum of all successful campaign payments)
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(amount), 0) as total_revenue FROM campaign_payments WHERE status = 'completed'");
            $totalRevenue = $stmt->fetch()['total_revenue'];

            return [
                'active_users' => $activeUsers,
                'pending_verifications' => $pendingVerifications,
                'active_disputes' => $activeDisputes,
                'total_revenue' => $totalRevenue
            ];
        } catch (PDOException $e) {
            error_log("Error fetching dashboard stats: " . $e->getMessage());
            return false;
        }
    }

    // Get verification queue
    public function getVerificationQueue($limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT u.id, u.email, u.role, u.created_at,
                       CASE 
                           WHEN u.role = 'influencer' THEN i.social_links
                           WHEN u.role = 'business' THEN b.name
                       END as profile_info
                FROM users u
                LEFT JOIN influencers i ON u.id = i.user_id
                LEFT JOIN businesses b ON u.id = b.user_id
                WHERE u.status = 0 AND u.role IN ('influencer', 'business')
                ORDER BY u.created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching verification queue: " . $e->getMessage());
            return false;
        }
    }

    // Handle user verification
    public function handleVerification($userId, $action) {
        try {
            $this->pdo->beginTransaction();

            if ($action === 'approve') {
                $stmt = $this->pdo->prepare("UPDATE users SET status = 1 WHERE id = ?");
                $stmt->execute([$userId]);
            } else if ($action === 'reject') {
                $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$userId]);
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error handling verification: " . $e->getMessage());
            return false;
        }
    }

    // Get active disputes
    public function getActiveDisputes($limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT d.*, 
                       c.title as campaign_title,
                       u1.email as reported_by_email,
                       u2.email as reported_user_email
                FROM disputes d
                JOIN campaigns c ON d.campaign_id = c.id
                JOIN users u1 ON d.reported_by = u1.id
                JOIN users u2 ON d.reported_user = u2.id
                WHERE d.status = 'open'
                ORDER BY d.created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching active disputes: " . $e->getMessage());
            return false;
        }
    }

    // Handle dispute resolution
    public function handleDispute($disputeId, $action, $resolution = '') {
        try {
            $this->pdo->beginTransaction();

            if ($action === 'resolve') {
                $stmt = $this->pdo->prepare("
                    UPDATE disputes 
                    SET status = 'resolved', 
                        resolution = ?,
                        resolved_at = CURRENT_TIMESTAMP 
                    WHERE id = ?
                ");
                $stmt->execute([$resolution, $disputeId]);
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error handling dispute: " . $e->getMessage());
            return false;
        }
    }

    // Get system status
    public function getSystemStatus() {
        try {
            // Check database connection
            $dbStatus = $this->pdo->query("SELECT 1")->fetch() ? 'operational' : 'error';
            
            // Get database performance metrics
            $stmt = $this->pdo->query("SHOW STATUS LIKE 'Threads_connected'");
            $threadsConnected = $stmt->fetch()['Value'];
            
            return [
                'database' => [
                    'status' => $dbStatus,
                    'connections' => $threadsConnected
                ],
                'api' => [
                    'status' => 'operational' // This would need to be implemented with actual API checks
                ]
            ];
        } catch (PDOException $e) {
            error_log("Error checking system status: " . $e->getMessage());
            return false;
        }
    }

    // Get recent activity
    public function getRecentActivity($limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                (SELECT 'user_registration' as type, 
                        u.email as description,
                        u.created_at as timestamp
                FROM users u
                WHERE u.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR))
                UNION ALL
                (SELECT 'campaign_created' as type,
                        c.title as description,
                        c.created_at as timestamp
                FROM campaigns c
                WHERE c.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR))
                ORDER BY timestamp DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching recent activity: " . $e->getMessage());
            return false;
        }
    }
}
?> 