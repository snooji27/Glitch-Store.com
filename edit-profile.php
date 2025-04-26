<?php
include 'db_connection.php';
session_start();
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    $update_query = "UPDATE users SET username = '$username', email = '$email', bio = '$bio' WHERE id = '$user_id'";
    mysqli_query($conn, $update_query);

    header("Location: userprofile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Glitch</title>
    <link rel="stylesheet" href="CSS/up.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <div class="logo-container">
        <img src="Media/icon2.png" alt="Logo" class="header-logo">
        <span class="site-name">Glitch</span>
    </div>
    <nav>
        <ul>
            <li><a href="Homepage.html">HOME</a></li>
            <li><a href="gamestore.html">GAMES</a></li>
            <li><a href="gamesowned.html">GAMES OWNED</a></li>
            <li><a href="#about">ABOUT US</a></li>
            <li><a href="support.html">SUPPORT</a></li>
        </ul>
    </nav>
</header>

<main style="padding-top: 80px;">
    <section class="profile-container">
        <div class="profile-header">
            <h2 class="username">Edit Profile</h2>
            <p class="bio">Update your info and personalize your account</p>
        </div>

        <form class="edit-form" enctype="multipart/form-data" method="POST">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label>Bio</label>
            <textarea name="bio" rows="3"><?php echo $user['bio']; ?></textarea>

            <button type="submit" class="settings-btn">Save Changes</button>
            <a href="userprofile.php" class="settings-btn" style="background-color:#777;">Cancel</a>
        </form>
    </section>
</main>

<footer>
    <div class="footer-social">
        <a href="#"><i class="fab fa-discord"></i></a>
        <a href="#"><i class="fab fa-twitch"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
    </div>
    <p>&copy; 2025 GLITCH Game Store | All Rights Reserved</p>
</footer>

</body>
</html>
