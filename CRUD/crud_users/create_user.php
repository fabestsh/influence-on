<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = $_POST['password'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password_hash, $role, $status]);

    echo "User created successfully.";
}
?>

<form method="post">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Role:
    <select name="role">
        <option value="business">Business</option>
        <option value="influencer">Influencer</option>
        <option value="admin">Admin</option>
    </select><br>
    Status: <input type="number" name="status" value="1"><br>
    <input type="submit" value="Create">
</form>
