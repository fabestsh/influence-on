<?php
require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirmation'] ?? '';

    if (!$token || !$password || !$confirm) {
        die("All fields are required.");
    }

    if ($password !== $confirm) {
        die("Passwords do not match.");
    }

    $hashedToken = hash('sha256', $token);
    $stmt = $pdo->prepare("SELECT email, created_at FROM password_resets WHERE token = :token LIMIT 1");
    $stmt->execute(['token' => $hashedToken]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reset) {
        die("Invalid or expired token.");
    }

    $createdAt = new DateTime($reset['created_at']);
    $now = new DateTime();
    $interval = $createdAt->diff($now);
    if ($interval->i > 15) {
        die("Token has expired. Please request a new reset.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password_hash = :password WHERE email = :email");
    $stmt->execute([
        'password' => $hashedPassword,
        'email' => $reset['email']
    ]);

    $pdo->prepare("DELETE FROM password_resets WHERE email = :email")->execute(['email' => $reset['email']]);

    header('Location: ../reset_success.php');
    exit;
}
