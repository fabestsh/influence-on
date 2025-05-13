<?php
session_start();
include '../../db/db_connection.php';

// Retrieve the message sent by the user
$message   = $_POST['message'] ?? '';
$contactId = $_SESSION['contact_id'] ?? null;

if ($message && $contactId) {
    // Save message to the database (assuming you have a `messages` table with sender_id, receiver_id, and message)
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)");
    $stmt->execute([
        ':sender_id'   => $_SESSION['user_id'], // Assuming you have user_id stored in session
        ':receiver_id' => $contactId,
        ':message'     => $message,
    ]);

    // Fetch the newly saved message (to get the timestamp and sender information)
    $lastMessage = $pdo->lastInsertId();
    $stmt        = $pdo->prepare("SELECT message, sender_id, created_at FROM messages WHERE id = :id");
    $stmt->execute([':id' => $lastMessage]);
    $messageData = $stmt->fetch(PDO::FETCH_ASSOC);

    $response = [
        'success'    => true,
        'message'    => $messageData['message'],
        'contact_id' => $contactId,
        'time'       => $messageData['created_at'],
        'token'      => $_SESSION['contact_id'], // To know which contact it relates to
    ];
} else {
    $response = ['success' => false, 'error' => 'Message or contact_id is missing'];
}

echo json_encode($response);
