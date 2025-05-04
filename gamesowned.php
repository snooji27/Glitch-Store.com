<?php 
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=gamesowned.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'title_asc';
?>


 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glitch - Games Owned</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search"  />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">    
    <main>
        <?php include "include/header_and_nav.php" ?>
        <h2>Your Owned Games: </h2>
        
        <!--- search bar --> 
        <form action="gamesowned.php" method="GET">
    <div class="search">
        <span class="search-icon material-symbols-outlined">search</span>
        <input class="search-input" type="search" name="search" placeholder="Search Your Games" style="color: #ffffff;" value="<?php echo htmlspecialchars($search_term); ?>">
    </div>
    <div class="sort">
        <label for="sort">Sort by: </label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="title_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title_asc') ? 'selected' : ''; ?>>Title (A-Z)</option>
            <option value="title_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title_desc') ? 'selected' : ''; ?>>Title (Z-A)</option>
        </select>
    </div>
</form>
        
<section class="games-list">
<?php
$query = "SELECT g.game_id, g.title, g.image_url, go.purchase_date
          FROM GameOwnership go
          JOIN Game g ON go.game_id = g.game_id
          WHERE go.user_id = $user_id";
if ($search_term) {
    $query .= " AND g.title LIKE '%$search_term%'";
}
if ($sort == 'title_asc') {
    $query .= " ORDER BY g.title ASC";
} elseif ($sort == 'title_desc') {
    $query .= " ORDER BY g.title DESC";
}
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $game_title = htmlspecialchars($row['title']);
        $game_image = htmlspecialchars($row['image_url']);
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
    
     <!-- footer should be here--> 
     <footer id="contact">
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
