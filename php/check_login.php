<?php
session_start();
require_once "db.php";

// Kontrolli i të dhënave
if (
    !isset($_POST['email'], $_POST['password'], $_POST['role']) ||
    !is_array($_POST['role']) ||
    count($_POST['role']) !== 1
) {
    die("Ju lutemi plotësoni të gjitha fushat dhe zgjidhni vetëm një rol.");
}

$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'][0]; // marrim vetëm një rol

// Pyetja në databazë me kolonat që ti ke
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? AND role = ?");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $role;

        if ($role === 'business') {
            header("Location: dashboard_business.php");
        } elseif ($role === 'influencer') {
            header("Location: dashboard_influencer.php");
        }
        exit();
    } else {
        echo "Fjalëkalimi është i pasaktë.";
    }
} else {
    echo "Email-i ose roli nuk janë të saktë.";
}
?>
