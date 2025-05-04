<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get cart count if user is logged in
$cartCount = 0;
if (isset($_SESSION['user_id']) && file_exists('db_connect.php')) {
    require_once 'db_connect.php';
    
    $sql = "SELECT COUNT(ci.cart_item_id) as count
            FROM CART c
            JOIN CartItem ci ON c.cart_id = ci.cart_id
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cartCount = $row['count'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GLITCH Game Store</title>
    <link rel="stylesheet" href="CSS/header-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Header Styles */
        header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 40px;
            background: rgba(26, 26, 26, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            height: 60px;
        }

        /* Logo Styling */
        .logo-container {
            position: absolute;
            left: 40px;
            display: flex;
            align-items: center;
        }

        .header-logo {
            width: 60px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .header-logo:hover {
            transform: rotate(10deg);
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 40px;
            padding: 0;
            margin: 0;
            justify-content: center;
        }

        nav ul li {
            display: inline;
            position: relative;
        }

        nav ul li a {
            text-decoration: none;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            padding: 6px 0;
        }

        /* Dropdown Menu */
        nav ul li ul.gamesdropdown {
            width: 200px;
            background: rgba(26, 26, 26, 0.9);
            position: absolute;
            left: 0;
            top: 100%;
            z-index: 999;
            display: none;
            padding: 10px 0;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            animation: fadeIn 0.3s ease forwards;
        }

        nav ul li:hover ul.gamesdropdown {
            display: block;
        }

        nav ul li ul.gamesdropdown li {
            display: block;
            padding: 8px 20px;
            text-align: left;
        }

        nav ul li ul.gamesdropdown li a {
            text-transform: none;
            padding: 8px 15px;
            white-space: nowrap;
            display: block;
        }

        nav ul li ul.gamesdropdown li:hover {
            background: rgba(255,255,255,0.1);
        }

        /* Account Dropdown Container */
        .acc-container {
            position: absolute;
            right: 80px;
            display: flex;
            align-items: center;
            z-index: 1000;
            padding-bottom: 10px; /* Creates hover overlap area */
        }

        /* Profile Button */
        .account-btn {
            position: relative;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .account-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(207, 77, 143, 0.5);
        }

        /* Account Dropdown Menu */
        .accountdropdown {
            width: 200px;
            background: rgba(26, 26, 26, 0.95);
            position: absolute;
            right: 0;
            top: calc(100% - 10px); /* Adjust to overlap the padding */
            z-index: 999;
            display: none;
            padding: 10px 0;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            animation: fadeIn 0.3s ease forwards;
        }

        .acc-container:hover .accountdropdown {
            display: block;
        }

        .accountdropdown li {
            display: block;
            padding: 8px 20px;
            text-align: left;
        }

        .accountdropdown li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            display: block;
            transition: all 0.2s ease;
            padding: 8px 15px;
        }

        .accountdropdown li:hover {
            background: rgba(255,255,255,0.1);
        }

        .accountdropdown li:hover a {
            color: #cf4d8f;
            padding-left: 20px;
        }

        /* Login/Signup Buttons */
        .login-btn, .signup-btn {
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .login-btn {
            background-color: transparent;
            border: 1px solid #99dfec;
            color: #99dfec;
        }

        .login-btn:hover {
            background-color: rgba(153, 223, 236, 0.1);
        }

        .signup-btn {
            background-color: #cf4d8f;
            color: white;
            border: none;
        }

        .signup-btn:hover {
            background-color: #b43d7d;
        }

        /* Cart Icon */
        .cart-icon {
            position: relative;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        #cart-count {
            background-color: #cf4d8f;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Dropdown Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            header {
                padding: 10px;
            }

            .header-logo {
                width: 50px;
            }

            nav ul {
                gap: 10px;
            }

            nav ul li a {
                font-size: 12px;
            }

            .acc-container {
                right: 60px;
            }

            .login-btn, .signup-btn {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <a href="homepage.html"><img src="Media/icon2.png" alt="Logo" class="header-logo"></a>
    </div>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Logged In State - With Account Dropdown -->
        <div class="acc-container">
            <img src="Media/default-profile.png" alt="Account" class="account-btn">
            <ul class="accountdropdown">
                <li><a href="userprofile.html">View account</a></li>
                <li><a href="edit-profile.html">Edit account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="nav-auth-wrapper">
        <nav>
            <ul>
                <li>
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cartCount > 0): ?>
                            <span id="cart-count"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li><a href="homepage.html">Home</a></li>
                <li>
                    <a href="gamestore.php">Games</a>
                    <ul class="gamesdropdown">
                        <li><a href="gamesowned.php">Games Owned</a></li>
                        <li><a href="wishlist.php">Wishlist</a></li>
                    </ul>
                </li>
                <li><a href="homepage.html#about">About Us</a></li>
                <li><a href="support.html">Contact</a></li>
                
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <!-- Not Logged In State - Show Login/Signup -->
                    <li><a href="Login_Signup/selection.html" class="login-btn">Login</a></li>
                    <li><a href="Login_Signup/selection.html" class="signup-btn">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
</body>
</html>