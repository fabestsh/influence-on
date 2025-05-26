<?php
require 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, password_hash=?, role=?, status=? WHERE id=?");
        $stmt->execute([$name, $email, $password_hash, $role, $status, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, role=?, status=? WHERE id=?");
        $stmt->execute([$name, $email, $role, $status, $id]);
    }

    echo "User updated successfully. <a href='users.php'>Back to list</a>";
    exit;
}
?>

<form method="post">
    Name: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>"><br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"><br>
    Password (leave blank to keep current): <input type="password" name="password"><br>
    Role:
    <select name="role">
        <option value="business" <?= $user['role'] == 'business' ? 'selected' : '' ?>>Business</option>
        <option value="influencer" <?= $user['role'] == 'influencer' ? 'selected' : '' ?>>Influencer</option>
        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
    </select><br>
    Status: <input type="number" name="status" value="<?= $user['status'] ?>"><br>
    <input type="submit" value="Update">
</form>
