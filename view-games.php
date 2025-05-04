<?php
session_start();
include 'db_connect.php';

$query = "SELECT * FROM game";
$result = mysqli_query($conn, $query);

$admin_id = $_SESSION['admin_id'];
$search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'title_asc';
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Games</title>
  <link rel="stylesheet" href="CSS/view-games.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="view-games">

<header>
  <div class="logo-container">
    <a href="admin.php"><img src="/Glitch-Store.com-main/Media/icon2.png" alt="Logo" class="header-logo"></a>
  </div>
  <nav>
    <ul>
      <li><a href="admin.php" class="function-btn">Admin Dashboard</a></li>
      <li><a href="add_game.php" class="function-btn">Add Game</a></li>
      <li><a href="view_games.php" class="function-btn">View Games</a></li>
      <li><a href="Login_Signup/Selection.html" class="logout-btn">Logout</a></li>
    </ul>
  </nav>
</header>

<main class="main-content">
  <h1 class="page-title">View All Games</h1>

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
?>
  <section class="games-grid">
    <?php while ($game = mysqli_fetch_assoc($result)): ?>
      <div class="game-card">
        <img src="Media/<?= htmlspecialchars($game['image_url']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
        <div class="game-info">
          <h3><?= htmlspecialchars($game['title']) ?></h3>
          <p><img src="/Glitch-Store.com-main/Media/SAR_Symbol-white.png" class="SAR"> <?= htmlspecialchars($game['price']) ?></p>
        </div>
        <div class="game-buttons">
          <form action="delete_game.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?= $game['game_id'] ?>">
            <button type="submit" class="btn-del" onclick="return confirm('Delete this game?')">Delete</button>
          </form>
          <a href="update_game.php?id=<?= $game['game_id'] ?>" class="btn-modify">Modify</a>
        </div>
      </div>
    <?php endwhile; ?>
  </section>
</main>

<footer>
  <p>&copy; 2025 GLITCH Game Store | All Rights Reserved</p>
</footer>

</body>
</html>