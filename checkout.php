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
        .checkout-dashboard {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 2rem 1rem;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .dashboard-header h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .dashboard-header p {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .checkout-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255,255,255,0.8);
        }

        .checkout-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .card-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header h2 i {
            font-size: 1.5rem;
            opacity: 0.9;
        }

        .card-content {
            padding: 2.5rem;
        }

        .form-section {
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #667eea;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
            transform: translateY(-1px);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
            line-height: 1.6;
        }

        .form-group input[readonly] {
            background: #f3f4f6;
            cursor: not-allowed;
            border-color: #d1d5db;
        }

        .payment-methods {
            display: grid;
            gap: 1rem;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            position: relative;
            overflow: hidden;
        }

        .payment-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .payment-option:hover::before {
            left: 100%;
        }

        .payment-option:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
        }

        .payment-option.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .payment-option input[type="radio"] {
            margin-right: 1rem;
            width: 20px;
            height: 20px;
            accent-color: #667eea;
        }

        .payment-option label {
            flex: 1;
            margin: 0;
            cursor: pointer;
            font-weight: 600;
            color: #1f2937;
        }

        .payment-option label strong {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .payment-option label small {
            color: #6b7280;
            font-weight: 400;
            font-size: 0.9rem;
        }

        .order-summary-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            position: sticky;
            top: 2rem;
            border: 1px solid rgba(255,255,255,0.8);
        }

        .summary-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .summary-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .summary-content {
            padding: 2rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.2s ease;
        }

        .summary-item:hover {
            background: #f9fafb;
            padding-left: 0.5rem;
            border-radius: 8px;
            margin: 0 -0.5rem;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item span:first-child {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .summary-item span:last-child {
            font-weight: 600;
            color: #1f2937;
        }

        .summary-divider {
            height: 2px;
            background: linear-gradient(90deg, #e5e7eb 0%, #667eea 50%, #e5e7eb 100%);
            margin: 1rem 0;
            border-radius: 1px;
        }

        .summary-total {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 1rem;
        }

        .summary-total .summary-item {
            border: none;
            padding: 0;
            margin: 0;
            background: transparent;
        }

        .summary-total .summary-item span:first-child {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
        }

        .summary-total .summary-item span:last-child {
            font-size: 1.25rem;
            font-weight: 900;
            color: #059669;
        }

        .place-order-btn {
            width: 100%;
            padding: 1.25rem 2rem;
            margin-top: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .place-order-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .place-order-btn:hover::before {
            left: 100%;
        }

        .place-order-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .place-order-btn i {
            margin-right: 0.5rem;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .alert.error {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert.success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .alert i {
            font-size: 1.1rem;
        }

        .payment-details-section {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(-10px);
        }

        .payment-details-section.show {
            opacity: 1;
            transform: translateY(0);
        }

        .payment-method-details {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(-10px);
        }

        .payment-method-details.show {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 1024px) {
            .checkout-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .order-summary-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .checkout-dashboard {
                padding: 1rem;
            }

            .dashboard-header h1 {
                font-size: 2rem;
            }

            .card-content {
                padding: 1.5rem;
            }

            .checkout-grid {
                gap: 1.5rem;
            }

            .place-order-btn {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }

            .payment-details-section {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-header h1 {
                font-size: 1.8rem;
            }

            .card-header {
                padding: 1.5rem;
            }

            .card-header h2 {
                font-size: 1.5rem;
            }

            .card-content {
                padding: 1.25rem;
            }

            .payment-option {
                padding: 1.25rem;
            }

            .place-order-btn {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }

            .payment-details-section {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>

<?php @include 'header.php'; ?>

<div class="checkout-dashboard">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Complete Your Order</h1>
            <p>Secure checkout with multiple payment options available</p>
        </div>

        <div class="checkout-grid">
            <!-- Checkout Form Card -->
            <div class="checkout-card">
                <div class="card-header">
                    <h2><i class="fas fa-shopping-cart"></i> Checkout Details</h2>
                </div>
                <div class="card-content">

                    <?php
                    foreach($message as $msg){
                        if(strpos($msg, 'error:') === 0){
                            echo '<div class="alert error"><i class="fas fa-exclamation-triangle"></i> ' . substr($msg, 6) . '</div>';
                        } elseif(strpos($msg, 'success:') === 0){
                            echo '<div class="alert success"><i class="fas fa-check-circle"></i> ' . substr($msg, 8) . '</div>';
                        }
                    }
                    ?>

                    <form method="POST" action="checkout.php" id="checkoutForm">
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-user"></i>
                                Personal Information
                            </h3>

                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Delivery Address
                            </h3>

                            <div class="form-group">
                                <label for="delivery_address">Street Address</label>
                                <textarea id="delivery_address" name="delivery_address" required><?php echo htmlspecialchars($user_data['address']); ?></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-credit-card"></i>
                                Payment Method
                            </h3>

                            <div class="payment-methods">
                                <div class="payment-option" data-method="cod">
                                    <input type="radio" id="cod" name="payment_method" value="cash_on_delivery" checked>
                                    <label for="cod">
                                        <strong><i class="fas fa-money-bill-wave"></i> Cash on Delivery</strong>
                                        <small>Pay when your order arrives</small>
                                    </label>
                                </div>

                                <div class="payment-option" data-method="gcash">
                                    <input type="radio" id="gcash" name="payment_method" value="gcash">
                                    <label for="gcash">
                                        <strong><i class="fas fa-mobile-alt"></i> GCash</strong>
                                        <small>Send money via GCash mobile app</small>
                                    </label>
                                </div>

                                <div class="payment-option" data-method="maya">
                                    <input type="radio" id="maya" name="payment_method" value="maya">
                                    <label for="maya">
                                        <strong><i class="fas fa-digital-tachograph"></i> Maya</strong>
                                        <small>Maya digital wallet payment</small>
                                    </label>
                                </div>

                                <div class="payment-option" data-method="bank">
                                    <input type="radio" id="bank" name="payment_method" value="bank_transfer">
                                    <label for="bank">
                                        <strong><i class="fas fa-university"></i> Bank Transfer</strong>
                                        <small>BDO, BPI, or other Philippine banks</small>
                                    </label>
                                </div>

                                <div class="payment-option" data-method="installment">
                                    <input type="radio" id="installment" name="payment_method" value="installment">
                                        <label for="installment">
                                            <strong><i class="fas fa-credit-card"></i> Credit Card Installment</strong>
                                            <small>0% interest installment available</small>
                                        </label>
                                </div>
                            </div>

                            <!-- Payment Details Section -->
                            <div id="payment-details" class="payment-details-section" style="display: none; margin-top: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 12px; border: 1px solid #e5e7eb;">
                                <h4 style="color: #1f2937; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-info-circle"></i>
                                    Payment Details
                                </h4>

                                <!-- GCash Details -->
                                <div id="gcash-details" class="payment-method-details" style="display: none;">
                                    <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 2px solid #667eea; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);">
                                        <h5 style="color: #667eea; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-mobile-alt"></i> GCash Payment Information
                                        </h5>
                                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                            <p style="margin: 0; font-weight: 600; color: #1f2937; font-size: 1.1rem;">
                                                <i class="fas fa-phone"></i> GCash Number: 
                                                <span style="background: #667eea; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 700;">0917-123-4567</span>
                                            </p>
                                        </div>
                                        <div style="background: #fff3cd; padding: 1rem; border-radius: 8px; border-left: 4px solid #ffc107;">
                                            <p style="margin: 0; color: #856404; font-weight: 500;">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                <strong>Important:</strong> Send the exact amount and take a screenshot of the confirmation. Send the receipt to <strong>tinapa@example.com</strong> for order confirmation.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Maya Details -->
                                <div id="maya-details" class="payment-method-details" style="display: none;">
                                    <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 2px solid #f59e0b; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.1);">
                                        <h5 style="color: #f59e0b; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-digital-tachograph"></i> Maya Payment Information
                                        </h5>
                                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                            <p style="margin: 0; font-weight: 600; color: #1f2937; font-size: 1.1rem;">
                                                <i class="fas fa-phone"></i> Maya Account: 
                                                <span style="background: #f59e0b; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 700;">0918-123-4567</span>
                                            </p>
                                        </div>
                                        <div style="background: #d1ecf1; padding: 1rem; border-radius: 8px; border-left: 4px solid #17a2b8;">
                                            <p style="margin: 0; color: #0c5460; font-weight: 500;">
                                                <i class="fas fa-gift"></i> 
                                                <strong>Rewards:</strong> Earn points and cashback on your Maya transaction! Send receipt screenshot to <strong>tinapa@example.com</strong>.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bank Transfer Details -->
                                <div id="bank-details" class="payment-method-details" style="display: none;">
                                    <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 2px solid #10b981; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);">
                                        <h5 style="color: #10b981; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-university"></i> Bank Transfer Information
                                        </h5>
                                        <div style="display: grid; gap: 1rem;">
                                            <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px;">
                                                <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #1f2937;">
                                                    <i class="fas fa-landmark"></i> Primary Bank: BDO Unibank
                                                </p>
                                                <p style="margin: 0; color: #6b7280;">
                                                    Account Name: <strong>PTL Best Tinapa</strong><br>
                                                    Account Number: <strong>1234-5678-9012</strong>
                                                </p>
                                            </div>
                                            <div style="background: #e0f2fe; padding: 1rem; border-radius: 8px; border-left: 4px solid #0284c7;">
                                                <p style="margin: 0; color: #0c4a6e; font-weight: 500;">
                                                    <i class="fas fa-info-circle"></i> 
                                                    <strong>Alternative Banks:</strong> You can also transfer from BPI, Metrobank, or any Philippine bank using InstaPay or PesoNet to the same account details.
                                                </p>
                                            </div>
                                            <div style="background: #fef3c7; padding: 1rem; border-radius: 8px; border-left: 4px solid #d97706;">
                                                <p style="margin: 0; color: #92400e; font-weight: 500;">
                                                    <i class="fas fa-clock"></i> 
                                                    <strong>Processing:</strong> Bank transfers take 1-3 hours. Email the bank receipt to <strong>tinapa@example.com</strong> for immediate order confirmation.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Installment Details -->
                                <div id="installment-details" class="payment-method-details" style="display: none;">
                                    <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 2px solid #8b5cf6; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.1);">
                                        <h5 style="color: #8b5cf6; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-credit-card"></i> Credit Card Installment
                                        </h5>
                                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">
                                                <i class="fas fa-percentage"></i> 0% Interest Available
                                            </p>
                                            <p style="margin: 0.5rem 0 0 0; color: #6b7280;">
                                                3-month, 6-month, or 12-month plans available
                                            </p>
                                        </div>
                                        <div style="background: #ecfdf5; padding: 1rem; border-radius: 8px; border-left: 4px solid #10b981;">
                                            <p style="margin: 0; color: #065f46; font-weight: 500;">
                                                <i class="fas fa-phone"></i> 
                                                <strong>Contact Us:</strong> Our team will call you after checkout to arrange the installment payment through your bank's partner merchants.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-sticky-note"></i>
                                Order Notes (Optional)
                            </h3>

                            <div class="form-group">
                                <label for="notes">Special Instructions</label>
                                <textarea id="notes" name="notes" placeholder="Special requests or instructions..."></textarea>
                            </div>
                        </div>

                        <button type="submit" name="place_order" class="place-order-btn" id="placeOrderBtn">
                            <i class="fas fa-check-circle"></i>
                            Place Order
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="order-summary-card">
                <div class="summary-header">
                    <h2><i class="fas fa-receipt"></i> Order Summary</h2>
                </div>
                <div class="summary-content">
                    <?php
                    foreach($items as $item){
                        $subtotal = $item['price'] * $item['quantity'];
                    ?>
                        <div class="summary-item">
                            <span><?php echo htmlspecialchars($item['title']); ?> (x<?php echo $item['quantity']; ?>)</span>
                            <span>₱<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                    <?php
                    }
                    ?>

                    <div class="summary-divider"></div>

                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>₱<?php echo number_format($total, 2); ?></span>
                    </div>

                    <div class="summary-item">
                        <span>Shipping</span>
                        <span>₱<?php echo number_format(50, 2); ?></span>
                    </div>

                    <div class="summary-total">
                        <div class="summary-item">
                            <span>Total</span>
                            <span>₱<?php echo number_format($total + 50, 2); ?></span>
                        </div>
                    </div>
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

// Add smooth animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and enhancement
    const form = document.getElementById('checkoutForm');
    const placeOrderBtn = document.getElementById('placeOrderBtn');

    // Payment method selection handling
    const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
    const paymentDetails = document.getElementById('payment-details');
    const methodDetails = document.querySelectorAll('.payment-method-details');

    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Update visual selection
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            this.closest('.payment-option').classList.add('selected');

            // Show/hide payment details
            const method = this.value;

            // Hide all details first
            methodDetails.forEach(detail => {
                detail.style.display = 'none';
                detail.style.opacity = '0';
                detail.style.transform = 'translateY(-10px)';
            });

            // Show relevant details with animation
            if (method === 'gcash') {
                showPaymentDetails('gcash-details');
            } else if (method === 'maya') {
                showPaymentDetails('maya-details');
            } else if (method === 'bank_transfer') {
                showPaymentDetails('bank-details');
            } else if (method === 'installment') {
                showPaymentDetails('installment-details');
            } else {
                // COD - hide details
                paymentDetails.style.display = 'none';
                paymentDetails.style.opacity = '0';
                paymentDetails.style.transform = 'translateY(-10px)';
            }
        });
    });

    function showPaymentDetails(detailId) {
        const detailElement = document.getElementById(detailId);
        paymentDetails.style.display = 'block';

        setTimeout(() => {
            paymentDetails.style.opacity = '1';
            paymentDetails.style.transform = 'translateY(0)';
            detailElement.style.display = 'block';

            setTimeout(() => {
                detailElement.style.opacity = '1';
                detailElement.style.transform = 'translateY(0)';
            }, 100);
        }, 50);
    }

    // Add loading state to button on submit
    form.addEventListener('submit', function(e) {
        placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        placeOrderBtn.disabled = true;
        placeOrderBtn.style.opacity = '0.7';
    });

    // Payment option hover effects
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('mouseenter', function() {
            if (!this.querySelector('input[type="radio"]').checked) {
                this.style.transform = 'translateY(-2px)';
            }
        });

        option.addEventListener('mouseleave', function() {
            if (!this.querySelector('input[type="radio"]').checked) {
                this.style.transform = 'translateY(0)';
            }
        });
    });

    // Form field focus effects
    document.querySelectorAll('.form-group input, .form-group textarea').forEach(field => {
        field.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-1px)';
        });

        field.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // Smooth scroll to top on page load
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Initialize COD as selected (default)
    document.getElementById('cod').checked = true;
    document.querySelector('[data-method="cod"]').classList.add('selected');
});
</script>

</body>
</html>
