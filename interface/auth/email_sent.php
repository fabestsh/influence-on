<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email Sent - InfluenceON</title>

    <style>
        :root {
            --primary-color: #6366F1;
            --primary-hover: #4F46E5;
            --text-color: #1F2937;
            --gray-dark: #4B5563;
            --gray-light: #F3F4F6;
            --white: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #EEF2FF 0%, #F3E8FF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .icon-envelope {
            background-color: #EEF2FF;
            padding: 10px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 20px;
            color: var(--text-color);
            margin-bottom: 12px;
        }

        p {
            font-size: 16px;
            color: var(--gray-dark);
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 14px 24px;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
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
            <div
                style="background-color: #EEF2FF; border-radius: 50%; width: 120px; height: 60px; margin: 0 auto 20px auto; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="#6366F1"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <rect x="3" y="5" width="18" height="14" rx="2" ry="2" />
                    <polyline points="3 7 12 13 21 7" />
                </svg>
            </div>


            <h2>Email Sent!</h2>
            <p>An email has been sent to reset your password.</p>

            <a href="login.php" class="btn">Back to Login</a>
        </div>
    </div>
</body>

</html>