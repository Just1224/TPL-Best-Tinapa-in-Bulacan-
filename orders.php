<?php
@include 'includes/config.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all orders
$orders_result = db_query("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC", ['user_id' => $user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - PTL Best Tinapa</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .container-max {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .section-header {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-header h1 {
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .section-header p {
            margin: 10px 0 0 0;
            color: var(--text-color);
        }

        .order-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
            transition: var(--transition);
        }

        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            background: var(--light-color);
            padding: 15px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .order-header-item {
            display: flex;
            flex-direction: column;
        }

        .order-header-label {
            color: var(--text-color);
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }

        .order-header-value {
            color: var(--dark-color);
            font-weight: 600;
            font-size: 1rem;
        }

        .order-body {
            padding: 20px;
        }

        .order-items-list {
            margin-bottom: 15px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: var(--dark-color);
        }

        .item-qty {
            font-size: 0.85rem;
            color: var(--text-color);
        }

        .item-price {
            text-align: right;
            font-weight: 600;
            color: var(--primary-color);
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
            margin-top: 15px;
        }

        .order-total {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .total-label {
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .total-amount {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .order-status {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-shipped {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-delivered {
            background: #c3e6cb;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .payment-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .payment-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #856404;
        }

        .payment-paid {
            background: rgba(39, 174, 96, 0.2);
            color: #155724;
        }

        .view-details-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
        }

        .view-details-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(196, 30, 58, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 8px;
            color: var(--text-color);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--border-color);
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .order-header {
                grid-template-columns: 1fr 1fr;
            }

            .order-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .order-status {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<?php @include 'header.php'; ?>

<div class="container-max">
    <div class="section-header">
        <h1>
            <i class="fas fa-box"></i> My Orders
        </h1>
        <p>Track and manage your orders</p>
    </div>

    <?php
    if(db_num_rows($orders_result) > 0){
        while($order = db_fetch_assoc($orders_result)){
            // Get order items
            $items = db_query("SELECT product_name, quantity, price, subtotal FROM order_items WHERE order_id = :order_id", ['order_id' => $order['id']]);
    ?>
        <div class="order-card">
            <div class="order-header">
                <div class="order-header-item">
                    <span class="order-header-label"><i class="fas fa-hashtag"></i> Order ID</span>
                    <span class="order-header-value"><?php echo htmlspecialchars($order['order_number']); ?></span>
                </div>
                <div class="order-header-item">
                    <span class="order-header-label"><i class="fas fa-calendar"></i> Order Date</span>
                    <span class="order-header-value"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                </div>
                <div class="order-header-item">
                    <span class="order-header-label"><i class="fas fa-money-bill"></i> Total Amount</span>
                    <span class="order-header-value">₱<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
            </div>

            <div class="order-body">
                <div class="order-items-list">
                    <?php
                    while($item = db_fetch_assoc($items)){
                    ?>
                        <div class="order-item">
                            <div class="item-info">
                                <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                <div class="item-qty">Quantity: <?php echo $item['quantity']; ?> × ₱<?php echo number_format($item['price'], 2); ?></div>
                            </div>
                            <div class="item-price">₱<?php echo number_format($item['subtotal'], 2); ?></div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                <div class="order-footer">
                    <div class="order-total">
                        <span class="total-label">Total:</span>
                        <span class="total-amount">₱<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>

                    <div class="order-status">
                        <span class="status-badge status-<?php echo $order['order_status']; ?>">
                            <i class="fas fa-box"></i> <?php echo ucfirst($order['order_status']); ?>
                        </span>
                        <span class="payment-badge payment-<?php echo ($order['payment_status'] === 'paid') ? 'paid' : 'pending'; ?>">
                            <i class="fas fa-credit-card"></i> <?php echo ucfirst($order['payment_status']); ?>
                        </span>
                        <a href="order_confirmation.php?order_id=<?php echo $order['id']; ?>" class="view-details-btn">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php
        }
    } else {
    ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Orders Yet</h3>
            <p>You haven't placed any orders yet.</p>
            <a href="services.php" class="btn" style="display: inline-block; margin-top: 20px;">
                <i class="fas fa-shopping-cart"></i> Start Shopping
            </a>
        </div>
    <?php
    }
    ?>
</div>

<?php @include 'footer.php'; ?>

</body>
</html>
