<?php
require 'db.php';

$stmt = $pdo->query("SELECT * FROM businesses ORDER BY id DESC");
$businesses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="create.php">Create New Business</a>
<table border="1">
    <tr>
        <th>ID</th><th>User ID</th><th>Name</th><th>Industry</th><th>Website</th><th>Contact</th><th>Actions</th>
    </tr>
    <?php foreach ($businesses as $biz): ?>
    <tr>
        <td><?= $biz['id'] ?></td>
        <td><?= $biz['user_id'] ?></td>
        <td><?= $biz['name'] ?></td>
        <td><?= $biz['industry'] ?></td>
        <td><?= $biz['website'] ?></td>
        <td><?= $biz['contact_info'] ?></td>
        <td>
            <a href="edit.php?id=<?= $biz['id'] ?>">Edit</a> |
            <a href="delete.php?id=<?= $biz['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
