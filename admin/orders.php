<?php
@include '../includes/config.php';
@include 'includes/auth.php';

// Update order status
if(isset($_POST['update_status'])){
    $order_id = intval($_POST['order_id']);
    $order_status = $_POST['order_status'];
    $payment_status = $_POST['payment_status'] ?? 'pending';

    $update = $conn->prepare("UPDATE orders SET order_status = ?, payment_status = ? WHERE id = ?");
    $update->bind_param("ssi", $order_status, $payment_status, $order_id);
    $update->execute();
}

// Delete order
if(isset($_GET['delete'])){
    $order_id = intval($_GET['delete']);
    $delete = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $delete->bind_param("i", $order_id);
    $delete->execute();
    
    $delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $delete_order->bind_param("i", $order_id);
    $delete_order->execute();
}

// Get all orders
$select_orders = $conn->prepare("SELECT o.*, u.email as user_email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$select_orders->execute();
$orders_result = $select_orders->get_result();

// Get statistics
$stats = [];
$stats_stmt = $conn->prepare("SELECT 
    COUNT(*) as total_orders,
    SUM(total_amount) as total_revenue,
    SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as completed_orders,
    SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as pending_payments
FROM orders");
$stats_stmt->execute();
$stats = $stats_stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Orders Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: var(--light-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-container {
            flex: 1;
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .admin-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .admin-section h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-box i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.95;
            margin-top: 5px;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .orders-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .orders-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border: 1px solid var(--border-color);
        }

        .orders-table td {
            padding: 12px 15px;
            border: 1px solid var(--border-color);
        }

        .orders-table tbody tr:hover {
            background-color: rgba(196, 30, 58, 0.05);
        }

        .order-number {
            font-weight: 600;
            color: var(--primary-color);
        }

        .customer-info {
            font-size: 0.9rem;
        }

        .amount {
            font-weight: 600;
            color: var(--primary-color);
        }

        .status-select {
            padding: 6px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-shipped {
            background: #cce5ff;
            color: #004085;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .payment-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #856404;
        }

        .payment-paid {
            background: rgba(39, 174, 96, 0.2);
            color: #155724;
        }

        .action-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 0.9rem;
            padding: 4px 8px;
            margin: 0 2px;
            transition: var(--transition);
        }

        .action-btn:hover {
            color: var(--secondary-color);
        }

        .delete-btn {
            color: var(--danger-color);
        }

        .delete-btn:hover {
            color: #c92a2a;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
        }

        .modal-header {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .modal-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-close {
            background: var(--light-color);
            color: var(--dark-color);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-update {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .orders-table {
                font-size: 0.9rem;
            }

            .orders-table th,
            .orders-table td {
                padding: 10px;
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .orders-table {
                font-size: 0.85rem;
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

<?php @include 'header_admin.php'; ?>

<div class="admin-container">
    <div class="admin-section">
        <h2><i class="fas fa-box"></i> Orders Management</h2>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-box">
                <i class="fas fa-shopping-bag"></i>
                <div class="stat-value"><?php echo $stats['total_orders'] ?? 0; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-money-bill-wave"></i>
                <div class="stat-value">₱<?php echo number_format($stats['total_revenue'] ?? 0, 0); ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-check-circle"></i>
                <div class="stat-value"><?php echo $stats['completed_orders'] ?? 0; ?></div>
                <div class="stat-label">Completed Orders</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-clock"></i>
                <div class="stat-value"><?php echo $stats['pending_payments'] ?? 0; ?></div>
                <div class="stat-label">Pending Payments</div>
            </div>
        </div>

        <!-- Orders Table -->
        <?php if($orders_result->num_rows > 0){ ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($order = $orders_result->fetch_assoc()){
                    ?>
                    <tr>
                        <td><strong class="order-number"><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                        <td>
                            <div class="customer-info">
                                <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                                <small><?php echo htmlspecialchars($order['customer_email']); ?></small>
                            </div>
                        </td>
                        <td class="amount">₱<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge payment-<?php echo $order['payment_status']; ?>">
                                <?php echo ucfirst($order['payment_status']); ?>
                            </span>
                        </td>
                        <td><small><?php echo date('M d, Y', strtotime($order['created_at'])); ?></small></td>
                        <td>
                            <button class="action-btn" onclick="openModal(<?php echo $order['id']; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="orders.php?delete=<?php echo $order['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Delete this order?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p style="text-align: center; color: var(--text-color); padding: 40px;">No orders yet.</p>
        <?php } ?>
    </div>
</div>

<!-- Modal for updating order -->
<div class="modal" id="orderModal">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fas fa-edit"></i> Update Order Status
        </div>
        <form method="POST">
            <input type="hidden" id="modalOrderId" name="order_id">
            
            <div class="modal-body">
                <label style="display: block; margin-bottom: 15px;">
                    <strong>Order Status</strong>
                    <select name="order_status" style="width: 100%; margin-top: 5px; padding: 8px;">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </label>

                <label style="display: block;">
                    <strong>Payment Status</strong>
                    <select name="payment_status" style="width: 100%; margin-top: 5px; padding: 8px;">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                    </select>
                </label>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-close" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-update" name="update_status">Update</button>
            </div>
        </form>
    </div>
</div>

<?php @include 'footer_admin.php'; ?>

<script>
function openModal(orderId) {
    document.getElementById('modalOrderId').value = orderId;
    document.getElementById('orderModal').classList.add('active');
}

function closeModal() {
    document.getElementById('orderModal').classList.remove('active');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('orderModal');
    if(event.target === modal) {
        closeModal();
    }
}
</script>

</body>
</html>
