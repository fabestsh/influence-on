<?php
require 'db.php';

$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="create_user.php">Create New User</a>
<table border="1">
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= $user['role'] ?></td>
        <td><?= $user['status'] ?></td>
        <td>
            <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a> |
            <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
