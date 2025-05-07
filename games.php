<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $game_id = $_GET['id'];
    $query = "SELECT * FROM GAME WHERE game_id = $game_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $game = mysqli_fetch_assoc($result);
    } else {
        echo "Game not found.";
        exit;
    }
} else {
    echo "Invalid game ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $game['title'] ?> - Game Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .game-details {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            border: 1px solid #ccc;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .game-title {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .game-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }
        .game-description {
            margin-top: 1rem;
        }
        .stock-info {
            margin-top: 1rem;
            font-weight: bold;
        }
        .quantity-section {
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .quantity-input {
            width: 60px;
            padding: 0.3rem;
        }
        .add-to-cart-btn {
            padding: 0.5rem 1rem;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .add-to-cart-btn:hover {
            background-color: #218838;
        }
        .help-button {
            margin-left: 10px;
            cursor: pointer;
            color: #007bff;
            font-size: 1.2rem;
        }
        .help-popup {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            top: 60px;
            right: 20px;
            z-index: 100;
        }
    </style>
</head>
<body>

<div class="game-details">
    <h1 class="game-title">
        <?= $game['title'] ?>
        <span id="help-button" class="help-button" title="Click for help">?</span>
    </h1>
    <img src="<?= $game['image_url'] ?>" alt="<?= $game['title'] ?>" class="game-image">
    <p class="game-description"><?= $game['description'] ?></p>
    <p class="stock-info">Stock: <?= $game['stock'] ?></p>

    <div class="quantity-section">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" class="quantity-input" min="1" max="<?= $game['stock'] ?>" value="1">
        <button class="add-to-cart-btn" onclick="addToCart(<?= $game['game_id'] ?>, <?= $game['stock'] ?>)">Add to Cart</button>
    </div>
</div>

<div class="help-popup" id="help-popup">
    Select the quantity (up to available stock) and click "Add to Cart".
</div>

<script>
    function addToCart(gameId, maxStock) {
        const quantity = parseInt(document.getElementById('quantity').value);

        if (isNaN(quantity) || quantity < 1) {
            alert("Please enter a valid quantity.");
            return;
        }

        if (quantity > maxStock) {
            alert("Quantity exceeds available stock.");
            return;
        }

        // Simulate adding to cart (replace with actual functionality)
        alert("Added " + quantity + " item(s) of game ID " + gameId + " to cart.");
    }

    document.getElementById('help-button').addEventListener('click', function() {
        const popup = document.getElementById('help-popup');
        popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function(event) {
        const helpPopup = document.getElementById('help-popup');
        const helpButton = document.getElementById('help-button');
        if (!helpPopup.contains(event.target) && !helpButton.contains(event.target)) {
            helpPopup.style.display = 'none';
        }
    });
</script>

</body>
</html>
