<?php
session_start();
include 'db_connect.php';

$game_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($game_id <= 0) {
    echo "Invalid game ID.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $release_date = $_POST['release_date'];
    $price = $_POST['price'];

    $update = "UPDATE game SET title=?, category=?, description=?, image_url=?, release_date=?, price=? WHERE game_id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ssssssi", $title, $category, $description, $image_url, $release_date, $price, $game_id);

    if ($stmt->execute()) {
        header("Location: view_games.php");
        exit;
    } else {
        echo "Failed to update game.";
    }
}

// Fetch game details
$query = "SELECT * FROM game WHERE game_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    echo "Game not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Modify Game</title>
  <link rel="stylesheet" href="CSS/modify-game.css">
</head>
<body>

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
  <h1>Modify Game</h1>

  <form method="post" class="modify-form">
  <div class="form-group">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($game['title']) ?>" required>
  </div>

  <div class="form-group">
    <label>Category:</label>
    <input type="text" name="category" value="<?= htmlspecialchars($game['category']) ?>" required>
  </div>

  <div class="form-group">
    <label>Description:</label>
    <textarea name="description" rows="5" required><?= htmlspecialchars($game['description']) ?></textarea>
  </div>

  <div class="form-group">
    <label>Image URL:</label>
    <input type="text" name="image_url" value="<?= htmlspecialchars($game['image_url']) ?>" required>
  </div>

  <div class="form-group">
    <label>Release Date:</label>
    <input type="date" name="release_date" value="<?= htmlspecialchars($game['release_date']) ?>" required>
  </div>

  <div class="form-group">
    <label>Price (SAR):</label>
    <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($game['price']) ?>" required>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn-save">Confirm Update</button>
    <a href="view_games.php" class="btn-cancel">Cancel</a>
  </div>
</form>
</main>

<footer>
  <p>&copy; 2025 GLITCH Game Store | All Rights Reserved</p>
</footer>

</body>
</html>