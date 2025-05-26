<?php
require 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM businesses WHERE id = ?");
$stmt->execute([$id]);

header("Location: index_businesses.php");
exit;
