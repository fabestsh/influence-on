<?php
session_start();
require '../../../vendor/autoload.php';
$pdo = include '../../../db/db_connection.php';

use \Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php?error=Method%20not%20allowed");
    exit;
}

$email    = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if (! $email || ! $password) {
    header("Location: ../login.php?error=Invalid%20input%20data");
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash, status, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (! $user || ! password_verify($password, $user['password_hash'])) {
        throw new Exception('Invalid email or password');
    }

    $_SESSION['user_id']       = $user['id'];
    $_SESSION['user_name']     = $user['name'];
    $_SESSION['user_email']    = $user['email'];
    $_SESSION['user_role']     = $user['role'];
    $_SESSION['status']        = $user['status'];
    $_SESSION['authenticated'] = true;

    $key     = 'O{#ZG)OwVuvXC>ceBR8DUS~O~H6Cgs';
    $payload = [
        'iss'   => 'php-auth-app',
        'sub'   => $user['id'],
        'email' => $user['email'],
        'name'  => $user['name'],
        'role'  => $user['role'],
        'exp'   => time() + 3600,
    ];
    $jwt                   = JWT::encode($payload, $key, 'HS256');
    $_SESSION['jwt_token'] = $jwt;

    if ($user['status'] === 0) {
        header('Location: ../step2.php');
        exit;
    }

    $redirectUrl = $user['role'] === 'business'
    ? '../../business/business_dashboard.php'
    : ($user['role'] === 'influencer'
        ? '../../influencer/influencer_dashboard.php'
        : '../../admin/admin_dashboard.php');

    header("Location: $redirectUrl");
    exit;

} catch (Exception $e) {
    $errorMsg = urlencode($e->getMessage());
    header("Location: ../login.php?error=$errorMsg");
    exit;
}
