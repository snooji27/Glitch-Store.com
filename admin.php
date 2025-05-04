<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    // Not logged in — redirect to login page
    header("Location: /Glitch-Store.com-main/Login_Signup/Adm_Log.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="CSS/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
  <div class="logo-container">
    <a href="admin.php"><img src="/Glitch-Store.com-main/Media/icon2.png" alt="Logo" class="header-logo"></a>
  </div>
  </nav>
    <ul>
      <li><a href="admin.php" class="function-btn">Admin Dashboard</a></li>
      <li><a href="add_game.php" class="function-btn">Add Game</a></li>
      <li><a href="view_games.php" class="function-btn">View Games</a></li>
      <li><a href="Login_Signup/Selection.html" class="logout-btn">Logout</a></li>
    </ul>
  </nav>

</header>

<main class="main-content">
  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
  <p>Select an action from the menu above.</p>
</main>

<footer>
  <p>&copy; 2025 GLITCH Game Store | All Rights Reserved</p>
</footer>

</body>
</html>
