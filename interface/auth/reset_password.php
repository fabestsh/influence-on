<?php
$token = $_GET['token'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - InfluenceON</title>
    <link rel="stylesheet" href="../../assets/css/styles.css" />
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h1 class="logo">InfluenceON</h1>

            <form action="php/reset_password_process.php" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="password-input">
                        <input type="password" name="password" id="password" placeholder="Enter new password" required>
                        <button type="button" class="toggle-password" data-toggle="#password">
                            <span class="eye-icon">👁️</span>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="password-input">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Confirm new password" required>
                        <button type="button" class="toggle-password" data-toggle="#password_confirmation">
                            <span class="eye-icon">👁️</span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn primary-btn">Update Password</button>

                <p class="signin-link mt-4">
                    Back to <a href="login.php">Login</a>
                </p>
            </form>

            <p class="terms">
                By resetting your password, you agree to our <a href="#">Terms</a> and
                <a href="#">Privacy Policy</a>
            </p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const input = document.querySelector(this.dataset.toggle);
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                this.innerHTML = isPassword ? '🙈' : '👁️';
            });
        });
    </script>
</body>

</html>