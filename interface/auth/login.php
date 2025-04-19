<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";

$client = new Google\Client;

$client->setClientId('7000529869-s2fo1ku82mp82sit49dj79pdaolr2mr6.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/influence-on/redirect.php');

$client->addScope("email");
$client->addScope("profile");

$client->setAccessType('offline');
$client->setPrompt('consent');

$url = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>InfluenceON - Login</title>
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

    .social-btn.google {
      background: #db4437;
      color: white;
      text-align: center;
      display: block;
      padding: 10px;
      border-radius: 5px;
      text-decoration: none;
      margin-top: 10px;
    }

    .social-btn.google:hover {
      background: #c23321;
    }

    .toggle-password {
      border: none;
      background: none;
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }

    .password-input {
      position: relative;
    }

    .eye-icon {
      font-size: 16px;
    }

    .terms {
      font-size: 0.875rem;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="form-container">
      <h1 class="logo">InfluenceON</h1>

      <form id="loginForm" action="php/login.php" method="POST">
        <div class="mb-3">
          <label for="loginEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="loginEmail" name="email" placeholder="Enter your email"
            required />
        </div>

        <div class="mb-3">
          <label for="loginPassword" class="form-label">Password</label>
          <div class="password-input">
            <input type="password" class="form-control" id="loginPassword" name="password"
              placeholder="Enter your password" required />
            <button type="button" class="toggle-password" id="togglePasswordBtn">
              <span class="eye-icon" id="eyeIcon">👁️</span>
            </button>

          </div>
        </div>

        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">Sign In</button>
        </div>

        <a href="<?= $url ?>" class="btn btn-light border w-100 d-flex align-items-center justify-content-center"
          style="padding: 8px 12px; font-weight: 500;">
          <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo"
            style="width:20px; height:20px; margin-right:10px;">
          <span>Continue with Google</span>
        </a>

        <div class="mt-3 text-center">
          <a href="forgot_password.php">Forgot Password?</a><br>
          <span>Don't have an account? <a href="register.php">Sign up</a></span>
        </div>
      </form>

      <p class="terms mt-4">
        By signing in, you agree to our <a href="#">Terms</a> and
        <a href="#">Privacy Policy</a>
      </p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('togglePasswordBtn').addEventListener('click', function () {
      const passwordField = document.getElementById('loginPassword');
      const icon = document.getElementById('eyeIcon');

      const isPassword = passwordField.getAttribute('type') === 'password';
      passwordField.setAttribute('type', isPassword ? 'text' : 'password');
      icon.textContent = isPassword ? '🔒' : '👁️';
    });

    const urlParams = new URLSearchParams(window.location.search);

    const error = urlParams.get("error");
    if (error) {
      showError("loginEmail", decodeURIComponent(error));
    }

    function showError(inputId, message) {
      const input = document.getElementById(inputId);
      const parent = input.parentNode;

      const oldError = parent.querySelector(".error");
      if (oldError) oldError.remove();

      const errorDiv = document.createElement("div");
      errorDiv.className = "error";
      errorDiv.style.color = "red";
      errorDiv.style.fontSize = "14px";
      errorDiv.style.marginTop = "5px";
      errorDiv.textContent = message;
      parent.appendChild(errorDiv);
    }
  </script>
</body>

</html>