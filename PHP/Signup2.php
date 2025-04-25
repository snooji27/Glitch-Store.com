<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "";
    $username = "";
    $password = "";
    $dbname = "";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $day = intval($_POST['day']);
    $month = intval($_POST['month']);
    $year = intval($_POST['year']);
    $promo = isset($_POST['promo']) ? 1 : 0;
    $agreed_terms = isset($_POST['terms']) ? 1 : 0;

    $today = new DateTime();
    $birthdate = DateTime::createFromFormat('Y-m-d', "$year-$month-$day");
    $age = $birthdate ? $birthdate->diff($today)->y : 0;

    if ($age < 8) {
        header("Location: ../Login_Signup/Too_young.html");
        exit();
    }

    if ($day && $month && $year && $agreed_terms) {
        $stmt = $conn->prepare("INSERT INTO users (day, month, year, promo, agreed_terms, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiiii", $day, $month, $year, $promo, $agreed_terms);
        $stmt->execute();
        $stmt->close();

        session_start();
        $_SESSION['user_logged_in'] = true;

        header("Location: ../php/gamestore.php");
        exit();
    } else {
        echo "<script>alert('Please complete all required fields.');</script>";
    }

    $conn->close();
}
?>