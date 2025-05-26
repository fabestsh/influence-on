<?php
include 'db.php';

$user_id = $_POST['user_id'];
$social_links = $_POST['social_links'];
$expertise = $_POST['expertise'];
$age = $_POST['age'];
$bio = $_POST['bio'];

$sql = "INSERT INTO influencers (user_id, social_links, expertise, age, bio, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issis", $user_id, $social_links, $expertise, $age, $bio);

if ($stmt->execute()) {
    echo "New influencer created successfully";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
