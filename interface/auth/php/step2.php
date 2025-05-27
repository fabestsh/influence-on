<?php
session_start();
header('Content-Type: application/json');

$pdo = include('../../../db/db_connection.php');

function sanitize($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$role = $_POST['role'] ?? null;

if (!in_array($role, ['business', 'influencer','admin'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Update role and status in users table
    $stmt = $pdo->prepare('UPDATE users SET status = 1, role = ? WHERE id = ?');
    $stmt->execute([$role, $userId]);

    if ($role === 'business') {
        $businessName = sanitize($_POST['businessName']);
        $industry = sanitize($_POST['industry']);
        $website = sanitize($_POST['website'] ?? '');
        $contact = sanitize($_POST['businessContact']);

        $stmt = $pdo->prepare('INSERT INTO businesses (user_id, name, industry, website, contact_info) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $businessName, $industry, $website, $contact]);

    } elseif ($role === 'influencer') {
        $socialLinks = json_encode(array_map('sanitize', $_POST['socialLinks'] ?? []));
        $expertise = json_encode($_POST['expertise'] ?? []);
        $age = isset($_POST['age']) ? (int) $_POST['age'] : null;
        $bio = sanitize($_POST['bio'] ?? '');

        $stmt = $pdo->prepare('INSERT INTO influencers (user_id, social_links, expertise, age, bio) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $socialLinks, $expertise, $age, $bio]);
    }

    $pdo->commit();

    $_SESSION['status'] = 1;
    $_SESSION['user_role'] = $role;

    if ($role === 'business') {
        header('Location: ../../business/business_dashboard.php');
    } else if($role === 'influencer'){
        header('Location: ../../influencer/influencer_dashboard.php');
    } else {
        header('Location: ../../admin/admin_dashboard.php');
    }
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
