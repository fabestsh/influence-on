<?php
session_start();
session_unset();
session_destroy();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    echo json_encode(['success' => true, 'message' => 'Logged out']);
    exit;
}

header("Location: ../../index.php");
exit;
