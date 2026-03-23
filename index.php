<?php
@include 'includes/config.php';
session_start();

// Fetch homepage content
$select_home = mysqli_query($conn, "SELECT * FROM site_content WHERE section='home'");
$home = mysqli_fetch_assoc($select_home);
?>

<?php include 'header.php'; ?>

<section class="home">
    <div class="container">
        <h1><?php echo $home['title'] ?? 'Welcome to PTL Best Tinapa'; ?></h1>
        <p><?php echo $home['content'] ?? 'Premium quality smoked fish products'; ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Our Premium Products</h2>
        </div>
        
        <div class="products-grid">
            <?php
            $select_services = mysqli_query($conn, "SELECT * FROM services");

            if(mysqli_num_rows($select_services) > 0){
                while($row = mysqli_fetch_assoc($select_services)){
            ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </div>
                    <div class="product-content">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                        <?php if(isset($row['price'])): ?>
                            <div class="product-price">₱<?php echo number_format($row['price'], 2); ?></div>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['user_id'])){ ?>
                            <form method="POST" action="cart.php" style="display: flex; gap: 10px; align-items: center; margin-top: 15px;">
                                <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                                <input type="number" name="quantity" value="1" min="1" max="100" style="width: 60px; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; text-align: center;">
                                <button type="submit" name="add_to_cart" class="btn" style="flex: 1; white-space: nowrap;">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                        <?php } else { ?>
                            <a href="login.php?redirect=index.php" class="btn" style="display: block; text-align: center; width: 100%; margin-top: 15px;">
                                <i class="fas fa-sign-in-alt"></i> Login to Order
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php
                }
            } else {
                echo '<p class="text-center">No products available yet.</p>';
            }
            ?>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Why Choose Us?</h2>
        </div>
        
        <div class="box-container">
            <div class="box">
                <h3><i class="fas fa-leaf"></i> All Natural</h3>
                <p>Made from the finest quality tinapa using traditional smoking methods with no artificial additives.</p>
            </div>
            <div class="box">
                <h3><i class="fas fa-check-circle"></i> Quality Assured</h3>
                <p>Every product is carefully inspected to ensure the highest standards of quality and freshness.</p>
            </div>
            <div class="box">
                <h3><i class="fas fa-truck"></i> Fast Delivery</h3>
                <p>Quick and reliable delivery service to bring our products fresh to your doorstep.</p>
            </div>
            <div class="box">
                <h3><i class="fas fa-heart"></i> Customer Love</h3>
                <p>Trusted by thousands of satisfied customers throughout Bulacan and beyond.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script>
    document.getElementById('menu-btn')?.addEventListener('click', function() {
        document.querySelector('.navbar').classList.toggle('active');
    });
</script>

</body>
</html>