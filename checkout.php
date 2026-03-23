<?php
@include 'includes/config.php';
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    $_SESSION['redirect_to'] = 'checkout.php';
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = [];

// Get user information
$user_stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_data = $user_stmt->get_result()->fetch_assoc();

// Get cart items and calculate total
$select_cart = $conn->prepare("SELECT c.id, c.quantity, s.id as service_id, s.title, s.price FROM cart c 
                               JOIN services s ON c.service_id = s.id 
                               WHERE c.user_id = ?");
$select_cart->bind_param("i", $user_id);
$select_cart->execute();
$cart_result = $select_cart->get_result();

if($cart_result->num_rows == 0){
    header('location: cart.php');
    exit();
}

$total = 0;
$items = [];
while($item = $cart_result->fetch_assoc()){
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $items[] = $item;
}

// Process order
if(isset($_POST['place_order'])){
    $payment_method = $_POST['payment_method'] ?? '';
    $delivery_address = $_POST['delivery_address'] ? trim($_POST['delivery_address']) : $user_data['address'];
    $notes = $_POST['notes'] ? trim($_POST['notes']) : '';
    $total_amount = $total + 50;

    if(empty($payment_method)){
        $message[] = 'error:Please select a payment method';
    } elseif(empty($delivery_address)){
        $message[] = 'error:Please enter a delivery address';
    } else {
        // Create order
        $order_number = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
        
        $insert_order = $conn->prepare("INSERT INTO orders (order_number, user_id, total_amount, payment_method, customer_name, customer_email, customer_phone, delivery_address, notes) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_order->bind_param("sidssssss", $order_number, $user_id, $total_amount, $payment_method, 
                                   $user_data['name'], $user_data['email'], $user_data['phone'], $delivery_address, $notes);
        
        if($insert_order->execute()){
            $order_id = $insert_order->insert_id;

            // Insert order items
            foreach($items as $item){
                $subtotal = $item['price'] * $item['quantity'];
                $insert_item = $conn->prepare("INSERT INTO order_items (order_id, service_id, product_name, quantity, price, subtotal) 
                                              VALUES (?, ?, ?, ?, ?, ?)");
                $insert_item->bind_param("iisssi", $order_id, $item['service_id'], $item['title'], $item['quantity'], $item['price'], $subtotal);
                $insert_item->execute();
            }

            // Clear cart
            $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $clear_cart->bind_param("i", $user_id);
            $clear_cart->execute();

            // Redirect to order confirmation
            header("location: order_confirmation.php?order_id=$order_id");
            exit();
        } else {
            $message[] = 'error:Failed to create order. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - PTL Best Tinapa</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .checkout-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }

        .checkout-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .checkout-section h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        .checkout-section h3 {
            color: var(--dark-color);
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(196, 30, 58, 0.3);
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: var(--transition);
        }

        .payment-option:hover {
            border-color: var(--primary-color);
            background: rgba(196, 30, 58, 0.05);
        }

        .payment-option input[type="radio"] {
            margin-right: 15px;
            width: auto;
            cursor: pointer;
        }

        .payment-option.selected {
            border-color: var(--primary-color);
            background: rgba(196, 30, 58, 0.1);
        }

        .order-summary {
            background: var(--light-color);
            padding: 20px;
            border-radius: 8px;
            position: sticky;
            top: 20px;
        }

        .order-summary h2 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            border: none;
            padding-bottom: 0;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .summary-item span:last-child {
            font-weight: 600;
            color: var(--primary-color);
        }

        .summary-divider {
            border-top: 1px solid var(--border-color);
            margin: 10px 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-top: 10px;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert.error {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        .alert.success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .place-order-btn {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(196, 30, 58, 0.3);
        }

        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }
        }
    </style>
</head>
<body>

<?php @include 'header.php'; ?>

<div class="checkout-container">
    <div class="checkout-grid">
        <div>
            <div class="checkout-section">
                <h2><i class="fas fa-credit-card"></i> Checkout</h2>

                <?php
                foreach($message as $msg){
                    if(strpos($msg, 'error:') === 0){
                        echo '<div class="alert error"><i class="fas fa-exclamation"></i> ' . substr($msg, 6) . '</div>';
                    }
                }
                ?>

                <form method="POST">
                    <h3>Delivery Information</h3>
                    
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" value="<?php echo htmlspecialchars($user_data['name']); ?>" readonly style="background: var(--light-color);">
                    </div>

                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly style="background: var(--light-color);">
                    </div>

                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="tel" value="<?php echo htmlspecialchars($user_data['phone']); ?>" readonly style="background: var(--light-color);">
                    </div>

                    <div class="form-group">
                        <label>Delivery Address *</label>
                        <textarea name="delivery_address" required><?php echo htmlspecialchars($user_data['address']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Order Notes (Optional)</label>
                        <textarea name="notes" placeholder="Special requests or instructions..."></textarea>
                    </div>

                    <h3>Payment Method</h3>
                    
                    <div class="payment-option">
                        <input type="radio" id="cod" name="payment_method" value="cash_on_delivery" checked>
                        <label for="cod" style="flex: 1; margin: 0; cursor: pointer;">
                            <strong><i class="fas fa-money-bill-wave"></i> Cash on Delivery (COD)</strong><br>
                            <small style="color: var(--text-color);">Pay when your order arrives</small>
                        </label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="gcash" name="payment_method" value="gcash">
                        <label for="gcash" style="flex: 1; margin: 0; cursor: pointer;">
                            <strong><i class="fas fa-mobile-alt"></i> GCash</strong><br>
                            <small style="color: var(--text-color);">Send money via GCash mobile app</small>
                        </label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="maya" name="payment_method" value="maya">
                        <label for="maya" style="flex: 1; margin: 0; cursor: pointer;">
                            <strong><i class="fas fa-digital-tachograph"></i> Maya</strong><br>
                            <small style="color: var(--text-color);">Maya digital wallet payment</small>
                        </label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="bank" name="payment_method" value="bank_transfer">
                        <label for="bank" style="flex: 1; margin: 0; cursor: pointer;">
                            <strong><i class="fas fa-university"></i> Bank Transfer</strong><br>
                            <small style="color: var(--text-color);">BDO, BPI, or other Philippine banks</small>
                        </label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="installment" name="payment_method" value="installment">
                        <label for="installment" style="flex: 1; margin: 0; cursor: pointer;">
                            <strong><i class="fas fa-credit-card"></i> Credit Card Installment</strong><br>
                            <small style="color: var(--text-color);">0% interest installment available</small>
                        </label>
                    </div>

                    <button type="submit" name="place_order" class="place-order-btn">
                        <i class="fas fa-check"></i> Place Order
                    </button>
                </form>
            </div>
        </div>

        <div>
            <div class="order-summary">
                <h2><i class="fas fa-receipt"></i> Order Summary</h2>
                
                <?php
                foreach($items as $item){
                    $subtotal = $item['price'] * $item['quantity'];
                ?>
                    <div class="summary-item">
                        <span><?php echo htmlspecialchars($item['title']); ?> x<?php echo $item['quantity']; ?></span>
                        <span>₱<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                <?php
                }
                ?>

                <div class="summary-divider"></div>

                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span>₱<?php echo number_format($total, 2); ?></span>
                </div>

                <div class="summary-item">
                    <span>Shipping:</span>
                    <span>₱50.00</span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total">
                    <span>Total:</span>
                    <span>₱<?php echo number_format($total + 50, 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php @include 'footer.php'; ?>

<script>
// Update payment option styling on selection
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function(){
        document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
        this.closest('.payment-option').classList.add('selected');
    });
    
    if(radio.checked){
        radio.closest('.payment-option').classList.add('selected');
    }
});
</script>

</body>
</html>
