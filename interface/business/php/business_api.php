<?php
// At the very top of the file, before any output
ob_start();

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Function to send JSON response
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    ob_clean(); // Clear any previous output
    echo json_encode($data);
    exit;
}

// Function to handle errors
function handleError($message, $statusCode = 500) {
    error_log($message);
    sendJsonResponse(['error' => $message], $statusCode);
}

try {
    require_once __DIR__ . '/business_crud_operations.php';
    
    session_start();
    
    // Verify business authentication
    if (!isset($_SESSION['authenticated']) || $_SESSION['user_role'] !== 'business' || $_SESSION['status'] != 1) {
        handleError('Unauthorized', 401);
    }

    // Get business ID from session
    $business_id = $_SESSION['user_id'];

    // Initialize database connection
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]
        );
    } catch (PDOException $e) {
        handleError('Database connection failed: ' . $e->getMessage());
    }

    // Initialize operations classes
    try {
        $campaignOps = new BusinessCampaignOperations($pdo, $business_id);
        $influencerOps = new BusinessInfluencerOperations($pdo, $business_id);
        $contractOps = new BusinessContractOperations($pdo, $business_id);
        $analyticsOps = new BusinessAnalyticsOperations($pdo, $business_id);
    } catch (Exception $e) {
        handleError('Failed to initialize operations: ' . $e->getMessage());
    }

    // Get request method and endpoint
    $method = $_SERVER['REQUEST_METHOD'];
    $endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

    // Handle different endpoints
    switch ($endpoint) {
        case 'campaigns':
            switch ($method) {
                case 'GET':
                    $filters = [
                        'status' => $_GET['status'] ?? null,
                        'search' => $_GET['search'] ?? null
                    ];
                    $campaigns = $campaignOps->getCampaigns($filters);
                    echo json_encode(['success' => true, 'data' => $campaigns]);
                    break;

                case 'POST':
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (!$data) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Invalid request data']);
                        exit;
                    }
                    $campaign_id = $campaignOps->createCampaign($data);
                    if ($campaign_id) {
                        echo json_encode(['success' => true, 'campaign_id' => $campaign_id]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => 'Failed to create campaign']);
                    }
                    break;

                case 'PUT':
                    $campaign_id = $_GET['id'] ?? null;
                    if (!$campaign_id) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Campaign ID required']);
                        exit;
                    }
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (!$data) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Invalid request data']);
                        exit;
                    }
                    if ($campaignOps->updateCampaign($campaign_id, $data)) {
                        echo json_encode(['success' => true]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => 'Failed to update campaign']);
                    }
                    break;

                case 'DELETE':
                    $campaign_id = $_GET['id'] ?? null;
                    if (!$campaign_id) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Campaign ID required']);
                        exit;
                    }
                    if ($campaignOps->deleteCampaign($campaign_id)) {
                        echo json_encode(['success' => true]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => 'Failed to delete campaign']);
                    }
                    break;

                default:
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                    break;
            }
            break;

        case 'influencers':
            switch ($method) {
                case 'GET':
                    try {
                        $filters = [
                            'search' => $_GET['search'] ?? null,
                            'expertise' => $_GET['expertise'] ?? null
                        ];
                        
                        error_log("Fetching influencers with filters: " . json_encode($filters));
                        
                        if (isset($_GET['id'])) {
                            $influencer = $influencerOps->getInfluencerDetails($_GET['id']);
                            if ($influencer === false) {
                                throw new Exception($influencerOps->getLastError() ?? 'Failed to fetch influencer details');
                            }
                            sendJsonResponse(['success' => true, 'data' => $influencer]);
                        } else {
                            $influencers = $influencerOps->getInfluencers($filters);
                            if ($influencers === false) {
                                throw new Exception($influencerOps->getLastError() ?? 'Failed to fetch influencers');
                            }
                            sendJsonResponse(['success' => true, 'data' => $influencers]);
                        }
                    } catch (Exception $e) {
                        handleError('Error in influencers endpoint: ' . $e->getMessage());
                    }
                    break;

                default:
                    handleError('Method not allowed', 405);
                    break;
            }
            break;

        case 'contracts':
            switch ($method) {
                case 'GET':
                    $filters = [
                        'status' => $_GET['status'] ?? null
                    ];
                    $contracts = $contractOps->getContracts($filters);
                    echo json_encode(['success' => true, 'data' => $contracts]);
                    break;

                case 'POST':
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (!$data) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Invalid request data']);
                        exit;
                    }
                    $contract_id = $contractOps->createContract($data);
                    if ($contract_id) {
                        echo json_encode(['success' => true, 'contract_id' => $contract_id]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => 'Failed to create contract']);
                    }
                    break;

                case 'PUT':
                    $contract_id = $_GET['id'] ?? null;
                    if (!$contract_id) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Contract ID required']);
                        exit;
                    }
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (!$data) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Invalid request data']);
                        exit;
                    }
                    if ($contractOps->updateContract($contract_id, $data)) {
                        echo json_encode(['success' => true]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => 'Failed to update contract']);
                    }
                    break;

                default:
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                    break;
            }
            break;

        case 'analytics':
            switch ($method) {
                case 'GET':
                    $filters = [
                        'start_date' => $_GET['start_date'] ?? null,
                        'end_date' => $_GET['end_date'] ?? null
                    ];
                    $type = $_GET['type'] ?? 'campaigns';
                    
                    if ($type === 'campaigns') {
                        $metrics = $analyticsOps->getCampaignMetrics($filters);
                    } else if ($type === 'influencers') {
                        $metrics = $analyticsOps->getInfluencerMetrics($filters);
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => 'Invalid analytics type']);
                        exit;
                    }
                    
                    echo json_encode(['success' => true, 'data' => $metrics]);
                    break;

                default:
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                    break;
            }
            break;

        default:
            handleError('Endpoint not found', 404);
            break;
    }
} catch (Throwable $e) {
    // Catch any unhandled errors
    handleError('Internal server error: ' . $e->getMessage());
} finally {
    // Ensure we always send a response
    if (ob_get_length() > 0) {
        ob_end_flush();
    }
}
?> 