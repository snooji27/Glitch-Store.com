<?php
session_start();
require_once 'db_connect.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Add game to wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wishlist_game_id'])) {
    $game_id = intval($_POST['wishlist_game_id']);
    $user_id = $_SESSION['user_id'];

    // Check if already in wishlist
    $check = $conn->prepare("SELECT * FROM Wishlist WHERE user_id = ? AND game_id = ?");
    $check->bind_param("ii", $user_id, $game_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows === 0) {
        // Insert into wishlist
        $insert = $conn->prepare("INSERT INTO Wishlist (user_id, game_id) VALUES (?, ?)");
        $insert->bind_param("ii", $user_id, $game_id);
        $insert->execute();
    }

    header("Location: wishlist.php");
    exit();
}


// Handle remove from wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_game_id'])) {
    $game_id = $_POST['remove_game_id'];
    $user_id = $_SESSION['user_id'];
    
    $sql = "DELETE FROM Wishlist WHERE user_id = ? AND game_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $game_id);
    $stmt->execute();
    
    header("Location: wishlist.php");
    exit();
}

// Handle add to cart from wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart_id'])) {
    $game_id = $_POST['add_to_cart_id'];
    $user_id = $_SESSION['user_id'];
    
    // 1. Get or create cart
    $sql = "SELECT cart_id FROM CART WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $sql = "INSERT INTO CART (user_id, total) VALUES (?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cart_id = $conn->insert_id;
    } else {
        $row = $result->fetch_assoc();
        $cart_id = $row['cart_id'];
    }
    
    // 2. Get game price
    $sql = "SELECT price FROM GAME WHERE game_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $game_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $game = $result->fetch_assoc();
    $price = $game['price'];
    
    // 3. Add to cart
    $sql = "INSERT INTO CartItem (cart_id, game_id, unit_price, quantity) 
            VALUES (?, ?, ?, 1)
            ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iid", $cart_id, $game_id, $price);
    $stmt->execute();
    
    header("Location: cart.php");
    exit();
}

// Get wishlist items
$sql = "SELECT w.wishlist_id, g.game_id, g.title, g.price, g.image_url, g.category 
        FROM Wishlist w
        JOIN GAME g ON w.game_id = g.game_id
        WHERE w.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$wishlistItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get cart count
$sql = "SELECT COUNT(ci.cart_item_id) as count
        FROM CART c
        JOIN CartItem ci ON c.cart_id = ci.cart_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$cartCount = $result->fetch_assoc()['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Wishlist - GLITCH</title>
  <link rel="stylesheet" href="CSS/header-footer.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #0a0a0a;
      color: #ebe1de;
      margin: 0;
      padding: 0;
    }
    
    .wishlist-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .wishlist-item {
      background: rgba(42, 44, 77, 0.7);
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      border: 1px solid rgba(89, 64, 107, 0.3);
    }
    
    .wishlist-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }
    
    .wishlist-item img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .item-info {
      padding: 15px;
    }
    
    .item-info h3 {
      margin: 0 0 10px;
      color: #ffffff;
    }
    
    .item-info .category {
      color: #99dfec;
      font-size: 0.9em;
      margin-bottom: 10px;
    }
    
    .item-info .price {
      color: #cf4d8f;
      font-weight: bold;
      font-size: 1.1em;
    }
    
    .item-actions {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
    }
    
    .remove-btn {
      background: #ff4d4d;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s;
    }
    
    .remove-btn:hover {
      background: #ff3333;
    }
    
    .add-to-cart {
      background: #4CAF50;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s;
    }
    
    .add-to-cart:hover {
      background: #45a049;
    }
    
    .empty-wishlist {
      text-align: center;
      padding: 50px;
      font-size: 1.2em;
      color: #99dfec;
      grid-column: 1 / -1;
    }
    
    .wishlist-header {
      text-align: center;
      margin: 20px 0;
      color: #ffffff;
      padding-top: 80px;
    }
    
    @media (max-width: 768px) {
      .wishlist-container {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      }
    }
  </style>
</head>
<body>
  <?php include "include/header_and_nav.php" ?>

  <main>
    <h1 class="wishlist-header">Your Wishlist</h1>
    <div class="wishlist-container">
      <?php if (empty($wishlistItems)): ?>
        <p class="empty-wishlist">Your wishlist is currently empty. Start adding games!</p>
      <?php else: ?>
        <?php foreach ($wishlistItems as $item): ?>
          <div class="wishlist-item">
            <img src="Media/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
            <div class="item-info">
              <h3><?php echo htmlspecialchars($item['title']); ?></h3>
              <p class="category"><?php echo htmlspecialchars($item['category']); ?></p>
              <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
              <div class="item-actions">
                <form method="POST">
                  <input type="hidden" name="remove_game_id" value="<?php echo $item['game_id']; ?>">
                  <button type="submit" class="remove-btn">Remove</button>
                </form>
                <form method="POST">
                  <input type="hidden" name="add_to_cart_id" value="<?php echo $item['game_id']; ?>">
                  <button type="submit" class="add-to-cart">Add to Cart</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>

  <?php include "include/footer.php" ?>
</body>
</html>