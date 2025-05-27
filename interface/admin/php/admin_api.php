<?php
session_start();
header('Content-Type: application/json');

// Check if user is authenticated and is an admin
if (!isset($_SESSION['authenticated']) || $_SESSION['user_role'] !== 'admin' || $_SESSION['status'] != 1) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../../../db/db_connection.php';
require_once 'admin_dashboard_operations.php';

$pdo = include('../../../db/db_connection.php');
$adminOps = new AdminDashboardOperations($pdo);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_stats':
        $stats = $adminOps->getDashboardStats();
        if ($stats === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error fetching dashboard stats']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $stats]);
        break;

    case 'get_verification_queue':
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $queue = $adminOps->getVerificationQueue($limit);
        if ($queue === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error fetching verification queue']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $queue]);
        break;

    case 'handle_verification':
        if (!isset($_POST['user_id']) || !isset($_POST['action'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            exit;
        }

        $userId = (int)$_POST['user_id'];
        $action = $_POST['action'];

        if (!in_array($action, ['approve', 'reject'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            exit;
        }

        $result = $adminOps->handleVerification($userId, $action);
        if ($result === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error handling verification']);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Verification handled successfully']);
        break;

    case 'get_disputes':
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $disputes = $adminOps->getActiveDisputes($limit);
        if ($disputes === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error fetching disputes']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $disputes]);
        break;

    case 'handle_dispute':
        if (!isset($_POST['dispute_id']) || !isset($_POST['action'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            exit;
        }

        $disputeId = (int)$_POST['dispute_id'];
        $action = $_POST['action'];
        $resolution = $_POST['resolution'] ?? '';

        if ($action === 'resolve' && empty($resolution)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Resolution text is required']);
            exit;
        }

        $result = $adminOps->handleDispute($disputeId, $action, $resolution);
        if ($result === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error handling dispute']);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Dispute handled successfully']);
        break;

    case 'get_system_status':
        $status = $adminOps->getSystemStatus();
        if ($status === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error fetching system status']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $status]);
        break;

    case 'get_recent_activity':
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $activity = $adminOps->getRecentActivity($limit);
        if ($activity === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error fetching recent activity']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $activity]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?> 