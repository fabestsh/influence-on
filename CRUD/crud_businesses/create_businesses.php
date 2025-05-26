<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $industry = $_POST['industry'];
    $website = $_POST['website'];
    $contact_info = $_POST['contact_info'];

    $stmt = $pdo->prepare("INSERT INTO businesses (user_id, name, industry, website, contact_info) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $name, $industry, $website, $contact_info]);

    echo "Business created successfully.";
}
?>

<form method="post">
    User ID: <input type="number" name="user_id" required><br>
    Name: <input type="text" name="name" required><br>
    Industry: <input type="text" name="industry" required><br>
    Website: <input type="text" name="website"><br>
    Contact Info: <input type="text" name="contact_info"><br>
    <input type="submit" value="Create">
</form>
