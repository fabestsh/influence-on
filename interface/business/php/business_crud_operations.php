<?php
require_once __DIR__ . '/../../config/database.php';

class BusinessCampaignOperations {
    private $pdo;
    private $business_id;

    public function __construct($pdo, $business_id) {
        $this->pdo = $pdo;
        $this->business_id = $business_id;
    }

    // Create a new campaign
    public function createCampaign($data) {
        try {
            $query = "INSERT INTO campaigns (
                business_id, title, description, budget, requirements,
                status, start_date, end_date
            ) VALUES (
                :business_id, :title, :description, :budget, :requirements,
                :status, :start_date, :end_date
            )";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'business_id' => $this->business_id,
                'title' => $data['title'],
                'description' => $data['description'],
                'budget' => $data['budget'],
                'requirements' => $data['requirements'],
                'status' => $data['status'] ?? 'draft',
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date']
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating campaign: " . $e->getMessage());
            return false;
        }
    }

    // Get all campaigns for the business
    public function getCampaigns($filters = []) {
        try {
            $where = ["business_id = :business_id"];
            $params = ['business_id' => $this->business_id];

            if (!empty($filters['status'])) {
                $where[] = "status = :status";
                $params['status'] = $filters['status'];
            }

            if (!empty($filters['search'])) {
                $where[] = "(title LIKE :search OR description LIKE :search)";
                $params['search'] = "%{$filters['search']}%";
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

            $query = "SELECT * FROM campaigns $whereClause ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching campaigns: " . $e->getMessage());
            return false;
        }
    }

    // Update a campaign
    public function updateCampaign($campaign_id, $data) {
        try {
            $allowedFields = ['title', 'description', 'budget', 'requirements', 'status', 'start_date', 'end_date'];
            $updates = [];
            $params = ['campaign_id' => $campaign_id, 'business_id' => $this->business_id];

            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $updates[] = "$key = :$key";
                    $params[$key] = $value;
                }
            }

            if (empty($updates)) {
                return false;
            }

            $query = "UPDATE campaigns SET " . implode(", ", $updates) . 
                    " WHERE id = :campaign_id AND business_id = :business_id";
            
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating campaign: " . $e->getMessage());
            return false;
        }
    }

    // Delete a campaign
    public function deleteCampaign($campaign_id) {
        try {
            $query = "DELETE FROM campaigns WHERE id = :campaign_id AND business_id = :business_id";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                'campaign_id' => $campaign_id,
                'business_id' => $this->business_id
            ]);
        } catch (PDOException $e) {
            error_log("Error deleting campaign: " . $e->getMessage());
            return false;
        }
    }
}

class BusinessInfluencerOperations {
    private $pdo;
    private $business_id;
    private $lastError;

    public function __construct($pdo, $business_id) {
        $this->pdo = $pdo;
        $this->business_id = $business_id;
        $this->lastError = null;
        
        // Verify database connection
        try {
            $this->pdo->query("SELECT 1");
        } catch (PDOException $e) {
            error_log("Database connection verification failed: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }

    public function getLastError() {
        return $this->lastError;
    }

    // Get all influencers who have applied to the business's campaigns
    public function getInfluencers($filters = []) {
        try {
            error_log("Starting getInfluencers query with business_id: " . $this->business_id);
            
            // First, verify the tables exist
            $tables = ['users', 'influencers', 'campaign_payments', 'campaigns'];
            foreach ($tables as $table) {
                try {
                    $this->pdo->query("SELECT 1 FROM $table LIMIT 1");
                } catch (PDOException $e) {
                    error_log("Table '$table' does not exist or is not accessible: " . $e->getMessage());
                    throw new Exception("Required table '$table' is not available");
                }
            }

            // Build the query with proper table aliases and joins
            $where = ["c.business_id = :business_id"];
            $params = ['business_id' => $this->business_id];

            if (!empty($filters['search'])) {
                $where[] = "(u.name LIKE :search OR i.bio LIKE :search)";
                $params['search'] = "%{$filters['search']}%";
            }

            if (!empty($filters['expertise'])) {
                $where[] = "JSON_CONTAINS(i.expertise, :expertise)";
                $params['expertise'] = json_encode($filters['expertise']);
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

            // Modified query to handle potential NULL values and ensure proper joins
            $query = "
                SELECT DISTINCT 
                    u.id,
                    u.name,
                    u.email,
                    i.id as influencer_id,
                    i.bio,
                    i.expertise,
                    i.social_links,
                    COALESCE(COUNT(DISTINCT cp.id), 0) as total_collaborations,
                    COALESCE(SUM(cp.amount), 0) as total_earnings
                FROM users u
                INNER JOIN influencers i ON u.id = i.user_id
                LEFT JOIN campaign_payments cp ON i.id = cp.influencer_id
                LEFT JOIN campaigns c ON cp.campaign_id = c.id
                $whereClause
                GROUP BY u.id, u.name, u.email, i.id, i.bio, i.expertise, i.social_links
                ORDER BY total_collaborations DESC
            ";

            error_log("Executing query: " . $query);
            error_log("With params: " . json_encode($params));

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Process the results to ensure JSON fields are properly handled
            foreach ($result as &$row) {
                if (isset($row['expertise'])) {
                    try {
                        $row['expertise'] = json_decode($row['expertise'], true) ?? [];
                    } catch (Exception $e) {
                        error_log("Error decoding expertise JSON for user {$row['id']}: " . $e->getMessage());
                        $row['expertise'] = [];
                    }
                }
                if (isset($row['social_links'])) {
                    try {
                        $row['social_links'] = json_decode($row['social_links'], true) ?? [];
                    } catch (Exception $e) {
                        error_log("Error decoding social_links JSON for user {$row['id']}: " . $e->getMessage());
                        $row['social_links'] = [];
                    }
                }
            }
            
            error_log("Query returned " . count($result) . " results");
            return $result;
        } catch (PDOException $e) {
            $this->lastError = "Database error: " . $e->getMessage();
            error_log("PDO Error in getInfluencers: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log("General Error in getInfluencers: " . $e->getMessage());
            return false;
        }
    }

    // Get influencer details
    public function getInfluencerDetails($influencer_id) {
        try {
            $query = "
                SELECT 
                    u.*, i.*,
                    COUNT(DISTINCT cp.id) as total_collaborations,
                    COALESCE(SUM(cp.amount), 0) as total_earnings,
                    GROUP_CONCAT(DISTINCT c.title) as campaign_history
                FROM users u
                JOIN influencers i ON u.id = i.user_id
                LEFT JOIN campaign_payments cp ON i.id = cp.influencer_id
                LEFT JOIN campaigns c ON cp.campaign_id = c.id
                WHERE i.id = :influencer_id
                GROUP BY u.id
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['influencer_id' => $influencer_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching influencer details: " . $e->getMessage());
            return false;
        }
    }
}

class BusinessContractOperations {
    private $pdo;
    private $business_id;

    public function __construct($pdo, $business_id) {
        $this->pdo = $pdo;
        $this->business_id = $business_id;
    }

    // Create a new contract
    public function createContract($data) {
        try {
            $this->pdo->beginTransaction();

            // First, verify the campaign belongs to this business
            $campaignQuery = "SELECT id FROM campaigns WHERE id = :campaign_id AND business_id = :business_id";
            $stmt = $this->pdo->prepare($campaignQuery);
            $stmt->execute([
                'campaign_id' => $data['campaign_id'],
                'business_id' => $this->business_id
            ]);

            if (!$stmt->fetch()) {
                throw new Exception("Campaign not found or unauthorized");
            }

            // Insert contract details
            $query = "INSERT INTO contracts (
                campaign_id, influencer_id, terms, payment_terms,
                deliverables, status, start_date, end_date
            ) VALUES (
                :campaign_id, :influencer_id, :terms, :payment_terms,
                :deliverables, :status, :start_date, :end_date
            )";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'campaign_id' => $data['campaign_id'],
                'influencer_id' => $data['influencer_id'],
                'terms' => $data['terms'],
                'payment_terms' => $data['payment_terms'],
                'deliverables' => $data['deliverables'],
                'status' => 'draft',
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date']
            ]);

            $contract_id = $this->pdo->lastInsertId();
            $this->pdo->commit();
            return $contract_id;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error creating contract: " . $e->getMessage());
            return false;
        }
    }

    // Get all contracts for the business
    public function getContracts($filters = []) {
        try {
            $where = ["c.business_id = :business_id"];
            $params = ['business_id' => $this->business_id];

            if (!empty($filters['status'])) {
                $where[] = "ct.status = :status";
                $params['status'] = $filters['status'];
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

            $query = "
                SELECT 
                    ct.*, c.title as campaign_title,
                    u.name as influencer_name,
                    i.social_links as influencer_social
                FROM contracts ct
                JOIN campaigns c ON ct.campaign_id = c.id
                JOIN influencers i ON ct.influencer_id = i.id
                JOIN users u ON i.user_id = u.id
                $whereClause
                ORDER BY ct.created_at DESC
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching contracts: " . $e->getMessage());
            return false;
        }
    }

    // Update a contract
    public function updateContract($contract_id, $data) {
        try {
            $allowedFields = ['terms', 'payment_terms', 'deliverables', 'status', 'start_date', 'end_date'];
            $updates = [];
            $params = ['contract_id' => $contract_id];

            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $updates[] = "$key = :$key";
                    $params[$key] = $value;
                }
            }

            if (empty($updates)) {
                return false;
            }

            $query = "
                UPDATE contracts ct
                JOIN campaigns c ON ct.campaign_id = c.id
                SET " . implode(", ", $updates) . "
                WHERE ct.id = :contract_id AND c.business_id = :business_id
            ";
            
            $params['business_id'] = $this->business_id;
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating contract: " . $e->getMessage());
            return false;
        }
    }
}

class BusinessAnalyticsOperations {
    private $pdo;
    private $business_id;

    public function __construct($pdo, $business_id) {
        $this->pdo = $pdo;
        $this->business_id = $business_id;
    }

    // Get campaign performance metrics
    public function getCampaignMetrics($filters = []) {
        try {
            $where = ["c.business_id = :business_id"];
            $params = ['business_id' => $this->business_id];

            if (!empty($filters['start_date'])) {
                $where[] = "c.start_date >= :start_date";
                $params['start_date'] = $filters['start_date'];
            }

            if (!empty($filters['end_date'])) {
                $where[] = "c.end_date <= :end_date";
                $params['end_date'] = $filters['end_date'];
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

            $query = "
                SELECT 
                    c.id,
                    c.title,
                    c.status,
                    c.budget,
                    COUNT(DISTINCT cp.influencer_id) as total_influencers,
                    COALESCE(SUM(cp.amount), 0) as total_spent,
                    COUNT(DISTINCT ct.id) as total_contracts,
                    COUNT(DISTINCT CASE WHEN ct.status = 'completed' THEN ct.id END) as completed_contracts
                FROM campaigns c
                LEFT JOIN campaign_payments cp ON c.id = cp.campaign_id
                LEFT JOIN contracts ct ON c.id = ct.campaign_id
                $whereClause
                GROUP BY c.id
                ORDER BY c.created_at DESC
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching campaign metrics: " . $e->getMessage());
            return false;
        }
    }

    // Get influencer performance metrics
    public function getInfluencerMetrics($filters = []) {
        try {
            $where = ["c.business_id = :business_id"];
            $params = ['business_id' => $this->business_id];

            if (!empty($filters['start_date'])) {
                $where[] = "cp.payment_date >= :start_date";
                $params['start_date'] = $filters['start_date'];
            }

            if (!empty($filters['end_date'])) {
                $where[] = "cp.payment_date <= :end_date";
                $params['end_date'] = $filters['end_date'];
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

            $query = "
                SELECT 
                    i.id,
                    u.name as influencer_name,
                    i.social_links,
                    COUNT(DISTINCT c.id) as total_campaigns,
                    COALESCE(SUM(cp.amount), 0) as total_paid,
                    COUNT(DISTINCT ct.id) as total_contracts,
                    COUNT(DISTINCT CASE WHEN ct.status = 'completed' THEN ct.id END) as completed_contracts
                FROM influencers i
                JOIN users u ON i.user_id = u.id
                LEFT JOIN campaign_payments cp ON i.id = cp.influencer_id
                LEFT JOIN campaigns c ON cp.campaign_id = c.id
                LEFT JOIN contracts ct ON c.id = ct.campaign_id AND i.id = ct.influencer_id
                $whereClause
                GROUP BY i.id
                ORDER BY total_paid DESC
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching influencer metrics: " . $e->getMessage());
            return false;
        }
    }
}
?> 