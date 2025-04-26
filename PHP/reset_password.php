<?php
session_start();
$conn = new mysqli("localhost", "root", "", "glitch_store");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Session expired. Please try again.'); window.location.href='../Login_Signup/Forgot.html';</script>";
    exit();
}

if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.'); window.location.href='../Login_Signup/Reset.html';</script>";
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $email = $conn->real_escape_string($_SESSION['email']);

    // Check admin or user
    $stmt_admin = $conn->prepare("SELECT admin_id FROM admin WHERE email = ?");
    $stmt_admin->bind_param("s", $email);
    $stmt_admin->execute();
    $stmt_admin->store_result();

    if ($stmt_admin->num_rows > 0) {
        $update = $conn->prepare("UPDATE admin SET password = ? WHERE email = ?");
        $update->bind_param("ss", $hashed_password, $email);
        $update->execute();
    } else {
        $update = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
        $update->bind_param("ss", $hashed_password, $email);
        $update->execute();
    }

    session_unset();
    session_destroy();

    echo "<script>alert('Password reset successfully! You can now log in.'); window.location.href='../Login_Signup/User_Login_Signup.html';</script>";
    exit();
} else {
    echo "<script>alert('Invalid access.'); window.location.href='../Login_Signup/Forgot.html';</script>";
    exit();
}
?>