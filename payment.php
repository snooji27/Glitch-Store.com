<?php
session_start();
require_once 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get cart total only
$user_id = $_SESSION['user_id'];
$total = 0;

$sql = "SELECT SUM(ci.unit_price * ci.quantity) as total
        FROM CART c
        JOIN CartItem ci ON c.cart_id = ci.cart_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $subtotal = $row['total'] ?? 0;
    $tax = $subtotal * 0.15;
    $total = $subtotal + $tax;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - GLITCH</title>
    <link rel="stylesheet" href="CSS/header-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a;
            color: #ebe1de;
            padding: 80px 0 60px;
        }
        
        .payment-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .payment-box {
            background-color: #2a2c4d;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .total-amount {
            font-size: 24px;
            color: #99dfec;
            margin: 20px 0;
            text-align: center;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: 600;
            cursor: pointer;
        }
        
        .pay-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        
        .back-btn {
            background-color: #59406b;
            color: white;
            text-decoration: none;
        }
        
        .SAR {
            width: 16px;
            vertical-align: middle;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <?php include 'include/header_and_nav.php'; ?>

    <main class="payment-container">
        <div class="payment-box">
            <h2>Complete Your Purchase</h2>
            
            <div class="total-amount">
                Total: <?php echo number_format($total, 2); ?> 
                <img src="Media/SAR_Symbol-white.png" alt="SAR" class="SAR">
            </div>
            
            <!-- Simple payment confirmation -->
            <form action="payment-confirm.php" method="POST">
                <input type="hidden" name="amount" value="<?php echo $total; ?>">
                <button type="submit" class="btn pay-btn">
                    <i class="fas fa-lock"></i> Confirm Payment
                </button>
            </form>
            
            <a href="cart.php" class="btn back-btn">
                <i class="fas fa-arrow-left"></i> Back to Cart
            </a>
        </div>
    </main>

    <?php include 'include/footer.php'; ?>
</body>
</html>