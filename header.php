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
      <div class="alert success fade-in" style="position: fixed; top: 100px; right: 20px; z-index: 10000; max-width: 400px;">
         <i class="fas fa-check-circle"></i>
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();" style="cursor: pointer; margin-left: auto;"></i>
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
            <a href="admin/login.php" class="admin-login-btn" style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.875rem 1.5rem; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); position: relative; overflow: hidden; z-index: 10;">
                <span style="position: relative; z-index: 2;">
                    <i class="fas fa-lock"></i> Admin Login
                </span>
                <span style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); transition: left 0.5s ease; z-index: 1;"></span>
            </a>
        </div>

        <style>
            .admin-login-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
            }
            .admin-login-btn:active {
                transform: translateY(-1px);
            }
            @media (max-width: 768px) {
                .admin-login-btn {
                    padding: 0.75rem 1.25rem !important;
                    font-size: 0.85rem !important;
                }
            }
        </style>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="#" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="account-box" style="position: absolute; top: 120%; right: 2rem; background: var(--bg-primary); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-xl); padding: 2rem; min-width: 280px; display: none; z-index: 1000; border: 1px solid rgba(255,255,255,0.8);">
            <?php if(isset($_SESSION['user_id'])){
                $user_type = $_SESSION['user_type'] ?? 'user';
            ?>
                <div style="text-align: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--bg-tertiary);">
                    <div style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <p style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">Welcome back!</p>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;"><?php echo $_SESSION['user_name'] ?? 'User'; ?></p>
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--primary-gradient); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-top: 0.5rem;">
                        <i class="fas fa-tag"></i>
                        <?php echo ucfirst($user_type); ?>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <?php if($user_type === 'admin'){ ?>
                        <a href="admin/" class="btn btn-primary" style="justify-content: center;">
                            <i class="fas fa-cog"></i> Admin Panel
                        </a>
                    <?php } else { ?>
                        <a href="cart.php" class="btn btn-secondary" style="justify-content: center;">
                            <i class="fas fa-shopping-cart"></i> My Cart
                        </a>
                        <a href="orders.php" class="btn btn-outline" style="justify-content: center;">
                            <i class="fas fa-box"></i> My Orders
                        </a>
                        <a href="dashboard.php" class="btn btn-outline" style="justify-content: center;">
                            <i class="fas fa-user-circle"></i> My Profile
                        </a>
                    <?php } ?>

                    <hr style="border: none; height: 1px; background: var(--bg-tertiary); margin: 0.5rem 0;">

                    <a href="logout.php" class="btn" style="justify-content: center; background: var(--text-light); color: var(--text-primary);">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            <?php } else { ?>
                <div style="text-align: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--bg-tertiary);">
                    <div style="font-size: 2rem; color: var(--text-light); margin-bottom: 0.5rem;">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <p style="font-weight: 600; color: var(--text-primary);">Welcome Guest</p>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Please sign in to continue</p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="login.php" class="btn btn-primary" style="justify-content: center;">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="register.php" class="btn btn-outline" style="justify-content: center;">
                        <i class="fas fa-user-plus"></i> Register
                    </a>

                    <hr style="border: none; height: 1px; background: var(--bg-tertiary); margin: 0.5rem 0;">

                    <a href="admin/login.php" class="btn btn-danger" style="justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600; padding: 0.875rem; border-radius: 12px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; position: relative; overflow: hidden; cursor: pointer; z-index: 10 !important;">
                        <span style="position: relative; z-index: 2;">
                            <i class="fas fa-lock"></i> Admin Login
                        </span>
                        <span style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); transition: left 0.5s ease; z-index: 1;"></span>
                    </a>
                    <style>
                        a[href="admin/login.php"] {
                            pointer-events: auto !important;
                        }
                        a[href="admin/login.php"]:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
                        }
                        a[href="admin/login.php"]:active {
                            transform: translateY(-1px);
                        }
                    </style>
                </div>
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