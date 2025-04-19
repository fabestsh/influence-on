<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . "/vendor/autoload.php";
$pdo = include('db/db_connection.php');

$client = new Google\Client;
$client->setClientId('7000529869-s2fo1ku82mp82sit49dj79pdaolr2mr6.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/influence-on/redirect.php');

try {
    if (!isset($_GET['code'])) {
        throw new Exception('Google login failed or was cancelled');
    }

    $token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

    if (isset($token['error'])) {
        throw new Exception('Failed to fetch Google access token');
    }

    $client->setAccessToken($token['access_token']);

    $oauth = new Google\Service\Oauth2($client);
    $userinfo = $oauth->userinfo->get();

    $email = $userinfo->email;
    $name = $userinfo->name ?? 'Unknown';

    if (!$email) {
        throw new Exception('Google account did not return an email address');
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT id, email, status, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Existing user — log in
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['status'] = $user['status'];
        $_SESSION['authenticated'] = true;

        $pdo->commit();

        if ($user['status'] === 0) {
            header('Location: interface/auth/step2.php');
            exit;
        }

        $redirectUrl = $user['role'] === 'business'
            ? 'interface/business/business_dashboard.php'
            : 'interface/influencer/influencer_dashboard.php';

        header("Location: $redirectUrl");
        exit;
    }

    // New user — create record
    $fakePassword = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
    $defaultRole = 'influencer';
    $status = 0;

    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $email, $fakePassword, $defaultRole, $status]);
    $userId = $pdo->lastInsertId();

    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = $defaultRole;
    $_SESSION['status'] = $status;
    $_SESSION['authenticated'] = true;

    $pdo->commit();

    header('Location: interface/auth/step2.php');
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(400);
    $_SESSION['swal_error'] = $e->getMessage();
    header('Location: interface/auth/register.php');
    exit;
}
