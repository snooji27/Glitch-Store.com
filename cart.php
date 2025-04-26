<?php 
session_start();
require_once ('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=cart.php');
    exit;
}

$database = new $Database();
$database->connect();

if (isset($_POST['remove_all'])) {
    $_SESSION['cart'] = [];
    header('Location: cart.html');
    exit;
}

//if (isset($_POST['remove_games'])){
//    $game_id = $_POST['remove_games'];
//    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($games) use($game_id) {return $games('id') != $game_id};
//});
//header('Location: cart.html');
//exit;
//}

//$subtotal = array_reduce($_SESSION['cart'], function($carry, $games) {
//    return $carry + ($games['price'] * $games['quantity']);
//}, 0);

//$tax = $subtotal * 0.15;
//$total = $subtotal + $tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - GLITCH</title>
    <link rel="stylesheet" href="CSS/header-footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #0a0a0a;
        color: #ebe1de;
        line-height: 1.6;
        padding-bottom: 100px;
    }


    .SAR {
        width: 15px;
        height: auto;
        transition: transform 0.3s ease;
    }

    nav ul {
        display: flex;
        list-style: none;
        gap: 30px;
    }

    nav a {
        color: #ebe1de;
        font-weight: 500;
        position: relative;
        padding: 5px 0;
        text-decoration: none;
    }

    nav a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: #99dfec;
        transition: width 0.3s ease;
    }

    nav a:hover::after {
        width: 100%;
    }

    main.cart {
        max-width: 1000px;
        margin: 100px auto 50px;
        padding: 0 20px;
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .cart-header h2 {
        color: #99dfec;
        font-size: 28px;
        text-align: left;
    }

    .cart-actions {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .remove-all-btn, .wishlist-btn {
        padding: 10px 20px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
        font-size: 14px;
        border: none;
    }

    .remove-all-btn {
        background-color: #cf4d8f;
        color: white;
    }

    .remove-all-btn:hover {
        background-color: #b43d7d;
    }

    .wishlist-btn {
        background-color: #59406b;
        color: white;
        text-decoration: none;
    }

    .wishlist-btn:hover {
        background-color: #4a3557;
    }

    .cart-gamess {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 30px;
    }

    .cart-games {
        display: flex;
        background: rgba(42, 44, 77, 0.7);
        padding: 20px;
        border-radius: 8px;
        gap: 20px;
        align-items: center;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(89, 64, 107, 0.3);
        transition: all 0.3s ease;
    }

    .games-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .games-details {
        flex: 1;
        text-align: left;
    }

    .games-details h3 {
        color: #ebe1de;
        margin-bottom: 10px;
        font-size: 18px;
        text-align: left;
    }

    .games-details p {
        color: #99dfec;
        margin-bottom: 5px;
        font-size: 14px;
        text-align: left;
    }

    .remove-games {
        background: #cf4d8f;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .remove-games:hover {
        background: #b43d7d;
    }

    .cart-summary {
        background: rgba(42, 44, 77, 0.7);
        padding: 25px;
        border-radius: 8px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(89, 64, 107, 0.3);
    }

    .cart-summary h3 {
        color: #99dfec;
        margin-bottom: 15px;
        font-size: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(89, 64, 107, 0.5);
        text-align: left;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        color: #ebe1de;
    }

    .total-row {
        font-weight: bold;
        font-size: 1.1em;
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px solid rgba(89, 64, 107, 0.5);
    }

    .checkout-btn {
        width: 100%;
        padding: 15px;
        background: #59406b;
        color: white;
        border: none;
        border-radius: 5px;
        font-weight: 600;
        margin-top: 20px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 16px;
    }

    .checkout-btn:hover {
        background: #4a3557;
    }

    footer {
        background: rgba(26, 26, 26, 0.9);
        padding: 30px;
        text-align: center;
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    .footer-social {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .footer-social a {
        color: #ebe1de;
        font-size: 24px;
        transition: color 0.3s;
    }

    .footer-social a:hover {
        color: #99dfec;
    }

    footer p {
        color: #ebe1de;
        font-size: 14px;
    }

    .scroll-to-top {
        position: fixed;
        bottom: 90px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #59406b;
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
        z-index: 999;
    }

    .scroll-to-top.visible {
        opacity: 1;
        visibility: visible;
    }

    .scroll-to-top:hover {
        background: #4a3557;
    }

    .empty-cart-message {
        text-align: center;
        padding: 60px 20px;
        background: rgba(42, 44, 77, 0.3);
        border-radius: 8px;
        margin: 20px 0;
    }

    .empty-cart-message i {
        font-size: 48px;
        color: #99dfec;
        margin-bottom: 20px;
    }

    .empty-cart-message h3 {
        color: #ebe1de;
        margin-bottom: 15px;
    }

    .empty-cart-message a {
        color: #99dfec;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
    }

    .empty-cart-message a:hover {
        color: #ebe1de;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        header {
            padding: 15px 20px;
        }

        nav ul {
            gap: 15px;
        }

        .cart-games {
            flex-direction: column;
            align-items: flex-start;
        }

        .games-image {
            width: 100%;
            height: auto;
            max-height: 200px;
        }

        .cart-actions {
            width: 100%;
        }

        .remove-all-btn, .wishlist-btn {
            flex: 1;
            justify-content: center;
        }

        .scroll-to-top {
            bottom: 80px;
            right: 20px;
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        /* Nav and Buttons Wrapper */
    .nav-auth-wrapper {
        display: flex;
        align-items: center;
        gap: 20px; 
        justify-content: center;
        margin: 0 auto;
        }
    }

    @media (max-width: 480px) {
        .cart-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .cart-actions {
            width: 100%;
        }
    }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="Media/icon2.png" alt="Logo" class="header-logo">
        </div>
        <div class="nav-auth-wrapper">
        <nav>
            <ul>
                <li><a href="Homepage.html">HOME</a></li>
                <li><a href="gamestore.html">GAMES</a></li>
                <li><a href="gamesowned.html">GAMES OWNED</a></li>
                <li><a href="Homepage.html/#about">ABOUT US</a></li>
                <li><a href="support.html">SUPPORT</a></li>
            </ul>
        </nav>
    </header>

    <main class="cart">
        <div class="cart-header">
            <h2>Your Cart</h2>
            <div class="cart-actions">
                <form method="post" style="display: inline;">
                    <button type="submit" name="remove_all" class="remove-all-btn" id="removeAllBtn">
                        <i class="fas fa-trash-alt"></i> Remove All
                    </button>
                </form>
                <a href="wishlist.html" class="wishlist-btn">
                    <i class="fas fa-heart"></i> View Wishlist
                </a>
            </div>
        </div>
        
        <div class="cart-gamess" id="cartgamessContainer">
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart-message">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <a href="gamestore.html">Continue Shopping</a>
                </div>
            <?php else: ?>
                <?php foreach ($_SESSION['cart'] as $games): ?>
                    <div class="cart-games">
                        <img src="<?php echo htmlspecialchars($games['image']); ?>" alt="<?php echo htmlspecialchars($games['name']); ?>" class="games-image">
                        <div class="games-details">
                            <h3><?php echo htmlspecialchars($games['name']); ?></h3>
                            <p>Price: <?php echo number_format($games['price'], 2); ?> <img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"></p>
                            <p>Quantity: <?php echo $games['quantity']; ?></p>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="remove_games" value="<?php echo $games['id']; ?>">
                                <button type="submit" class="remove-games">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="cart-summary" id="cartSummary" <?php echo empty($_SESSION['cart']) ? 'style="display: none;"' : ''; ?>>
            <h3>Order Summary</h3>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span><?php echo number_format($subtotal, 2); ?> <img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"></span>
            </div>
            <div class="summary-row">
                <span>Tax:</span>
                <span><?php echo number_format($tax, 2); ?> <img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"></span>
            </div>
            <div class="summary-row total-row">
                <span>Total:</span>
                <span><?php echo number_format($total, 2); ?> <img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"></span>
            </div>
            <button class="checkout-btn" onclick="window.location.href='payment.html'">
                Proceed to Checkout
            </button>
        </div>
    </main>

    <footer id="contact">
        <div class="footer-social">
            <a href="#"><i class="fab fa-discord"></i></a>
            <a href="#"><i class="fab fa-twitch"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
        <p>&copy; 2025 GLITCH Game Store | All Rights Reserved</p>
    </footer>

    <button id="scrollToTop" class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        const scrollToTopBtn = document.getElementById('scrollToTop');
    
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });
        
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

    </script>
</body>
</html>
