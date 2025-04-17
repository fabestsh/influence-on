<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$db_config = [
    'host' => 'localhost',
    'dbname' => 'influenceon',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Validate and sanitize input
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get and validate input
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$role = in_array($_POST['role'] ?? '', ['business', 'influencer']) ? $_POST['role'] : null;

if (!$email || !$password || !$role) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Check if email already exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        throw new Exception('Email already registered');
    }

    // Insert user
    $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)');
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->execute([$email, $password_hash, $role]);
    $userId = $pdo->lastInsertId();

    // Handle role-specific data
    if ($role === 'business') {
        $stmt = $pdo->prepare('INSERT INTO businesses (user_id, name, industry, website, contact_info) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $userId,
            sanitize($_POST['businessName'] ?? ''),
            sanitize($_POST['industry'] ?? ''),
            filter_var($_POST['website'] ?? '', FILTER_VALIDATE_URL) ?: null,
            sanitize($_POST['businessContact'] ?? '')
        ]);
        $redirectUrl = '../html/business_dashboard.html';
    } else {
        $socialLinks = [];
        if (isset($_POST['socialLinks']) && is_array($_POST['socialLinks'])) {
            foreach ($_POST['socialLinks'] as $platform => $url) {
                if ($url = filter_var($url, FILTER_VALIDATE_URL)) {
                    $socialLinks[$platform] = $url;
                }
            }
        }

        // Handle expertise areas
        $expertise = [];
        if (isset($_POST['expertise']) && is_array($_POST['expertise'])) {
            $expertise = array_map('sanitize', $_POST['expertise']);
        }

        $stmt = $pdo->prepare('INSERT INTO influencers (user_id, social_links, expertise, age, bio) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $userId,
            json_encode($socialLinks),
            json_encode($expertise),
            filter_var($_POST['age'] ?? null, FILTER_VALIDATE_INT) ?: null,
            sanitize($_POST['bio'] ?? '')
        ]);
        $redirectUrl = '../html/influencer_dashboard.html';
    }

    // Commit transaction
    $pdo->commit();

    // Set session variables
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = $role;
    $_SESSION['authenticated'] = true;

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful',
        'redirect' => $redirectUrl,
        'user' => [
            'id' => $userId,
            'email' => $email,
            'role' => $role
        ]
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 