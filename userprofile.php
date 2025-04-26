<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$sql = "SELECT username, email, bio, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Glitch</title>
    <link rel="stylesheet" href="CSS/up.css">
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
        <h2>Edit Profile</h2>

        <img src="<?php echo htmlspecialchars($user['profile_picture'] ?: 'Media/default-profile.png'); ?>" 
             alt="Profile Picture" 
             class="profile-pic" 
             style="width:100px; height:100px; border-radius:50%; margin-bottom:20px;">

        <form class="edit-form" action="update-profile.php" method="POST" enctype="multipart/form-data">
            <label>Username (Uneditable)</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Bio</label>
            <textarea name="bio" rows="3"><?php echo htmlspecialchars($user['bio']); ?></textarea>

            <label>Change Profile Picture</label>
            <input type="file" name="profile_picture" accept="image/*">

            <label>New Password (Optional)</label>
            <input type="password" name="password" placeholder="New Password">

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