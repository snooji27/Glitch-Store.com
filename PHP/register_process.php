<?php
session_start();

$conn = new mysqli("localhost", "root", "", "glitch_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['step']) && $_POST['step'] === '1') {
    $_SESSION['signup_username'] = $_POST['username'];
    $_SESSION['signup_email'] = $_POST['email'];
    $_SESSION['signup_password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);

    header("Location: ../Login_Signup/Signup_2.html");
    exit();
}

if (isset($_POST['step']) && $_POST['step'] === '2') {
    if (!isset($_SESSION['signup_username'], $_SESSION['signup_email'], $_SESSION['signup_password'])) {
        die("Session data missing. Please start signup again.");
    }

    $username = $_SESSION['signup_username'];
    $email = $_SESSION['signup_email'];
    $password = $_SESSION['signup_password'];

    $day = intval($_POST['day']);
    $month = intval($_POST['month']);
    $year = intval($_POST['year']);
    $promo = isset($_POST['promo']) ? 1 : 0;

    $birthdate = sprintf('%04d-%02d-%02d', $year, $month, $day);

    // Check age if less than 8 exit
    $today = new DateTime();
    $dob = DateTime::createFromFormat('Y-m-d', $birthdate);
    $age = $dob ? $dob->diff($today)->y : 0;

    if ($age < 8) {
        header("Location: ../Login_Signup/Too_young.html");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO user (username, email, password, birthdate, promo) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssi", $username, $email, $password, $birthdate, $promo);
        $success = $stmt->execute();
        $stmt->close();

        unset($_SESSION['signup_username'], $_SESSION['signup_email'], $_SESSION['signup_password']);

        if ($success) {
            header("Location: ../Login_Signup/User_Login_Signup.html?signup=success");
            exit();
        } else {
            die("Error inserting user: " . $conn->error);
        }
    } else {
        die("Error preparing statement: " . $conn->error);
    }
}
?>