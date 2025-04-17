<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Forma e Login-it</h2>
    <br>

    <form action="check_login.php" method="POST">
        <label>Zgjidh rolin:</label><br>
        <input type="checkbox" name="role[]" value="business"> Business<br>
        <input type="checkbox" name="role[]" value="influencer"> Influencer<br><br>

        <label>Përdoruesi:</label>
        <input type="text" name="email" required><br><br>

        <label>Fjalëkalimi:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="login">
    </form>
</body>
</html>
