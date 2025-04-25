<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['code1'], $_POST['code2'], $_POST['code3'], $_POST['code4'], $_POST['code5'], $_POST['code6'])) {

        $entered_code = $_POST['code1'] . $_POST['code2'] . $_POST['code3'] . $_POST['code4'] . $_POST['code5'] . $_POST['code6'];

        if (isset($_SESSION['otp']) && $_SESSION['otp'] === $entered_code) {
            header("Location: reset_password.php");
            exit();
        } else {
            echo "<script>alert('Invalid OTP. Please try again.'); window.location.href='OTP.html';</script>";
        }
    } else {
        echo "<script>alert('Please enter all OTP fields.'); window.location.href='OTP.html';</script>";
    }
}
?>