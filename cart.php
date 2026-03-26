<?php
@include 'includes/config.php';
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    $_SESSION['redirect_to'] = 'cart.php';
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Verify user exists in users table
$verify_user = db_query("SELECT id FROM users WHERE id = :user_id", ['user_id' => $user_id]);
if(db_num_rows($verify_user) == 0){
    // User doesn't exist in users table - they may be admin or invalid session
    session_destroy();
    header('location: login.php');
    exit();
}

$message = [];

// Add to cart
if(isset($_POST['add_to_cart'])){
    $service_id = intval($_POST['service_id']);
    $quantity = intval($_POST['quantity']) ?? 1;

    if($quantity > 0){
        // Check if item already in cart
        $result = db_query("SELECT id, quantity FROM cart WHERE user_id = :user_id AND service_id = :service_id", [
            'user_id' => $user_id,
            'service_id' => $service_id,
        ]);

        if(db_num_rows($result) > 0){
            // Update quantity
            $row = db_fetch_assoc($result);
            $new_qty = $row['quantity'] + $quantity;
            db_query("UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND service_id = :service_id", [
                'quantity' => $new_qty,
                'user_id' => $user_id,
                'service_id' => $service_id,
            ]);
            $message[] = 'quantity_updated';
        } else {
            // Insert new item
            $insert = db_query("INSERT INTO cart (user_id, service_id, quantity) VALUES (:user_id, :service_id, :quantity)", [
                'user_id' => $user_id,
                'service_id' => $service_id,
                'quantity' => $quantity,
            ]);
            if($insert){
                $message[] = 'added_to_cart';
            } else {
                $message[] = 'error:Failed to add to cart';
            }
        }
    }
}

// Update cart item quantity
if(isset($_POST['update_quantity'])){
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    if($quantity > 0){
        db_query("UPDATE cart SET quantity = :quantity WHERE id = :id AND user_id = :user_id", [
            'quantity' => $quantity,
            'id' => $cart_id,
            'user_id' => $user_id,
        ]);
    } else {
        // Delete if quantity is 0
        db_query("DELETE FROM cart WHERE id = :id AND user_id = :user_id", [
            'id' => $cart_id,
            'user_id' => $user_id,
        ]);
    }
}

// Remove from cart
if(isset($_GET['remove'])){
    $cart_id = intval($_GET['remove']);
    db_query("DELETE FROM cart WHERE id = :id AND user_id = :user_id", [
        'id' => $cart_id,
        'user_id' => $user_id,
    ]);
}

// Get cart items
$cart_result = db_query("SELECT c.id, c.quantity, s.id as service_id, s.title, s.price, s.image FROM cart c 
                               JOIN services s ON c.service_id = s.id 
                               WHERE c.user_id = :user_id 
                               ORDER BY c.added_at DESC", ['user_id' => $user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - PTL Best Tinapa</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cart-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .cart-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .cart-section h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr 150px 150px 100px;
            gap: 20px;
            align-items: center;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            margin-bottom: 15px;
            background: var(--light-color);
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }

        .item-info h3 {
            margin: 0 0 5px 0;
            color: var(--dark-color);
        }

        .item-price {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .quantity-control input {
            width: 60px;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            text-align: center;
        }

        .quantity-control button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .quantity-control button:hover {
            background: var(--secondary-color);
        }

        .subtotal {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .remove-btn {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .remove-btn:hover {
            background: darken(var(--danger-color), 10%);
        }

        .cart-summary {
            background: var(--light-color);
            padding: 20px;
            border-radius: 8px;
            text-align: right;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .summary-row.total {
            border-top: 2px solid var(--border-color);
            padding-top: 10px;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-color);
        }

        .empty-cart i {
            font-size: 3rem;
            color: var(--border-color);
            margin-bottom: 15px;
        }

        .btn-group-cart {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-group-cart .btn {
            flex: 1;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert.success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        @media (max-width: 768px) {
            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }

            .cart-item > :nth-child(3),
            .cart-item > :nth-child(4),
            .cart-item > :nth-child(5) {
                grid-column: 2;
            }

            .item-info {
                grid-column: 2;
            }
        }
    </style>
</head>
<body>

<?php @include 'header.php'; ?>

<div class="cart-container">
    <div class="cart-section">
        <h2><i class="fas fa-shopping-cart"></i> Shopping Cart</h2>

        <?php
        if(in_array('added_to_cart', $message)){
            echo '<div class="alert success"><i class="fas fa-check"></i> Product added to cart!</div>';
        }
        if(in_array('quantity_updated', $message)){
            echo '<div class="alert success"><i class="fas fa-check"></i> Quantity updated!</div>';
        }
        ?>

        <?php
        if($cart_result->num_rows > 0){
            $total = 0;
            while($item = $cart_result->fetch_assoc()){
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
        ?>
            <div class="cart-item">
                <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                
                <div class="item-info">
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <div class="item-price">₱<?php echo number_format($item['price'], 2); ?></div>
                </div>

                <div class="quantity-control">
                    <form method="POST" style="display: flex; gap: 5px; align-items: center;">
                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="100">
                        <button type="submit" name="update_quantity"><i class="fas fa-sync"></i></button>
                    </form>
                </div>

                <div class="subtotal">₱<?php echo number_format($subtotal, 2); ?></div>

                <a href="cart.php?remove=<?php echo $item['id']; ?>" class="remove-btn" onclick="return confirm('Remove from cart?');">
                    <i class="fas fa-trash"></i> Remove
                </a>
            </div>
        <?php
            }
        ?>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>₱<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>₱50.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>₱<?php echo number_format($total + 50, 2); ?></span>
                </div>
            </div>

            <div class="btn-group-cart">
                <a href="services.php" class="btn" style="background: none; border: 2px solid var(--primary-color); color: var(--primary-color);">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
                <a href="checkout.php" class="btn" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                    <i class="fas fa-credit-card"></i> Proceed to Checkout
                </a>
            </div>

        <?php
        } else {
        ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p>Add some delicious tinapa to your cart!</p>
                <a href="services.php" class="btn" style="display: inline-block; margin-top: 20px;">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
        <?php
        }
        ?>
    </div>
</div>

<?php @include 'footer.php'; ?>

</body>
</html>
