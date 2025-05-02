<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../db_connect.php";

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    echo "Username received: " . $username;

    $stmt = $conn->prepare("SELECT user_id, username, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;
            header("Location: /Glitch-Store.com-main/gamestore.php");
            exit();
        } else {
            header("Location: /Glitch-Store.com-main/Login_Signup/User_Login_Signup.html?error=wrongpassword");
            exit();
        }
    } else {
        header("Location: /Glitch-Store.com-main/Login_Signup/User_Login_Signup.html?error=usernotfound");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
