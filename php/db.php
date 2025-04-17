<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "influenceon"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Lidhja me databazën dështoi: " . $conn->connect_error);
}
?>
