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
require_once 'admin_crud_operations.php';

$pdo = include('../../../db/db_connection.php');
$adminCrud = new AdminCrudOperations($pdo);

$action = $_GET['action'] ?? '';
$entity = $_GET['entity'] ?? '';

switch ($entity) {
    case 'users':
        switch ($action) {
            case 'list':
                $filters = [
                    'role' => $_GET['role'] ?? null,
                    'status' => isset($_GET['status']) ? (int)$_GET['status'] : null,
                    'search' => $_GET['search'] ?? null
                ];
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
                
                $result = $adminCrud->getUsers($filters, $page, $perPage);
                if ($result === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error fetching users']);
                    exit;
                }
                echo json_encode(['success' => true, 'data' => $result]);
                break;

            case 'create':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                    exit;
                }

                $requiredFields = ['name', 'email', 'password', 'role'];
                foreach ($requiredFields as $field) {
                    if (!isset($_POST[$field]) || empty($_POST[$field])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                        exit;
                    }
                }

                $userId = $adminCrud->createUser($_POST);
                if ($userId === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error creating user']);
                    exit;
                }
                echo json_encode(['success' => true, 'data' => ['id' => $userId]]);
                break;

            case 'update':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                    exit;
                }

                if (!isset($_POST['id'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Missing user ID']);
                    exit;
                }

                $result = $adminCrud->updateUser($_POST['id'], $_POST);
                if ($result === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error updating user']);
                    exit;
                }
                echo json_encode(['success' => true]);
                break;

            case 'delete':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                    exit;
                }

                if (!isset($_POST['id'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Missing user ID']);
                    exit;
                }

                $result = $adminCrud->deleteUser($_POST['id']);
                if ($result === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error deleting user']);
                    exit;
                }
                echo json_encode(['success' => true]);
                break;

            case 'get':
                if (!isset($_GET['id'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Missing user ID']);
                    exit;
                }

                $user = $adminCrud->getUserById($_GET['id']);
                if ($user === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error fetching user']);
                    exit;
                }
                if (!$user) {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'User not found']);
                    exit;
                }
                echo json_encode(['success' => true, 'data' => $user]);
                break;

            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
        break;

    case 'disputes':
        switch ($action) {
            case 'list':
                $filters = [
                    'status' => $_GET['status'] ?? null,
                    'search' => $_GET['search'] ?? null
                ];
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
                
                $result = $adminCrud->getDisputes($filters, $page, $perPage);
                if ($result === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error fetching disputes']);
                    exit;
                }
                echo json_encode(['success' => true, 'data' => $result]);
                break;

            case 'create':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                    exit;
                }

                $requiredFields = ['campaign_id', 'reported_by', 'reported_user', 'reason'];
                foreach ($requiredFields as $field) {
                    if (!isset($_POST[$field]) || empty($_POST[$field])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                        exit;
                    }
                }

                $disputeId = $adminCrud->createDispute($_POST);
                if ($disputeId === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error creating dispute']);
                    exit;
                }
                echo json_encode(['success' => true, 'data' => ['id' => $disputeId]]);
                break;

            case 'update':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                    exit;
                }

                if (!isset($_POST['id'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Missing dispute ID']);
                    exit;
                }

                $result = $adminCrud->updateDispute($_POST['id'], $_POST);
                if ($result === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error updating dispute']);
                    exit;
                }
                echo json_encode(['success' => true]);
                break;

            case 'delete':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                    exit;
                }

                if (!isset($_POST['id'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Missing dispute ID']);
                    exit;
                }

                $result = $adminCrud->deleteDispute($_POST['id']);
                if ($result === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error deleting dispute']);
                    exit;
                }
                echo json_encode(['success' => true]);
                break;

            case 'get':
                if (!isset($_GET['id'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Missing dispute ID']);
                    exit;
                }

                $dispute = $adminCrud->getDisputeById($_GET['id']);
                if ($dispute === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error fetching dispute']);
                    exit;
                }
                if (!$dispute) {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Dispute not found']);
                    exit;
                }
                echo json_encode(['success' => true, 'data' => $dispute]);
                break;

            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
        break;

    case 'reports':
        switch ($action) {
            case 'users':
                $filters = [
                    'role' => $_GET['role'] ?? null,
                    'status' => isset($_GET['status']) ? (int)$_GET['status'] : null,
                    'date_from' => $_GET['date_from'] ?? null,
                    'date_to' => $_GET['date_to'] ?? null
                ];
                
                $result = $adminCrud->generateUserReport($filters);
                if ($result === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error generating user report']);
                    exit;
                }
                echo json_encode(['success' => true, 'data' => $result]);
                break;

            case 'disputes':
                $filters = [
                    'status' => $_GET['status'] ?? null,
                    'date_from' => $_GET['date_from'] ?? null,
                    'date_to' => $_GET['date_to'] ?? null
                ];
                
                $result = $adminCrud->generateDisputeReport($filters);
                if ($result === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error generating dispute report']);
                    exit;
                }
                echo json_encode(['success' => true, 'data' => $result]);
                break;

            case 'revenue':
                $filters = [
                    'status' => $_GET['status'] ?? null,
                    'date_from' => $_GET['date_from'] ?? null,
                    'date_to' => $_GET['date_to'] ?? null
                ];
                
                $result = $adminCrud->generateRevenueReport($filters);
                if ($result === false) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error generating revenue report']);
                    exit;
                }
                echo json_encode(['success' => true, 'data' => $result]);
                break;

            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid report type']);
                break;
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid entity']);
        break;
}
?> 