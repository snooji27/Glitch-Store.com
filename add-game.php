<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $release_date = $_POST['release_date'];
    $price = $_POST['price'];
    $stock = $_POST['stock_quant'];

//og way
    // $update = "UPDATE game SET title=?, category=?, description=?, image_url=?, release_date=?, price=? WHERE game_id=?";
    // $stmt = $conn->prepare($update);
    // $stmt->bind_param("ssssssi", $title, $category, $description, $image_url, $release_date, $price, $game_id);
  //   if ($stmt->execute()) {
  //     header("Location: view_games.php");
  //     exit;
  // } else {
  //     echo "Failed to add game.";
  // }

//new way
    $insert = "INSERT INTO game (title, category, description, image_url, release_date, price) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("sssssd", $title, $category, $description, $image_url, $release_date, $price);

    $insert1 = "INSERT INTO stock (stock_quant) VALUES (?)";
    $stmt1 = $conn->prepare($insert1);
    $stmt1->bind_param("i",$stock);

    if ($stmt1->execute()) {
        header("Location: view_games.php");
        exit;
    } else {
        echo "Failed to add game.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Game</title>
  <link rel="stylesheet" href="CSS/admin.css">
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
  <h1>Add New Game</h1>

  <form method="post" class="modify-form">
  <div class="form-group">
    <label>Title:</label>
    <input type="text" name="title" required>
  </div>

  <div class="form-group">
    <label>Category:</label>
    <input type="text" name="category" required>
  </div>

  <div class="form-group">
    <label>Description:</label>
    <textarea name="description" rows="5" required></textarea>
  </div>

  <div class="form-group">
    <label>Image URL:</label>
    <input type="text" name="image_url" required>
  </div>

  <div class="form-group">
    <label>Release Date:</label>
    <input type="date" name="release_date" required>
  </div>

  <div class="form-group">
    <label>Price (SAR):</label>
    <input type="number" name="price" step="0.01" required>
  </div>

  <div class="form-group">
    <label>Stock:</label>
    <input type="number" name="Stock_quant" step="1" required>
  </div>

  <button type="submit">Add Game</button>
</form>
</main>

<footer>
  <p>&copy; 2025 GLITCH Game Store | All Rights Reserved</p>
</footer>

</body>
</html>