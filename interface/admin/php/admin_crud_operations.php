<?php
require_once '../../../db/db_connection.php';

class AdminCrudOperations {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // User Management CRUD
    public function getUsers($filters = [], $page = 1, $perPage = 10) {
        try {
            $where = [];
            $params = [];
            
            if (!empty($filters['role'])) {
                $where[] = "u.role = ?";
                $params[] = $filters['role'];
            }
            if (isset($filters['status'])) {
                $where[] = "u.status = ?";
                $params[] = $filters['status'];
            }
            if (!empty($filters['search'])) {
                $where[] = "(u.email LIKE ? OR u.name LIKE ?)";
                $params[] = "%{$filters['search']}%";
                $params[] = "%{$filters['search']}%";
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            $offset = ($page - 1) * $perPage;

            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM users u $whereClause";
            $stmt = $this->pdo->prepare($countQuery);
            $stmt->execute($params);
            $total = $stmt->fetch()['total'];

            // Get users with pagination
            $query = "
                SELECT u.*, 
                       CASE 
                           WHEN u.role = 'influencer' THEN i.social_links
                           WHEN u.role = 'business' THEN b.name
                           ELSE NULL
                       END as profile_info
                FROM users u
                LEFT JOIN influencers i ON u.id = i.user_id
                LEFT JOIN businesses b ON u.id = b.user_id
                $whereClause
                ORDER BY u.created_at DESC
                LIMIT ? OFFSET ?
            ";
            
            $params[] = $perPage;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $users = $stmt->fetchAll();

            return [
                'data' => $users,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("Error fetching users: " . $e->getMessage());
            return false;
        }
    }

    public function createUser($data) {
        try {
            $this->pdo->beginTransaction();

            // Create user
            $stmt = $this->pdo->prepare("
                INSERT INTO users (name, email, password_hash, role, status)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['role'],
                $data['status'] ?? 0
            ]);
            $userId = $this->pdo->lastInsertId();

            // Create role-specific profile
            if ($data['role'] === 'influencer') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO influencers (user_id, social_links, expertise, age, bio)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $userId,
                    json_encode($data['social_links'] ?? []),
                    json_encode($data['expertise'] ?? []),
                    $data['age'] ?? null,
                    $data['bio'] ?? null
                ]);
            } elseif ($data['role'] === 'business') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO businesses (user_id, name, industry, website, contact_info)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $userId,
                    $data['business_name'],
                    $data['industry'],
                    $data['website'] ?? null,
                    $data['contact_info']
                ]);
            }

            $this->pdo->commit();
            return $userId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    public function updateUser($userId, $data) {
        try {
            $this->pdo->beginTransaction();

            // Update user
            $updates = [];
            $params = [];
            
            if (isset($data['name'])) {
                $updates[] = "name = ?";
                $params[] = $data['name'];
            }
            if (isset($data['email'])) {
                $updates[] = "email = ?";
                $params[] = $data['email'];
            }
            if (isset($data['password'])) {
                $updates[] = "password_hash = ?";
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            if (isset($data['status'])) {
                $updates[] = "status = ?";
                $params[] = $data['status'];
            }

            if (!empty($updates)) {
                $params[] = $userId;
                $stmt = $this->pdo->prepare("
                    UPDATE users 
                    SET " . implode(", ", $updates) . "
                    WHERE id = ?
                ");
                $stmt->execute($params);
            }

            // Update role-specific profile
            $user = $this->getUserById($userId);
            if ($user['role'] === 'influencer') {
                $updates = [];
                $params = [];
                
                if (isset($data['social_links'])) {
                    $updates[] = "social_links = ?";
                    $params[] = json_encode($data['social_links']);
                }
                if (isset($data['expertise'])) {
                    $updates[] = "expertise = ?";
                    $params[] = json_encode($data['expertise']);
                }
                if (isset($data['age'])) {
                    $updates[] = "age = ?";
                    $params[] = $data['age'];
                }
                if (isset($data['bio'])) {
                    $updates[] = "bio = ?";
                    $params[] = $data['bio'];
                }

                if (!empty($updates)) {
                    $params[] = $userId;
                    $stmt = $this->pdo->prepare("
                        UPDATE influencers 
                        SET " . implode(", ", $updates) . "
                        WHERE user_id = ?
                    ");
                    $stmt->execute($params);
                }
            } elseif ($user['role'] === 'business') {
                $updates = [];
                $params = [];
                
                if (isset($data['business_name'])) {
                    $updates[] = "name = ?";
                    $params[] = $data['business_name'];
                }
                if (isset($data['industry'])) {
                    $updates[] = "industry = ?";
                    $params[] = $data['industry'];
                }
                if (isset($data['website'])) {
                    $updates[] = "website = ?";
                    $params[] = $data['website'];
                }
                if (isset($data['contact_info'])) {
                    $updates[] = "contact_info = ?";
                    $params[] = $data['contact_info'];
                }

                if (!empty($updates)) {
                    $params[] = $userId;
                    $stmt = $this->pdo->prepare("
                        UPDATE businesses 
                        SET " . implode(", ", $updates) . "
                        WHERE user_id = ?
                    ");
                    $stmt->execute($params);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($userId) {
        try {
            $this->pdo->beginTransaction();

            // Delete user (cascade will handle related records)
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }

    public function getUserById($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT u.*, 
                       CASE 
                           WHEN u.role = 'influencer' THEN i.social_links
                           WHEN u.role = 'business' THEN b.name
                           ELSE NULL
                       END as profile_info
                FROM users u
                LEFT JOIN influencers i ON u.id = i.user_id
                LEFT JOIN businesses b ON u.id = b.user_id
                WHERE u.id = ?
            ");
            $stmt->execute([$userId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return false;
        }
    }

    // Dispute Management CRUD
    public function getDisputes($filters = [], $page = 1, $perPage = 10) {
        try {
            $where = [];
            $params = [];
            
            if (!empty($filters['status'])) {
                $where[] = "d.status = ?";
                $params[] = $filters['status'];
            }
            if (!empty($filters['search'])) {
                $where[] = "(c.title LIKE ? OR u1.email LIKE ? OR u2.email LIKE ?)";
                $params[] = "%{$filters['search']}%";
                $params[] = "%{$filters['search']}%";
                $params[] = "%{$filters['search']}%";
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            $offset = ($page - 1) * $perPage;

            // Get total count
            $countQuery = "
                SELECT COUNT(*) as total 
                FROM disputes d
                JOIN campaigns c ON d.campaign_id = c.id
                JOIN users u1 ON d.reported_by = u1.id
                JOIN users u2 ON d.reported_user = u2.id
                $whereClause
            ";
            $stmt = $this->pdo->prepare($countQuery);
            $stmt->execute($params);
            $total = $stmt->fetch()['total'];

            // Get disputes with pagination
            $query = "
                SELECT d.*, 
                       c.title as campaign_title,
                       u1.email as reported_by_email,
                       u2.email as reported_user_email
                FROM disputes d
                JOIN campaigns c ON d.campaign_id = c.id
                JOIN users u1 ON d.reported_by = u1.id
                JOIN users u2 ON d.reported_user = u2.id
                $whereClause
                ORDER BY d.created_at DESC
                LIMIT ? OFFSET ?
            ";
            
            $params[] = $perPage;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $disputes = $stmt->fetchAll();

            return [
                'data' => $disputes,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("Error fetching disputes: " . $e->getMessage());
            return false;
        }
    }

    public function createDispute($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO disputes (campaign_id, reported_by, reported_user, reason, status)
                VALUES (?, ?, ?, ?, 'open')
            ");
            $stmt->execute([
                $data['campaign_id'],
                $data['reported_by'],
                $data['reported_user'],
                $data['reason']
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating dispute: " . $e->getMessage());
            return false;
        }
    }

    public function updateDispute($disputeId, $data) {
        try {
            $updates = [];
            $params = [];
            
            if (isset($data['status'])) {
                $updates[] = "status = ?";
                $params[] = $data['status'];
            }
            if (isset($data['resolution'])) {
                $updates[] = "resolution = ?";
                $params[] = $data['resolution'];
            }
            if (isset($data['status']) && $data['status'] === 'resolved') {
                $updates[] = "resolved_at = CURRENT_TIMESTAMP";
            }

            if (!empty($updates)) {
                $params[] = $disputeId;
                $stmt = $this->pdo->prepare("
                    UPDATE disputes 
                    SET " . implode(", ", $updates) . "
                    WHERE id = ?
                ");
                $stmt->execute($params);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error updating dispute: " . $e->getMessage());
            return false;
        }
    }

    public function deleteDispute($disputeId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM disputes WHERE id = ?");
            $stmt->execute([$disputeId]);
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting dispute: " . $e->getMessage());
            return false;
        }
    }

    public function getDisputeById($disputeId) {
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
                WHERE d.id = ?
            ");
            $stmt->execute([$disputeId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching dispute: " . $e->getMessage());
            return false;
        }
    }

    // Report Generation
    public function generateUserReport($filters = []) {
        try {
            $where = [];
            $params = [];
            
            if (!empty($filters['role'])) {
                $where[] = "u.role = ?";
                $params[] = $filters['role'];
            }
            if (isset($filters['status'])) {
                $where[] = "u.status = ?";
                $params[] = $filters['status'];
            }
            if (!empty($filters['date_from'])) {
                $where[] = "u.created_at >= ?";
                $params[] = $filters['date_from'];
            }
            if (!empty($filters['date_to'])) {
                $where[] = "u.created_at <= ?";
                $params[] = $filters['date_to'];
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

            $query = "
                SELECT 
                    u.*,
                    CASE 
                        WHEN u.role = 'influencer' THEN i.social_links
                        WHEN u.role = 'business' THEN b.name
                        ELSE NULL
                    END as profile_info,
                    COUNT(DISTINCT CASE WHEN u.role = 'business' THEN c.id END) as total_campaigns,
                    COUNT(DISTINCT CASE WHEN u.role = 'influencer' THEN cp.id END) as total_payments,
                    COALESCE(SUM(CASE WHEN u.role = 'influencer' THEN cp.amount ELSE 0 END), 0) as total_earnings
                FROM users u
                LEFT JOIN influencers i ON u.id = i.user_id
                LEFT JOIN businesses b ON u.id = b.user_id
                LEFT JOIN campaigns c ON b.id = c.business_id
                LEFT JOIN campaign_payments cp ON i.id = cp.influencer_id
                $whereClause
                GROUP BY u.id
                ORDER BY u.created_at DESC
            ";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error generating user report: " . $e->getMessage());
            return false;
        }
    }

    public function generateDisputeReport($filters = []) {
        try {
            $where = [];
            $params = [];
            
            if (!empty($filters['status'])) {
                $where[] = "d.status = ?";
                $params[] = $filters['status'];
            }
            if (!empty($filters['date_from'])) {
                $where[] = "d.created_at >= ?";
                $params[] = $filters['date_from'];
            }
            if (!empty($filters['date_to'])) {
                $where[] = "d.created_at <= ?";
                $params[] = $filters['date_to'];
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

            $query = "
                SELECT 
                    d.*,
                    c.title as campaign_title,
                    u1.email as reported_by_email,
                    u2.email as reported_user_email,
                    DATEDIFF(COALESCE(d.resolved_at, NOW()), d.created_at) as resolution_time
                FROM disputes d
                JOIN campaigns c ON d.campaign_id = c.id
                JOIN users u1 ON d.reported_by = u1.id
                JOIN users u2 ON d.reported_user = u2.id
                $whereClause
                ORDER BY d.created_at DESC
            ";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error generating dispute report: " . $e->getMessage());
            return false;
        }
    }

    public function generateRevenueReport($filters = []) {
        try {
            $where = [];
            $params = [];
            
            if (!empty($filters['status'])) {
                $where[] = "cp.status = ?";
                $params[] = $filters['status'];
            }
            if (!empty($filters['date_from'])) {
                $where[] = "cp.created_at >= ?";
                $params[] = $filters['date_from'];
            }
            if (!empty($filters['date_to'])) {
                $where[] = "cp.created_at <= ?";
                $params[] = $filters['date_to'];
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

            $query = "
                SELECT 
                    DATE(cp.created_at) as date,
                    COUNT(DISTINCT cp.id) as total_transactions,
                    SUM(cp.amount) as total_amount,
                    COUNT(DISTINCT cp.campaign_id) as total_campaigns,
                    COUNT(DISTINCT cp.influencer_id) as total_influencers
                FROM campaign_payments cp
                $whereClause
                GROUP BY DATE(cp.created_at)
                ORDER BY date DESC
            ";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error generating revenue report: " . $e->getMessage());
            return false;
        }
    }
}
?> 