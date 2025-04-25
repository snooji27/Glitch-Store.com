<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a connection to the database (MySQL)
    $conn = new mysqli("localhost", "root", "", "database_name");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Capture and sanitize input data to avoid SQL injection
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Prepare a query to check if the username exists in the database
    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // If username exists, verify the password
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();

        // Check if the entered password matches the hashed password in the database
        if (password_verify($password, $db_password)) {
            // Store session variables and redirect to the admin dashboard
            $_SESSION['admin_id'] = $id;
            $_SESSION['username'] = $db_username;
            header("Location: admin.html"); // Redirect to the admin page
            exit();
        } else {
            // Invalid password, show an error message
            echo "<script>alert('Incorrect password. Please try again.'); window.location.href='adminlogin.html';</script>";
        }
    } else {
        // Username not found, show an error message
        echo "<script>alert('Username not found. Please try again.'); window.location.href='adminlogin.html';</script>";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>