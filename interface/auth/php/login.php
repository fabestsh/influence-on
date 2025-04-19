<?php
session_start();
$pdo = include('../../../db/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php?error=Method%20not%20allowed");
    exit;
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    header("Location: ../login.php?error=Invalid%20input%20data");
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, email, password_hash, status, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        throw new Exception('Invalid email or password');
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['status'] = $user['status'];
    $_SESSION['authenticated'] = true;

    if ($user['status'] === 0) {
        header('Location: ../step2.php');
        exit;
    }

    $redirectUrl = $user['role'] === 'business'
        ? '../../business/business_dashboard.php'
        : '../../influencer/influencer_dashboard.php';

    header("Location: $redirectUrl");
    exit;

} catch (Exception $e) {
    $errorMsg = urlencode($e->getMessage());
    header("Location: ../login.php?error=$errorMsg");
    exit;
}
