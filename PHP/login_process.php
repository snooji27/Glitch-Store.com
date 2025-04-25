<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = new mysqli("localhost", "root", "", "database_name");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['username'] = $db_username;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.'); window.location.href='adminlogin.html';</script>";
        }
    } else {
        echo "<script>alert('Username not found.Please try again.'); window.location.href='adminlogin.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>