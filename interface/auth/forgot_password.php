<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>InfluenceON - Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/styles.css" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h1 class="logo">InfluenceON</h1>

            <form id="forgotPasswordForm" action="php/forgot_password_process.php" method="POST">
                <div class="mb-3">
                    <label for="forgotEmail" class="form-label">Enter your email to reset password</label>
                    <input type="email" class="form-control" id="forgotEmail" name="email"
                        placeholder="Enter your email" required />
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>

                <div class="text-center">
                    <a href="login.php">Back to Login</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>