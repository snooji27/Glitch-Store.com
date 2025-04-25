<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = new mysqli("localhost", "root", "", "database_name");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Username or email already exists.'); window.location.href='signup.html';</script>";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.html';</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.'); window.location.href='signup.html';</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>