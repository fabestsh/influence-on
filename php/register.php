<?php

require_once "db.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email-i nuk është valid.");
    }

    if (strlen($password) < 8) {
        die("Fjalëkalimi duhet të jetë të paktën 8 karaktere.");
    }

    if ($role !== 'business' && $role !== 'influencer') {
        die("Roli i zgjedhur nuk është valid.");
    }

   
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        echo "Llogaria u krijua me sukses!";
    } else {
        echo "Gabim: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
