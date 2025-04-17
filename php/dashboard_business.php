<?php
session_start();
if ($_SESSION['role'] != 'business') {
    header("Location: login.php");
    exit();
}
echo "Welcome, Business!";
?>
