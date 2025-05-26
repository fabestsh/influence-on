<?php
include 'db.php';

$id = $_POST['id'];
$user_id = $_POST['user_id'];
$social_links = $_POST['social_links'];
$expertise = $_POST['expertise'];
$age = $_POST['age'];
$bio = $_POST['bio'];

$sql = "UPDATE influencers 
        SET user_id=?, social_links=?, expertise=?, age=?, bio=?, updated_at=NOW() 
        WHERE id=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issisi", $user_id, $social_links, $expertise, $age, $bio, $id);

if ($stmt->execute()) {
    echo "Influencer updated successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
