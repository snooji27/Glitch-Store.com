<?php
session_start();
include 'db_connect.php';

// Process Add to Cart action (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $gameId = intval($_POST['game_id']);
    $quantity = intval($_POST['quantity']);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['redirect' => 'Login_Signup/User_Login_Signup.html']);
        exit();
    }

    $userId = $_SESSION['user_id'];

    $check = "SELECT * FROM cartitem WHERE user_id = ? AND game_id = ?";
    $stmt = mysqli_prepare($conn, $check);
    if (!$stmt) {
        echo json_encode(['error' => 'Failed to prepare statement']);
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $userId, $gameId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_num_rows($result) > 0;

    if ($exists) {
        $update = "UPDATE cartitem SET quantity = quantity + ? WHERE user_id = ? AND game_id = ?";
        $stmt = mysqli_prepare($conn, $update);
        if (!$stmt) {
            echo json_encode(['error' => 'Failed to prepare update statement']);
            exit();
        }
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $userId, $gameId);
        mysqli_stmt_execute($stmt);
    } else {
        $insert = "INSERT INTO cartitem (user_id, game_id, quantity) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert);
        if (!$stmt) {
            echo json_encode(['error' => 'Failed to prepare insert statement']);
            exit();
        }
        mysqli_stmt_bind_param($stmt, "iii", $userId, $gameId, $quantity);
        mysqli_stmt_execute($stmt);

        if (!isset($_SESSION['cart_count'])) {
            $_SESSION['cart_count'] = 0;
        }
        $_SESSION['cart_count'] += 1;
    }

    echo json_encode(['success' => true, 'newItem' => !$exists]);
    exit();
}

// Get game ID from URL
$game_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch game data
$sql = "SELECT * FROM game WHERE game_id = ?";
$stmt = $conn->prepare($sql);
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="CSS/gamestyle.css"/>
  <title><?= htmlspecialchars($game['title']) ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
/* Header Login/Signup Buttons */
    .login-btn {
        color: #fff;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .signup-btn {
        background: #cf4d8f;
        color: #fff;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .login-btn:hover {
        color: #cf4d8f;
        text-decoration: none;
    }

    .signup-btn:hover {
        background: #b83d7a;
        text-decoration: none;
    }

    .cart-icon {
      position: relative;
    }
    #cart-count {
      background-color:rgb(204, 12, 121);
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
      font-size: 12px;
      position: absolute;
      top: -10px;
      right: -10px;
    }
    
    header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background: rgba(26, 26, 26, 0.8);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    height: 60px;
    }
    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }
    nav ul li a {
      color: white;
      text-decoration: none;
    }
    .quantity-control {
      display: flex;
      align-items: center;
      margin-top: 10px;
    }
    .quantity-control button {
      background-color: #555;
      color: white;
      border: none;
      padding: 5px 10px;
      font-size: 18px;
      cursor: pointer;
      border-radius: 4px;
    }
    .quantity-control input {
      width: 50px;
      text-align: center;
      margin: 0 10px;
      font-size: 16px;
      background-color: #222;
      color: white;
      border: none;
      border-radius: 4px;
    }
    .btn.add-cart {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 20px;
      background:rgb(153, 14, 83);
      color: white;
      font-weight: bold;
      border-radius: 5px;
      text-decoration: none;
    }
    .btn.add-cart:hover {
        background: #b83d7a;
    }

    /* Styling for the game image */
    .game-image-container {
      background: rgba(0, 0, 0, 0.6);
      padding: 20px;
      border-radius: 8px;
      width: 350px;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.6);
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .game-image-container img {
      width: 100%;
      height: auto;
      border-radius: 8px;
    }

    /* Footer */
    footer {
        padding: 20px;
        background: rgba(26, 26, 26, 0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        text-align: center;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        z-index: 1;
    }

    footer p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        margin: 10px 0 0 0;
    }

    .footer-social {
        display: flex;
        justify-content: center;
        gap: 25px;
        margin-bottom: 10px;
    }

    .footer-social a {
        color: rgba(255, 255, 255, 0.7);
        font-size: 16px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .footer-social a:hover {
        color: #cf4d8f;
        transform: translateY(-2px);
    }

    .footer-social .fa-discord:hover {
        color: #7289da;
    }

    .footer-social .fa-twitch:hover {
        color: #9146ff;
    }

    .footer-social .fa-youtube:hover {
        color: #ff0000;
    }

    .footer-social .fa-twitter:hover {
        color: #1da1f2;
    }

    @media (max-width: 768px) {
        footer {
            padding: 15px;
        }

        .footer-social {
            gap: 20px;
        }

        .footer-social a {
            font-size: 14px;
        }
    }
    .SAR {
      width: 14px;
      vertical-align: middle;
      margin-right: 5px;
    } 
  </style>

</head>
<body>

<?php include "include/header_and_nav.php" ?>

<main class="game-page-wrapper">
  <section class="game-detail-container" style="display: flex; gap: 30px; margin: 50px;">
    <div class="media-carousel game-image-container">
      <!-- Game Image -->
      <img src="Media/<?= htmlspecialchars($game['image_url']) ?>" alt="cover">
    </div>

    <div class="game-info">
      <h1 class="game-title"><?= htmlspecialchars($game['title']) ?></h1>
      <p class="game-price">
        <img src="Media/SAR_Symbol-white.png" class="SAR"><?= htmlspecialchars($game['price']) ?>
      </p>
      <p class="game-description">
        <strong>Genre:</strong> <?= htmlspecialchars($game['category']) ?><br>
        <strong>Release Date:</strong> <?= htmlspecialchars($game['release_date']) ?><br><br>
        <strong>Description:</strong><br> <?= nl2br(htmlspecialchars($game['description'])) ?>
      </p>

      <form id="add-to-cart-form" method="post" style="margin-top: 20px;">
        <div class="quantity-control">
          <button type="button" onclick="changeQuantity(-1)">-</button>
          <input type="text" id="quantity" name="quantity" value="1" readonly>
          <button type="button" onclick="changeQuantity(1)">+</button>
        </div>

        <input type="hidden" name="game_id" value="<?= $game_id ?>">
        <input type="hidden" name="add_to_cart" value="1">
        <button type="submit" class="btn add-cart">Add to Cart</button>
      </form>
    </div>
  </section>
</main>

<?php include "include/footer.php" ?>

<script>
function changeQuantity(change) {
  const quantityInput = document.getElementById('quantity');
  let quantity = parseInt(quantityInput.value) || 1;
  quantity += change;
  if (quantity < 1) quantity = 1;
  quantityInput.value = quantity;
}

document.getElementById('add-to-cart-form').addEventListener('submit', function(event) {
  event.preventDefault();
  const formData = new FormData(this);

  fetch(window.location.href, {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.redirect) {
      window.location.href = data.redirect;
    } else if (data.success) {
      if (data.newItem) {
        const cartCount = document.getElementById('cart-count');
        cartCount.innerText = parseInt(cartCount.innerText) + 1;
      }
      window.location.href = 'cart.php';
    }
  })
  .catch(err => {
    console.error(err);
    alert('Error adding to cart.');
  });
});
</script>

</body>
</html>
