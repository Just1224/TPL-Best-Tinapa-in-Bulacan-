<?php
@include 'includes/config.php';
session_start();

// Fetch homepage content
$select_home = db_query("SELECT * FROM site_content WHERE section = :section", ['section' => 'home']);
$home = db_fetch_assoc($select_home);
?>

<?php include 'header.php'; ?>

<section class="home">
    <div class="container">
        <small style="color: #555; letter-spacing: 0.15em; text-transform: uppercase; font-weight: 600;">Bulacan's Pride</small>
        <h1><?php echo $home['title'] ?? 'Welcome to PTL Best Tinapa'; ?></h1>
        <p><?php echo $home['content'] ?? 'Welcome to PTL Best Tinapa in Bulacan. We provide the finest smoked fish products, crafted with generational expertise and architectural precision.'; ?></p>
        <a href="services.php" class="btn">Explore Our Selection</a>
    </div>
</section>

<section class="dashboard-section">
    <div class="section-container">
        <div class="section-header">
            <h1 class="section-title">Our Premium Products</h1>
            <p class="section-subtitle">Discover our finest selection of smoked fish products, crafted with traditional Bulacan expertise and modern quality standards.</p>
        </div>

        <div class="products-grid">
            <?php
            $select_services = db_query("SELECT * FROM services");

            if(db_num_rows($select_services) > 0){
                while($row = db_fetch_assoc($select_services)){
            ?>
                <div class="product-card fade-in-up">
                    <div class="product-image">
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </div>
                    <div class="product-content">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                        <?php if(isset($row['price'])): ?>
                            <div class="product-price"><?php echo number_format($row['price'], 2); ?></div>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['user_id'])){ ?>
                            <div class="product-actions">
                                <form method="POST" action="cart.php" class="product-quantity">
                                    <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="100">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        <?php } else { ?>
                            <div class="product-actions">
                                <a href="login.php?redirect=index.php" class="btn btn-outline">
                                    <i class="fas fa-sign-in-alt"></i> Login to Order
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php
                }
            } else {
                echo '<div class="dashboard-card fade-in-up">
                        <div class="card-content text-center">
                            <i class="fas fa-box-open" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                            <h3>No Products Available</h3>
                            <p>Please check back later for our premium selection.</p>
                        </div>
                      </div>';
            }
            ?>
        </div>
    </div>
</section>

<section class="dashboard-section">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Why Choose Us?</h2>
            <p class="section-subtitle">Experience the difference that quality, tradition, and customer satisfaction make in every product we deliver.</p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card fade-in-up delay-1">
                <div class="card-content text-center">
                    <div style="font-size: 3rem; color: var(--success-color); margin-bottom: 1rem;">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>All Natural</h3>
                    <p>Made from the finest quality tinapa using traditional smoking methods with no artificial additives.</p>
                </div>
            </div>

            <div class="dashboard-card fade-in-up delay-2">
                <div class="card-content text-center">
                    <div style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>Quality Assured</h3>
                    <p>Every product is carefully inspected to ensure the highest standards of quality and freshness.</p>
                </div>
            </div>

            <div class="dashboard-card fade-in-up delay-3">
                <div class="card-content text-center">
                    <div style="font-size: 3rem; color: var(--warning-color); margin-bottom: 1rem;">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Fast Delivery</h3>
                    <p>Quick and reliable delivery service to bring our products fresh to your doorstep.</p>
                </div>
            </div>

            <div class="dashboard-card fade-in-up delay-4">
                <div class="card-content text-center">
                    <div style="font-size: 3rem; color: var(--danger-color); margin-bottom: 1rem;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Customer Love</h3>
                    <p>Trusted by thousands of satisfied customers throughout Bulacan and beyond.</p>
                </div>
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