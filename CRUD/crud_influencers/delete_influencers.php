<?php
include 'db.php';

$id = $_POST['id'];

$sql = "DELETE FROM influencers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Influencer deleted successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
