<?php
session_start();
require_once __DIR__ . '/business_crud_operations.php';

// Verify business authentication
if (!isset($_SESSION['authenticated']) || $_SESSION['user_role'] !== 'business' || $_SESSION['status'] != 1) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get business ID from session
$business_id = $_SESSION['user_id'];

// Initialize database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Initialize operations classes
$campaignOps = new BusinessCampaignOperations($pdo, $business_id);
$influencerOps = new BusinessInfluencerOperations($pdo, $business_id);
$contractOps = new BusinessContractOperations($pdo, $business_id);
$analyticsOps = new BusinessAnalyticsOperations($pdo, $business_id);

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
                $filters = [
                    'search' => $_GET['search'] ?? null,
                    'expertise' => $_GET['expertise'] ?? null
                ];
                if (isset($_GET['id'])) {
                    $influencer = $influencerOps->getInfluencerDetails($_GET['id']);
                    echo json_encode(['success' => true, 'data' => $influencer]);
                } else {
                    $influencers = $influencerOps->getInfluencers($filters);
                    echo json_encode(['success' => true, 'data' => $influencers]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
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
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
        break;
}
?> 