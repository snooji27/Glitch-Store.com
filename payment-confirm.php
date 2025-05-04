<?php
session_start();
require_once 'db_connect.php';

// Check if order_id is provided
if (!isset($_GET['order_id']) || !isset($_SESSION['user_id'])) {
    header('Location: Homepage.html');
    exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Get order details
$order_sql = "SELECT o.order_id, o.total_price, o.tax, o.order_date, 
                     p.First, p.Last, p.cardNum, p.PhoneNum, p.City, p.Country, p.ZIP
              FROM ORDER o
              JOIN Payment p ON o.payment_id = p.payment_id
              WHERE o.order_id = ? AND o.user_id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    header('Location: Homepage.html');
    exit;
}

$order = $order_result->fetch_assoc();
$subtotal = $order['total_price'] - $order['tax'];

// Get order items
$items_sql = "SELECT g.title, g.image_url, oi.price_at_purchase, oi.quantity
              FROM OrderItem oi
              JOIN GAME g ON oi.game_id = g.game_id
              WHERE oi.order_id = ?";
$stmt = $conn->prepare($items_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Format card number for display
$card_last4 = substr($order['cardNum'], -4);
$card_type = (str_starts_with($order['cardNum'], '4')) ? 'Visa' : 'Mastercard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment - GLITCH</title>
    <link rel="stylesheet" href="CSS/header-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a;
            color: #ffffff;
            padding-top: 80px;
            min-height: 100vh;
            padding-bottom: 60px;
        }
        /* Main Container */
        .confirm-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #ebe1de;
            padding-bottom: 100px;
        }
        
        /* Typography */
        .page-title {
            text-align: left;
            margin-bottom: 30px;
            color: #99dfec;
            font-size: 28px;
        }

        .section-title {
            text-align: left;
            margin: 0 0 20px 0;
            color: #99dfec;
            font-size: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #59406b;
        }

        /* Order Summary */
        .order-summary {
            background-color: #2a2c4d;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .order-items {
            margin: 20px 0;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #3a3c5d;
            gap: 20px;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 5px;
            object-fit: cover;
            border: 1px solid #59406b;
        }
        
        .SAR {
            width: 15px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .item-details {
            flex-grow: 1;
            text-align: left;
        }

        .game-title {
            margin: 0 0 5px 0;
            color: #ffffff;
            font-size: 16px;
        }

        .game-quantity {
            margin: 0;
            color: #99dfec;
            font-size: 14px;
        }

        .item-price {
            font-weight: bold;
            min-width: 70px;
            text-align: right;
        }

        /* Order Totals */
        .order-totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #59406b;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .total-label {
            color: #ebe1de;
        }

        .total-value {
            color: #ffffff;
            font-weight: 500;
        }

        .grand-total {
            font-weight: bold;
            font-size: 1.1em;
            margin-top: 15px;
        }

        /* Payment Method */
        .payment-method {
            background-color: #2a2c4d;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .method-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #1e1f3a;
            border-radius: 5px;
            margin-top: 15px;
        }

        .payment-type {
            color: #ebe1de;
        }

        .change-method {
            color: #99dfec;
            text-decoration: none;
        }

        .change-method:hover {
            text-decoration: underline;
        }

        /* Confirmation */
        .confirmation {
            background-color: #2a2c4d;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .order-success {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
            font-size: 18px;
        }

        .order-number {
            text-align: center;
            margin-bottom: 20px;
            color: #ebe1de;
        }

        .order-date {
            text-align: center;
            margin-bottom: 20px;
            color: #99dfec;
        }

        .continue-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s;
            display: block;
            text-align: center;
            text-decoration: none;
        }

        .continue-btn:hover {
            background-color: #45a049;
        }

        /* Billing Info */
        .billing-info {
            background-color: #2a2c4d;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .billing-details {
            padding: 15px;
            background-color: #1e1f3a;
            border-radius: 5px;
            margin-top: 15px;
        }

        .billing-row {
            margin-bottom: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .confirm-container {
                padding: 15px;
            }

            .order-summary, 
            .payment-method, 
            .confirmation,
            .billing-info {
                padding: 15px;
            }

            .order-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .item-price {
                text-align: left;
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 24px;
            }

            .section-title {
                font-size: 18px;
            }

            .item-image {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header_and_nav.php'; ?>

    <!-- Main Content -->
    <main class="confirm-container">
        <h2 class="page-title">Order Confirmation</h2>
        
        <div class="order-success">
            <i class="fas fa-check-circle"></i> Thank you for your purchase!
        </div>
        
        <div class="order-number">
            Order #<?php echo htmlspecialchars($order_id); ?>
        </div>
        
        <div class="order-date">
            <?php echo date('F j, Y', strtotime($order['order_date'])); ?>
        </div>
        
        <!-- Order Summary Section -->
        <section class="order-summary">
            <h3 class="section-title">Items Purchased</h3>
            <div class="order-items">
                <?php foreach ($items as $item): ?>
                    <article class="order-item">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="item-image">
                        <div class="item-details">
                            <h4 class="game-title"><?php echo htmlspecialchars($item['title']); ?></h4>
                            <p class="game-quantity"><?php echo $item['quantity']; ?> × <img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"> <?php echo number_format($item['price_at_purchase'], 2); ?></p>
                        </div>
                        <p class="item-price"><img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"> <?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <!-- Order Totals -->
            <div class="order-totals">
                <div class="total-row">
                    <span class="total-label">Subtotal:</span>
                    <span class="total-value"><img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"> <?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="total-row">
                    <span class="total-label">Tax:</span>
                    <span class="total-value"><img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"> <?php echo number_format($order['tax'], 2); ?></span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">Total:</span>
                    <span class="total-value"><img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"> <?php echo number_format($order['total_price'], 2); ?></span>
                </div>
            </div>
        </section>
        
        <!-- Payment Method Section -->
        <section class="payment-method">
            <h3 class="section-title">Payment Method</h3>
            <div class="method-details">
                <span class="payment-type"><?php echo htmlspecialchars($card_type); ?> ending in <?php echo htmlspecialchars($card_last4); ?></span>
            </div>
        </section>
        
        <!-- Billing Information -->
        <section class="billing-info">
            <h3 class="section-title">Billing Information</h3>
            <div class="billing-details">
                <div class="billing-row">
                    <strong>Name:</strong> <?php echo htmlspecialchars($order['First'] . ' ' . $order['Last']); ?>
                </div>
                <div class="billing-row">
                    <strong>Phone:</strong> <?php echo htmlspecialchars($order['PhoneNum']); ?>
                </div>
                <div class="billing-row">
                    <strong>Address:</strong> <?php echo htmlspecialchars($order['City'] . ', ' . $order['Country'] . ' ' . $order['ZIP']); ?>
                </div>
            </div>
        </section>
        
        <!-- Confirmation Section -->
        <section class="confirmation">
            <p>Your games have been added to your <a href="gamesowned.php" style="color: #99dfec;">Games Owned</a> library.</p>
            <p>An order confirmation has been sent to your email.</p>
            <a href="gamestore.php" class="continue-btn">Continue Shopping</a>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>