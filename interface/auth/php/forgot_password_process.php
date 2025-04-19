<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        die("Invalid email address.");
    }

    $stmt = $pdo->prepare("SELECT name, email FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("No user found with this email.");
    }

    $resetToken = bin2hex(random_bytes(32));
    $resetLink = "http://localhost/Influencers/interface/auth/reset_password.php?token=$resetToken";

    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (:email, :token, NOW())");
    $stmt->execute([
        'email' => $email,
        'token' => hash('sha256', $resetToken)
    ]);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';
        $mail->SMTPAuth = true;
        $mail->Username = '8ae9ef001@smtp-brevo.com';
        $mail->Password = 'g7cYbPmVyJOhBvD6';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('klevismurati21@gmail.com', 'InfluenceON');
        $mail->addAddress($user['email'], $user['name']);

        $mail->isHTML(true);
        $mail->Subject = 'Reset your password';
        $mail->CharSet = 'UTF-8'; // Set character encoding

        $mail->Body = "
<!DOCTYPE html>
<html>
  <head>
    <meta charset='UTF-8'>
    <title>Reset Your Password</title>
  </head>
  <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
    <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);'>
      <h2 style='color: #6366F1; text-align: center;'>InfluenceON</h2>

      <p style='font-size: 16px; color: #333;'>Hello <strong>{$user['name']}</strong>,</p>

      <p style='font-size: 16px; color: #333;'>We received a request to reset your password. Click the button below to set a new password:</p>

      <div style='text-align: center; margin: 30px 0;'>
        <a href='$resetLink' style='background-color: #6366F1; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 6px; display: inline-block; font-size: 16px; font-weight: bold;'>Reset Password</a>
      </div>

      <p style='font-size: 14px; color: #666;'>If you didn&rsquo;t request this, you can safely ignore this email. Your current password will remain unchanged.</p>

      <hr style='border: none; border-top: 1px solid #eee; margin: 40px 0;' />

      <p style='font-size: 12px; color: #aaa; text-align: center;'>
        &copy; 2025 InfluenceON. All rights reserved.
      </p>
    </div>
  </body>
</html>
";

        $mail->send();
        header("Location: ../email_sent.php");
        exit;
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
