<?php
session_start();
if ($_SESSION['role'] != 'influencer') {
    header("Location: login.php");
    exit();
}
echo "Welcome, Influencer!";
?>
