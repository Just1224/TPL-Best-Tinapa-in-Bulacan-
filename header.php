<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

@include 'includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
?>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
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
            <img src="images/logo2.png" alt="Logo"> PTL
        </a>

        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Store</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>

            <?php
            if($user_id){
                $select_wishlist_count = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_id = '$user_id'");
                $wishlist_num_rows = mysqli_num_rows($select_wishlist_count);

                $select_cart_count = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
                $cart_num_rows = mysqli_num_rows($select_cart_count);
            } else {
                $wishlist_num_rows = 0;
                $cart_num_rows = 0;
            }
            ?>

            <a href="wishlist.php">
                <i class="fas fa-heart"></i>
                <span>(<?php echo $wishlist_num_rows; ?>)</span>
            </a>

            <a href="cart.php">
                <i class="fas fa-shopping-cart"></i>
                <span>(<?php echo $cart_num_rows; ?>)</span>
            </a>
        </div>

        <div class="account-box">
            <?php if(isset($_SESSION['user_name'])){ ?>
                <p>Username : <span><?php echo $_SESSION['user_name']; ?></span></p>
                <p>Email : <span><?php echo $_SESSION['user_email']; ?></span></p>
                <a href="logout.php" class="delete-btn">Logout</a>
            <?php } else { ?>
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn">Register</a>
            <?php } ?>
        </div>

    </div>

</header>