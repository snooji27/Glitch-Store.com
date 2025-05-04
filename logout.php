<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to homepage after logout
header("Location: Homepage.html");
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out - GLITCH</title>
    <link rel="stylesheet" href="CSS/header-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-image: url('../Glitch-Store.com/Media/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            color: #ffffff;
            text-align: center;
            padding-top: 80px;
            padding-bottom: 60px;
        }

        /* Logout Container */
        .logout-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            background: rgba(42, 44, 77, 0.8);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(89, 64, 107, 0.3);
        }

        .logout-icon {
            font-size: 48px;
            color: #cf4d8f;
            margin-bottom: 20px;
        }

        .logout-message {
            font-size: 24px;
            margin-bottom: 30px;
            color: #99dfec;
        }

        .redirect-message {
            font-size: 16px;
            color: #ebe1de;
            margin-bottom: 30px;
        }

        .login-again-btn {
            background-color: #59406b;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .login-again-btn:hover {
            background-color: #4a3557;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .logout-container {
                padding: 30px 20px;
                margin: 30px 20px;
            }
            
            .logout-message {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header_and_nav.php'; ?>

    <div class="logout-container">
        <div class="logout-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h2 class="logout-message">You have been logged out</h2>
        <p class="redirect-message">You are being redirected to the homepage...</p>
        <p>If you are not redirected automatically, click below:</p>
        <a href="Homepage.html" class="login-again-btn">Return to Homepage</a>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Redirect after 3 seconds if JavaScript is enabled
        setTimeout(function() {
            window.location.href = "Homepage.html";
        }, 3000);
    </script>
</body>
</html>