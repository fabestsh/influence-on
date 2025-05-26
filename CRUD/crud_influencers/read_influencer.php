<?php
include 'db.php';

$id = $_GET['id'];

$sql = "SELECT * FROM influencers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($data);

$stmt->close();
$conn->close();
?>
