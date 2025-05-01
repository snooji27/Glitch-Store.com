<?php
session_start();
require_once "../db_connect.php";

if (isset($_POST['resend']) && $_POST['resend'] == '1') {
    if (isset($_SESSION['email'])) {
        $otp = random_int(100000, 999999);
        $_SESSION['otp'] = (string)$otp;

        echo "<script>alert('A new OTP has been sent to your email! (Demo: Your new OTP is $otp)'); window.location.href='../Login_Signup/OTP.html';</script>";
        exit();
    } else {
        echo "<script>alert('Session expired. Please restart the process.'); window.location.href='../Login_Signup/Forgot.html';</script>";
        exit();
    }
}

// Email Submission
if (isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format. Please try again.'); window.location.href='../Login_Signup/Forgot.html';</script>";
        exit();
    }

    $email = $conn->real_escape_string($email);

    $stmt_admin = $conn->prepare("SELECT admin_id, username FROM admin WHERE email = ?");
    $stmt_admin->bind_param("s", $email);
    $stmt_admin->execute();
    $stmt_admin->store_result();

    $stmt_user = $conn->prepare("SELECT user_id, username FROM user WHERE email = ?");
    $stmt_user->bind_param("s", $email);
    $stmt_user->execute();
    $stmt_user->store_result();

    if ($stmt_admin->num_rows > 0 || $stmt_user->num_rows > 0) {
        $otp = random_int(100000, 999999);

        $_SESSION['otp'] = (string)$otp;
        $_SESSION['email'] = $email;

        echo "<script>alert('A 6-digit OTP has been sent to your email! (Demo: Your OTP is $otp)'); window.location.href='../Login_Signup/OTP.html';</script>";
        exit();
    } else {
        echo "<script>alert('This email is not registered.'); window.location.href='../Login_Signup/Forgot.html';</script>";
    }

    $stmt_admin->close();
    $stmt_user->close();
    $conn->close();
    exit();
}

// OTP Verification
if (isset($_POST['code1'], $_POST['code2'], $_POST['code3'], $_POST['code4'], $_POST['code5'], $_POST['code6'])) {
    $entered_code = $_POST['code1'] . $_POST['code2'] . $_POST['code3'] . $_POST['code4'] . $_POST['code5'] . $_POST['code6'];

    if (isset($_SESSION['otp']) && $_SESSION['otp'] === $entered_code) {
        header("Location: ../Login_Signup/Reset.html");
        exit();
    } else {
        echo "<script>alert('Invalid OTP. Please try again.'); window.location.href='../Login_Signup/OTP.html';</script>";
        exit();
    }
}
?>