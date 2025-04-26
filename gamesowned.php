<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glitch - Games Owned</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search"  />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'db_connect.php'; ?>

// to check if the user is looged in //
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo '<main><h2>Please log in to view your owned games.</h2></main>';
    echo '</body></html>';
    exit();
}
$user_id = $_SESSION['user_id'];
$search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
?>

    <header>
        <div class="logo-container">
            <img src="Media/icon2.png" class="header-logo" alt="Logo">
        </div>
        
        <div class="nav-auth-wrapper">
        <nav>
            <ul>
                <li><a href="Homepage.html">HOME</a></li>
                <li><a href="gamestore.html">GAMES</a></li>
                <li><a href="gamesowned.html">GAMES OWNED</a></li>
                <li><a href="#about">ABOUT US</a></li>
                <li><a href="support.html">SUPPORT</a></li>
            </ul>
        </nav>
        
         <!-- log-in and sign-up bottuns--> 
        <div class="auth-buttons">
            <a href="Login_Signup/Selection.html" class="login-btn">Login</a>
            <a href="Login_Signup/User_Login_Signup.html?form=signup" class="signup-btn">Sign Up</a>
        </div>
        </div>
    </header>
    
    <main>
        <h2>Your Owned Games: </h2>
        
        <!--- search bar --> 
        <form action="gamesowned.php" method="GET">
    <div class="search">
        <span class="search-icon material-symbols-outlined">search</span>
        <input class="search-input" type="search" name="search" placeholder="Search Your Games" style="color: #ffffff;">
    </div>
</form>
        
        <section class="games-list">
<?php
$query = "SELECT g.game_id, g.title, g.image_url, g.download_url, go.purchase_date
          FROM GameOwnership go
          JOIN Game g ON go.game_id = g.game_id
          WHERE go.user_id = $user_id";
if ($search_term) {
    $query .= " AND g.title LIKE '%$search_term%'";
}
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $game_title = htmlspecialchars($row['title']);
        $game_image = htmlspecialchars($row['image_url']);
        $game_download = htmlspecialchars($row['download_url']);
        $purchase_date = date('d/m/Y', strtotime($row['purchase_date']));
        ?>
        <div class="game-card">
            <img src="<?php echo $game_image; ?>" alt="<?php echo $game_title; ?>">
            <h3><?php echo $game_title; ?></h3>
            <p>Purchase Date: <?php echo $purchase_date; ?></p>
            <button onclick="window.location.href='<?php echo $game_download; ?>'">Download</button>
        </div>
        <?php
    }
} else {
    echo '<p>You don\'t own any games yet.</p>';
}
mysqli_free_result($result);
?>
        </section>
    </main>
    
    <!-- footer--> 
    <footer id="contact">
    <p>Need help? Visit our <a href="support.html">Support Page</a></p>
    <div class="footer-social">
        <a href="#"><i class="fab fa-discord"></i></a>
        <a href="#"><i class="fab fa-twitch"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
    </div>
    <p>© 2025 GLITCH Game Store | All Rights Reserved</p>
</footer>
<button id="scrollToTop" class="scroll-to-top">
    <i class="fas fa-arrow-up"></i>
</button>

   
</body>
</html>
