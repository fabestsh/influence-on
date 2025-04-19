<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Password Reset Success</title>

    <style>
        :root {
            --primary-color: #6366F1;
            --primary-hover: #4F46E5;
            --text-color: #1F2937;
            --gray-light: #F3F4F6;
            --gray-medium: #9CA3AF;
            --gray-dark: #4B5563;
            --white: #FFFFFF;
            --success: #10B981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #EEF2FF 0%, #F3E8FF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 480px;
        }

        .form-container {
            background: var(--white);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            color: var(--primary-color);
            font-size: 24px;
            margin-bottom: 32px;
        }

        .icon-check {
            background-color: #ECFDF5;
            border-radius: 50%;
            padding: 10px;
            margin-bottom: 24px;
        }

        h2 {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-color);
            margin-top: 20px;
        }

        p {
            font-size: 16px;
            color: var(--gray-dark);
            margin-top: 12px;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 14px;
            margin-top: 28px;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn:hover {
            background: var(--primary-hover);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h1 class="logo">InfluenceON</h1>

            <svg xmlns="http://www.w3.org/2000/svg" class="icon-check" viewBox="0 0 24 24" width="64" height="64"
                fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5" />
            </svg>

            <h2>Password Updated Successfully!</h2>
            <p>You can now log in with your new password.</p>

            <a href="login.php" class="btn">Login Now</a>
        </div>
    </div>
</body>

</html>