<?php
session_start();
include '../../db/db_connection.php';

if (! isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User is not logged in']);
    exit;
}

$token = $_POST['token'] ?? '';

if ($token) {
    $secretKey = 'your-secret-key-here';
    $secretKey = str_pad($secretKey, 16, "\0");

    $decodedToken = base64_decode($token);

    if ($decodedToken === false) {
        echo json_encode(['success' => false, 'error' => 'Base64 decoding failed']);
        exit;
    }

    $contactId = openssl_decrypt($decodedToken, 'AES-128-ECB', $secretKey);

    if ($contactId === false) {
        echo json_encode(['success' => false, 'error' => 'Failed to decrypt token']);
        exit;
    }

    if (! is_numeric($contactId)) {
        echo json_encode(['success' => false, 'error' => 'Invalid contact ID']);
        exit;
    }

    $_SESSION['contact_id'] = $contactId;

    $stmt = $pdo->prepare("SELECT id, sender_id, message, created_at FROM messages WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) OR (sender_id = :receiver_id AND receiver_id = :sender_id) ORDER BY created_at ASC");
    $stmt->execute([
        ':sender_id'   => $_SESSION['user_id'],
        ':receiver_id' => $contactId,
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        'success'    => true,
        'messages'   => $messages,
        'contact_id' => $contactId,
    ];
} else {
    $response = ['success' => false, 'error' => 'Invalid token'];
}

echo json_encode($response);
