<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

@include 'includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTL Best Tinapa - Premium Smoked Fish</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message success">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
    <div class="flex">
        <a href="index.php" class="logo">
            <i class="fas fa-fish"></i> PTL Best Tinapa
        </a>

        <nav class="navbar">
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="services.php">Products</a></li>
                <li><a href="payment_methods.php">Payment Methods</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>

        <div class="actions">
            <a href="services.php" class="btn-order">ORDER NOW</a>
        </div>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="#" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="account-box">
            <?php if(isset($_SESSION['user_id'])){ 
                $user_type = $_SESSION['user_type'] ?? 'user';
            ?>
                <p>Welcome, <span><?php echo $_SESSION['user_name'] ?? 'User'; ?></span></p>
                <p style="font-size: 0.9rem; margin-bottom: 15px;">
                    <i class="fas fa-tag" style="color: var(--primary-color);"></i>
                    <?php echo ucfirst($user_type); ?>
                </p>
                
                <?php if($user_type === 'admin'){ ?>
                    <a href="admin/" class="btn" style="display: block; text-align: center; margin-bottom: 10px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                        <i class="fas fa-cog"></i> Admin Panel
                    </a>
                <?php } else { ?>
                    <a href="cart.php" class="btn" style="display: block; text-align: center; margin-bottom: 10px; background: linear-gradient(135deg, #FF9800, #F57C00);">
                        <i class="fas fa-shopping-cart"></i> My Cart
                    </a>
                    <a href="orders.php" class="btn" style="display: block; text-align: center; margin-bottom: 10px;">
                        <i class="fas fa-box"></i> My Orders
                    </a>
                    <a href="dashboard.php" class="btn" style="display: block; text-align: center; margin-bottom: 10px;">
                        <i class="fas fa-user-circle"></i> My Profile
                    </a>
                <?php } ?>
                
                <a href="logout.php" class="btn" style="display: block; text-align: center; background: #95a5a6;">Logout</a>
            <?php } else { ?>
                <a href="login.php" class="btn" style="display: block; text-align: center; margin-bottom: 10px;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="register.php" class="btn" style="display: block; text-align: center; margin-bottom: 10px;">
                    <i class="fas fa-user-plus"></i> Register
                </a>
                <hr style="margin: 10px 0;">
                <a href="admin/login.php" style="display: block; text-align: center; color: var(--primary-color); font-weight: bold; padding: 10px 0;">
                    <i class="fas fa-lock"></i> Admin Login
                </a>
            <?php } ?>
        </div>

    </div>

</header>

<style>
    .account-box {
        display: none;
        position: absolute;
        top: 100%;
        right: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 20px;
        min-width: 200px;
        z-index: 999;
    }

    .account-box.active {
        display: block;
    }

    .account-box p {
        margin-bottom: 10px;
        color: var(--text-color);
    }

    .account-box span {
        color: var(--primary-color);
        font-weight: bold;
    }

    .header {
        position: relative;
    }
</style>

<script>
    document.getElementById('user-btn').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('.account-box').classList.toggle('active');
    });

    // Close account box when clicking outside
    document.addEventListener('click', function(e) {
        if(!e.target.closest('#user-btn') && !e.target.closest('.account-box')) {
            document.querySelector('.account-box').classList.remove('active');
        }
    });
</script>