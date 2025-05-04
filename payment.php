<?php
session_start();
require_once 'db_connect.php';

// Redirect if not logged in or cart is empty
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get cart items and total
$cart_items = [];
$subtotal = 0;
$tax = 0;
$total = 0;
$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT g.game_id, g.title, g.price, g.image_url, ci.quantity, ci.unit_price
        FROM CART c
        JOIN CartItem ci ON c.cart_id = ci.cart_id
        JOIN GAME g ON ci.game_id = g.game_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Calculate totals
foreach ($cart_items as $item) {
    $subtotal += $item['unit_price'] * $item['quantity'];
}
$tax = $subtotal * 0.15;
$total = $subtotal + $tax;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process payment and save information
    $save_info = isset($_POST['save-payment']) ? 1 : 0;
    $phone = $_POST['phone'];
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $zip = $_POST['zip'];
    $card_number = $_POST['card-number'];
    $exp_month = $_POST['exp-month'];
    $exp_year = $_POST['exp-year'];
    $cvv = $_POST['security-code'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // 1. Create payment record
        $sql = "INSERT INTO Payment (SaveInfo, PhoneNum, First, Last, City, Country, ZIP, cardNum)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssss", $save_info, $phone, $first_name, $last_name, $city, $country, $zip, $card_number);
        $stmt->execute();
        $payment_id = $conn->insert_id;
        
        // 2. Create payment method if saving
        if ($save_info) {
            $expiry_date = date('Y-m-d', strtotime("20$exp_year-$exp_month-01"));
            $sql = "INSERT INTO PaymentMethod (cardNum, First, Last, cvv, Expiry_Date)
                    VALUES (?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE cvv = VALUES(cvv), Expiry_Date = VALUES(Expiry_Date)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssis", $card_number, $first_name, $last_name, $cvv, $expiry_date);
            $stmt->execute();
        }
        
        // 3. Create order
        $sql = "INSERT INTO ORDER (user_id, total_price, order_date, tax, payment_status)
                VALUES (?, ?, NOW(), ?, 'Paid')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idd", $user_id, $total, $tax);
        $stmt->execute();
        $order_id = $conn->insert_id;
        
        // 4. Add order items
        foreach ($cart_items as $item) {
            $sql = "INSERT INTO OrderItem (order_id, game_id, price_at_purchase, quantity)
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iidi", $order_id, $item['game_id'], $item['unit_price'], $item['quantity']);
            $stmt->execute();
            
            // Add to game ownership
            $sql = "INSERT INTO GameOwnership (user_id, game_id, purchase_date)
                    VALUES (?, ?, NOW())
                    ON DUPLICATE KEY UPDATE purchase_date = NOW()";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $item['game_id']);
            $stmt->execute();
        }
        
        // 5. Clear cart
        $sql = "DELETE ci FROM CartItem ci
                JOIN CART c ON ci.cart_id = c.cart_id
                WHERE c.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        $sql = "UPDATE CART SET total = 0 WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        // Redirect to confirmation
        header("Location: payment-confirm.php?order_id=$order_id");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Payment processing failed: " . $e->getMessage();
    }
}

// Get user info if available
$user_info = [];
$sql = "SELECT email, username FROM USER WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_info = $result->fetch_assoc();
}

// Get saved payment info if available
$saved_payment = [];
$sql = "SELECT p.First, p.Last, p.PhoneNum, p.City, p.Country, p.ZIP, 
               pm.cardNum, pm.cvv, pm.Expiry_Date
        FROM Payment p
        JOIN PaymentMethod pm ON p.cardNum = pm.cardNum
        JOIN ORDER o ON o.user_id = ?
        WHERE p.payment_id = (
            SELECT MAX(payment_id) FROM Payment WHERE First = p.First AND Last = p.Last
        ) LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $saved_payment = $result->fetch_assoc();
    if ($saved_payment['Expiry_Date']) {
        $expiry = date_create($saved_payment['Expiry_Date']);
        $saved_payment['exp_month'] = date_format($expiry, 'm');
        $saved_payment['exp_year'] = date_format($expiry, 'y');
    }
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
       /* General Styles */
       body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-image: url('../Glitch-Store.com/Media/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            color: #ffffff;
            text-align: center;
            padding-top: 80px;
            padding-bottom: 60px;
        }
        /* Payment Page Styles */
        .payment-container {
            max-width: 800px;
            margin: 20px auto 80px;
            padding: 20px;
            color: #ebe1de;
            text-align: left;
        }

        .section-title {
            color: #fff !important;
            margin: 25px 0 15px;   
            font-size: 1.1rem;     
            font-weight: 600;       
            letter-spacing: 0.5px;  
        }

        .payment-method-text {
            font-size: 18px;
            margin-bottom: 25px;
            color: #99dfec;
        }


        .payment-form {
        background-color: #2a2c4d;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-group {
        margin-bottom: 20px;
        }

        .form-row {
        display: flex;
        gap: 15px;
        }

        .form-row .form-group {
        flex: 1;
        }

        label {
        display: block;
        margin-bottom: 8px;
        color: #99dfec;
        }

        .form-control {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #59406b;
        background-color: #1e1f3a;
        color: #ebe1de;
        }

        .SAR {
            width: 15px;
            height: auto;
            vertical-align: middle;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input {
            width: auto;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            margin-left: 5px;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #1e1f3a;
            color: #ebe1de;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .back-btn, .continue-btn {
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }

        .back-btn {
            background-color: #2a2c4d;
            color: #99dfec;
            border: 1px solid #59406b;
        }

        .continue-btn {
            background-color: #59406b;
            color: #ebe1de;
            border: none;
        }

        /* Footer */
        footer {
            width: 100%;
            background: rgba(26, 26, 26, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            text-align: center;
            padding: 15px;
            position: fixed;
            bottom: 0;
            left: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        footer a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            margin: 0 15px;
            transition: opacity 0.3s ease;
        }

        
        .acc-container {
            position: absolute;
            right: 80px;
            display: flex;
            align-items: center;
        }

        .account-btn {
            width: 45px;
            height: 45px;
            transition: transform 0.3s ease;
        }

        .account-btn:hover {
            transform: rotate(10deg);
        }

        /* Responsive */
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

            .form-row {
                flex-direction: column;
                gap: 15px;
            }
        }
        
/* Dropdown Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
        .error-message {
            color: #ff4d4d;
            background: rgba(255, 77, 77, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include 'header_and_nav.php'; ?>

    <main class="payment-container">
        <h2>Payment Information</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <p class="payment-method-text">Please complete your payment for <?php echo number_format($total, 2); ?> <img src="Media/SAR_Symbol-white.png" alt="SAR currency logo" class="SAR"> :</p>
        
        <form method="POST" class="payment-form">
            <div class="form-group">
                <label for="payment-method">Payment Method</label>
                <select id="payment-method" name="payment-method" class="form-control" required>
                    <option value="visa">Visa</option>
                    <option value="mastercard">Mastercard</option>
                </select>
            </div>

            <div class="form-group">
                <label for="card-number">Card Number</label>
                <input type="text" id="card-number" name="card-number" class="form-control" 
                       placeholder="1234 5678 9012 3456" pattern="\d{16}" 
                       value="<?php echo isset($saved_payment['cardNum']) ? substr($saved_payment['cardNum'], 0, 16) : ''; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="exp-month">Expiration Month</label>
                    <input type="text" id="exp-month" name="exp-month" class="form-control" 
                           placeholder="MM" style="width: 60px;" pattern="\d{2}" 
                           value="<?php echo $saved_payment['exp_month'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="exp-year">Expiration Year</label>
                    <input type="text" id="exp-year" name="exp-year" class="form-control" 
                           placeholder="YY" style="width: 70px;" pattern="\d{2}" 
                           value="<?php echo $saved_payment['exp_year'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="security-code">Security Code 
                        <span class="tooltip">
                            <i class="fas fa-question-circle"></i>
                            <span class="tooltip-text">This number is located on the back of the card in the signature area. It's the last three digits (after the account number).</span>
                        </span>
                    </label>
                    <input type="text" id="security-code" name="security-code" class="form-control" 
                           placeholder="123" style="width: 60px;" pattern="\d{3}" 
                           value="<?php echo $saved_payment['cvv'] ?? ''; ?>" required>
                </div>
            </div>

            <h3 class="section-title">BILLING INFORMATION</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first-name" class="form-control" 
                           value="<?php echo $saved_payment['First'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last-name" class="form-control" 
                           value="<?php echo $saved_payment['Last'] ?? ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Billing Address</label>
                <input type="text" id="address" name="address" class="form-control" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" 
                           value="<?php echo $saved_payment['City'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="zip">Zip/Postal Code</label>
                    <input type="text" id="zip" name="zip" class="form-control" 
                           value="<?php echo $saved_payment['ZIP'] ?? ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" class="form-control" 
                       value="<?php echo $saved_payment['Country'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" 
                       value="<?php echo $saved_payment['PhoneNum'] ?? ''; ?>" required>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="save-payment" name="save-payment">
                <label for="save-payment">Save my payment information so checkout is easy next time</label>
            </div>

            <p class="order-review-text">You'll have a chance to review your order before it's placed.</p>

            <div class="btn-group">
                <a href="cart.php" class="back-btn">Back</a>
                <button type="submit" class="continue-btn">Complete Payment</button>
            </div>
        </form>
    </main>

    <footer class="footer">
        <p><a href="#">Contact Us</a> | <a href="#">Support</a></p>
    </footer>
</body>
</html>