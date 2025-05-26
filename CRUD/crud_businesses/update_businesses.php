<?php
require 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM businesses WHERE id = ?");
$stmt->execute([$id]);
$biz = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $industry = $_POST['industry'];
    $website = $_POST['website'];
    $contact_info = $_POST['contact_info'];

    $stmt = $pdo->prepare("UPDATE businesses SET user_id=?, name=?, industry=?, website=?, contact_info=? WHERE id=?");
    $stmt->execute([$user_id, $name, $industry, $website, $contact_info, $id]);

    echo "Business updated successfully. <a href='index.php'>Back to list</a>";
    exit;
}
?>

<form method="post">
    User ID: <input type="number" name="user_id" value="<?= $biz['user_id'] ?>"><br>
    Name: <input type="text" name="name" value="<?= $biz['name'] ?>"><br>
    Industry: <input type="text" name="industry" value="<?= $biz['industry'] ?>"><br>
    Website: <input type="text" name="website" value="<?= $biz['website'] ?>"><br>
    Contact Info: <input type="text" name="contact_info" value="<?= $biz['contact_info'] ?>"><br>
    <input type="submit" value="Update">
</form>
