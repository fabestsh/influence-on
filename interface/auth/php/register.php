<?php
session_start();
header('Content-Type: application/json');

$pdo = include '../../../db/db_connection.php';

function sanitize($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name            = sanitize($_POST['name'] ?? '');
$email           = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password        = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$status          = 0;

if (! $name || ! $email || ! $password || ! $confirmPassword) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
    exit;
}

if ($password !== $confirmPassword) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        throw new Exception('Email already registered');
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, status) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $password_hash, $status]);

    $userId = $pdo->lastInsertId();

    $pdo->commit();

    $_SESSION['user_id']       = $userId;
    $_SESSION['user_name']     = $name;
    $_SESSION['user_email']    = $email;
    $_SESSION['status']        = $status;
    $_SESSION['authenticated'] = true;

    echo json_encode([
        'success'  => true,
        'message'  => 'Registration successful',
        'redirect' => '../auth/step2.php',
        'user'     => [
            'id'     => $userId,
            'email'  => $email,
            'name'   => $name,
            'status' => $status,
        ],
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
