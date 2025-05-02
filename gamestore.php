<?php
session_start();
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Game Store</title>
  <link rel="stylesheet" href="/GlitchStore_PHP_CorePages/CSS/styleGamestore.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* header {
      background-color: #111;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
    } */

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    nav ul li a {
      color: white;
      text-decoration: none;
    }

    .hero-container {
      /* background: linear-gradient(to right, #59406b, #1e2128); */
      text-align: center;
      padding: 60px 20px 30px;
      color: #fff;
    }

    .hero-container p {
      font-size: 1.8rem;
      /* margin: 0; */
    }

    main {
      flex: 1;
      padding: 40px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
    }

    .game-card {
      width: 180px;
      background-color: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      transition: transform 0.2s;
      color: #fff;
    }

    .game-card img {
      width: 100%;
      border-radius: 10px;
    }

    .game-card:hover {
      transform: scale(1.05);
    }

    /* footer {
      background: #111;
      color: white;
      text-align: center;
      padding: 20px 0;
    } */

    /* .footer-social a {
      margin: 0 10px;
      color: white;
      font-size: 18px;
    }

    .footer-social a:hover {
      color: #f39c12;
    } */

    .SAR {
      width: 14px;
      vertical-align: middle;
      margin-right: 5px;
    } 
  </style>
</head>
<body>

<!-- Header -->
<?php include "include/header_and_nav.php" ?>

<!-- Hero Section (One line only) -->
<div class="hero-container">
  <p>Game Store</p>
</div>

<!-- Dynamic Game Cards -->
<main id="start_here">
  <?php
    $query = "SELECT * FROM game";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
      $title = htmlspecialchars($row['title']);
      $price = number_format($row['price'], 2);
      $img = htmlspecialchars($row['image_url']);
      $gameId = $row['game_id'];

      echo '
        <a href="games.php?id=' . $gameId . '" class="game-link">
          <div class="game-card">
            <img src="Media/' . $img . '" alt="' . $title . '"/>
            <div class="game-info">
              <h3>' . $title . '</h3>
              <p><img src="Media/SAR_Symbol-white.png" alt="SAR" class="SAR">' . $price . '</p>
            </div>
          </div>
        </a>';
    }
  ?>
</main>

<!-- Footer -->
<?php include "include/footer.php" ?>

</body>
</html>