<?php
include 'db.php';

$sql = "SELECT * FROM influencers";
$result = $conn->query($sql);

$influencers = [];
while ($row = $result->fetch_assoc()) {
    $influencers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($influencers);

$conn->close();
?>
