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
      <li><a href="add-game.php" class="function-btn">Add Game</a></li>
      <li><a href="view-games.php" class="function-btn">View Games</a></li>
      <li><a href="Login_Signup/Selection.html" class="logout-btn">Logout</a></li>
    </ul>
  </nav>
</header>

<main class="main-content">
  <h1 class="page-title">View All Games</h1>

 
        
<section class="games-list">
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