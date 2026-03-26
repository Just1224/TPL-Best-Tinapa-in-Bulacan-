<?php
@include 'includes/config.php';
session_start();

if(!isset($_GET['order_id'])){
    header('location: index.php');
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'] ?? 0;

// Get order details
$order_stmt = db_query("SELECT * FROM orders WHERE id = :order_id AND user_id = :user_id", [
    'order_id' => $order_id,
    'user_id' => $user_id,
]);
$order = db_fetch_assoc($order_stmt);

if(!$order){
    header('location: index.php');
    exit();
}

// Get order items
$items = db_query("SELECT * FROM order_items WHERE order_id = :order_id", ['order_id' => $order_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - PTL Best Tinapa</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .confirmation-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .confirmation-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .confirmation-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .confirmation-header i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .confirmation-header h1 {
            color: white;
            margin: 0;
            font-size: 2rem;
        }

        .confirmation-header p {
            margin: 10px 0 0 0;
            opacity: 0.95;
        }

        .confirmation-content {
            padding: 30px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }

        .order-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            padding: 12px;
            background: var(--light-color);
            border-radius: 4px;
        }

        .info-label {
            color: var(--text-color);
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .info-value {
            color: var(--dark-color);
            font-weight: 600;
            font-size: 1rem;
        }

        .order-items {
            background: var(--light-color);
            border-radius: 4px;
            padding: 15px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-name {
            color: var(--dark-color);
            font-weight: 600;
        }

        .item-qty {
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .item-total {
            color: var(--primary-color);
            font-weight: 600;
        }

        .order-total {
            padding: 15px;
            background: rgba(196, 30, 58, 0.1);
            border-radius: 4px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-total strong {
            color: var(--primary-color);
            font-size: 1.3rem;
        }

        .payment-instructions {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .payment-instructions strong {
            color: #856404;
        }

        .payment-instructions p {
            margin: 10px 0;
            color: #856404;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .action-buttons a {
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(196, 30, 58, 0.3);
        }

        .btn-secondary {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-secondary:hover {
            background: var(--light-color);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        @media (max-width: 768px) {
            .order-info {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<?php @include 'header.php'; ?>

<div class="confirmation-container">
    <div class="confirmation-box">
        <div class="confirmation-header">
            <i class="fas fa-check-circle"></i>
            <h1>Order Confirmed!</h1>
            <p>Thank you for your purchase</p>
        </div>

        <div class="confirmation-content">
            <!-- Order Details -->
            <div class="section">
                <h2><i class="fas fa-box"></i> Order Details</h2>
                <div class="order-info">
                    <div class="info-item">
                        <div class="info-label">Order Number</div>
                        <div class="info-value"><?php echo htmlspecialchars($order['order_number']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Order Date</div>
                        <div class="info-value"><?php echo date('M d, Y g:i A', strtotime($order['created_at'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Payment Method</div>
                        <div class="info-value"><?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="status-badge status-pending">
                                <i class="fas fa-clock"></i> <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="section">
                <h2><i class="fas fa-map-marker-alt"></i> Delivery Information</h2>
                <div class="order-info">
                    <div class="info-item">
                        <div class="info-label">Recipient Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Contact Email</div>
                        <div class="info-value"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value"><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                    </div>
                </div>
                <div style="margin-top: 15px; padding: 12px; background: var(--light-color); border-radius: 4px;">
                    <strong>Delivery Address:</strong><br>
                    <?php echo nl2br(htmlspecialchars($order['delivery_address'])); ?>
                </div>
                <?php if($order['notes']){ ?>
                    <div style="margin-top: 15px; padding: 12px; background: var(--light-color); border-radius: 4px;">
                        <strong>Special Notes:</strong><br>
                        <?php echo nl2br(htmlspecialchars($order['notes'])); ?>
                    </div>
                <?php } ?>
            </div>

            <!-- Order Items -->
            <div class="section">
                <h2><i class="fas fa-shopping-bag"></i> Order Items</h2>
                <div class="order-items">
                    <?php
                    $total_items = 0;
                    $total_price = 0;
                    while($item = db_fetch_assoc($items)){
                        $total_items += $item['quantity'];
                        $total_price += $item['subtotal'];
                    ?>
                        <div class="item">
                            <div>
                                <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                <div class="item-qty">Quantity: <?php echo $item['quantity']; ?> × ₱<?php echo number_format($item['price'], 2); ?></div>
                            </div>
                            <div class="item-total">₱<?php echo number_format($item['subtotal'], 2); ?></div>
                        </div>
                    <?php
                    }
                    ?>

                    <div class="order-total">
                        <strong>Total Amount:</strong>
                        <strong>₱<?php echo number_format($order['total_amount'], 2); ?></strong>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <?php if($order['payment_method'] === 'cash_on_delivery'){ ?>
                <div class="payment-instructions">
                    <strong><i class="fas fa-info-circle"></i> Cash on Delivery (COD)</strong>
                    <p>Our delivery personnel will collect payment upon delivery. <strong>Please have the exact amount of ₱<?php echo number_format($order['total_amount'], 2); ?> ready.</strong></p>
                    <p>Delivery typically takes <strong>1-3 business days</strong> in Metro Manila and Bulacan area.</p>
                </div>
            <?php } elseif($order['payment_method'] === 'gcash'){ ?>
                <div class="payment-instructions">
                    <strong><i class="fas fa-mobile-alt"></i> GCash Payment</strong>
                    <p><strong>Amount to Send:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?></p>
                    <p><strong>GCash Number:</strong> <span style="background: #f0f0f0; padding: 5px 10px; border-radius: 4px; font-weight: 600;">09171234567</span></p>
                    <p><strong>Reference/Message:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                    <p>📱 <strong>How to send:</strong></p>
                    <ol style="margin: 10px 0; padding-left: 20px;">
                        <li>Open your GCash app</li>
                        <li>Tap "Send Money"</li>
                        <li>Enter our GCash number</li>
                        <li>Amount: ₱<?php echo number_format($order['total_amount'], 2); ?></li>
                        <li>Include order number in reference</li>
                        <li>Confirm and send</li>
                        <li>Take screenshot as proof</li>
                    </ol>
                    <p><strong>After sending:</strong> Please share the screenshot proof via email (tinapa@example.com) or Facebook Messenger</p>
                </div>
            <?php } elseif($order['payment_method'] === 'maya'){ ?>
                <div class="payment-instructions">
                    <strong><i class="fas fa-digital-tachograph"></i> Maya Payment</strong>
                    <p><strong>Amount to Send:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?></p>
                    <p><strong>Maya Account Number:</strong> <span style="background: #f0f0f0; padding: 5px 10px; border-radius: 4px; font-weight: 600;">09181234567</span></p>
                    <p><strong>Reference/Message:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                    <p>📲 <strong>How to send:</strong></p>
                    <ol style="margin: 10px 0; padding-left: 20px;">
                        <li>Open the Maya app</li>
                        <li>Select "Send Money"</li>
                        <li>Choose "Send to Maya Account"</li>
                        <li>Enter our Maya number</li>
                        <li>Amount: ₱<?php echo number_format($order['total_amount'], 2); ?></li>
                        <li>Add order number in reference</li>
                        <li>Review and confirm payment</li>
                        <li>Take screenshot as proof</li>
                    </ol>
                    <p><strong>After sending:</strong> Send payment proof screenshot to tinapa@example.com</p>
                </div>
            <?php } elseif($order['payment_method'] === 'bank_transfer'){ ?>
                <div class="payment-instructions">
                    <strong><i class="fas fa-university"></i> Bank Transfer</strong>
                    <p><strong>Amount to Transfer:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?></p>
                    <h4 style="margin-top: 15px; margin-bottom: 10px;">Account Details:</h4>
                    <div style="background: #f0f0f0; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                        <p><strong>Bank:</strong> BDO Unibank</p>
                        <p><strong>Account Name:</strong> PTL Best Tinapa</p>
                        <p><strong>Account Number:</strong> <span style="font-weight: 600;">123-456-789-123</span></p>
                    </div>
                    <p><strong>Alternative Banks:</strong> You can also transfer from BPI, Metrobank, or any Philippine bank using InstaPay or PesoNet</p>
                    <p><strong>Transfer Reference:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                    <p><strong>After transfer:</strong> Send the bank receipt/screenshot to tinapa@example.com for order confirmation</p>
                </div>
            <?php } elseif($order['payment_method'] === 'installment'){ ?>
                <div class="payment-instructions">
                    <strong><i class="fas fa-credit-card"></i> Credit Card Installment (0% Interest)</strong>
                    <p><strong>Total Amount:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?></p>
                    <p><strong>Installment Plans Available:</strong></p>
                    <ul style="margin: 10px 0; padding-left: 20px;">
                        <li>3 months: ₱<?php echo number_format($order['total_amount']/3, 2); ?>/month</li>
                        <li>6 months: ₱<?php echo number_format($order['total_amount']/6, 2); ?>/month</li>
                        <li>12 months: ₱<?php echo number_format($order['total_amount']/12, 2); ?>/month</li>
                    </ul>
                    <p><strong>Available Credit Cards:</strong> BDO, BPI, Metabank, Maybank, Citibank, and other major credit cards</p>
                    <p><strong>Next Step:</strong> Our team will contact you at <?php echo htmlspecialchars($order['customer_phone']); ?> to arrange the installment payment details via your bank's partner merchants or online platforms like Shopee, Lazada, or our partner payment gateway.</p>
                </div>
            <?php } ?>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="orders.php" class="action-buttons btn-secondary">
                    <i class="fas fa-list"></i> View My Orders
                </a>
                <a href="index.php" class="action-buttons btn-primary">
                    <i class="fas fa-home"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

<?php @include 'footer.php'; ?>

</body>
</html>
