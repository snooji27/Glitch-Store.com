<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format. Please try again.'); window.location.href='forgot_password.html';</script>";
        exit();
    }

    $conn = new mysqli("localhost", "root", "", "database_name");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($email);

    $stmt = $conn->prepare("SELECT id, username FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $otp = random_int(100000, 999999);

        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        $subject = "Password Reset Request - Glitch";
        $message = "Hello,\n\nYou requested a password reset. Please use the following OTP to reset your password:\n\nOTP: $otp\n\nIf you did not request this, please ignore this email.";
        $headers = "From: no-reply@glitch.com";

        if (mail($email, $subject, $message, $headers)) {
            header("Location: OTP.html");
            exit();
        } else {
            echo "Failed to send email. Please try again later.";
        }
    } else {
        echo "<script>alert('This email address is not registered.'); window.location.href='forgot_password.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>